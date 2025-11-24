<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have permissions and roles
        $this->command->info('Seeding role-permission assignments...');
        
        // Get all roles with full model data
        $roles = Role::all();
        
        // Get all permissions
        $permissions = Permission::all();
        
        // If we don't have roles or permissions, exit early
        if ($roles->isEmpty() || $permissions->isEmpty()) {
            $this->command->warn('No roles or permissions found. Skipping role-permission assignments.');
            return;
        }
        
        // For each role, assign relevant permissions
        foreach ($roles as $role) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($role->id);
            if ($freshRole) {
                $this->assignPermissionsToRole($freshRole, $permissions);
            } else {
                $this->command->warn("Could not find role with ID: {$role->id}");
            }
        }
        
        $this->command->info('Role-permission assignments completed successfully!');
    }
    
    /**
     * Assign permissions to a role based on role name
     */
    private function assignPermissionsToRole(Role $role, $allPermissions): void
    {
        $validPermissionIds = [];
        
        // Define role-specific permissions
        switch (strtolower($role->name)) {
            case 'super admin':
                // Super admin gets all permissions
                $validPermissionIds = $this->getValidPermissionIds($allPermissions->pluck('id')->toArray());
                break;
                
            case 'tenant admin':
                // Tenant admin gets specific permissions
                $relevantPermissions = $allPermissions->filter(function ($permission) {
                    return in_array($permission->resource, [
                        'booking', 'provider', 'service', 'user', 'role', 'permission', 'workflow', 'notification', 'admin', 'tenant',
                        'dashboard', 'system', 'system-user', 'system-report', 'tenant-user', 'approval', 'profile', 'security'
                    ]) || Str::startsWith($permission->name, [
                        'view-tenantadmin-dashboard', 'manage-tenant', 'manage-providers', 'manage-bookings',
                        'manage-roles', 'manage-permissions', 'manage-customer-account', 'view-customer-bookings',
                        'update-profile', 'manage-security'
                    ]);
                });
                $validPermissionIds = $this->getValidPermissionIds($relevantPermissions->pluck('id')->toArray());
                break;
                
            case 'provider':
                // Provider gets specific permissions
                $relevantPermissions = $allPermissions->filter(function ($permission) {
                    return in_array($permission->resource, [
                        'booking', 'provider-service', 'provider-booking', 'provider-schedule'
                    ]) || Str::startsWith($permission->name, [
                        'view-provider-dashboard', 'manage-provider-services', 'view-provider-bookings',
                        'manage-provider-schedule'
                    ]);
                });
                $validPermissionIds = $this->getValidPermissionIds($relevantPermissions->pluck('id')->toArray());
                break;
                
            case 'operations':
                // Operations gets workflow and approval permissions
                $relevantPermissions = $allPermissions->filter(function ($permission) {
                    return in_array($permission->resource, [
                        'workflow', 'approval'
                    ]) || Str::startsWith($permission->name, [
                        'view-ops-dashboard', 'view-workflows', 'manage-workflows', 'manage-approvals'
                    ]);
                });
                $validPermissionIds = $this->getValidPermissionIds($relevantPermissions->pluck('id')->toArray());
                break;
                
            case 'customer':
            case 'premium customer':
                // Customer gets booking and service permissions
                $relevantPermissions = $allPermissions->filter(function ($permission) {
                    return in_array($permission->resource, [
                        'booking', 'service', 'customer-account', 'customer-booking'
                    ]) || Str::startsWith($permission->name, [
                        'view-customer-dashboard', 'manage-customer-account', 'view-customer-bookings',
                        'browse-services'
                    ]);
                });
                $validPermissionIds = $this->getValidPermissionIds($relevantPermissions->pluck('id')->toArray());
                break;
                
            default:
                // For other roles, assign view permissions only
                $relevantPermissions = $allPermissions->filter(function ($permission) {
                    return $permission->action === 'view' || $permission->action === 'list' || $permission->action === 'read';
                });
                $validPermissionIds = $this->getValidPermissionIds($relevantPermissions->pluck('id')->toArray());
                break;
        }
        
        // Only sync if we have valid permissions
        if (!empty($validPermissionIds)) {
            try {
                // Before syncing, ensure all permissions actually exist
                $existingPermissionIds = Permission::whereIn('id', $validPermissionIds)->pluck('id')->toArray();
                
                if (!empty($existingPermissionIds)) {
                    // Refresh the role one more time before syncing
                    $freshRole = Role::find($role->id);
                    if ($freshRole) {
                        $freshRole->permissions()->sync($existingPermissionIds);
                        $this->command->info("Assigned " . count($existingPermissionIds) . " permissions to role: {$role->name}");
                    } else {
                        $this->command->warn("Could not refresh role before syncing permissions: {$role->name}");
                    }
                } else {
                    $this->command->warn("No existing permissions found for role: {$role->name}");
                }
            } catch (\Exception $e) {
                $this->command->error("Failed to assign permissions to role {$role->name}: " . $e->getMessage());
                Log::error("Seeder error assigning permissions to role {$role->name}", [
                    'role_id' => $role->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            $this->command->warn("No valid permissions found for role: {$role->name}");
        }
    }
    
    /**
     * Validate permission IDs and return only existing ones
     *
     * @param array $permissionIds
     * @return array
     */
    private function getValidPermissionIds(array $permissionIds): array
    {
        $validPermissionIds = [];
        $invalidPermissionIds = [];

        foreach ($permissionIds as $permissionId) {
            // Check if the permission exists in the database
            if (Permission::where('id', $permissionId)->exists()) {
                $validPermissionIds[] = $permissionId;
            } else {
                $invalidPermissionIds[] = $permissionId;
            }
        }

        // Log invalid permission IDs
        if (!empty($invalidPermissionIds)) {
            $this->command->warn('Skipping invalid permission IDs: ' . implode(', ', $invalidPermissionIds));
            Log::warning('Seeder skipping invalid permission IDs', [
                'invalid_permission_ids' => $invalidPermissionIds
            ]);
        }

        return $validPermissionIds;
    }
}