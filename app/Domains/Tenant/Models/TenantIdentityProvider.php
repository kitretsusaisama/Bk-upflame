<?php

namespace App\Domains\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TenantIdentityProvider extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'tenant_id',
        'key',
        'display_name',
        'config',
        'group_role_mapping',
        'enabled'
    ];
    
    protected $casts = [
        'config' => 'array',
        'group_role_mapping' => 'array',
        'enabled' => 'boolean'
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}