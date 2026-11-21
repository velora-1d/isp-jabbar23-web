<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterHealthLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'router_id',
        'cpu_load',
        'memory_usage',
        'voltage',
        'temperature',
        'active_hotspot',
        'active_pppoe',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'cpu_load' => 'float',
        'memory_usage' => 'float',
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
}
