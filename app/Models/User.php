<?php

namespace App\Models;

use App\Models\Cyber\Order as CyberOrder;
use App\Models\Food\Order as FoodOrder;
use App\Models\Food\Subscription as FoodSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_admin',
        'preferred_service',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // ================================================
    // LEGACY RELATIONSHIPS (for backward compatibility)
    // ================================================

    /**
     * Get the legacy orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the legacy subscriptions for the user.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the legacy orders assigned to the user.
     */
    public function assignedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'assigned_to');
    }

    /**
     * Get the legacy subscription deliveries assigned to the user.
     */
    public function assignedDeliveries(): HasMany
    {
        return $this->hasMany(SubscriptionDelivery::class, 'assigned_to');
    }

    // ================================================
    // MONANA CYBER CAFE RELATIONSHIPS
    // ================================================

    /**
     * Get the user's Cyber Cafe orders.
     */
    public function cyberOrders(): HasMany
    {
        return $this->hasMany(CyberOrder::class);
    }

    /**
     * Get Cyber Cafe orders assigned to this user for delivery.
     */
    public function assignedCyberOrders(): HasMany
    {
        return $this->hasMany(CyberOrder::class, 'assigned_to');
    }

    // ================================================
    // MONANA FOOD RELATIONSHIPS
    // ================================================

    /**
     * Get the user's Food orders.
     */
    public function foodOrders(): HasMany
    {
        return $this->hasMany(FoodOrder::class);
    }

    /**
     * Get the user's Food subscriptions.
     */
    public function foodSubscriptions(): HasMany
    {
        return $this->hasMany(FoodSubscription::class);
    }

    /**
     * Get Food orders assigned to this user for delivery.
     */
    public function assignedFoodOrders(): HasMany
    {
        return $this->hasMany(FoodOrder::class, 'assigned_to');
    }

    // ================================================
    // SHARED RELATIONSHIPS
    // ================================================

    /**
     * Get all notifications for the user.
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all payments for the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ================================================
    // HELPER METHODS
    // ================================================

    /**
     * Get count of active food subscriptions.
     */
    public function getActiveFoodSubscriptionsCount(): int
    {
        return $this->foodSubscriptions()->active()->count();
    }

    /**
     * Get total orders count (both services).
     */
    public function getTotalOrdersCount(): int
    {
        return $this->cyberOrders()->count() + $this->foodOrders()->count();
    }

    /**
     * Check if user prefers a specific service.
     */
    public function prefersService(string $service): bool
    {
        return $this->preferred_service === $service;
    }
}
