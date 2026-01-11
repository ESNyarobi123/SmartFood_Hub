<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Calculate total amount first
            $totalAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                if ($item['type'] === 'food') {
                    $product = FoodItem::findOrFail($item['id']);
                } else {
                    $product = KitchenProduct::findOrFail($item['id']);
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                // Store item data for later
                $itemsData[] = [
                    'orderable_type' => $item['type'] === 'food' ? FoodItem::class : KitchenProduct::class,
                    'orderable_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // Create and save the order first (so it gets an ID)
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'delivery_address' => $request->delivery_address,
                'notes' => $request->notes,
                'status' => 'pending',
                'source' => 'web',
                'total_amount' => $totalAmount,
            ]);

            // Now create order items with the order_id
            foreach ($itemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'orderable_type' => $itemData['orderable_type'],
                    'orderable_id' => $itemData['orderable_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'],
                ]);
            }

            DB::commit();

            return redirect()->route('payment.create', ['order' => $order->id])
                ->with('success', 'Order placed successfully! Please complete payment.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
}
