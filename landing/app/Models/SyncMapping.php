<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncMapping extends Model
{
    protected $table = 'sync_mapping';

    protected $fillable = [
        'erp_customer_id',
        'radius_username',
        'inventory_device_sn',
        'status',
        'last_synced_at'
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];
}
