<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionPackageFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_type',
        'meals_per_week',
        'delivery_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'meals_per_week' => 'integer',
            'delivery_days' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the subscriptions for the package.
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
