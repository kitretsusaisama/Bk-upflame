<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Domains\Menu\Services\MenuService as DomainMenuService;

class MenuService
{
    /**
     * Get the menu for the current user
     *
     * @return array
     */
    public function getForCurrentUser(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        // Use the existing domain menu service
        $domainMenuService = app(DomainMenuService::class);
        
        try {
            $menuCollection = $domainMenuService->getSidebarForCurrentUser();
            
            // Convert the collection to the format expected by Inertia
            return $this->convertMenuStructure($menuCollection->toArray());
        } catch (\Exception $e) {
            // Return empty array if there's an error
            return [];
        }
    }

    /**
     * Convert menu structure from domain format to Inertia format
     *
     * @param array $menus
     * @return array
     */
    protected function convertMenuStructure(array $menus): array
    {
        return array_map(function($menu) {
            return [
                'id' => $menu['id'] ?? null,
                'name' => $menu['label'] ?? $menu['name'] ?? '',
                'url' => $menu['url'] ?? '',
                'route' => $menu['route'] ?? '',
                'icon' => $menu['icon'] ?? '',
                'permission' => $menu['permission'] ?? null,
                'type' => $menu['type'] ?? 'link',
                'children' => isset($menu['children']) ? $this->convertMenuStructure($menu['children']->toArray()) : [],
                'metadata' => $menu['metadata'] ?? []
            ];
        }, $menus);
    }
}