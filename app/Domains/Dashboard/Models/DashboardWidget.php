<?php

namespace App\Domains\Dashboard\Models;

use App\Domains\Access\Models\Permission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardWidget extends Model
{
    use HasUuids;

    protected $fillable = [
        'key',
        'label',
        'component',
        'permission_id',
        'config',
        'is_active',
        'sort_order',
        'size',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Permission required to view this widget
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Scope: Only active widgets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Widgets visible to a specific user based on permissions
     */
    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('permission_id')
                ->orWhereHas('permission', function ($permQuery) use ($user) {
                    // Check if user has this permission through any of their roles
                    $permQuery->whereHas('roles', function ($roleQuery) use ($user) {
                        $roleQuery->whereIn('id', $user->roles->pluck('id'));
                    });
                });
        });
    }

    /**
     * Check if widget is accessible by user
     */
    public function isAccessibleBy($user): bool
    {
        if (!$this->permission_id) {
            return true; // No permission required
        }

        return $user->hasPermission($this->permission->name);
    }
}
