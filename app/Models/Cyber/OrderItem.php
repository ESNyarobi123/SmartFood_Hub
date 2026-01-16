<?php

namespace App\Models\Cyber;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'cyber_order_items';

    protected $fillable = [
        'cyber_order_id',
        'menu_item_id',
        'item_name',
        'unit_price',
        'quantity',
        'total_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the order this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'cyber_order_id');
    }

    /**
     * Get the menu item.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    /**
     * Calculate and set total price.
     */
    public function calculateTotal(): void
    {
        $this->total_price = $this->unit_price * $this->quantity;
    }
}
