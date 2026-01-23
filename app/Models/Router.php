<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'port',
        'username',
        'password',
        'type',
        'status',
        'identity',
        'version',
        'model',
        'last_sync_at',
        'notes',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'online' => 'emerald',
            'offline' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'online' => 'Online',
            'offline' => 'Offline',
            default => 'Unknown',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'mikrotik' => '🔧',
            'cisco' => '🌐',
            'ubiquiti' => '📡',
            default => '🖥️',
        };
    }
}
