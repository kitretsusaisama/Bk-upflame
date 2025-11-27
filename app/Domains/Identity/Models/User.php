<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasUuids;
    use HasFactory;

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
     * Get the highest priority role for this user
     * Lower priority number = higher priority
     *
     * @return \App\Domains\Access\Models\Role|null
     */
    public function getHighestPriorityRole()
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }

        return $this->roles->sortBy('priority')->first();
    }

    /**
     * Get all active sessions for this user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveSessions()
    {
        return $this->sessions()
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    /**
     * Terminate all sessions for this user
     * Optionally exclude a specific session
     *
     * @param string|null $exceptSessionId
     * @return int Number of sessions terminated
     */
    public function terminateAllSessions(?string $exceptSessionId = null): int
    {
        $query = $this->sessions();
        
        if ($exceptSessionId) {
            $query->where('session_id', '!=', $exceptSessionId);
        }

        return $query->delete();
    }

    /**
     * Check if user has super admin role
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }
}