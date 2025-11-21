<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'domain',
        'config_json',
        'status'
    ];

    protected $casts = [
        'config_json' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function configs()
    {
        return $this->hasOne(TenantConfig::class);
    }

    public function modules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
