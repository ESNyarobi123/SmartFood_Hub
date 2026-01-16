<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageRule extends Model
{
    use HasFactory;

    protected $table = 'food_package_rules';

    protected $fillable = [
        'package_id',
        'from_product_id',
        'to_product_id',
        'adjustment_type',
        'adjustment_value',
        'is_allowed',
    ];

    protected function casts(): array
    {
        return [
            'adjustment_value' => 'decimal:2',
            'is_allowed' => 'boolean',
        ];
    }

    /**
     * Get the package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the source product.
     */
    public function fromProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'from_product_id');
    }

    /**
     * Get the target product.
     */
    public function toProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'to_product_id');
    }

    /**
     * Calculate the price adjustment for a given quantity.
     */
    public function calculateAdjustment(float $quantity, float $basePrice = 0): float
    {
        if ($this->adjustment_type === 'percentage') {
            return ($basePrice * $this->adjustment_value / 100) * $quantity;
        }

        return $this->adjustment_value * $quantity;
    }

    /**
     * Get adjustment label.
     */
    public function getAdjustmentLabel(): string
    {
        if ($this->adjustment_value == 0) {
            return 'No extra charge';
        }

        $sign = $this->adjustment_value > 0 ? '+' : '';

        if ($this->adjustment_type === 'percentage') {
            return $sign.$this->adjustment_value.'%';
        }

        return $sign.'TZS '.number_format($this->adjustment_value, 0);
    }

    /**
     * Scope to get only allowed rules.
     */
    public function scopeAllowed($query)
    {
        return $query->where('is_allowed', true);
    }
}
