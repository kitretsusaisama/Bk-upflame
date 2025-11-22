<?php

namespace App\Domains\Access\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use App\Support\Domain\Shared\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasUuid, TenantScoped;

    /**
     * The UUID column name.
     *
     * @var string
     */
    protected $uuidColumn = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'policies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'rules',
        'priority',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rules' => 'array',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tenant that owns the policy.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Tenant\Models\Tenant::class);
    }
}