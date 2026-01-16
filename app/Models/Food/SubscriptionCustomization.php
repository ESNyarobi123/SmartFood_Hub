<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionCustomization extends Model
{
    use HasFactory;

    protected $table = 'food_subscription_customizations';

    protected $fillable = [
        'subscription_id',
        'delivery_date',
        'action_type',
        'original_product_id',
        'new_product_id',
        'original_quantity',
        'new_quantity',
        'price_adjustment',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'original_quantity' => 'decimal:2',
            'new_quantity' => 'decimal:2',
            'price_adjustment' => 'decimal:2',
        ];
    }

    /**
     * Get the subscription.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Get the original product.
     */
    public function originalProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'original_product_id');
    }

    /**
     * Get the new product.
     */
    public function newProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'new_product_id');
    }

    /**
     * Get action type label.
     */
    public function getActionLabel(): string
    {
        return match ($this->action_type) {
            'pause' => 'Delivery Paused',
            'swap' => 'Item Swapped',
            'remove' => 'Item Removed',
            'add' => 'Item Added',
            default => ucfirst($this->action_type),
        };
    }

    /**
     * Get action type color.
     */
    public function getActionColor(): string
    {
        return match ($this->action_type) {
            'pause' => 'yellow',
            'swap' => 'blue',
            'remove' => 'red',
            'add' => 'green',
            default => 'gray',
        };
    }

    /**
     * Scope for specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('delivery_date', $date);
    }

    /**
     * Scope for pauses.
     */
    public function scopePauses($query)
    {
        return $query->where('action_type', 'pause');
    }

    /**
     * Scope for swaps.
     */
    public function scopeSwaps($query)
    {
        return $query->where('action_type', 'swap');
    }
}
