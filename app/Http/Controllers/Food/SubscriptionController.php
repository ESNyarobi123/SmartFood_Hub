<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Package;
use App\Models\Food\Subscription;
use App\Services\PackageCustomizationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        private PackageCustomizationService $customizationService
    ) {}

    /**
     * Show user's subscription dashboard.
     */
    public function dashboard(): View
    {
        $subscriptions = Subscription::where('user_id', auth()->id())
            ->with('package')
            ->latest()
            ->get();

        $activeSubscription = $subscriptions->firstWhere('status', 'active');

        // Get upcoming deliveries for active subscription
        $upcomingDeliveries = [];
        if ($activeSubscription) {
            $dates = $this->customizationService->getUpcomingDeliveryDates($activeSubscription, 7);
            foreach ($dates as $date) {
                $upcomingDeliveries[] = [
                    'date' => $date,
                    'items' => $this->customizationService->getEffectiveItemsForDate($activeSubscription, $date),
                    'is_paused' => $activeSubscription->isDeliveryPaused($date),
                    'can_customize' => $this->customizationService->canCustomizeForDate($activeSubscription, $date),
                ];
            }
        }

        return view('food.dashboard', compact('subscriptions', 'activeSubscription', 'upcomingDeliveries'));
    }

    /**
     * Subscribe to a package.
     */
    public function subscribe(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric|between:-90,90',
            'delivery_lng' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string',
        ]);

        // Calculate dates
        $startDate = Carbon::now()->addDay();
        $endDate = $package->calculateEndDate($startDate);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'delivery_lat' => $validated['delivery_lat'] ?? null,
            'delivery_lng' => $validated['delivery_lng'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'source' => 'web',
        ]);

        // Redirect to payment
        return redirect()->route('payment.create', [
            'type' => 'food_subscription',
            'id' => $subscription->id,
        ])->with('success', 'Subscription created. Please complete payment.');
    }

    /**
     * Show subscription details.
     */
    public function show(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $subscription->load(['package.items.product', 'customizations', 'payments']);

        // Get upcoming deliveries
        $upcomingDeliveries = [];
        if ($subscription->status === 'active') {
            $dates = $this->customizationService->getUpcomingDeliveryDates($subscription, 14);
            foreach ($dates as $date) {
                $upcomingDeliveries[] = [
                    'date' => $date,
                    'items' => $this->customizationService->getEffectiveItemsForDate($subscription, $date),
                    'is_paused' => $subscription->isDeliveryPaused($date),
                    'can_customize' => $this->customizationService->canCustomizeForDate($subscription, $date),
                ];
            }
        }

        return view('food.subscription', compact('subscription', 'upcomingDeliveries'));
    }

    /**
     * Customize delivery for a specific date.
     */
    public function customize(Request $request, Subscription $subscription): RedirectResponse
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'action' => 'required|in:pause,resume,swap,remove',
            'original_product_id' => 'required_if:action,swap,remove|exists:food_products,id',
            'new_product_id' => 'required_if:action,swap|exists:food_products,id',
            'quantity' => 'required_if:action,swap|numeric|min:0.1',
        ]);

        $date = Carbon::parse($validated['date']);

        if (! $this->customizationService->canCustomizeForDate($subscription, $date)) {
            return back()->with('error', 'Customization deadline has passed for this date.');
        }

        switch ($validated['action']) {
            case 'pause':
                $this->customizationService->pauseDelivery($subscription, $date);
                $message = 'Delivery paused for '.$date->format('M d');
                break;

            case 'resume':
                $this->customizationService->resumeDelivery($subscription, $date);
                $message = 'Delivery resumed for '.$date->format('M d');
                break;

            case 'swap':
                $packageItem = $subscription->package->items()
                    ->where('product_id', $validated['original_product_id'])
                    ->first();

                $this->customizationService->swapItem(
                    $subscription,
                    $date,
                    $validated['original_product_id'],
                    $validated['new_product_id'],
                    $packageItem->default_quantity ?? 1,
                    $validated['quantity']
                );
                $message = 'Item swapped successfully';
                break;

            case 'remove':
                $packageItem = $subscription->package->items()
                    ->where('product_id', $validated['original_product_id'])
                    ->first();

                $this->customizationService->removeItem(
                    $subscription,
                    $date,
                    $validated['original_product_id'],
                    $packageItem->default_quantity ?? 1
                );
                $message = 'Item removed for '.$date->format('M d');
                break;

            default:
                $message = 'Action completed';
        }

        return back()->with('success', $message);
    }

    /**
     * Pause entire subscription.
     */
    public function pause(Subscription $subscription): RedirectResponse
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if (! $subscription->canBePaused()) {
            return back()->with('error', 'This subscription cannot be paused.');
        }

        $subscription->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);

        return back()->with('success', 'Subscription paused successfully.');
    }

    /**
     * Resume subscription.
     */
    public function resume(Subscription $subscription): RedirectResponse
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if (! $subscription->canBeResumed()) {
            return back()->with('error', 'This subscription cannot be resumed.');
        }

        $subscription->update([
            'status' => 'active',
            'resumed_at' => now(),
        ]);

        return back()->with('success', 'Subscription resumed successfully.');
    }
}
