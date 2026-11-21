<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'key',
        'description',
        'permissions',
        'user_id',
        'last_used_at',
        'usage_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($apiKey) {
            if (empty($apiKey->key)) {
                $apiKey->key = 'jbr_' . Str::random(32);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        if ($this->expires_at && $this->expires_at->isPast()) return 'expired';
        return 'active';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'emerald',
            'inactive' => 'gray',
            'expired' => 'red',
            default => 'gray',
        };
    }

    public function getMaskedKeyAttribute(): string
    {
        return substr($this->key, 0, 8) . str_repeat('*', 20) . substr($this->key, -4);
    }
}
