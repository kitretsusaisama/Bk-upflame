<?php

namespace App\Domains\Access\Models;

use App\Support\Domain\Shared\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Permission extends Model
{
    use HasUuids, TenantScoped;

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
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'resource',
        'action',
        'description',
    ];

    /**
     * Get the tenant that owns the permission.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Tenant\Models\Tenant::class);
    }

    /**
     * Get the roles with this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }
}