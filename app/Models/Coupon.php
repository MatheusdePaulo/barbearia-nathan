<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_percent',
        'type',
        'user_id',
        'client_phone',
        'used',
        'used_at',
        'expires_at',
        'max_uses',
        'use_count',
        'is_active',
        'appointment_id',
    ];

    protected $casts = [
        'used'       => 'boolean',
        'is_active'  => 'boolean',
        'used_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;

        // max_uses == 0 = ilimitado
        if ($this->max_uses > 0 && $this->use_count >= $this->max_uses) return false;

        return true;
    }

    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) return 'desativado';
        if ($this->expires_at && $this->expires_at->isPast()) return 'expirado';
        if ($this->max_uses > 0 && $this->use_count >= $this->max_uses) return 'esgotado';
        return 'ativo';
    }
}
