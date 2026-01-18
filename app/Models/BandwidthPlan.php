<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandwidthPlan extends Model
{
    protected $fillable = [
        'name',
        'code',
        'download_speed',
        'upload_speed',
        'burst_download',
        'burst_upload',
        'priority',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getSpeedLabelAttribute(): string
    {
        return "{$this->download_speed}M/{$this->upload_speed}M";
    }

    public function getBurstLabelAttribute(): string
    {
        if ($this->burst_download && $this->burst_upload) {
            return "{$this->burst_download}M/{$this->burst_upload}M";
        }
        return '-';
    }
}
