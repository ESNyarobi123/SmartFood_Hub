<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'food_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'unit',
        'image',
        'stock_quantity',
        'is_available',
        'can_be_customized',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'can_be_customized' => 'boolean',
        ];
    }

    /**
     * Get packages that include this product.
     */
    public function packageItems(): HasMany
    {
        return $this->hasMany(PackageItem::class, 'product_id');
    }

    /**
     * Get order items containing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /**
     * Get rules where this product is the source.
     */
    public function rulesFrom(): HasMany
    {
        return $this->hasMany(PackageRule::class, 'from_product_id');
    }

    /**
     * Get rules where this product is the target.
     */
    public function rulesTo(): HasMany
    {
        return $this->hasMany(PackageRule::class, 'to_product_id');
    }

    /**
     * Check if product is in stock.
     */
    public function inStock(): bool
    {
        return $this->is_available && $this->stock_quantity > 0;
    }

    /**
     * Get formatted price with unit.
     */
    public function getFormattedPrice(): string
    {
        return 'TZS '.number_format($this->price, 0).'/'.$this->unit;
    }

    /**
     * Scope to get only available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get only in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('is_available', true)->where('stock_quantity', '>', 0);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
