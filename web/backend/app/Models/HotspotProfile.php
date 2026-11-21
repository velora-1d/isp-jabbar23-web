<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'price',
        'validity_hours',
        'data_limit_mb',
    ];

    public function vouchers()
    {
        return $this->hasMany(HotspotVoucher::class);
    }
}
