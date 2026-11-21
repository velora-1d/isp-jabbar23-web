<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;

/**
 * @property int $id
 * @property string $name
 * @property string|null $ip_address
 * @property string|null $brand
 * @property string $type
 * @property int $total_pon_ports
 * @property string|null $location
 * @property string $status
 * @property string|null $username
 * @property string|null $password
 * @property int|null $port
 * @property string|null $community
 * @property string|null $server_profile
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[Table('olts')]
class Olt extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'ip_address',
        'brand',
        'type',
        'total_pon_ports',
        'location',
        'status',
        'username',
        'password',
        'port',
        'community',
        'server_profile',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'total_pon_ports' => 'integer',
        'port' => 'integer',
    ];
}
