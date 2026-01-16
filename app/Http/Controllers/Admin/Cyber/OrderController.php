<?php

namespace App\Http\Controllers\Admin\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'mealSlot', 'items']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by meal slot
        if ($request->filled('meal_slot')) {
            $query->where('meal_slot_id', $request->meal_slot);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(15);

        $mealSlots = \App\Models\Cyber\MealSlot::all();

        return view('admin.cyber.orders.index', compact('orders', 'mealSlots'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'mealSlot', 'items.menuItem', 'payments', 'assignedUser']);

        $deliveryStaff = User::where('is_admin', false)->get();

        return view('admin.cyber.orders.show', compact('order', 'deliveryStaff'));
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
     * Show all orders on a map.
     */
    public function map(): View
    {
        // Get all non-delivered orders with GPS coordinates
        $orders = Order::with(['user', 'mealSlot'])
            ->whereNotNull('delivery_lat')
            ->whereNotNull('delivery_lng')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->get()
            ->map(function ($order) {
                // Determine marker color based on meal slot
                $markerColor = '#00d4aa'; // default
                if ($order->mealSlot) {
                    switch (strtolower($order->mealSlot->name)) {
                        case 'asubuhi':
                            $markerColor = '#ff6b35'; // Orange
                            break;
                        case 'mchana':
                            $markerColor = '#dc2626'; // Red
                            break;
                        case 'usiku':
                            $markerColor = '#1a1a1a'; // Black
                            break;
                    }
                }
                
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'lat' => (float) $order->delivery_lat,
                    'lng' => (float) $order->delivery_lng,
                    'address' => $order->delivery_address,
                    'status' => $order->status,
                    'total' => $order->total_amount,
                    'customer' => $order->user->name ?? 'Guest',
                    'meal_slot' => $order->mealSlot->display_name ?? '-',
                    'meal_slot_id' => $order->meal_slot_id,
                    'color' => $markerColor,
                    'url' => route('admin.cyber.orders.show', $order),
                ];
            });

        return view('admin.cyber.orders.map', compact('orders'));
    }
}
