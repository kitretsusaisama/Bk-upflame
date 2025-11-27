<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a default tenant exists
        $tenant = \App\Domains\Identity\Models\Tenant::firstOrCreate(
            ['domain' => 'system.local'],
            [
                'name' => 'System Tenant',
                'slug' => 'system',
                'status' => 'active',
                'tier' => 'enterprise',
            ]
        );

        // Define Roles
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Platform Administrator with full access',
                'priority' => 1,
                'is_system' => true,
                'role_family' => 'Internal',
                'permissions' => ['*'], // Special wildcard handling or all permissions
            ],
            [
                'name' => 'Tenant Admin',
                'description' => 'Organization Administrator',
                'priority' => 10,
                'is_system' => true,
                'role_family' => 'Internal',
                'permissions' => [
                    'users.*', 'roles.*', 'finance.*', 'hr.*', 'operations.*', 
                    'appointments.*', 'inventory.*', 'notifications.*', 'reports.*', 'settings.*'
                ],
            ],
            [
                'name' => 'Operations Manager',
                'description' => 'Manages day-to-day operations',
                'priority' => 20,
                'is_system' => false,
                'role_family' => 'Internal',
                'permissions' => [
                    'users.view', 'finance.view', 'operations.*', 'appointments.*', 
                    'inventory.*', 'reports.view'
                ],
            ],
            [
                'name' => 'Finance Manager',
                'description' => 'Manages financial records',
                'priority' => 20,
                'is_system' => false,
                'role_family' => 'Internal',
                'permissions' => [
                    'finance.*', 'reports.view', 'reports.export'
                ],
            ],
            [
                'name' => 'HR Manager',
                'description' => 'Manages employees and HR',
                'priority' => 20,
                'is_system' => false,
                'role_family' => 'Internal',
                'permissions' => [
                    'hr.*', 'users.view', 'users.create', 'users.update'
                ],
            ],
            [
                'name' => 'Staff',
                'description' => 'General staff member',
                'priority' => 50,
                'is_system' => false,
                'role_family' => 'Internal',
                'permissions' => [
                    'appointments.view', 'appointments.create', 'inventory.view'
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name'], 'tenant_id' => $tenant->id],
                [
                    'description' => $roleData['description'],
                    'priority' => $roleData['priority'],
                    'is_system' => $roleData['is_system'],
                    'role_family' => $roleData['role_family'],
                ]
            );

            // Assign Permissions
            $permissionsToSync = [];
            
            if (in_array('*', $roleData['permissions'])) {
                // Assign all permissions
                $permissionsToSync = Permission::all()->pluck('id')->toArray();
            } else {
                foreach ($roleData['permissions'] as $permPattern) {
                    if (str_ends_with($permPattern, '.*')) {
                        // Wildcard module match
                        $module = explode('.', $permPattern)[0];
                        $perms = Permission::where('resource', $module)->pluck('id')->toArray();
                        $permissionsToSync = array_merge($permissionsToSync, $perms);
                    } else {
                        // Exact match
                        $perm = Permission::where('name', $permPattern)->first();
                        if ($perm) {
                            $permissionsToSync[] = $perm->id;
                        }
                    }
                }
            }

            $role->permissions()->sync(array_unique($permissionsToSync));
        }
    }
}