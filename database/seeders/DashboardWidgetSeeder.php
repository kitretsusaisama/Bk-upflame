<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Dashboard\Models\DashboardWidget;
use App\Domains\Access\Models\Permission;
use Illuminate\Support\Str;

class DashboardWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get permissions for conditional widgets
        $viewStatsPermission = Permission::where('name', 'view_stats')->first();
        $viewReportsPermission = Permission::where('name', 'view_reports')->first();

        // Stats Card Widget (accessible to all)
        DashboardWidget::create([
            'id' => Str::uuid(),
            'key' => 'stats_overview',
            'label' => 'Statistics Overview',
            'component' => 'StatsCard',
            'permission_id' => null,
            'config' => [
                'refreshInterval' => 300, // 5 minutes
            ],
            'is_active' => true,
            'sort_order' => 1,
            'size' => 'full',
        ]);

        // Recent Activity Widget
        DashboardWidget::create([
            'id' => Str::uuid(),
            'key' => 'recent_activity',
            'label' => 'Recent Activity',
            'component' => 'ActivityFeed',
            'permission_id' => null,
            'config' => [
                'limit' => 10,
            ],
            'is_active' => true,
            'sort_order' => 2,
            'size' => 'medium',
        ]);

        // Analytics Chart (for users with view reports permission)
        if ($viewReportsPermission) {
            DashboardWidget::create([
                'id' => Str::uuid(),
                'key' => 'analytics_chart',
                'label' => 'Analytics Overview',
                'component' => 'ChartWidget',
                'permission_id' => $viewReportsPermission->id,
                'config' => [
                    'chartType' => 'line',
                    'period' => '30days',
                ],
                'is_active' => true,
                'sort_order' => 3,
                'size' => 'large',
            ]);
        }

        // Quick Actions Widget
        DashboardWidget::create([
            'id' => Str::uuid(),
            'key' => 'quick_actions',
            'label' => 'Quick Actions',
            'component' => 'QuickActions',
            'permission_id' => null,
            'config' => [
                'actions' => [
                    ['label' => 'New Booking', 'icon' => '➕', 'route' => 'dashboard'],
                    ['label' => 'View Profile', 'icon' => '👤', 'route' => 'dashboard'],
                ],
            ],
            'is_active' => true,
            'sort_order' => 4,
            'size' => 'small',
        ]);

        $this->command->info('Dashboard widgets seeded successfully.');
    }
}
