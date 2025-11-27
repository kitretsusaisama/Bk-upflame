<?php

namespace App\Services;

use App\Domains\Access\Models\DashboardWidget;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Cache;

class WidgetResolver
{
    /**
     * Get dashboard widgets for the given user
     *
     * @param User $user
     * @return array
     */
    public function resolveForUser(User $user): array
    {
        // Cache widgets for 10 minutes per user
        $cacheKey = "dashboard_widgets_{$user->id}";
        
        return Cache::remember($cacheKey, 600, function () use ($user) {
            return DashboardWidget::active()
                ->forUser($user)
                ->orderBy('sort_order')
                ->get()
                ->map(function ($widget) {
                    return [
                        'key' => $widget->key,
                        'label' => $widget->label,
                        'component' => $widget->component,
                        'size' => $widget->size,
                        'config' => $widget->config,
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Clear widget cache for a specific user
     *
     * @param User $user
     * @return void
     */
    public function clearCache(User $user): void
    {
        Cache::forget("dashboard_widgets_{$user->id}");
    }
}
