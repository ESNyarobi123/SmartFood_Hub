<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    use HasFactory;

    protected $table = 'food_package_items';

    protected $fillable = [
        'package_id',
        'product_id',
        'default_quantity',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'default_quantity' => 'decimal:2',
            'is_required' => 'boolean',
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
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get total price for this item.
     */
    public function getTotalPrice(): float
    {
        return $this->product->price * $this->default_quantity;
    }

    /**
     * Get formatted quantity with unit.
     */
    public function getFormattedQuantity(): string
    {
        return $this->default_quantity.' '.($this->product->unit ?? '');
    }
}
