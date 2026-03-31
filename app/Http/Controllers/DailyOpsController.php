<?php

namespace App\Http\Controllers;

use App\Models\Cyber\MealSlot;
use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Subscription;
use App\Models\Food\SubscriptionCustomization;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyOpsController extends Controller
{
    public function show(Request $request, string $token)
    {
        $storedToken = Setting::get('daily_ops_token', '');

        if (! $storedToken || $token !== $storedToken) {
            abort(404, 'Link si sahihi au imeisha muda.');
        }

        // Allow date filter via ?date=2026-03-25, default to today
        $filterDate = $request->query('date')
            ? Carbon::parse($request->query('date'))->startOfDay()
            : Carbon::today();
        $todayStr = $filterDate->toDateString();
        $isToday = $filterDate->isToday();

        // ── Meal Slots ──
        $mealSlots = MealSlot::active()->ordered()->get();

        // ── Cyber Orders for selected date, grouped by meal slot ──
        $cyberOrders = CyberOrder::with(['user', 'mealSlot', 'items.menuItem'])
            ->whereDate('created_at', $filterDate)
            ->latest()
            ->get()
            ->groupBy(fn ($order) => $order->meal_slot_id);

        // ── Food/Sokoni Orders for selected date ──
        $foodOrders = FoodOrder::with(['user', 'items'])
            ->whereDate('created_at', $filterDate)
            ->latest()
            ->get();

        // ── ALL Subscriptions (active, paused, pending, expired) ──
        $subscriptions = Subscription::with(['user', 'package.items.product'])
            ->latest()
            ->get();

        // ── Customization Requests for selected date ──
        $customizations = SubscriptionCustomization::with(['subscription.user', 'subscription.package', 'originalProduct', 'newProduct'])
            ->whereDate('delivery_date', $filterDate)
            ->latest()
            ->get();

        // ── All-time recent orders (last 7 days) for overview ──
        $recentCyberOrders = CyberOrder::with(['user', 'mealSlot', 'items.menuItem'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->get();

        $recentFoodOrders = FoodOrder::with(['user', 'items'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->get();

        // ── All customizations (last 7 days) ──
        $allCustomizations = SubscriptionCustomization::with(['subscription.user', 'subscription.package', 'originalProduct', 'newProduct'])
            ->where('delivery_date', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->get();

        // ── Stats ──
        $cyberDateQuery = CyberOrder::whereDate('created_at', $filterDate);
        $foodDateQuery = FoodOrder::whereDate('created_at', $filterDate);

        $stats = [
            'total_cyber_orders' => (clone $cyberDateQuery)->count(),
            'total_food_orders' => (clone $foodDateQuery)->count(),
            'active_subscriptions' => $subscriptions->where('status', 'active')->count(),
            'paused_subscriptions' => $subscriptions->where('status', 'paused')->count(),
            'pending_subscriptions' => $subscriptions->where('status', 'pending')->count(),
            'delivered_cyber' => (clone $cyberDateQuery)->where('status', 'delivered')->count(),
            'pending_cyber' => (clone $cyberDateQuery)->whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'customizations_today' => $customizations->count(),
            'total_cyber_revenue' => (clone $cyberDateQuery)->whereIn('status', ['approved', 'preparing', 'ready', 'on_delivery', 'delivered'])->sum('total_amount'),
            'total_food_revenue' => (clone $foodDateQuery)->whereIn('status', ['approved', 'preparing', 'ready', 'on_delivery', 'delivered'])->sum('total_amount'),
            // All-time totals
            'all_cyber_orders' => CyberOrder::count(),
            'all_food_orders' => FoodOrder::count(),
            'all_subscriptions' => $subscriptions->count(),
        ];

        return view('ops.daily', compact(
            'mealSlots', 'cyberOrders', 'foodOrders',
            'subscriptions', 'customizations', 'stats', 'todayStr',
            'isToday', 'recentCyberOrders', 'recentFoodOrders', 'allCustomizations',
            'filterDate', 'token'
        ));
    }
}
