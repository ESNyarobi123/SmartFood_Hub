<?php

namespace App\Services;

use App\Models\Food\Package;
use App\Models\Food\PackageItem;
use App\Models\Food\PackageRule;
use App\Models\Food\Product;
use App\Models\Food\Subscription;
use App\Models\Food\SubscriptionCustomization;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PackageCustomizationService
{
    /**
     * Get default items for a package.
     *
     * @return Collection<int, PackageItem>
     */
    public function getPackageItems(int $packageId): Collection
    {
        return PackageItem::where('package_id', $packageId)
            ->with('product')
            ->get();
    }

    /**
     * Get available swap options for an item in a package.
     *
     * @return Collection<int, array{product: Product, rule: PackageRule|null, adjustment: string}>
     */
    public function getSwapOptions(int $packageId, int $productId): Collection
    {
        $rules = PackageRule::where('package_id', $packageId)
            ->where('from_product_id', $productId)
            ->where('is_allowed', true)
            ->with('toProduct')
            ->get();

        return $rules->map(function (PackageRule $rule) {
            return [
                'product' => $rule->toProduct,
                'rule' => $rule,
                'adjustment' => $rule->getAdjustmentLabel(),
            ];
        });
    }

    /**
     * Check if a swap is allowed.
     */
    public function isSwapAllowed(int $packageId, int $fromProductId, int $toProductId): bool
    {
        return PackageRule::where('package_id', $packageId)
            ->where('from_product_id', $fromProductId)
            ->where('to_product_id', $toProductId)
            ->where('is_allowed', true)
            ->exists();
    }

    /**
     * Get the swap rule.
     */
    public function getSwapRule(int $packageId, int $fromProductId, int $toProductId): ?PackageRule
    {
        return PackageRule::where('package_id', $packageId)
            ->where('from_product_id', $fromProductId)
            ->where('to_product_id', $toProductId)
            ->first();
    }

    /**
     * Calculate price adjustment for a swap.
     */
    public function calculateSwapAdjustment(int $packageId, int $fromProductId, int $toProductId, float $quantity): float
    {
        $rule = $this->getSwapRule($packageId, $fromProductId, $toProductId);

        if (! $rule) {
            return 0;
        }

        $fromProduct = Product::find($fromProductId);
        $basePrice = $fromProduct ? $fromProduct->price : 0;

        return $rule->calculateAdjustment($quantity, $basePrice);
    }

    /**
     * Check if customization is still possible for a delivery date.
     */
    public function canCustomizeForDate(Subscription $subscription, Carbon $deliveryDate): bool
    {
        if ($subscription->status !== 'active') {
            return false;
        }

        $package = $subscription->package;
        $cutoffTime = Carbon::parse($package->customization_cutoff_time);

        // If delivery is tomorrow or later, check cutoff time
        if ($deliveryDate->isToday()) {
            return false; // Can't customize for today
        }

        if ($deliveryDate->isTomorrow()) {
            // Check if we're before cutoff time today
            return Carbon::now()->lt($cutoffTime);
        }

        // For future dates, always allow
        return true;
    }

    /**
     * Get customizations for a subscription on a specific date.
     *
     * @return Collection<int, SubscriptionCustomization>
     */
    public function getCustomizationsForDate(int $subscriptionId, Carbon $date): Collection
    {
        return SubscriptionCustomization::where('subscription_id', $subscriptionId)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->with(['originalProduct', 'newProduct'])
            ->get();
    }

    /**
     * Pause delivery for a specific date.
     */
    public function pauseDelivery(Subscription $subscription, Carbon $date, ?string $notes = null): SubscriptionCustomization
    {
        // Remove any existing customizations for this date
        SubscriptionCustomization::where('subscription_id', $subscription->id)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->delete();

        return SubscriptionCustomization::create([
            'subscription_id' => $subscription->id,
            'delivery_date' => $date->format('Y-m-d'),
            'action_type' => 'pause',
            'notes' => $notes,
        ]);
    }

    /**
     * Resume delivery for a specific date (remove pause).
     */
    public function resumeDelivery(Subscription $subscription, Carbon $date): bool
    {
        return SubscriptionCustomization::where('subscription_id', $subscription->id)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->where('action_type', 'pause')
            ->delete() > 0;
    }

    /**
     * Swap an item for a specific date.
     */
    public function swapItem(
        Subscription $subscription,
        Carbon $date,
        int $originalProductId,
        int $newProductId,
        float $originalQuantity,
        float $newQuantity,
        ?string $notes = null
    ): ?SubscriptionCustomization {
        $package = $subscription->package;

        // Check if swap is allowed
        if (! $this->isSwapAllowed($package->id, $originalProductId, $newProductId)) {
            return null;
        }

        // Calculate price adjustment
        $adjustment = $this->calculateSwapAdjustment(
            $package->id,
            $originalProductId,
            $newProductId,
            $newQuantity
        );

        // Remove any existing swap for this product on this date
        SubscriptionCustomization::where('subscription_id', $subscription->id)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->where('original_product_id', $originalProductId)
            ->where('action_type', 'swap')
            ->delete();

        return SubscriptionCustomization::create([
            'subscription_id' => $subscription->id,
            'delivery_date' => $date->format('Y-m-d'),
            'action_type' => 'swap',
            'original_product_id' => $originalProductId,
            'new_product_id' => $newProductId,
            'original_quantity' => $originalQuantity,
            'new_quantity' => $newQuantity,
            'price_adjustment' => $adjustment,
            'notes' => $notes,
        ]);
    }

    /**
     * Remove an item for a specific date.
     */
    public function removeItem(
        Subscription $subscription,
        Carbon $date,
        int $productId,
        float $quantity,
        ?string $notes = null
    ): SubscriptionCustomization {
        // Remove any existing modification for this product on this date
        SubscriptionCustomization::where('subscription_id', $subscription->id)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->where('original_product_id', $productId)
            ->delete();

        return SubscriptionCustomization::create([
            'subscription_id' => $subscription->id,
            'delivery_date' => $date->format('Y-m-d'),
            'action_type' => 'remove',
            'original_product_id' => $productId,
            'original_quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    /**
     * Get the effective items for a subscription on a specific date.
     * Returns the default items with any customizations applied.
     *
     * @return Collection<int, array{product: Product, quantity: float, is_modified: bool, modification: string|null}>
     */
    public function getEffectiveItemsForDate(Subscription $subscription, Carbon $date): Collection
    {
        // Check if paused
        $isPaused = SubscriptionCustomization::where('subscription_id', $subscription->id)
            ->where('delivery_date', $date->format('Y-m-d'))
            ->where('action_type', 'pause')
            ->exists();

        if ($isPaused) {
            return collect();
        }

        // Get default items
        $defaultItems = $this->getPackageItems($subscription->package_id);

        // Get customizations
        $customizations = $this->getCustomizationsForDate($subscription->id, $date);

        // Apply customizations
        return $defaultItems->map(function (PackageItem $item) use ($customizations) {
            $customization = $customizations
                ->where('original_product_id', $item->product_id)
                ->first();

            if (! $customization) {
                return [
                    'product' => $item->product,
                    'quantity' => $item->default_quantity,
                    'is_modified' => false,
                    'modification' => null,
                ];
            }

            if ($customization->action_type === 'remove') {
                return null; // Item removed
            }

            if ($customization->action_type === 'swap') {
                return [
                    'product' => $customization->newProduct,
                    'quantity' => $customization->new_quantity,
                    'is_modified' => true,
                    'modification' => 'Swapped from '.$item->product->name,
                ];
            }

            return [
                'product' => $item->product,
                'quantity' => $item->default_quantity,
                'is_modified' => false,
                'modification' => null,
            ];
        })->filter();
    }

    /**
     * Get upcoming delivery dates for a subscription.
     *
     * @return Collection<int, Carbon>
     */
    public function getUpcomingDeliveryDates(Subscription $subscription, int $days = 7): Collection
    {
        $package = $subscription->package;
        $deliveryDays = $package->delivery_days ?? [1, 2, 3, 4, 5];
        $dates = collect();

        $current = Carbon::now()->addDay();
        $endDate = min(Carbon::now()->addDays($days), $subscription->end_date);

        while ($current <= $endDate) {
            if (in_array($current->dayOfWeek, $deliveryDays)) {
                $dates->push($current->copy());
            }
            $current->addDay();
        }

        return $dates;
    }
}
