<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    protected $table = 'integration_logs';

    protected $fillable = [
        'source_system',
        'target_system',
        'action',
        'payload',
        'response',
        'status',
        'executed_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'executed_at' => 'datetime',
    ];
}
