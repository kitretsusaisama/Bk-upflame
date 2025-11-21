<?php

namespace App\Domains\Access\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\Tenant;

class Role extends BaseModel
{

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'role_family',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(
            \App\Domains\Identity\Models\User::class,
            'user_roles'
        )->withTimestamps();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains('name', $permission);
    }

    protected static function newFactory()
    {
        return \Database\Factories\RoleFactory::new();
    }
}
