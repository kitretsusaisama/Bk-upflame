<?php

namespace App\Domains\Menu\Services;

use App\Domains\Menu\Repositories\MenuRepository;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Collection;
use App\Domains\Menu\Exceptions\NoMenusFoundException;

class MenuService
{
    protected MenuRepository $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Get sidebar menu items for the current user.
     */
    public function getSidebarForCurrentUser(): Collection
    {
        // Safely get tenant ID, handling cases where tenant binding doesn't exist
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : null;
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, use null for global menus
            $tenantId = null;
        }
        
        $user = auth()->user();
        
        try {
            return $this->menuRepository->getMenuForTenantAndUser($tenantId, $user);
        } catch (NoMenusFoundException $e) {
            // Return empty collection when no menus are found
            return collect();
        }
    }

    /**
     * Get all menu items for a tenant (without permission filtering).
     */
    public function getAllForTenant(?string $tenantId): Collection
    {
        return $this->menuRepository->getAllForTenant($tenantId);
    }

    /**
     * Create a new menu item.
     */
    public function createMenuItem(array $data): \App\Domains\Menu\Models\Menu
    {
        return $this->menuRepository->create($data);
    }

    /**
     * Update a menu item.
     */
    public function updateMenuItem(\App\Domains\Menu\Models\Menu $menu, array $data): \App\Domains\Menu\Models\Menu
    {
        return $this->menuRepository->update($menu, $data);
    }

    /**
     * Delete a menu item.
     */
    public function deleteMenuItem(\App\Domains\Menu\Models\Menu $menu): bool
    {
        return $this->menuRepository->delete($menu);
    }

    /**
     * Find a menu item by key.
     */
    public function findMenuItemByKey(string $key, ?string $tenantId = null): ?\App\Domains\Menu\Models\Menu
    {
        return $this->menuRepository->findByKey($key, $tenantId);
    }
    
    /**
     * Clear all menu caches for a specific tenant
     */
    public function clearTenantCache(?string $tenantId = null): void
    {
        $this->menuRepository->clearTenantCache($tenantId);
    }
    
    /**
     * Clear all menu caches
     */
    public function clearCache(): void
    {
        // This would call a method in the repository to clear caches
        // Implementation depends on your cache strategy
    }
}