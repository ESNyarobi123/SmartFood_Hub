<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Order;
use App\Models\Food\Package;
use App\Models\Food\Product;
use App\Models\Food\Subscription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'pending_subscriptions' => Subscription::where('status', 'pending')->count(),
            'paused_subscriptions' => Subscription::where('status', 'paused')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'total_products' => Product::count(),
            'available_products' => Product::where('is_available', true)->count(),
            'active_packages' => Package::where('is_active', true)->count(),
            'subscription_revenue' => Subscription::where('status', 'active')
                ->with('package')
                ->get()
                ->sum(fn ($s) => $s->package->base_price ?? 0),
        ];

        // Subscriptions by package
        $subscriptionsByPackage = Package::withCount([
            'subscriptions as active_count' => fn ($q) => $q->where('status', 'active'),
            'subscriptions as total_count',
        ])->get();

        // Recent subscriptions
        $recentSubscriptions = Subscription::with(['user', 'package'])
            ->latest()
            ->take(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock_quantity', '<', 10)
            ->where('is_available', true)
            ->get();

        return view('admin.food.dashboard', compact(
            'stats',
            'subscriptionsByPackage',
            'recentSubscriptions',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
