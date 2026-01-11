<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Jobs\CheckPaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\ZenoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if ($request->has('order')) {
            $order = Order::with('orderItems.orderable')->findOrFail($request->order);

            return view('payment', compact('order'));
        }

        if ($request->has('subscription')) {
            $subscription = Subscription::with('subscriptionPackage')->findOrFail($request->subscription);

            return view('payment', compact('subscription'));
        }

        if ($request->has('package')) {
            $package = \App\Models\SubscriptionPackage::findOrFail($request->package);

            return view('payment', compact('package'));
        }

        return redirect()->route('home')->with('error', 'Invalid payment request.');
    }

    public function store(ProcessPaymentRequest $request, ZenoPayService $zenoPay): \Illuminate\Http\RedirectResponse
    {
        // Check if ZenoPay is configured
        if (! $zenoPay->isConfigured()) {
            return back()->with('error', 'Payment gateway is not configured. Please contact administrator.');
        }

        // Only process mobile money payments through ZenoPay
        if ($request->payment_method !== 'mobile_money') {
            return back()->with('error', 'Only mobile money payments are currently supported.');
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $orderId = Str::uuid()->toString(); // Generate unique order ID for ZenoPay
            $amount = 0;
            $orderModel = null;
            $subscription = null;

            // Determine amount and create payment record
            if ($request->order_id) {
                $orderModel = Order::findOrFail($request->order_id);
                $amount = $orderModel->total_amount;
            } elseif ($request->subscription_id) {
                $subscription = Subscription::findOrFail($request->subscription_id);
                $amount = $subscription->subscriptionPackage->price;
            } elseif ($request->package_id) {
                $package = \App\Models\SubscriptionPackage::findOrFail($request->package_id);
                $amount = $package->price;

                // Create subscription from package
                $startDate = now();
                $endDate = $package->duration_type === 'weekly'
                    ? $startDate->copy()->addWeek()
                    : $startDate->copy()->addMonth();

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'subscription_package_id' => $package->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'pending',
                    'delivery_schedule' => $this->generateDeliverySchedule($startDate, $endDate, $package->meals_per_week, $package->delivery_days),
                ]);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $orderModel->id ?? null,
                'subscription_id' => $subscription->id ?? null,
                'amount' => $amount,
                'payment_method' => 'mobile_money',
                'phone_number' => $request->phone_number,
                'transaction_id' => $orderId, // Store ZenoPay order_id as transaction_id
                'status' => 'pending',
            ]);

            // Format phone number to standard format (07XXXXXXXX)
            $phoneNumber = $this->formatPhoneNumber($request->phone_number);

            // Initiate payment with ZenoPay
            $paymentData = [
                'order_id' => $orderId,
                'buyer_email' => $user->email,
                'buyer_name' => $user->name,
                'buyer_phone' => $phoneNumber,
                'amount' => (int) $amount,
            ];

            $result = $zenoPay->initiatePayment($paymentData);

            if (! $result['success']) {
                DB::rollBack();

                // Log the failure for debugging
                \Illuminate\Support\Facades\Log::error('Payment Initiation Failed', [
                    'result' => $result,
                    'payment_data' => $paymentData,
                    'user_id' => $user->id,
                ]);

                $errorMessage = $result['message'] ?? 'Payment initiation failed. Please try again.';

                // If status code is 401 or 403, it's likely an API key issue
                if (isset($result['status_code']) && in_array($result['status_code'], [401, 403])) {
                    $errorMessage = 'Payment gateway authentication failed. Please contact administrator.';
                }

                return back()->with('error', $errorMessage);
            }

            DB::commit();

            // Schedule payment status check job (polling)
            CheckPaymentStatus::dispatch($payment->id)->delay(now()->addSeconds(30));

            // Redirect to confirmation page
            if ($request->order_id) {
                return redirect()->route('confirmation.show', ['order' => $request->order_id])
                    ->with('success', 'Payment request sent! Please complete the payment on your phone. We will confirm shortly.');
            }

            $subscriptionId = $subscription->id ?? $request->subscription_id;

            return redirect()->route('confirmation.show', ['subscription' => $subscriptionId])
                ->with('success', 'Payment request sent! Please complete the payment on your phone. We will confirm shortly.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the full exception for debugging
            \Illuminate\Support\Facades\Log::error('Payment Processing Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all(),
            ]);

            // Show more specific error message in development, generic in production
            $errorMessage = config('app.debug')
                ? 'Payment failed: '.$e->getMessage()
                : 'Payment failed. Please try again. If the problem persists, contact support.';

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Format phone number to standard Tanzania format (07XXXXXXXX).
     * Accepts: 06xxxx, 07xxxx, 255xxxx, or just digits.
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If empty, return as is
        if (empty($phoneNumber)) {
            return $phoneNumber;
        }

        // If starts with 255 (country code), remove it and add 0
        if (str_starts_with($phoneNumber, '255')) {
            $phoneNumber = '0'.substr($phoneNumber, 3);
        }
        // If doesn't start with 0, add 0
        elseif (! str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '0'.$phoneNumber;
        }

        // Ensure it's a valid Tanzania mobile number (should be 10 digits: 0 + 9 digits)
        // Valid prefixes: 06, 07, 08, 09
        if (strlen($phoneNumber) === 10 && in_array(substr($phoneNumber, 0, 2), ['06', '07', '08', '09'])) {
            return $phoneNumber;
        }

        // If format is not standard, return as is (let ZenoPay validate)
        return $phoneNumber;
    }

    /**
     * Generate delivery schedule for subscription.
     */
    private function generateDeliverySchedule($startDate, $endDate, $mealsPerWeek, $deliveryDays): array
    {
        $schedule = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if (in_array($currentDate->dayOfWeek, $deliveryDays ?? [1, 2, 3, 4, 5])) {
                $schedule[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $schedule;
    }
}
