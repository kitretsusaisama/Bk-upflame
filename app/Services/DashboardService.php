<?php

namespace App\Services;

use App\Domains\Dashboard\Models\DashboardWidget;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get all widgets visible to the user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWidgetsForUser(User $user)
    {
        return DashboardWidget::active()
            ->forUser($user)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get dashboard statistics based on user's highest priority role
     *
     * @param User $user
     * @return array
     */
    public function getStatsForUser(User $user): array
    {
        $role = $user->getHighestPriorityRole();
        
        if (!$role) {
            return [];
        }

        // Cache stats for 5 minutes
        $cacheKey = "dashboard_stats_{$user->id}_{$role->id}";
        
        return Cache::remember($cacheKey, 300, function () use ($user, $role) {
            return match ($role->name) {
                'Super Admin' => $this->getSuperAdminStats($user),
                'Tenant Admin', 'Admin' => $this->getAdminStats($user),
                'Provider' => $this->getProviderStats($user),
                'Ops' => $this->getOpsStats($user),
                'Vendor' => $this->getVendorStats($user),
                default => $this->getCustomerStats($user),
            };
        });
    }

    /**
     * Get complete dashboard data for user
     *
     * @param User $user
     * @return array
     */
    public function getDashboardData(User $user): array
    {
        $role = $user->getHighestPriorityRole();
        
        return [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $role?->name,
                'role_priority' => $role?->priority,
                'is_super_admin' => $user->isSuperAdmin(),
            ],
            'stats' => $this->getStatsForUser($user),
            'widgets' => $this->getWidgetsForUser($user),
            'role_context' => [
                'name' => $role?->name,
                'family' => $role?->role_family,
            ],
        ];
    }

    /**
     * Super Admin statistics
     */
    protected function getSuperAdminStats(User $user): array
    {
        return [
            'total_tenants' => \App\Domains\Identity\Models\Tenant::count(),
            'total_users' => User::count(),
            'active_sessions' => \App\Domains\Identity\Models\UserSession::whereNotNull('last_activity')
                ->where('last_activity', '>', now()->subHours(1))
                ->count(),
            'total_bookings' => \App\Domains\Booking\Models\Booking::count(),
        ];
    }

    /**
     * Tenant Admin statistics
     */
    protected function getAdminStats(User $user): array
    {
        return [
            'total_users' => User::where('tenant_id', $user->tenant_id)->count(),
            'total_providers' => \App\Domains\Provider\Models\Provider::where('tenant_id', $user->tenant_id)->count(),
            'total_bookings' => \App\Domains\Booking\Models\Booking::where('tenant_id', $user->tenant_id)->count(),
            'pending_approvals' => \App\Domains\Provider\Models\Provider::where('tenant_id', $user->tenant_id)
                ->where('status', 'pending')
                ->count(),
        ];
    }

    /**
     * Provider statistics
     */
    protected function getProviderStats(User $user): array
    {
        $provider = $user->provider;
        
        if (!$provider) {
            return [];
        }

        return [
            'total_bookings' => $provider->bookings()->count(),
            'pending_bookings' => $provider->bookings()->where('status', 'pending')->count(),
            'completed_bookings' => $provider->bookings()->where('status', 'completed')->count(),
            'revenue_this_month' => $provider->bookings()
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount') ?? 0,
        ];
    }

    /**
     * Ops statistics
     */
    protected function getOpsStats(User $user): array
    {
        return [
            'pending_workflows' => \App\Domains\Workflow\Models\Workflow::where('tenant_id', $user->tenant_id)
                ->where('status', 'pending')
                ->count(),
            'total_bookings' => \App\Domains\Booking\Models\Booking::where('tenant_id', $user->tenant_id)->count(),
            'pending_approvals' => \App\Domains\Workflow\Models\WorkflowStep::where('status', 'pending')
                ->whereHas('workflow', function ($q) use ($user) {
                    $q->where('tenant_id', $user->tenant_id);
                })
                ->count(),
        ];
    }

    /**
     * Vendor statistics
     */
    protected function getVendorStats(User $user): array
    {
        return [
            'total_services' => 0, // Placeholder - implement based on your vendor model
            'active_contracts' => 0,
            'revenue_this_month' => 0,
        ];
    }

    /**
     * Customer statistics
     */
    protected function getCustomerStats(User $user): array
    {
        return [
            'total_bookings' => $user->bookings()->count(),
            'upcoming_bookings' => $user->bookings()
                ->where('status', 'confirmed')
                ->where('scheduled_at', '>', now())
                ->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
        ];
    }
}
