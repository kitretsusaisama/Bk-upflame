<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\UserProfile;
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;
use App\Domains\Provider\Models\Provider;
use App\Domains\Booking\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or retrieve default tenant
        $tenant = Tenant::firstOrCreate([
            'domain' => 'default.local'
        ], [
            'name' => 'Default Tenant',
            'slug' => 'default-tenant',
            'status' => 'active',
            'config_json' => json_encode([
                'timezone' => 'Asia/Kolkata',
                'locale' => 'en',
                'currency' => 'INR'
            ])
        ]);

        // Create permissions
        $permissions = $this->createPermissions();
        
        // Create roles
        $roles = $this->createRoles($tenant, $permissions);
        
        // Create users
        $users = $this->createUsers($tenant, $roles);
        
        // Create providers
        $providers = $this->createProviders($tenant, $users);
        
        // Create services
        $services = $this->createServices($tenant);

        $this->command->info('Production data seeded successfully!');
    }

    /**
     * Create permissions for the application
     */
    private function createPermissions()
    {
        $permissionData = [
            // Booking permissions
            ['name' => 'booking.create', 'resource' => 'booking', 'action' => 'create', 'description' => 'Create bookings'],
            ['name' => 'booking.update', 'resource' => 'booking', 'action' => 'update', 'description' => 'Update bookings'],
            ['name' => 'booking.cancel', 'resource' => 'booking', 'action' => 'cancel', 'description' => 'Cancel bookings'],
            ['name' => 'booking.view', 'resource' => 'booking', 'action' => 'view', 'description' => 'View bookings'],
            ['name' => 'booking.list', 'resource' => 'booking', 'action' => 'list', 'description' => 'List bookings'],
            
            // Provider permissions
            ['name' => 'provider.create', 'resource' => 'provider', 'action' => 'create', 'description' => 'Create providers'],
            ['name' => 'provider.update', 'resource' => 'provider', 'action' => 'update', 'description' => 'Update providers'],
            ['name' => 'provider.view', 'resource' => 'provider', 'action' => 'view', 'description' => 'View providers'],
            ['name' => 'provider.list', 'resource' => 'provider', 'action' => 'list', 'description' => 'List providers'],
            ['name' => 'provider.approve', 'resource' => 'provider', 'action' => 'approve', 'description' => 'Approve providers'],
            ['name' => 'provider.reject', 'resource' => 'provider', 'action' => 'reject', 'description' => 'Reject providers'],
            
            // Service permissions
            ['name' => 'service.create', 'resource' => 'service', 'action' => 'create', 'description' => 'Create services'],
            ['name' => 'service.update', 'resource' => 'service', 'action' => 'update', 'description' => 'Update services'],
            ['name' => 'service.view', 'resource' => 'service', 'action' => 'view', 'description' => 'View services'],
            ['name' => 'service.list', 'resource' => 'service', 'action' => 'list', 'description' => 'List services'],
            
            // User permissions
            ['name' => 'user.create', 'resource' => 'user', 'action' => 'create', 'description' => 'Create users'],
            ['name' => 'user.update', 'resource' => 'user', 'action' => 'update', 'description' => 'Update users'],
            ['name' => 'user.view', 'resource' => 'user', 'action' => 'view', 'description' => 'View users'],
            ['name' => 'user.list', 'resource' => 'user', 'action' => 'list', 'description' => 'List users'],
            ['name' => 'user.assign.role', 'resource' => 'user', 'action' => 'assign_role', 'description' => 'Assign roles to users'],
            
            // Role permissions
            ['name' => 'role.create', 'resource' => 'role', 'action' => 'create', 'description' => 'Create roles'],
            ['name' => 'role.update', 'resource' => 'role', 'action' => 'update', 'description' => 'Update roles'],
            ['name' => 'role.view', 'resource' => 'role', 'action' => 'view', 'description' => 'View roles'],
            ['name' => 'role.list', 'resource' => 'role', 'action' => 'list', 'description' => 'List roles'],
            ['name' => 'role.assign.permission', 'resource' => 'role', 'action' => 'assign_permission', 'description' => 'Assign permissions to roles'],
            
            // Workflow permissions
            ['name' => 'workflow.view', 'resource' => 'workflow', 'action' => 'view', 'description' => 'View workflows'],
            ['name' => 'workflow.approve', 'resource' => 'workflow', 'action' => 'approve', 'description' => 'Approve workflow steps'],
            ['name' => 'workflow.reject', 'resource' => 'workflow', 'action' => 'reject', 'description' => 'Reject workflow steps'],
            
            // Notification permissions
            ['name' => 'notification.send', 'resource' => 'notification', 'action' => 'send', 'description' => 'Send notifications'],
            ['name' => 'notification.view', 'resource' => 'notification', 'action' => 'view', 'description' => 'View notifications'],
            
            // Admin permissions
            ['name' => 'admin.access', 'resource' => 'admin', 'action' => 'access', 'description' => 'Access admin panel'],
            ['name' => 'tenant.manage', 'resource' => 'tenant', 'action' => 'manage', 'description' => 'Manage tenants'],
        ];

        $permissions = [];
        foreach ($permissionData as $permission) {
            // Check if permission already exists
            $existingPermission = Permission::where('name', $permission['name'])->first();
            
            if ($existingPermission) {
                $permissions[$permission['name']] = $existingPermission;
            } else {
                // Explicitly set the UUID before creating the permission
                $permission['id'] = \Illuminate\Support\Str::uuid()->toString();
                // Create new permission with explicit UUID
                $newPermission = Permission::create($permission);
                $permissions[$permission['name']] = $newPermission;
            }
        }

        return $permissions;
    }

    /**
     * Create roles for the application
     */
    private function createRoles($tenant, $permissions)
    {
        // Super Admin Role
        $superAdminRole = Role::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Super Admin',
            'role_family' => 'Internal'
        ], [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'Super administrator with full access to all features',
            'is_system' => true
        ]);

        $this->command->info('Super Admin Role ID: ' . $superAdminRole->id);

        // Attach all permissions to Super Admin
        $permissionIds = collect(array_values($permissions))->pluck('id')->toArray();
        $validPermissionIds = $this->getValidPermissionIds($permissionIds);
        $this->command->info('Super Admin Valid Permission IDs: ' . implode(', ', $validPermissionIds));
        $superAdminRole->permissions()->sync($validPermissionIds);

        // Tenant Admin Role
        $tenantAdminRole = Role::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Tenant Admin',
            'role_family' => 'Internal'
        ], [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'Tenant administrator with access to tenant management features',
            'is_system' => true
        ]);

        // Attach relevant permissions to Tenant Admin
        $tenantAdminPermissions = [
            'booking.create', 'booking.update', 'booking.cancel', 'booking.view', 'booking.list',
            'provider.create', 'provider.update', 'provider.view', 'provider.list', 'provider.approve', 'provider.reject',
            'service.create', 'service.update', 'service.view', 'service.list',
            'user.create', 'user.update', 'user.view', 'user.list', 'user.assign.role',
            'role.create', 'role.update', 'role.view', 'role.list', 'role.assign.permission',
            'workflow.view', 'workflow.approve', 'workflow.reject',
            'notification.send', 'notification.view',
            'admin.access'
        ];

        $tenantAdminPermissionIds = collect($tenantAdminPermissions)
            ->map(function ($permissionName) use ($permissions) {
                return $permissions[$permissionName]->id ?? null;
            })
            ->filter()
            ->values()
            ->toArray();

        $validTenantAdminPermissionIds = $this->getValidPermissionIds($tenantAdminPermissionIds);
        $tenantAdminRole->permissions()->sync($validTenantAdminPermissionIds);

        // Provider Role
        $providerRole = Role::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Provider',
            'role_family' => 'Provider'
        ], [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'Service provider role',
            'is_system' => true
        ]);

        // Attach relevant permissions to Provider
        $providerPermissions = [
            'booking.view', 'booking.list',
            'service.create', 'service.update', 'service.view', 'service.list'
        ];

        $providerPermissionIds = collect($providerPermissions)
            ->map(function ($permissionName) use ($permissions) {
                return $permissions[$permissionName]->id ?? null;
            })
            ->filter()
            ->values()
            ->toArray();

        $validProviderPermissionIds = $this->getValidPermissionIds($providerPermissionIds);
        $providerRole->permissions()->sync($validProviderPermissionIds);

        // Customer Role
        $customerRole = Role::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Customer',
            'role_family' => 'Customer'
        ], [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'End customer role',
            'is_system' => true
        ]);

        // Attach relevant permissions to Customer
        $customerPermissions = [
            'booking.create', 'booking.update', 'booking.cancel', 'booking.view', 'booking.list'
        ];

        $customerPermissionIds = collect($customerPermissions)
            ->map(function ($permissionName) use ($permissions) {
                return $permissions[$permissionName]->id ?? null;
            })
            ->filter()
            ->values()
            ->toArray();

        $validCustomerPermissionIds = $this->getValidPermissionIds($customerPermissionIds);
        $customerRole->permissions()->sync($validCustomerPermissionIds);

        // Premium Customer Role
        $premiumCustomerRole = Role::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Premium Customer',
            'role_family' => 'Customer'
        ], [
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'description' => 'Premium customer with additional privileges',
            'is_system' => true
        ]);

        // Attach relevant permissions to Premium Customer
        $premiumCustomerPermissions = [
            'booking.create', 'booking.update', 'booking.cancel', 'booking.view', 'booking.list'
        ];

        $premiumCustomerPermissionIds = collect($premiumCustomerPermissions)
            ->map(function ($permissionName) use ($permissions) {
                return $permissions[$permissionName]->id ?? null;
            })
            ->filter()
            ->values()
            ->toArray();

        $validPremiumCustomerPermissionIds = $this->getValidPermissionIds($premiumCustomerPermissionIds);
        $premiumCustomerRole->permissions()->sync($validPremiumCustomerPermissionIds);

        return [
            'super_admin' => $superAdminRole,
            'tenant_admin' => $tenantAdminRole,
            'provider' => $providerRole,
            'customer' => $customerRole,
            'premium_customer' => $premiumCustomerRole
        ];
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

    /**
     * Create users for the application
     */
    private function createUsers($tenant, $roles)
    {
        // Super Admin User
        $superAdminUser = User::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'superadmin@example.com'
        ], [
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $roles['super_admin']->id
        ]);

        // Create user profile for Super Admin
        $superAdminUser->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '+1234567890'
        ]);

        // Assign Super Admin role (only if role exists)
        if (Role::where('id', $roles['super_admin']->id)->exists()) {
            $superAdminUser->roles()->attach($roles['super_admin']->id, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'assigned_by' => $superAdminUser->id
            ]);
        } else {
            $this->command->warn('Skipping assignment of invalid Super Admin role ID: ' . $roles['super_admin']->id);
            Log::warning('Seeder skipping invalid Super Admin role ID', [
                'invalid_role_id' => $roles['super_admin']->id
            ]);
        }

        // Tenant Admin User
        $tenantAdminUser = User::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'admin@example.com'
        ], [
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $roles['tenant_admin']->id ?? null
        ]);

        // Create user profile for Tenant Admin
        $tenantAdminUser->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Tenant',
            'last_name' => 'Admin',
            'phone' => '+1234567891'
        ]);

        // Assign Tenant Admin role (only if role exists)
        if (isset($roles['tenant_admin']) && Role::where('id', $roles['tenant_admin']->id)->exists()) {
            $tenantAdminUser->roles()->attach($roles['tenant_admin']->id, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'assigned_by' => $superAdminUser->id
            ]);
        } else {
            $roleId = $roles['tenant_admin']->id ?? 'undefined';
            $this->command->warn('Skipping assignment of invalid Tenant Admin role ID: ' . $roleId);
            Log::warning('Seeder skipping invalid Tenant Admin role ID', [
                'invalid_role_id' => $roleId
            ]);
        }

        // Provider User
        $providerUser = User::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'provider@example.com'
        ], [
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $roles['provider']->id ?? null
        ]);

        // Create user profile for Provider
        $providerUser->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Service',
            'last_name' => 'Provider',
            'phone' => '+1234567892'
        ]);

        // Assign Provider role (only if role exists)
        if (isset($roles['provider']) && Role::where('id', $roles['provider']->id)->exists()) {
            $providerUser->roles()->attach($roles['provider']->id, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'assigned_by' => $tenantAdminUser->id
            ]);
        } else {
            $roleId = $roles['provider']->id ?? 'undefined';
            $this->command->warn('Skipping assignment of invalid Provider role ID: ' . $roleId);
            Log::warning('Seeder skipping invalid Provider role ID', [
                'invalid_role_id' => $roleId
            ]);
        }

        // Customer User
        $customerUser = User::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'customer@example.com'
        ], [
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $roles['customer']->id ?? null
        ]);

        // Create user profile for Customer
        $customerUser->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Regular',
            'last_name' => 'Customer',
            'phone' => '+1234567893'
        ]);

        // Assign Customer role (only if role exists)
        if (isset($roles['customer']) && Role::where('id', $roles['customer']->id)->exists()) {
            $customerUser->roles()->attach($roles['customer']->id, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'assigned_by' => $tenantAdminUser->id
            ]);
        } else {
            $roleId = $roles['customer']->id ?? 'undefined';
            $this->command->warn('Skipping assignment of invalid Customer role ID: ' . $roleId);
            Log::warning('Seeder skipping invalid Customer role ID', [
                'invalid_role_id' => $roleId
            ]);
        }

        // Premium Customer User
        $premiumCustomerUser = User::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'premium@example.com'
        ], [
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $roles['premium_customer']->id ?? null
        ]);

        // Create user profile for Premium Customer
        $premiumCustomerUser->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Premium',
            'last_name' => 'Customer',
            'phone' => '+1234567894'
        ]);

        // Assign Premium Customer role (only if role exists)
        if (isset($roles['premium_customer']) && Role::where('id', $roles['premium_customer']->id)->exists()) {
            $premiumCustomerUser->roles()->attach($roles['premium_customer']->id, [
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'assigned_by' => $tenantAdminUser->id
            ]);
        } else {
            $roleId = $roles['premium_customer']->id ?? 'undefined';
            $this->command->warn('Skipping assignment of invalid Premium Customer role ID: ' . $roleId);
            Log::warning('Seeder skipping invalid Premium Customer role ID', [
                'invalid_role_id' => $roleId
            ]);
        }

        return [
            'super_admin' => $superAdminUser,
            'tenant_admin' => $tenantAdminUser,
            'provider' => $providerUser,
            'customer' => $customerUser,
            'premium_customer' => $premiumCustomerUser
        ];
    }

    /**
     * Create providers for the application
     */
    private function createProviders($tenant, $users)
    {
        // Create a provider
        $provider = Provider::firstOrCreate([
            'tenant_id' => $tenant->id,
            'user_id' => $users['provider']->id
        ], [
            'provider_type' => 'pandit',
            'first_name' => 'Service',
            'last_name' => 'Provider',
            'email' => 'provider@example.com',
            'phone' => '+1234567892',
            'status' => 'active',
            'profile_json' => json_encode([
                'experience' => '5 years',
                'specialization' => 'Wedding ceremonies'
            ])
        ]);

        return [$provider];
    }

    /**
     * Create services for the application
     */
    private function createServices($tenant)
    {
        $serviceData = [
            [
                'name' => 'Wedding Ceremony',
                'description' => 'Traditional wedding ceremony conducted by experienced pandit',
                'category' => 'Religious',
                'base_price' => 5000.00,
                'currency' => 'INR',
                'duration_minutes' => 120,
                'is_active' => true
            ],
            [
                'name' => 'House Warming',
                'description' => 'House warming ceremony (Griha Pravesh) for new homes',
                'category' => 'Religious',
                'base_price' => 3000.00,
                'currency' => 'INR',
                'duration_minutes' => 90,
                'is_active' => true
            ],
            [
                'name' => 'Naming Ceremony',
                'description' => 'Baby naming ceremony (Namkaran) with traditional rituals',
                'category' => 'Religious',
                'base_price' => 2500.00,
                'currency' => 'INR',
                'duration_minutes' => 60,
                'is_active' => true
            ],
            [
                'name' => 'Astrology Consultation',
                'description' => 'Personalized astrology consultation and predictions',
                'category' => 'Consultation',
                'base_price' => 1500.00,
                'currency' => 'INR',
                'duration_minutes' => 45,
                'is_active' => true
            ]
        ];

        $services = [];
        foreach ($serviceData as $service) {
            $service['tenant_id'] = $tenant->id;
            $services[] = Service::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $service['name']],
                $service
            );
        }

        return $services;
    }
}