<?php

namespace App\Services;

use App\Domains\Access\Models\Menu;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Cache;

class SidebarService
{
    /**
     * Build sidebar menu for the given user
     *
     * @param User $user
     * @return array
     */
    public function buildForUser(User $user): array
    {
        // Cache menu for 10 minutes per user
        $cacheKey = "sidebar_menu_{$user->id}";
        
        return Cache::remember($cacheKey, 600, function () use ($user) {
            return $this->buildMenuStructure($user);
        });
    }

    /**
     * Build structured menu
     *
     * @param User $user
     * @return array
     */
    protected function buildMenuStructure(User $user): array
    {
        try {
            // 1. Fetch Menus (Scoped by DB)
            $menus = Menu::active()
                ->forUser($user)
                ->orderBy('group_order')
                ->orderBy('sort_order')
                ->get();

            // 2. Filter & Deduplicate
            $groupedItems = [];
            $seenRoutes = [];

            foreach ($menus as $menu) {
                // Permission Check
                if (!$menu->isAccessibleBy($user)) {
                    continue;
                }

                // Deduplication by Route
                if ($menu->route && in_array($menu->route, $seenRoutes)) {
                    continue;
                }
                if ($menu->route) {
                    $seenRoutes[] = $menu->route;
                }

                // Build Item
                $item = [
                    'label' => $menu->label,
                    'icon' => $menu->icon,
                    'url' => $menu->route ? route($menu->route) : $menu->url,
                    'route_name' => $menu->route,
                    'active' => $this->isActive($menu),
                ];

                // Add to Group
                $groupName = $menu->group ?? 'Other';
                if (!isset($groupedItems[$groupName])) {
                    $groupedItems[$groupName] = [
                        'group' => $groupName,
                        'items' => [],
                        'order' => $menu->group_order,
                    ];
                }

                $groupedItems[$groupName]['items'][] = $item;
            }

            // 3. Sort Groups
            usort($groupedItems, fn($a, $b) => $a['order'] <=> $b['order']);

            return array_values($groupedItems);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to build sidebar menu: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    protected function isActive($menu): bool
    {
        if ($menu->route) {
            return request()->routeIs($menu->route);
        }
        return request()->fullUrl() === $menu->url;
    }

    /**
     * Clear menu cache for a specific user
     *
     * @param User $user
     * @return void
     */
    public function clearCache(User $user): void
    {
        Cache::forget("sidebar_menu_{$user->id}");
    }
}
