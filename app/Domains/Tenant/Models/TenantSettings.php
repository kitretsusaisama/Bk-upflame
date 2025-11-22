<?php

namespace App\Domains\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenant_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get the tenant that owns the settings.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}