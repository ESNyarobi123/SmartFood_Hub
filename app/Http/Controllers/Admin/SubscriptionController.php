<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $subscriptions = Subscription::with(['user', 'subscriptionPackage', 'payments', 'deliveries'])
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $subscription->load(['user', 'subscriptionPackage', 'payments', 'deliveries.assignedUser']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function pause(Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        $subscription->status = 'paused';
        $subscription->save();

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription paused successfully!');
    }

    public function resume(Subscription $subscription): \Illuminate\Http\RedirectResponse
    {
        $subscription->status = 'active';
        $subscription->save();

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Subscription resumed successfully!');
    }
}
