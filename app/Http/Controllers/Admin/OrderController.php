<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $orders = Order::with(['user', 'assignedUser', 'payments', 'orderItems.orderable'])
            ->latest()
            ->paginate(20);

        $deliveryStaff = User::where('is_admin', false)->get();

        return view('admin.orders.index', compact('orders', 'deliveryStaff'));
    }

    public function show(Order $order): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $order->load(['user', 'assignedUser', 'payments', 'orderItems.orderable']);
        $deliveryStaff = User::where('is_admin', false)->get();

        return view('admin.orders.show', compact('order', 'deliveryStaff'));
    }

    public function updateStatus(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,preparing,ready,delivered,cancelled',
        ]);

        $order->status = $request->status;

        if ($request->status === 'delivered') {
            $order->delivered_at = now();
        }

        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully!');
    }

    public function assignDelivery(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $order->assigned_to = $request->assigned_to;
        $order->save();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Delivery assigned successfully!');
    }
}
