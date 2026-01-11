<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDelivery extends Model
{
    protected $fillable = [
        'subscription_id',
        'scheduled_date',
        'delivered_date',
        'status',
        'notes',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'delivered_date' => 'date',
        ];
    }

    /**
     * Get the subscription that owns the delivery.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user assigned to deliver.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
