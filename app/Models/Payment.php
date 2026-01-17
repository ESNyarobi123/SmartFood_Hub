<?php

namespace App\Models;

use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Subscription as FoodSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'service_type',
        'order_id',
        'subscription_id',
        'cyber_order_id',
        'food_order_id',
        'food_subscription_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'phone_number',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // ================================================
    // LEGACY RELATIONSHIPS
    // ================================================

    /**
     * Get the legacy order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the legacy subscription.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    // ================================================
    // MONANA PLATFORM RELATIONSHIPS
    // ================================================

    /**
     * Get the Monana Food order.
     */
    public function cyberOrder(): BelongsTo
    {
        return $this->belongsTo(CyberOrder::class, 'cyber_order_id');
    }

    /**
     * Get the Food order.
     */
    public function foodOrder(): BelongsTo
    {
        return $this->belongsTo(FoodOrder::class, 'food_order_id');
    }

    /**
     * Get the Food subscription.
     */
    public function foodSubscription(): BelongsTo
    {
        return $this->belongsTo(FoodSubscription::class, 'food_subscription_id');
    }

    // ================================================
    // HELPER METHODS
    // ================================================

    /**
     * Check if payment is for Monana Food.
     */
    public function isCyberPayment(): bool
    {
        return $this->service_type === 'cyber';
    }

    /**
     * Check if payment is for Food service.
     */
    public function isFoodPayment(): bool
    {
        return $this->service_type === 'food';
    }

    /**
     * Get the related entity (order or subscription).
     */
    public function getRelatedEntity(): ?Model
    {
        if ($this->cyber_order_id) {
            return $this->cyberOrder;
        }

        if ($this->food_order_id) {
            return $this->foodOrder;
        }

        if ($this->food_subscription_id) {
            return $this->foodSubscription;
        }

        // Legacy fallback
        if ($this->order_id) {
            return $this->order;
        }

        if ($this->subscription_id) {
            return $this->subscription;
        }

        return null;
    }

    /**
     * Get status badge color.
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Scope by service type.
     */
    public function scopeServiceType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
