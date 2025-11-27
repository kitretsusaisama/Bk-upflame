<?php

namespace Database\Seeders;

use App\Domains\Access\Models\Menu;
use Illuminate\Database\Seeder;

class RBACMenuSeeder extends Seeder
{
    /**
     * Seed menu entries for Super Admin and Tenant Admin RBAC UI
     */
    public function run(): void
    {
        $menus = [
            // ==========================================
            // SUPER ADMIN MENUS (Platform Scope)
            // ==========================================
            
            [
                'label' => 'Roles & Permissions',
                'route' => 'admin.roles.index',
                'icon' => 'fa-shield-alt',
                'group' => 'Access Control',
                'group_order' => 1,
                'sort_order' => 1,
                'scope' => 'platform',
                'required_permissions' => ['platform.role.view'],
                'is_active' => true,
            ],
            
            [
                'label' => 'Permissions',
                'route' => 'admin.permissions.index',
                'icon' => 'fa-key',
                'group' => 'Access Control',
                'group_order' => 1,
                'sort_order' => 2,
                'scope' => 'platform',
                'required_permissions' => ['platform.permission.view'],
                'is_active' => true,
            ],
            
            [
                'label' => 'Users',
                'route' => 'admin.users.index',
                'icon' => 'fa-users',
                'group' => 'Access Control',
                'group_order' => 1,
                'sort_order' => 3,
                'scope' => 'platform',
                'required_permissions' => ['platform.user.view'],
                'is_active' => true,
            ],
            
            // ==========================================
            // TENANT ADMIN MENUS (Tenant Scope)
            // ==========================================
            
            [
                'label' => 'Roles',
                'route' => 'tenant.roles.index',
                'icon' => 'fa-user-shield',
                'group' => 'Organization',
                'group_order' => 2,
                'sort_order' => 1,
                'scope' => 'tenant',
                'required_permissions' => ['tenant.role.view'],
                'is_active' => true,
            ],
            
            [
                'label' => 'Team Members',
                'route' => 'tenant.users.index',
                'icon' => 'fa-user-friends',
                'group' => 'Organization',
                'group_order' => 2,
                'sort_order' => 2,
                'scope' => 'tenant',
                'required_permissions' => ['tenant.user.view'],
                'is_active' => true,
            ],
        ];

        foreach ($menus as $menuData) {
            Menu::firstOrCreate(
                [
                    'route' => $menuData['route'],
                    'scope' => $menuData['scope'],
                ],
                $menuData
            );
        }

        $this->command->info('RBAC menu entries seeded successfully.');
    }
}
