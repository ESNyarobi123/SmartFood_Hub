<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cyber\MenuItem;
use App\Models\Food\Package;
use App\Models\Food\Product;
use App\Services\MealTimeService;
use Illuminate\Http\JsonResponse;

class PublicApiController extends Controller
{
    public function __construct(
        private MealTimeService $mealTimeService
    ) {}

    /**
     * Get available services.
     */
    public function getServices(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 'cyber',
                    'name' => 'Monana Food',
                    'description' => 'Cooked food delivery with meal slots (Asubuhi, Mchana, Usiku)',
                    'icon' => 'computer',
                ],
                [
                    'id' => 'food',
                    'name' => 'Monana Market',
                    'description' => 'Kitchen essentials - Packages & Custom Orders',
                    'icon' => 'shopping_cart',
                ],
            ],
        ]);
    }

    /**
     * Get Monana Food menu items.
     */
    public function getCyberMenu(): JsonResponse
    {
        $items = MenuItem::available()
            ->with('mealSlot')
            ->ordered()
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'price' => (float) $item->price,
                'meal_slot' => $item->mealSlot?->display_name ?? 'All',
                'available_all_slots' => $item->available_all_slots,
            ]);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Get meal slots with status.
     */
    public function getMealSlots(): JsonResponse
    {
        $slots = $this->mealTimeService->getSlotsWithStatus();

        $data = array_map(fn ($slot) => [
            'id' => $slot['slot']->id,
            'name' => $slot['slot']->display_name,
            'delivery_time' => $slot['slot']->delivery_time_label,
            'cutoff' => $slot['cutoff_time'],
            'is_open' => $slot['is_open'],
        ], $slots);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Food products.
     */
    public function getFoodProducts(): JsonResponse
    {
        $products = Product::available()
            ->inStock()
            ->ordered()
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'unit' => $product->unit,
                'stock' => $product->stock_quantity,
            ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get Food packages.
     */
    public function getFoodPackages(): JsonResponse
    {
        $packages = Package::active()
            ->with('items.product')
            ->ordered()
            ->get()
            ->map(fn ($package) => [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => (float) $package->base_price,
                'duration_type' => $package->duration_type,
                'duration_days' => $package->duration_days,
                'items' => $package->items->map(fn ($item) => [
                    'product' => $item->product->name,
                    'quantity' => $item->default_quantity,
                    'unit' => $item->product->unit,
                ])->toArray(),
            ]);

        return response()->json([
            'success' => true,
            'data' => $packages,
        ]);
    }

    /**
     * Get single Food package.
     */
    public function getFoodPackage(int $id): JsonResponse
    {
        $package = Package::with('items.product')->find($id);

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
                'price' => (float) $package->base_price,
                'duration_type' => $package->duration_type,
                'duration_days' => $package->duration_days,
                'deliveries_per_week' => $package->deliveries_per_week,
                'items' => $package->items->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'product' => $item->product->name,
                    'quantity' => $item->default_quantity,
                    'unit' => $item->product->unit,
                    'price' => $item->product->price,
                ])->toArray(),
            ],
        ]);
    }
}
