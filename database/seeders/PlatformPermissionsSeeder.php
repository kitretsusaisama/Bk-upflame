<?php

namespace Database\Seeders;

use App\Domains\Access\Models\Permission;
use Illuminate\Database\Seeder;

class PlatformPermissionsSeeder extends Seeder
{
    /**
     * Seed platform-level and tenant-level permissions for RBAC UI
     */
    public function run(): void
    {
        $permissions = [
            // ==========================================
            // PLATFORM-LEVEL PERMISSIONS (Super Admin)
            // ==========================================
            
            // Role Management
            ['name' => 'platform.role.view', 'resource' => 'platform_role', 'action' => 'view', 'description' => 'View all roles across all tenants', 'tenant_id' => null],
            ['name' => 'platform.role.create', 'resource' => 'platform_role', 'action' => 'create', 'description' => 'Create platform-level roles', 'tenant_id' => null],
            ['name' => 'platform.role.update', 'resource' => 'platform_role', 'action' => 'update', 'description' => 'Update any role', 'tenant_id' => null],
            ['name' => 'platform.role.delete', 'resource' => 'platform_role', 'action' => 'delete', 'description' => 'Delete any role', 'tenant_id' => null],
            
            // Permission Management
            ['name' => 'platform.permission.view', 'resource' => 'platform_permission', 'action' => 'view', 'description' => 'View all permissions', 'tenant_id' => null],
            ['name' => 'platform.permission.create', 'resource' => 'platform_permission', 'action' => 'create', 'description' => 'Create new permissions', 'tenant_id' => null],
            ['name' => 'platform.permission.assign', 'resource' => 'platform_permission', 'action' => 'assign', 'description' => 'Assign permissions to roles', 'tenant_id' => null],
            ['name' => 'platform.permission.revoke', 'resource' => 'platform_permission', 'action' => 'revoke', 'description' => 'Revoke permissions from roles', 'tenant_id' => null],
            
            // User Management (Cross-Tenant)
            ['name' => 'platform.user.view', 'resource' => 'platform_user', 'action' => 'view', 'description' => 'View all users across all tenants', 'tenant_id' => null],
            ['name' => 'platform.user.create', 'resource' => 'platform_user', 'action' => 'create', 'description' => 'Create users in any tenant', 'tenant_id' => null],
            ['name' => 'platform.user.update', 'resource' => 'platform_user', 'action' => 'update', 'description' => 'Update any user', 'tenant_id' => null],
            ['name' => 'platform.user.delete', 'resource' => 'platform_user', 'action' => 'delete', 'description' => 'Delete any user', 'tenant_id' => null],
            ['name' => 'platform.user.activate', 'resource' => 'platform_user', 'action' => 'activate', 'description' => 'Activate/deactivate users', 'tenant_id' => null],
            ['name' => 'platform.user.assign_role', 'resource' => 'platform_user', 'action' => 'assign_role', 'description' => 'Assign roles to users', 'tenant_id' => null],
            
            // ==========================================
            // TENANT-LEVEL PERMISSIONS (Tenant Admin)
            // ==========================================
            
            // Role Management (Tenant-Scoped)
            ['name' => 'tenant.role.view', 'resource' => 'tenant_role', 'action' => 'view', 'description' => 'View roles within tenant', 'tenant_id' => null],
            ['name' => 'tenant.role.create', 'resource' => 'tenant_role', 'action' => 'create', 'description' => 'Create tenant-scoped roles', 'tenant_id' => null],
            ['name' => 'tenant.role.update', 'resource' => 'tenant_role', 'action' => 'update', 'description' => 'Update tenant roles', 'tenant_id' => null],
            ['name' => 'tenant.role.delete', 'resource' => 'tenant_role', 'action' => 'delete', 'description' => 'Delete tenant roles', 'tenant_id' => null],
            
            // User Management (Tenant-Scoped)
            ['name' => 'tenant.user.view', 'resource' => 'tenant_user', 'action' => 'view', 'description' => 'View users within tenant', 'tenant_id' => null],
            ['name' => 'tenant.user.create', 'resource' => 'tenant_user', 'action' => 'create', 'description' => 'Create users in tenant', 'tenant_id' => null],
            ['name' => 'tenant.user.update', 'resource' => 'tenant_user', 'action' => 'update', 'description' => 'Update tenant users', 'tenant_id' => null],
            ['name' => 'tenant.user.delete', 'resource' => 'tenant_user', 'action' => 'delete', 'description' => 'Delete tenant users', 'tenant_id' => null],
            ['name' => 'tenant.user.assign_role', 'resource' => 'tenant_user', 'action' => 'assign_role', 'description' => 'Assign roles to tenant users', 'tenant_id' => null],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        $this->command->info('Platform and tenant permissions seeded successfully.');
    }
}
