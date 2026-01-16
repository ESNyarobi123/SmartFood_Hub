<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'food_order_items';

    protected $fillable = [
        'food_order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'unit',
        'total_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the order this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'food_order_id');
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Calculate and set total price.
     */
    public function calculateTotal(): void
    {
        $this->total_price = $this->unit_price * $this->quantity;
    }

    /**
     * Get formatted quantity with unit.
     */
    public function getFormattedQuantity(): string
    {
        return $this->quantity.' '.$this->unit;
    }
}
