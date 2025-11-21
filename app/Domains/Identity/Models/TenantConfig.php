<?php

namespace App\Domains\Identity\Models;

use App\Support\BaseModel;

class TenantConfig extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'branding_config',
        'security_config',
        'feature_flags'
    ];

    protected $casts = [
        'branding_config' => 'array',
        'security_config' => 'array',
        'feature_flags' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
