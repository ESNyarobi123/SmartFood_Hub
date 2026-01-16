<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Package;
use App\Models\Food\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscription::with(['user', 'package']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('package')) {
            $query->where('package_id', $request->package);
        }

        $subscriptions = $query->latest()->paginate(15);

        $packages = Package::all();

        return view('admin.food.subscriptions.index', compact('subscriptions', 'packages'));
    }

    public function show(Subscription $subscription): View
    {
        $subscription->load([
            'user',
            'package.items.product',
            'customizations.originalProduct',
            'customizations.newProduct',
            'payments',
        ]);

        return view('admin.food.subscriptions.show', compact('subscription'));
    }

    public function pause(Subscription $subscription): RedirectResponse
    {
        if ($subscription->status !== 'active') {
            return redirect()
                ->back()
                ->with('error', 'Only active subscriptions can be paused.');
        }

        $subscription->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Subscription paused successfully.');
    }

    public function resume(Subscription $subscription): RedirectResponse
    {
        if ($subscription->status !== 'paused') {
            return redirect()
                ->back()
                ->with('error', 'Only paused subscriptions can be resumed.');
        }

        $subscription->update([
            'status' => 'active',
            'resumed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Subscription resumed successfully.');
    }

    public function cancel(Subscription $subscription): RedirectResponse
    {
        if (! in_array($subscription->status, ['active', 'paused', 'pending'])) {
            return redirect()
                ->back()
                ->with('error', 'This subscription cannot be cancelled.');
        }

        $subscription->update(['status' => 'cancelled']);

        return redirect()
            ->back()
            ->with('success', 'Subscription cancelled successfully.');
    }
}
