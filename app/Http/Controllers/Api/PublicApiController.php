<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\KitchenProduct;
use App\Models\SubscriptionPackage;
use Illuminate\Http\JsonResponse;

class PublicApiController extends Controller
{
    /**
     * Get list of available foods.
     */
    public function getFoods(): JsonResponse
    {
        $foods = FoodItem::where('is_available', true)
            ->select('id', 'name', 'price', 'is_available')
            ->get()
            ->map(function ($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    'price' => (float) $food->price,
                    'available_today' => $food->is_available,
                ];
            });

        return response()->json($foods);
    }

    /**
     * Get list of kitchen products.
     */
    public function getProducts(): JsonResponse
    {
        $products = KitchenProduct::where('is_available', true)
            ->select('id', 'name', 'price', 'is_available')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'stock' => 999, // Default stock value, adjust if you have stock tracking
                ];
            });

        return response()->json($products);
    }

    /**
     * Get list of subscription packages.
     */
    public function getSubscriptionPackages(): JsonResponse
    {
        $packages = SubscriptionPackage::where('is_active', true)
            ->select('id', 'name', 'description', 'price', 'duration_type', 'meals_per_week', 'delivery_days')
            ->get()
            ->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'items' => $this->formatPackageItems($package),
                    'price' => (float) $package->price,
                    'duration_type' => $package->duration_type,
                    'meals_per_week' => $package->meals_per_week,
                    'delivery_days' => $package->delivery_days ?? [],
                ];
            });

        return response()->json($packages);
    }

    /**
     * Format package items from description or return default items.
     */
    private function formatPackageItems(SubscriptionPackage $package): array
    {
        // If description contains items, parse them
        // Otherwise return default items based on package type
        if (! empty($package->description)) {
            // Try to extract items from description
            $items = [];
            $lines = explode("\n", $package->description);
            foreach ($lines as $line) {
                $line = trim($line);
                if (! empty($line) && (str_contains($line, 'kg') || str_contains($line, 'pcs') || str_contains($line, 'liters'))) {
                    $items[] = $line;
                }
            }
            if (! empty($items)) {
                return $items;
            }
        }

        // Default items based on package type
        return match ($package->duration_type) {
            'weekly' => ['Mchele 1kg', 'Unga 1kg', 'Mayai 5'],
            'monthly' => ['Mchele 5kg', 'Unga 4kg', 'Mayai 20'],
            default => ['Mchele 2kg', 'Unga 2kg', 'Mayai 10'],
        };
    }
}
