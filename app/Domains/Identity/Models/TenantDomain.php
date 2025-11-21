<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;

class TenantDomain extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'domain',
        'is_primary',
        'is_verified',
        'verification_token',
        'verified_at'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
