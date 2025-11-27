<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\DashboardWidget;
use App\Domains\Access\Models\Permission;

class WidgetSeeder extends Seeder
{
    public function run(): void
    {
        $widgets = [
            [
                'key' => 'stats_overview',
                'label' => 'Statistics Overview',
                'component' => 'StatsOverviewWidget',
                'permission' => 'reports.view',
                'size' => 'full',
                'sort_order' => 1,
            ],
            [
                'key' => 'recent_appointments',
                'label' => 'Recent Appointments',
                'component' => 'RecentAppointmentsWidget',
                'permission' => 'appointments.view',
                'size' => 'medium',
                'sort_order' => 2,
            ],
            [
                'key' => 'revenue_chart',
                'label' => 'Revenue Chart',
                'component' => 'RevenueChartWidget',
                'permission' => 'finance.view',
                'size' => 'medium',
                'sort_order' => 3,
            ],
            [
                'key' => 'pending_tasks',
                'label' => 'Pending Tasks',
                'component' => 'TaskListWidget',
                'permission' => 'operations.view',
                'size' => 'small',
                'sort_order' => 4,
            ],
            [
                'key' => 'system_health',
                'label' => 'System Health',
                'component' => 'SystemHealthWidget',
                'permission' => 'settings.view',
                'size' => 'small',
                'sort_order' => 5,
            ],
        ];

        foreach ($widgets as $widgetData) {
            $permissionId = null;
            if (isset($widgetData['permission'])) {
                $permission = Permission::where('name', $widgetData['permission'])->first();
                $permissionId = $permission ? $permission->id : null;
            }

            DashboardWidget::create([
                'key' => $widgetData['key'],
                'label' => $widgetData['label'],
                'component' => $widgetData['component'],
                'permission_id' => $permissionId,
                'size' => $widgetData['size'],
                'sort_order' => $widgetData['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
