<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Food\Order;
use App\Models\Food\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show custom order page.
     */
    public function custom(): View
    {
        $products = Product::available()
            ->inStock()
            ->ordered()
            ->get();

        return view('food.custom', compact('products'));
    }

    /**
     * Store a custom order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:food_products,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric|between:-90,90',
            'delivery_lng' => 'nullable|numeric|between:-180,180',
            'notes' => 'nullable|string',
        ]);

        // Calculate total
        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (! $product || ! $product->is_available || $product->stock_quantity < $item['quantity']) {
                continue;
            }

            $itemTotal = $product->price * $item['quantity'];
            $total += $itemTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $item['quantity'],
                'unit' => $product->unit,
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
            'type' => 'food_order',
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

        $order->load(['items.product', 'payments']);

        return view('food.order', compact('order'));
    }

    /**
     * Show order history.
     */
    public function history(): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('food.orders', compact('orders'));
    }
}
