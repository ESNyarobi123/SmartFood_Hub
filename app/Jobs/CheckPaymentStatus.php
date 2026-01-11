<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\ZenoPayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CheckPaymentStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $paymentId
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(ZenoPayService $zenoPay): void
    {
        $payment = Payment::find($this->paymentId);

        if (! $payment || ! $payment->transaction_id) {
            return;
        }

        $statusCheck = $zenoPay->checkOrderStatus($payment->transaction_id ?? '');

        if (! $statusCheck['success']) {
            // If still pending, schedule another check in 30 seconds
            if ($statusCheck['status'] === 'pending') {
                self::dispatch($this->paymentId)->delay(now()->addSeconds(30));
            }

            return;
        }

        DB::beginTransaction();
        try {
            $paymentStatus = strtoupper($statusCheck['status']);

            if ($paymentStatus === 'COMPLETED') {
                $payment->status = 'paid';
                $payment->save();

                // Update transaction_id with actual transid from ZenoPay if available
                if (isset($statusCheck['transaction_id'])) {
                    $payment->transaction_id = $statusCheck['transaction_id'];
                    $payment->save();
                }

                // Update related order or subscription
                if ($payment->order_id) {
                    $order = $payment->order;
                    if ($order && $order->status === 'pending') {
                        $order->status = 'approved';
                        $order->save();
                    }
                }

                if ($payment->subscription_id) {
                    $subscription = $payment->subscription;
                    if ($subscription && $subscription->status === 'pending') {
                        $subscription->status = 'active';
                        $subscription->save();
                    }
                }
            } elseif ($paymentStatus === 'FAILED' || $paymentStatus === 'CANCELLED') {
                $payment->status = 'failed';
                $payment->save();
            } else {
                // Still pending, schedule another check
                self::dispatch($this->paymentId)->delay(now()->addSeconds(30));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

