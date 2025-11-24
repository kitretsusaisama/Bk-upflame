<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasUuids;
    use HasFactory;
    use Authorizable;

    protected $fillable = [
        'tenant_id',
        'email',
        'password',
        'status',
        'primary_role_id',
        'mfa_enabled',
        'email_verified',
        'phone_verified',
        'last_login',
        'failed_login_attempts',
        'locked_until'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'mfa_enabled' => 'boolean',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'last_login' => 'datetime',
        'locked_until' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            \App\Domains\Access\Models\Role::class,
            'user_roles',
            'user_id',
            'role_id'
        )->withPivot('tenant_id', 'assigned_by')->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function provider()
    {
        return $this->hasOne(\App\Domains\Provider\Models\Provider::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Domains\Booking\Models\Booking::class);
    }

    public function mfaMethods()
    {
        return $this->hasMany(MfaMethod::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            // Ensure permissions are loaded
            if (!$role->relationLoaded('permissions')) {
                $role->load('permissions');
            }
            
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has a specific role
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        // First check if roles are loaded
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        
        // Check if user has the role by name
        return $this->roles->contains('name', $roleName);
    }
    
    /**
     * Determine if the user has a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     * @return bool
     */
    public function can($ability, $arguments = []): bool
    {
        return $this->hasPermission($ability);
    }
}