<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food\Order;
use App\Models\Food\Package;
use App\Models\Food\Product;
use App\Models\Food\Subscription;
use App\Services\PackageCustomizationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodApiController extends Controller
{
    public function __construct(
        private PackageCustomizationService $customizationService
    ) {}

    /**
     * Get all products.
     */
    public function getProducts(): JsonResponse
    {
        $products = Product::available()
            ->inStock()
            ->ordered()
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'unit' => $product->unit,
                'formatted_price' => $product->getFormattedPrice(),
                'image' => $product->image ? asset('storage/'.$product->image) : null,
                'stock_quantity' => $product->stock_quantity,
            ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get all packages.
     */
    public function getPackages(): JsonResponse
    {
        $packages = Package::active()
            ->with('items.product')
            ->ordered()
            ->get()
            ->map(fn ($package) => [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'base_price' => $package->base_price,
                'duration_type' => $package->duration_type,
                'duration_days' => $package->duration_days,
                'deliveries_per_week' => $package->deliveries_per_week,
                'delivery_days' => $package->getDeliveryDaysLabel(),
                'items_count' => $package->items->count(),
                'items' => $package->items->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->default_quantity,
                    'unit' => $item->product->unit,
                ]),
            ]);

        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }

    /**
     * Get single package details.
     */
    public function getPackage(int $id): JsonResponse
    {
        $package = Package::with(['items.product', 'rules.fromProduct', 'rules.toProduct'])
            ->find($id);

        if (! $package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'base_price' => $package->base_price,
                'duration_type' => $package->duration_type,
                'duration_days' => $package->duration_days,
                'items' => $package->items->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->default_quantity,
                    'unit' => $item->product->unit,
                    'price' => $item->product->price,
                ]),
                'swap_rules' => $package->rules->map(fn ($rule) => [
                    'from_product' => $rule->fromProduct->name,
                    'to_product' => $rule->toProduct->name,
                    'adjustment' => $rule->getAdjustmentLabel(),
                    'is_allowed' => $rule->is_allowed,
                ]),
            ],
        ]);
    }

    /**
     * Create a subscription.
     */
    public function createSubscription(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:food_packages,id',
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $package = Package::find($validated['package_id']);

        $startDate = Carbon::now()->addDay();
        $endDate = $package->calculateEndDate($startDate);

        $subscription = Subscription::create([
            'user_id' => $validated['user_id'],
            'package_id' => $validated['package_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'delivery_address' => $validated['delivery_address'],
            'delivery_lat' => $validated['delivery_lat'] ?? null,
            'delivery_lng' => $validated['delivery_lng'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'source' => 'whatsapp',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully.',
            'data' => [
                'subscription_id' => $subscription->id,
                'package_name' => $package->name,
                'amount' => $package->base_price,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'status' => $subscription->status,
            ],
        ]);
    }

    /**
     * Get subscription details.
     */
    public function getSubscription(int $id): JsonResponse
    {
        $subscription = Subscription::with(['package.items.product', 'payments'])
            ->find($id);

        if (! $subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $subscription->id,
                'status' => $subscription->status,
                'package_name' => $subscription->package->name,
                'start_date' => $subscription->start_date->toDateString(),
                'end_date' => $subscription->end_date->toDateString(),
                'days_remaining' => $subscription->getDaysRemaining(),
                'delivery_address' => $subscription->delivery_address,
                'items' => $subscription->package->items->map(fn ($item) => [
                    'product_name' => $item->product->name,
                    'quantity' => $item->default_quantity,
                    'unit' => $item->product->unit,
                ]),
                'payment_status' => $subscription->payments->first()?->status ?? 'unpaid',
            ],
        ]);
    }

    /**
     * Customize subscription for a specific date.
     */
    public function customizeSubscription(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:food_subscriptions,id',
            'date' => 'required|date|after:today',
            'action' => 'required|in:pause,resume,swap,remove',
            'original_product_id' => 'required_if:action,swap,remove|exists:food_products,id',
            'new_product_id' => 'required_if:action,swap|exists:food_products,id',
            'quantity' => 'required_if:action,swap|numeric|min:0.1',
        ]);

        $subscription = Subscription::find($validated['subscription_id']);
        $date = Carbon::parse($validated['date']);

        if (! $this->customizationService->canCustomizeForDate($subscription, $date)) {
            return response()->json([
                'success' => false,
                'message' => 'Customization deadline has passed.',
            ], 400);
        }

        switch ($validated['action']) {
            case 'pause':
                $this->customizationService->pauseDelivery($subscription, $date);
                $message = 'Delivery paused.';
                break;
            case 'resume':
                $this->customizationService->resumeDelivery($subscription, $date);
                $message = 'Delivery resumed.';
                break;
            case 'swap':
                $result = $this->customizationService->swapItem(
                    $subscription,
                    $date,
                    $validated['original_product_id'],
                    $validated['new_product_id'],
                    $subscription->package->items()
                        ->where('product_id', $validated['original_product_id'])
                        ->first()?->default_quantity ?? 1,
                    $validated['quantity']
                );
                if (! $result) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Swap not allowed.',
                    ], 400);
                }
                $message = 'Item swapped successfully.';
                break;
            case 'remove':
                $this->customizationService->removeItem(
                    $subscription,
                    $date,
                    $validated['original_product_id'],
                    $subscription->package->items()
                        ->where('product_id', $validated['original_product_id'])
                        ->first()?->default_quantity ?? 1
                );
                $message = 'Item removed.';
                break;
            default:
                $message = 'Action completed.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Pause subscription.
     */
    public function pauseSubscription(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:food_subscriptions,id',
        ]);

        $subscription = Subscription::find($validated['subscription_id']);

        if (! $subscription->canBePaused()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot pause this subscription.',
            ], 400);
        }

        $subscription->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription paused.',
        ]);
    }

    /**
     * Resume subscription.
     */
    public function resumeSubscription(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:food_subscriptions,id',
        ]);

        $subscription = Subscription::find($validated['subscription_id']);

        if (! $subscription->canBeResumed()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot resume this subscription.',
            ], 400);
        }

        $subscription->update([
            'status' => 'active',
            'resumed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription resumed.',
        ]);
    }

    /**
     * Get subscription history.
     */
    public function getSubscriptionHistory(int $userId): JsonResponse
    {
        $subscriptions = Subscription::where('user_id', $userId)
            ->with('package')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($sub) => [
                'id' => $sub->id,
                'package_name' => $sub->package->name,
                'status' => $sub->status,
                'start_date' => $sub->start_date->toDateString(),
                'end_date' => $sub->end_date->toDateString(),
            ]);

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
        ]);
    }

    /**
     * Create a custom order.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:food_products,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'delivery_address' => 'required|string',
            'delivery_lat' => 'nullable|numeric',
            'delivery_lng' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (! $product || ! $product->is_available) {
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
            return response()->json([
                'success' => false,
                'message' => 'No valid items.',
            ], 400);
        }

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'order_number' => Order::generateOrderNumber(),
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
            'message' => 'Order created.',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Get order details.
     */
    public function getOrder(int $id): JsonResponse
    {
        $order = Order::with(['items.product', 'payments'])->find($id);

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
                'items' => $order->items->map(fn ($item) => [
                    'name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'price' => $item->total_price,
                ]),
            ],
        ]);
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:food_orders,id',
        ]);

        $order = Order::find($validated['order_id']);

        if (! $order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel.',
            ], 400);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled.',
        ]);
    }

    /**
     * Get order history.
     */
    public function getOrderHistory(int $userId): JsonResponse
    {
        $orders = Order::where('user_id', $userId)
            ->latest()
            ->take(20)
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
