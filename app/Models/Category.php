<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the food items for the category.
     */
    public function foodItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    /**
     * Get the kitchen products for the category.
     */
    public function kitchenProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KitchenProduct::class);
    }
}
