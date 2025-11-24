<?php

namespace App\Support\Domain\Shared\Traits;

trait TenantScoped
{
    protected static function bootTenantScoped()
    {
        static::addGlobalScope('tenant', function ($query) {
            // This would typically scope queries to the current tenant
            // The implementation would depend on how tenant context is stored
            // For example: $query->where('tenant_id', app('tenant')->id);
        });

        static::creating(function ($model) {
            // This would typically set the tenant_id when creating new records
            // For example: $model->tenant_id = app('tenant')->id;
        });
    }
}