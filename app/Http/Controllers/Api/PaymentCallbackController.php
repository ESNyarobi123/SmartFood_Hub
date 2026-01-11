<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Handle payment callback from payment gateway.
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_id' => 'required|integer',
                'status' => 'required|string|in:paid,failed,cancelled',
                'reference' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $payment = Payment::findOrFail($validated['payment_id']);

            // Update payment status
            $payment->status = $validated['status'];
            if (! empty($validated['reference'])) {
                $payment->transaction_id = $validated['reference'];
            }
            $payment->save();

            // Update related order or subscription
            if ($validated['status'] === 'paid') {
                if ($payment->order_id) {
                    $order = Order::find($payment->order_id);
                    if ($order && $order->status === 'pending') {
                        $order->status = 'approved';
                        $order->save();
                    }
                }

                if ($payment->subscription_id) {
                    $subscription = Subscription::find($payment->subscription_id);
                    if ($subscription && $subscription->status === 'pending') {
                        $subscription->status = 'active';
                        $subscription->save();
                    }
                }
            } elseif (in_array($validated['status'], ['failed', 'cancelled'])) {
                if ($payment->order_id) {
                    $order = Order::find($payment->order_id);
                    if ($order && $order->status === 'pending') {
                        $order->status = 'cancelled';
                        $order->save();
                    }
                }

                if ($payment->subscription_id) {
                    $subscription = Subscription::find($payment->subscription_id);
                    if ($subscription && $subscription->status === 'pending') {
                        $subscription->status = 'cancelled';
                        $subscription->save();
                    }
                }
            }

            DB::commit();

            // Return response that bot can use
            return response()->json([
                'status' => 'success',
                'message' => 'Payment status updated',
                'payment_id' => $payment->id,
                'payment_status' => $payment->status,
                'order_status' => $payment->order ? $payment->order->status : null,
                'subscription_status' => $payment->subscription ? $payment->subscription->status : null,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Callback Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process payment callback: '.$e->getMessage(),
            ], 500);
        }
    }
}
