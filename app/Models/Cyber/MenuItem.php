<?php

namespace App\Models\Cyber;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'cyber_menu_items';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'meal_slot_id',
        'available_all_slots',
        'is_available',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'available_all_slots' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Get the meal slot for this item.
     */
    public function mealSlot(): BelongsTo
    {
        return $this->belongsTo(MealSlot::class, 'meal_slot_id');
    }

    /**
     * Get order items containing this menu item.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }

    /**
     * Check if item is available for a specific slot.
     */
    public function isAvailableForSlot(?int $slotId): bool
    {
        if (! $this->is_available) {
            return false;
        }

        if ($this->available_all_slots) {
            return true;
        }

        return $this->meal_slot_id === $slotId;
    }

    /**
     * Scope to get only available items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get items for a specific slot.
     */
    public function scopeForSlot($query, int $slotId)
    {
        return $query->where(function ($q) use ($slotId) {
            $q->where('available_all_slots', true)
                ->orWhere('meal_slot_id', $slotId);
        });
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
