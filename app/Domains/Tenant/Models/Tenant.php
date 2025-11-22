<?php

namespace App\Domains\Tenant\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use App\Support\Domain\Shared\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'status',
        'settings',
        'subscription_tier',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the tenant domains for the tenant.
     */
    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }

    /**
     * Get the tenant settings for the tenant.
     */
    public function settings()
    {
        return $this->hasMany(TenantSettings::class);
    }
}