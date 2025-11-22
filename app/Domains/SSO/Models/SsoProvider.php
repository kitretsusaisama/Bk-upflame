<?php

namespace App\Domains\SSO\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SsoProvider extends Model
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sso_providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'config',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'array',
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the tenant that owns the SSO provider.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Tenant\Models\Tenant::class);
    }

    /**
     * Get the SSO connections for this provider.
     */
    public function connections()
    {
        return $this->hasMany(SsoConnection::class);
    }
}