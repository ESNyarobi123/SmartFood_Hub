<?php

namespace App\Models\Food;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'food_subscriptions';

    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'notes',
        'source',
        'paused_at',
        'resumed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'delivery_lat' => 'decimal:8',
            'delivery_lng' => 'decimal:8',
            'paused_at' => 'datetime',
            'resumed_at' => 'datetime',
        ];
    }

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get customizations for this subscription.
     */
    public function customizations(): HasMany
    {
        return $this->hasMany(SubscriptionCustomization::class, 'subscription_id');
    }

    /**
     * Get payments for this subscription.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'food_subscription_id');
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    /**
     * Check if subscription can be paused.
     */
    public function canBePaused(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscription can be resumed.
     */
    public function canBeResumed(): bool
    {
        return $this->status === 'paused';
    }

    /**
     * Get days remaining.
     */
    public function getDaysRemaining(): int
    {
        if ($this->end_date < now()) {
            return 0;
        }

        return now()->diffInDays($this->end_date);
    }

    /**
     * Get customizations for a specific date.
     */
    public function getCustomizationsForDate($date): \Illuminate\Database\Eloquent\Collection
    {
        return $this->customizations()->where('delivery_date', $date)->get();
    }

    /**
     * Check if delivery is paused for a specific date.
     */
    public function isDeliveryPaused($date): bool
    {
        return $this->customizations()
            ->where('delivery_date', $date)
            ->where('action_type', 'pause')
            ->exists();
    }

    /**
     * Get status badge color.
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'active' => 'green',
            'paused' => 'orange',
            'cancelled' => 'red',
            'expired' => 'gray',
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
     * Scope for active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now());
    }

    /**
     * Scope for pending subscriptions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
