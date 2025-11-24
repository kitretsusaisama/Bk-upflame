<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default permissions
        $permissions = [
            // Tenant permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Tenants',
                'resource' => 'tenant',
                'action' => 'read',
                'description' => 'View tenant information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Tenants',
                'resource' => 'tenant',
                'action' => 'write',
                'description' => 'Create, update, and delete tenants'
            ],
            
            // User permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Users',
                'resource' => 'user',
                'action' => 'read',
                'description' => 'View user information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Users',
                'resource' => 'user',
                'action' => 'write',
                'description' => 'Create, update, and delete users'
            ],
            
            // Role permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Roles',
                'resource' => 'role',
                'action' => 'read',
                'description' => 'View role information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Roles',
                'resource' => 'role',
                'action' => 'write',
                'description' => 'Create, update, and delete roles'
            ],
            
            // Permission permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Permissions',
                'resource' => 'permission',
                'action' => 'read',
                'description' => 'View permission information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Permissions',
                'resource' => 'permission',
                'action' => 'write',
                'description' => 'Create, update, and delete permissions'
            ],
            
            // Provider permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Providers',
                'resource' => 'provider',
                'action' => 'read',
                'description' => 'View provider information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Providers',
                'resource' => 'provider',
                'action' => 'write',
                'description' => 'Create, update, and delete providers'
            ],
            
            // Booking permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Bookings',
                'resource' => 'booking',
                'action' => 'read',
                'description' => 'View booking information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Bookings',
                'resource' => 'booking',
                'action' => 'write',
                'description' => 'Create, update, and delete bookings'
            ],
            
            // Workflow permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'View Workflows',
                'resource' => 'workflow',
                'action' => 'read',
                'description' => 'View workflow information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Workflows',
                'resource' => 'workflow',
                'action' => 'write',
                'description' => 'Create, update, and delete workflows'
            ],
            
            // Dashboard permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-superadmin-dashboard',
                'resource' => 'dashboard',
                'action' => 'read',
                'description' => 'View Super Admin dashboard'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-tenantadmin-dashboard',
                'resource' => 'dashboard',
                'action' => 'read',
                'description' => 'View Tenant Admin dashboard'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-provider-dashboard',
                'resource' => 'dashboard',
                'action' => 'read',
                'description' => 'View Provider dashboard'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-ops-dashboard',
                'resource' => 'dashboard',
                'action' => 'read',
                'description' => 'View Operations dashboard'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-customer-dashboard',
                'resource' => 'dashboard',
                'action' => 'read',
                'description' => 'View Customer dashboard'
            ],
            
            // System management permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-system',
                'resource' => 'system',
                'action' => 'write',
                'description' => 'Manage system settings'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-system-users',
                'resource' => 'system-user',
                'action' => 'write',
                'description' => 'Manage system users'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-system-reports',
                'resource' => 'system-report',
                'action' => 'read',
                'description' => 'View system reports'
            ],
            
            // Tenant management permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-tenant',
                'resource' => 'tenant',
                'action' => 'write',
                'description' => 'Manage tenant settings'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-tenant-users',
                'resource' => 'tenant-user',
                'action' => 'write',
                'description' => 'Manage tenant users'
            ],
            
            // Provider service permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-provider-services',
                'resource' => 'provider-service',
                'action' => 'write',
                'description' => 'Manage provider services'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-provider-bookings',
                'resource' => 'provider-booking',
                'action' => 'read',
                'description' => 'View provider bookings'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-provider-schedule',
                'resource' => 'provider-schedule',
                'action' => 'write',
                'description' => 'Manage provider schedule'
            ],
            
            // Operations permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-approvals',
                'resource' => 'approval',
                'action' => 'write',
                'description' => 'Manage approvals'
            ],
            
            // Customer permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'manage-customer-account',
                'resource' => 'customer-account',
                'action' => 'write',
                'description' => 'Manage customer account'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'view-customer-bookings',
                'resource' => 'customer-booking',
                'action' => 'read',
                'description' => 'View customer bookings'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'browse-services',
                'resource' => 'service',
                'action' => 'read',
                'description' => 'Browse services'
            ],
            
            // Profile and security permissions
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Update Profile',
                'resource' => 'profile',
                'action' => 'write',
                'description' => 'Update user profile information'
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Manage Security',
                'resource' => 'security',
                'action' => 'write',
                'description' => 'Manage security settings including password and 2FA'
            ]
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }
    }
}