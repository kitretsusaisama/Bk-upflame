<?php

namespace App\Domains\Menu\Repositories;

use App\Domains\Menu\Models\Menu;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Domains\Menu\Exceptions\NoMenusFoundException;

class MenuRepository
{
    /**
     * Get menu items for a tenant and user, filtered by permissions.
     */
    public function getMenuForTenantAndUser(?string $tenantId, User $user): Collection
    {
        $cacheKey = "menus_tenant_{$tenantId}_user_{$user->id}";
        
        return Cache::remember($cacheKey, config('modules.settings.menu.cache_ttl', 3600), function () use ($tenantId, $user) {
            $query = Menu::where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)
                  ->orWhereNull('tenant_id');
            })
            ->enabled()
            ->orderBy('order');

            $menus = $query->get();

            // Filter menus by user permissions
            $filteredMenus = $menus->filter(function($menu) use ($user) {
                // If no permission is required, show the menu item
                if (!$menu->permission) {
                    return true;
                }
                
                // Check if user has the required permission
                return $user->can($menu->permission);
            })->values();
            
            // Build hierarchical structure
            $hierarchicalMenus = $this->buildMenuHierarchy($filteredMenus);
            
            // Check if any menus were found
            if ($hierarchicalMenus->isEmpty()) {
                throw new NoMenusFoundException("No menus found for user with ID: {$user->id}");
            }
            
            return $hierarchicalMenus;
        });
    }

    /**
     * Get all menu items for a tenant (without permission filtering).
     */
    public function getAllForTenant(?string $tenantId): Collection
    {
        $cacheKey = "menus_tenant_{$tenantId}_all";
        
        return Cache::remember($cacheKey, config('modules.settings.menu.cache_ttl', 3600), function () use ($tenantId) {
            $menus = Menu::where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)
                  ->orWhereNull('tenant_id');
            })
            ->enabled()
            ->orderBy('order')
            ->get();
            
            // Build hierarchical structure
            return $this->buildMenuHierarchy($menus);
        });
    }

    /**
     * Create a new menu item.
     */
    public function create(array $data): Menu
    {
        $menu = Menu::create($data);
        $this->clearCache();
        return $menu;
    }

    /**
     * Update a menu item.
     */
    public function update(Menu $menu, array $data): Menu
    {
        $menu->update($data);
        $this->clearCache();
        return $menu;
    }

    /**
     * Delete a menu item.
     */
    public function delete(Menu $menu): bool
    {
        $result = $menu->delete();
        $this->clearCache();
        return $result;
    }

    /**
     * Find a menu item by key.
     */
    public function findByKey(string $key, ?string $tenantId = null): ?Menu
    {
        return Menu::where('key', $key)
            ->where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)
                  ->orWhereNull('tenant_id');
            })
            ->first();
    }
    
    /**
     * Clear all menu caches for a specific tenant
     */
    public function clearTenantCache(?string $tenantId = null): void
    {
        $tenantId = $tenantId ?? 'null';
        
        // Clear tenant-specific caches
        Cache::forget("menus_tenant_{$tenantId}_all");
        
        // In a production environment, you might want to clear all user caches for this tenant
        // This would require a more sophisticated approach or a database of active user sessions
    }
    
    /**
     * Build hierarchical menu structure
     */
    private function buildMenuHierarchy(Collection $menus): Collection
    {
        // Get root level menus (no parent)
        $rootMenus = $menus->filter(function ($menu) {
            return !$menu->parent_id;
        })->sortBy('order');
        
        // Add children to each menu item
        $rootMenus->each(function ($menu) use ($menus) {
            $menu->children = $menus->filter(function ($child) use ($menu) {
                return $child->parent_id === $menu->id;
            })->sortBy('order');
            
            // Add grandchildren recursively
            $menu->children->each(function ($child) use ($menus) {
                $child->children = $menus->filter(function ($grandchild) use ($child) {
                    return $grandchild->parent_id === $child->id;
                })->sortBy('order');
            });
        });
        
        return $rootMenus;
    }
    
    /**
     * Clear menu cache
     */
    private function clearCache(): void
    {
        // In a production environment with Redis, you could use pattern-based deletion:
        // Cache::tags(['menus'])->flush();
        
        // For file cache or when tags are not available, clear specific known patterns
        // This is a simple approach - in production you might want to use a more sophisticated
        // cache invalidation strategy based on your cache driver
        
        // Clear common cache entries
        Cache::forget('menus_tenant_null_all');
        
        // If a user is logged in, clear their specific cache
        if (auth()->check()) {
            $user = auth()->user();
            // Safely get tenant ID, handling cases where tenant binding doesn't exist
            $tenantId = 'null';
            try {
                $tenant = app('tenant');
                $tenantId = $tenant ? $tenant->id : 'null';
            } catch (\Exception $e) {
                // Tenant binding doesn't exist, use 'null' for global menus
                $tenantId = 'null';
            }
            
            Cache::forget("menus_tenant_{$tenantId}_user_{$user->id}");
            Cache::forget("menus_tenant_{$tenantId}_all");
        }
    }
}