<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Subscription as FoodSubscription;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Cyber Cafe Stats
        $cyberStats = [
            'pending_orders' => CyberOrder::where('status', 'pending')->count(),
            'today_orders' => CyberOrder::whereDate('created_at', today())->count(),
            'total_orders' => CyberOrder::count(),
            'today_revenue' => CyberOrder::whereDate('created_at', today())
                ->whereIn('status', ['delivered', 'approved', 'preparing', 'ready', 'on_delivery'])
                ->sum('total_amount'),
        ];

        // Monana Food Stats
        $foodStats = [
            'active_subscriptions' => FoodSubscription::where('status', 'active')->count(),
            'pending_orders' => FoodOrder::where('status', 'pending')->count(),
            'total_orders' => FoodOrder::count(),
            'total_subscriptions' => FoodSubscription::count(),
        ];

        // Payment Stats
        $paymentStats = [
            'pending' => Payment::where('status', 'pending')->count(),
            'today_paid' => Payment::where('status', 'paid')->whereDate('updated_at', today())->sum('amount'),
        ];

        // Recent Orders (Both Services)
        $recentCyberOrders = CyberOrder::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentFoodOrders = FoodOrder::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentSubscriptions = FoodSubscription::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'cyberStats',
            'foodStats',
            'paymentStats',
            'recentCyberOrders',
            'recentFoodOrders',
            'recentSubscriptions'
        ));
    }
}
