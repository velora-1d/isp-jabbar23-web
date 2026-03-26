<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IpPool extends Model
{
    protected $fillable = [
        'name',
        'network',
        'prefix',
        'gateway',
        'dns_primary',
        'dns_secondary',
        'type',
        'description',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(IpAddress::class);
    }

    public function getNetworkCidrAttribute(): string
    {
        return "{$this->network}/{$this->prefix}";
    }

    public function getTotalIpsAttribute(): int
    {
        return pow(2, 32 - $this->prefix) - 2; // Exclude network and broadcast
    }

    public function getUsedIpsAttribute(): int
    {
        return $this->addresses()->where('status', '=', 'allocated')->count(['*']);
    }

    public function getAvailableIpsAttribute(): int
    {
        return $this->addresses()->where('status', '=', 'available')->count(['*']);
    }

    public function getUsagePercentAttribute(): float
    {
        $total = $this->addresses()->count(['*']);
        if ($total === 0) return 0;
        return round(($this->used_ips / $total) * 100, 1);
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'public' => 'emerald',
            'private' => 'blue',
            'cgnat' => 'amber',
            default => 'gray',
        };
    }
}
