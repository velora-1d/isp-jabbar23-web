<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

trait HasTenant
{
    public static function bootHasTenant()
    {
        static::creating(function ($model) {
            if (app()->bound('current_tenant_id')) {
                $model->tenant_id = app('current_tenant_id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->bound('current_tenant_id')) {
                $builder->where('tenant_id', app('current_tenant_id'));
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
