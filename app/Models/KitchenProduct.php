<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenProduct extends Model
{
    /** @use HasFactory<\Database\Factories\KitchenProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Get the category that owns the kitchen product.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for the kitchen product.
     */
    public function orderItems(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }
}
