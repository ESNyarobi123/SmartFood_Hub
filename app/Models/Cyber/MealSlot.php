<?php

namespace App\Models\Cyber;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealSlot extends Model
{
    use HasFactory;

    protected $table = 'cyber_meal_slots';

    protected $fillable = [
        'name',
        'display_name',
        'order_start_time',
        'order_end_time',
        'delivery_time_label',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'order_start_time' => 'datetime:H:i:s',
            'order_end_time' => 'datetime:H:i:s',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all menu items for this slot.
     */
    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'meal_slot_id');
    }

    /**
     * Get all orders for this slot.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'meal_slot_id');
    }

    /**
     * Check if the slot is currently open for orders.
     */
    public function isOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = Carbon::now();
        $startTime = Carbon::parse($this->order_start_time);
        $endTime = Carbon::parse($this->order_end_time);

        // Handle overnight slots (e.g., 22:00 - 06:30)
        if ($startTime > $endTime) {
            return $now >= $startTime || $now <= $endTime;
        }

        return $now->between($startTime, $endTime);
    }

    /**
     * Get the time remaining until slot closes.
     */
    public function getTimeRemaining(): ?string
    {
        if (! $this->isOpen()) {
            return null;
        }

        $now = Carbon::now();
        $endTime = Carbon::parse($this->order_end_time);

        // Handle overnight
        if ($now > $endTime) {
            $endTime->addDay();
        }

        return $now->diff($endTime)->format('%H:%I:%S');
    }

    /**
     * Scope to get only active slots.
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
}
