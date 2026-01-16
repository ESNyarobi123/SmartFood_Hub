<?php

namespace App\Http\Controllers\Admin\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.food.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'payments', 'assignedUser']);

        $deliveryStaff = User::where('is_admin', false)->get();

        return view('admin.food.orders.show', compact('order', 'deliveryStaff'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,preparing,ready,on_delivery,delivered,cancelled,rejected',
        ]);

        $order->update($validated);

        if ($validated['status'] === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully.');
    }

    public function assign(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $order->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Delivery person assigned successfully.');
    }

    /**
     * Show all food orders on a map.
     */
    public function map(): View
    {
        // Get all non-delivered orders with GPS coordinates
        $orders = Order::with(['user'])
            ->whereNotNull('delivery_lat')
            ->whereNotNull('delivery_lng')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'lat' => (float) $order->delivery_lat,
                    'lng' => (float) $order->delivery_lng,
                    'address' => $order->delivery_address,
                    'status' => $order->status,
                    'total' => $order->total_amount,
                    'customer' => $order->user->name ?? 'Guest',
                    'color' => '#ff6b35', // Orange for Food orders
                    'url' => route('admin.food.orders.show', $order),
                ];
            });

        return view('admin.food.orders.map', compact('orders'));
    }
}
