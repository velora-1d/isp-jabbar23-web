<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'type',
        'status',
        'bank_name',
        'bank_account',
        'npwp',
        'notes',
    ];

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'equipment' => 'cyan',
            'consumable' => 'emerald',
            'service' => 'violet',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'equipment' => 'Peralatan',
            'consumable' => 'Consumable',
            'service' => 'Jasa',
            default => 'Lainnya',
        };
    }
}
