<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $order = new Order();
            $order->user_id = Auth::id();
            $order->order_number = Order::generateOrderNumber();
            $order->delivery_address = $request->delivery_address;
            $order->notes = $request->notes;
            $order->status = 'pending';
            $order->source = 'web';
            $totalAmount = 0;

            foreach ($request->items as $item) {
                if ($item['type'] === 'food') {
                    $product = FoodItem::findOrFail($item['id']);
                } else {
                    $product = KitchenProduct::findOrFail($item['id']);
                }

                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItem = new OrderItem();
                $orderItem->orderable_type = $item['type'] === 'food' ? FoodItem::class : KitchenProduct::class;
                $orderItem->orderable_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->notes = $item['notes'] ?? null;
                $order->orderItems()->save($orderItem);
            }

            $order->total_amount = $totalAmount;
            $order->save();

            DB::commit();

            return redirect()->route('payment.create', ['order' => $order->id])
                ->with('success', 'Order placed successfully! Please complete payment.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
}
