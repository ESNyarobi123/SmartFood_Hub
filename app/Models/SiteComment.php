<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteComment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'is_approved',
        'approved_at',
        'approved_by_user_id',
        'is_admin_created',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'bool',
            'is_admin_created' => 'bool',
            'approved_at' => 'datetime',
        ];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }
}
