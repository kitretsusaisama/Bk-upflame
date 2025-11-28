<?php

namespace App\Domains\Access\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserRole extends Pivot
{
    use HasUuids;

    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'role_id',
        'tenant_id',
        'assigned_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Domains\Identity\Models\User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Domains\Identity\Models\Tenant::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(\App\Domains\Identity\Models\User::class, 'assigned_by');
    }
}
