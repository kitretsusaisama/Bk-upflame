<?php

namespace App\Domains\Access\Models;

use App\Support\Domain\Shared\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Role extends Model
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
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'is_system',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Get the tenant that owns the role.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Tenant\Models\Tenant::class);
    }

    /**
     * Get the users with this role.
     */
    public function users()
    {
        return $this->belongsToMany(
            \App\Domains\Identity\Models\User::class,
            'role_user',
            'role_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Get the permissions assigned to this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }
}