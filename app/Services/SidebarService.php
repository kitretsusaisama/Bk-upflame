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
        // Check if caching is enabled
        if (!config('sidebar.cache.enabled', true)) {
            return $this->buildMenuStructure($user);
        }

        // Get config values
        $cachePrefix = config('sidebar.cache.prefix', 'sidebar_menu');
        $cacheTtl = config('sidebar.cache.ttl', 600);
        $cacheDriver = config('sidebar.cache.driver', config('cache.default'));
        $cacheKey = "{$cachePrefix}_{$user->id}";
        
        try {
            $cache = Cache::store($cacheDriver);
            
            // Use cache tags if enabled (Redis/Memcached only)
            if (config('sidebar.cache.tags_enabled', true)) {
                return $cache->tags(['sidebar', "user:{$user->id}"])
                    ->remember($cacheKey, $cacheTtl, fn() => $this->buildMenuStructure($user));
            }
            
            return $cache->remember($cacheKey, $cacheTtl, fn() => $this->buildMenuStructure($user));
        } catch (\Exception $e) {
            // Fallback to no cache on cache failure
            \Illuminate\Support\Facades\Log::warning('Sidebar cache failed, building without cache', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return $this->buildMenuStructure($user);
        }
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
        $cachePrefix = config('sidebar.cache.prefix', 'sidebar_menu');
        $cacheDriver = config('sidebar.cache.driver', config('cache.default'));
        $cache = Cache::store($cacheDriver);
        
        if (config('sidebar.cache.tags_enabled', true)) {
            $cache->tags(['sidebar', "user:{$user->id}"])->flush();
        } else {
            $cache->forget("{$cachePrefix}_{$user->id}");
        }
    }
    
    /**
     * Clear all sidebar caches
     *
     * @return void
     */
    public function clearAllCaches(): void
    {
        $cacheDriver = config('sidebar.cache.driver', config('cache.default'));
        $cache = Cache::store($cacheDriver);
        
        if (config('sidebar.cache.tags_enabled', true)) {
            $cache->tags('sidebar')->flush();
        } else {
            \Illuminate\Support\Facades\Log::warning('Global sidebar cache flush not supported without tags');
        }
    }
}
