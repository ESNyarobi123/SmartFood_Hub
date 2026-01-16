<?php

namespace App\Http\Controllers;

use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Subscription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show unified customer dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Cyber Cafe Stats
        $cyberOrders = CyberOrder::where('user_id', $user->id)
            ->with('mealSlot')
            ->latest()
            ->take(5)
            ->get();

        $cyberPendingOrders = CyberOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $cyberTotalSpent = CyberOrder::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->sum('total_amount');

        // Monana Food Stats
        $foodOrders = FoodOrder::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $foodPendingOrders = FoodOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $foodTotalSpent = FoodOrder::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->sum('total_amount');

        // Subscriptions
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('package')
            ->first();

        $subscriptions = Subscription::where('user_id', $user->id)
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        // Recent Activity (combined)
        $recentActivity = collect()
            ->merge($cyberOrders->map(fn($order) => [
                'type' => 'cyber_order',
                'id' => $order->id,
                'title' => $order->order_number,
                'description' => $order->mealSlot->display_name ?? 'Cyber Order',
                'status' => $order->status,
                'amount' => $order->total_amount,
                'date' => $order->created_at,
                'url' => route('cyber.order.show', $order),
            ]))
            ->merge($foodOrders->map(fn($order) => [
                'type' => 'food_order',
                'id' => $order->id,
                'title' => $order->order_number,
                'description' => 'Custom Food Order',
                'status' => $order->status,
                'amount' => $order->total_amount,
                'date' => $order->created_at,
                'url' => route('food.order.show', $order),
            ]))
            ->merge($subscriptions->map(fn($sub) => [
                'type' => 'subscription',
                'id' => $sub->id,
                'title' => $sub->package->name,
                'description' => ucfirst($sub->status) . ' Subscription',
                'status' => $sub->status,
                'amount' => $sub->package->base_price,
                'date' => $sub->created_at,
                'url' => route('food.subscription.show', $sub),
            ]))
            ->sortByDesc('date')
            ->take(10);

        return view('dashboard', compact(
            'cyberOrders',
            'cyberPendingOrders',
            'cyberTotalSpent',
            'foodOrders',
            'foodPendingOrders',
            'foodTotalSpent',
            'activeSubscription',
            'subscriptions',
            'recentActivity'
        ));
    }
}
