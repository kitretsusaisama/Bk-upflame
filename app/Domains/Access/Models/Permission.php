<?php

namespace App\Domains\Access\Models;

use App\Support\BaseModel;

class Permission extends BaseModel
{

    protected $fillable = [
        'name',
        'resource',
        'action',
        'description'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')->withTimestamps();
    }

    protected static function newFactory()
    {
        return \Database\Factories\PermissionFactory::new();
    }
}
