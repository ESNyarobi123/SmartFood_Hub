<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $orders = Order::with(['user', 'payments'])
            ->latest()
            ->take(10)
            ->get();

        $subscriptions = Subscription::with(['user', 'subscriptionPackage', 'payments'])
            ->latest()
            ->take(10)
            ->get();

        $pendingOrders = Order::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'orders',
            'subscriptions',
            'pendingOrders',
            'totalOrders',
            'activeSubscriptions',
            'pendingPayments'
        ));
    }
}
