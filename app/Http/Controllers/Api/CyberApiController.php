<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MealSlot;
use App\Models\Cyber\MenuItem;
use App\Models\Cyber\Order;
use App\Models\User;
use App\Services\MealTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CyberApiController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    /**
     * Get all menu items with meal slot info.
     */
    public function getMenu(Request $request): JsonResponse
    {
        $query = MenuItem::available()->ordered();

        if ($request->has('meal_slot_id')) {
            $query->forSlot($request->meal_slot_id);
        }

        $items = $query->get()->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'price' => $item->price,
            'image' => $item->image ? asset('storage/'.$item->image) : null,
            'meal_slot_id' => $item->meal_slot_id,
            'meal_slot_name' => $item->mealSlot?->display_name,
            'available_all_slots' => $item->available_all_slots,
            'is_available' => $item->is_available,
        ]);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Get meal slots with current status.
     */
    public function getMealSlots(): JsonResponse
    {
        $slots = $this->mealTimeService->getSlotsWithStatus();

        $data = array_map(fn ($slot) => [
            'id' => $slot['slot']->id,
            'name' => $slot['slot']->name,
            'display_name' => $slot['slot']->display_name,
            'delivery_time' => $slot['slot']->delivery_time_label,
            'cutoff_time' => $slot['cutoff_time'],
            'is_open' => $slot['is_open'],
            'time_remaining' => $slot['time_remaining'],
            'description' => $slot['slot']->description,
        ], $slots);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Create a Monana Food order.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meal_slot_id' => 'required|exists:cyber_meal_slots,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:cyber_menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        // Check if slot is open
        $slot = MealSlot::find($validated['meal_slot_id']);
        if (! $slot || ! $slot->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'This meal slot is currently closed.',
            ], 400);
        }

        // Calculate total
        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            if (! $menuItem || ! $menuItem->is_available) {
                continue;
            }

            $itemTotal = $menuItem->price * $item['quantity'];
            $total += $itemTotal;

            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'item_name' => $menuItem->name,
                'unit_price' => $menuItem->price,
                'quantity' => $item['quantity'],
                'total_price' => $itemTotal,
            ];
        }

        if (empty($orderItems)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid items in order.',
            ], 400);
        }

        // Create order
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'order_number' => Order::generateOrderNumber(),
            'meal_slot_id' => $validated['meal_slot_id'],
            'total_amount' => $total,
            'status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'delivery_lat' => $validated['delivery_lat'] ?? null,
            'delivery_lng' => $validated['delivery_lng'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'source' => 'whatsapp',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'meal_slot' => $slot->display_name,
                'delivery_time' => $slot->delivery_time_label,
            ],
        ]);
    }

    /**
     * Get order details.
     */
    public function getOrder(int $id): JsonResponse
    {
        $order = Order::with(['items.menuItem', 'mealSlot', 'payments'])
            ->find($id);

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'meal_slot' => $order->mealSlot?->display_name,
                'delivery_time' => $order->mealSlot?->delivery_time_label,
                'delivery_address' => $order->delivery_address,
                'items' => $order->items->map(fn ($item) => [
                    'name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]),
                'payment_status' => $order->payments->first()?->status ?? 'unpaid',
                'created_at' => $order->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:cyber_orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        if (! $order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be cancelled.',
            ], 400);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
        ]);
    }

    /**
     * Get order history for a user.
     */
    public function getOrderHistory(int $userId): JsonResponse
    {
        $orders = Order::where('user_id', $userId)
            ->with('mealSlot')
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'meal_slot' => $order->mealSlot?->display_name,
                'created_at' => $order->created_at->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
