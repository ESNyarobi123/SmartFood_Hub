<?php

namespace App\Models\Food;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'food_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'notes',
        'source',
        'assigned_to',
        'scheduled_delivery_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'delivery_lat' => 'decimal:8',
            'delivery_lng' => 'decimal:8',
            'scheduled_delivery_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to deliver the order.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'food_order_id');
    }

    /**
     * Get the payments for this order.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'food_order_id');
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'FD-'.strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Get status badge color.
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'preparing' => 'indigo',
            'ready' => 'cyan',
            'on_delivery' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for today's orders.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
