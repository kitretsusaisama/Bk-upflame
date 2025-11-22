<?php

namespace App\Domains\Access\Services;

use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Cache;

class AccessEvaluationService
{
    protected $roleService;
    protected $permissionService;
    protected $cacheTtl;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->cacheTtl = config('auth.access.cache_ttl', 3600); // 1 hour default
    }

    /**
     * Check if a user has a specific permission
     *
     * @param User $user
     * @param string $permission
     * @param mixed $resourceContext
     * @return bool
     */
    public function can(User $user, string $permission, $resourceContext = null): bool
    {
        // Check if user is super admin (global)
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Get user permissions from cache
        $userPermissions = $this->getCachedUserPermissions($user->id);
        
        // If not in cache, calculate and cache
        if ($userPermissions === null) {
            $userPermissions = $this->calculateUserPermissions($user);
            $this->cacheUserPermissions($user->id, $userPermissions);
        }

        // Check if user has the permission
        if (in_array($permission, $userPermissions)) {
            return true;
        }

        // Apply policies if resource context is provided
        if ($resourceContext !== null) {
            return $this->applyPolicies($user, $permission, $resourceContext);
        }

        return false;
    }

    /**
     * Get cached user permissions
     *
     * @param string $userId
     * @return array|null
     */
    protected function getCachedUserPermissions(string $userId): ?array
    {
        $cacheKey = "user_perms:{$userId}";
        $permissions = Cache::get($cacheKey);
        
        return $permissions !== null ? $permissions : null;
    }

    /**
     * Cache user permissions
     *
     * @param string $userId
     * @param array $permissions
     * @return void
     */
    protected function cacheUserPermissions(string $userId, array $permissions): void
    {
        $cacheKey = "user_perms:{$userId}";
        Cache::put($cacheKey, $permissions, $this->cacheTtl);
    }

    /**
     * Calculate user permissions from roles
     *
     * @param User $user
     * @return array
     */
    protected function calculateUserPermissions(User $user): array
    {
        $permissions = [];
        
        // Load user roles with permissions
        $user->load('roles.permissions');
        
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }
        
        // Remove duplicates
        return array_unique($permissions);
    }

    /**
     * Apply policies for resource-level access control
     *
     * @param User $user
     * @param string $permission
     * @param mixed $resourceContext
     * @return bool
     */
    protected function applyPolicies(User $user, string $permission, $resourceContext): bool
    {
        // In a real implementation, this would check policies stored in the database
        // For now, we'll return false as a placeholder
        return false;
    }

    /**
     * Invalidate user permissions cache
     *
     * @param string $userId
     * @return void
     */
    public function invalidateUserCache(string $userId): void
    {
        $cacheKey = "user_perms:{$userId}";
        Cache::forget($cacheKey);
    }
}