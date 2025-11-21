<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;

class TenantModule extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'module_name',
        'is_enabled',
        'config'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'config' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
