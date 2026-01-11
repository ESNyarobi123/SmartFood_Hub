<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    public function show(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if ($request->has('order')) {
            $order = Order::with(['orderItems.orderable', 'payments'])
                ->findOrFail($request->order);

            // Check payment status if still pending
            if ($order->payments->first()?->status === 'pending') {
                \App\Jobs\CheckPaymentStatus::dispatch($order->payments->first()->id)->delay(now()->addSeconds(10));
            }

            return view('confirmation', compact('order'));
        }

        if ($request->has('subscription')) {
            $subscription = Subscription::with(['subscriptionPackage', 'payments', 'deliveries'])
                ->findOrFail($request->subscription);

            // Check payment status if still pending
            if ($subscription->payments->first()?->status === 'pending') {
                \App\Jobs\CheckPaymentStatus::dispatch($subscription->payments->first()->id)->delay(now()->addSeconds(10));
            }

            return view('confirmation', compact('subscription'));
        }

        return redirect()->route('home')->with('error', 'Invalid confirmation request.');
    }
}
