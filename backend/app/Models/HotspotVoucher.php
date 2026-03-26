<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotspot_profile_id',
        'code',
        'username',
        'password',
        'status',
        'router_id',
        'created_by',
        'used_at',
    ];

    public function profile()
    {
        return $this->belongsTo(HotspotProfile::class, 'hotspot_profile_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
