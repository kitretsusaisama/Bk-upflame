<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate menus table to start fresh
        DB::table('menus')->truncate();

        $menus = [
            // ==========================================
            // SUPER ADMIN (Platform Scope)
            // ==========================================
            [
                'group' => 'Platform',
                'group_order' => 10,
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'heroicons:home',
                'scope' => 'platform',
                'required_permissions' => [],
                'sort_order' => 1,
            ],
            [
                'group' => 'Platform',
                'group_order' => 10,
                'label' => 'Tenants',
                'route' => 'admin.tenants.index',
                'icon' => 'heroicons:building-office',
                'scope' => 'platform',
                'required_permissions' => ['tenants.view'],
                'sort_order' => 2,
            ],
            [
                'group' => 'System',
                'group_order' => 90,
                'label' => 'Audit Logs',
                'route' => 'audit-logs.index',
                'icon' => 'heroicons:clipboard-document-list',
                'scope' => 'platform',
                'required_permissions' => ['audit_logs.view'],
                'sort_order' => 1,
            ],
            [
                'group' => 'System',
                'group_order' => 90,
                'label' => 'Settings',
                'route' => 'settings.index',
                'icon' => 'heroicons:cog-6-tooth',
                'scope' => 'platform',
                'required_permissions' => ['settings.view'],
                'sort_order' => 2,
            ],

            // ==========================================
            // TENANT ADMIN (Tenant Scope)
            // ==========================================
            [
                'group' => 'Organization',
                'group_order' => 10,
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'heroicons:home',
                'scope' => 'tenant',
                'required_permissions' => [],
                'sort_order' => 1,
            ],
            [
                'group' => 'Organization',
                'group_order' => 10,
                'label' => 'Profile',
                'route' => 'tenant.organization.show',
                'icon' => 'heroicons:building-library',
                'scope' => 'tenant',
                'required_permissions' => ['settings.update'], // Assuming tenant admin has this
                'sort_order' => 2,
            ],
            [
                'group' => 'Organization',
                'group_order' => 10,
                'label' => 'Users',
                'route' => 'tenant.users.index',
                'icon' => 'heroicons:users',
                'scope' => 'tenant',
                'required_permissions' => ['users.view'],
                'sort_order' => 3,
            ],
            [
                'group' => 'Organization',
                'group_order' => 10,
                'label' => 'Roles',
                'route' => 'tenant.roles.index',
                'icon' => 'heroicons:shield-check',
                'scope' => 'tenant',
                'required_permissions' => ['roles.view'],
                'sort_order' => 4,
            ],

            // ==========================================
            // OPERATIONS (Tenant Scope)
            // ==========================================
            [
                'group' => 'Operations',
                'group_order' => 20,
                'label' => 'Appointments',
                'route' => 'appointments.index',
                'icon' => 'heroicons:calendar',
                'scope' => 'tenant',
                'required_permissions' => ['appointments.view'],
                'sort_order' => 1,
            ],
            [
                'group' => 'Operations',
                'group_order' => 20,
                'label' => 'Inventory',
                'route' => 'inventory.index',
                'icon' => 'heroicons:archive-box',
                'scope' => 'tenant',
                'required_permissions' => ['inventory.view'],
                'sort_order' => 2,
            ],

            // ==========================================
            // FINANCE (Tenant Scope)
            // ==========================================
            [
                'group' => 'Finance',
                'group_order' => 30,
                'label' => 'Invoices',
                'route' => 'finance.invoices',
                'icon' => 'heroicons:document-currency-dollar',
                'scope' => 'tenant',
                'required_permissions' => ['finance.view'],
                'sort_order' => 1,
            ],
            [
                'group' => 'Finance',
                'group_order' => 30,
                'label' => 'Payments',
                'route' => 'finance.payments',
                'icon' => 'heroicons:credit-card',
                'scope' => 'tenant',
                'required_permissions' => ['finance.view'],
                'sort_order' => 2,
            ],
        ];

        foreach ($menus as $menuData) {
            $this->createMenu($menuData);
        }
    }

    protected function createMenu(array $data, ?string $parentId = null): void
    {
        $menu = Menu::create([
            'parent_id' => $parentId,
            'label' => $data['label'],
            'route' => $data['route'] ?? null,
            'url' => $data['url'] ?? null,
            'icon' => $data['icon'] ?? null,
            'group' => $data['group'] ?? null,
            'group_order' => $data['group_order'] ?? 0,
            'sort_order' => $data['sort_order'] ?? 0,
            'scope' => $data['scope'] ?? 'both',
            'required_permissions' => $data['required_permissions'] ?? null,
            'is_active' => true,
        ]);

        if (isset($data['children'])) {
            foreach ($data['children'] as $childData) {
                // Inherit group and scope from parent if not set
                $childData['group'] = $childData['group'] ?? $data['group'];
                $childData['group_order'] = $childData['group_order'] ?? $data['group_order'];
                $childData['scope'] = $childData['scope'] ?? $data['scope'];
                
                $this->createMenu($childData, $menu->id);
            }
        }
    }
}
