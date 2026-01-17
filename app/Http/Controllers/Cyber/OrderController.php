<?php

namespace App\Http\Controllers\Cyber;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MealSlot;
use App\Models\Cyber\MenuItem;
use App\Models\Cyber\Order;
use App\Services\MealTimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    /**
     * Show the Monana Food landing page.
     */
    public function index(): View
    {
        $mealSlots = $this->mealTimeService->getSlotsWithStatus();

        return view('cyber.index', compact('mealSlots'));
    }

    /**
     * Show the menu for ordering.
     */
    public function menu(Request $request): View
    {
        $mealSlots = $this->mealTimeService->getSlotsWithStatus();
        $selectedSlot = null;

        if ($request->has('slot')) {
            $selectedSlot = MealSlot::find($request->slot);
        }

        // Get menu items
        if ($selectedSlot) {
            $menuItems = MenuItem::available()
                ->forSlot($selectedSlot->id)
                ->ordered()
                ->get();
        } else {
            $menuItems = MenuItem::available()
                ->where('available_all_slots', true)
                ->ordered()
                ->get();
        }

        return view('cyber.menu', compact('mealSlots', 'menuItems', 'selectedSlot'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meal_slot_id' => 'required|exists:cyber_meal_slots,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:cyber_menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric|between:-90,90',
            'delivery_lng' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string',
        ]);

        // Check if slot is open
        $slot = MealSlot::find($validated['meal_slot_id']);
        if (! $slot || ! $slot->isOpen()) {
            return back()->with('error', 'This meal slot is currently closed.');
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
            return back()->with('error', 'No valid items in your order.');
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'meal_slot_id' => $validated['meal_slot_id'],
            'total_amount' => $total,
            'status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'delivery_lat' => $validated['delivery_lat'] ?? null,
            'delivery_lng' => $validated['delivery_lng'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'source' => 'web',
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        // Redirect to payment
        return redirect()->route('payment.create', [
            'type' => 'cyber_order',
            'id' => $order->id,
        ])->with('success', 'Order created. Please complete payment.');
    }

    /**
     * Show order details.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.menuItem', 'mealSlot', 'payments']);

        return view('cyber.order', compact('order'));
    }

    /**
     * Show order history.
     */
    public function history(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['mealSlot', 'items'])
            ->latest()
            ->paginate(10);

        return view('cyber.orders', compact('orders'));
    }
}
