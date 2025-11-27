<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define modules and their actions
        $modules = [
            'users' => ['view', 'create', 'update', 'delete', 'manage', 'export'],
            'roles' => ['view', 'create', 'update', 'delete', 'manage'],
            'permissions' => ['view', 'manage'],
            'finance' => ['view', 'create', 'update', 'delete', 'export', 'approve'],
            'hr' => ['view', 'create', 'update', 'delete', 'manage'],
            'operations' => ['view', 'create', 'update', 'delete', 'manage', 'approve'],
            'appointments' => ['view', 'create', 'update', 'delete', 'manage', 'cancel'],
            'inventory' => ['view', 'create', 'update', 'delete', 'manage'],
            'notifications' => ['view', 'create', 'send', 'manage'],
            'audit_logs' => ['view', 'export'],
            'reports' => ['view', 'export'],
            'settings' => ['view', 'update'],
            'tenants' => ['view', 'create', 'update', 'delete', 'manage'], // Super Admin only
        ];

        $permissions = [];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => "{$module}.{$action}",
                    'resource' => $module,
                    'action' => $action,
                    'description' => ucfirst($action) . ' ' . ucfirst($module),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert permissions avoiding duplicates
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}