<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $table = 'food_packages';

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'duration_days',
        'duration_type',
        'deliveries_per_week',
        'delivery_days',
        'customization_cutoff_time',
        'image',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'delivery_days' => 'array',
            'customization_cutoff_time' => 'datetime:H:i:s',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the items in this package.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    /**
     * Get the rules for this package.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(PackageRule::class, 'package_id');
    }

    /**
     * Get subscriptions for this package.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'package_id');
    }

    /**
     * Get items with product details.
     */
    public function itemsWithProducts()
    {
        return $this->items()->with('product');
    }

    /**
     * Calculate end date from start date.
     */
    public function calculateEndDate(\DateTime $startDate): \DateTime
    {
        return (clone $startDate)->modify("+{$this->duration_days} days");
    }

    /**
     * Get delivery days as readable string.
     */
    public function getDeliveryDaysLabel(): string
    {
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $deliveryDays = $this->delivery_days ?? [];

        return collect($deliveryDays)
            ->map(fn ($d) => $days[$d] ?? '')
            ->filter()
            ->implode(', ');
    }

    /**
     * Scope to get only active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope to get weekly packages.
     */
    public function scopeWeekly($query)
    {
        return $query->where('duration_type', 'weekly');
    }

    /**
     * Scope to get monthly packages.
     */
    public function scopeMonthly($query)
    {
        return $query->where('duration_type', 'monthly');
    }
}
