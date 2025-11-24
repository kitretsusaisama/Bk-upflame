<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserProfile;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Access\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant or create one if none exist
        $tenant = Tenant::first() ?? Tenant::create([
            'name' => 'Default Tenant',
            'slug' => 'default-tenant',
            'domain' => 'default.example.com',
            'status' => 'active'
        ]);

        // Create super admin user
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create super admin user profile
        $superAdmin->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '+1234567890'
        ]);

        // Assign super admin role (if role exists)
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($superAdminRole->id);
            if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                // Check if the role is already attached
                if (!$superAdmin->roles()->where('role_id', $freshRole->id)->exists()) {
                    try {
                        $superAdmin->roles()->attach($freshRole, [
                            'id' => Str::uuid()->toString(),
                            'tenant_id' => $tenant->id,
                            'assigned_by' => $superAdmin->id
                        ]);
                        $this->command->info('Attached Super Admin role to admin@example.com');
                    } catch (\Exception $e) {
                        $this->command->warn('Could not attach Super Admin role: ' . $e->getMessage());
                        Log::warning('Could not attach Super Admin role', [
                            'user_id' => $superAdmin->id,
                            'role_id' => $freshRole->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->command->warn('Super Admin role not found or invalid');
            }
        } else {
            $this->command->warn('Super Admin role not found');
        }

        // Create tenant admin user
        $tenantAdmin = User::firstOrCreate([
            'email' => 'tenant@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create tenant admin user profile
        $tenantAdmin->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Tenant',
            'last_name' => 'Admin',
            'phone' => '+1234567891'
        ]);

        // Assign tenant admin role (if role exists)
        $tenantAdminRole = Role::where('name', 'Tenant Admin')->first();
        if ($tenantAdminRole) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($tenantAdminRole->id);
            if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                // Check if the role is already attached
                if (!$tenantAdmin->roles()->where('role_id', $freshRole->id)->exists()) {
                    try {
                        $tenantAdmin->roles()->attach($freshRole, [
                            'id' => Str::uuid()->toString(),
                            'tenant_id' => $tenant->id,
                            'assigned_by' => $superAdmin->id
                        ]);
                        $this->command->info('Attached Tenant Admin role to tenant@example.com');
                    } catch (\Exception $e) {
                        $this->command->warn('Could not attach Tenant Admin role: ' . $e->getMessage());
                        Log::warning('Could not attach Tenant Admin role', [
                            'user_id' => $tenantAdmin->id,
                            'role_id' => $freshRole->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->command->warn('Tenant Admin role not found or invalid');
            }
        } else {
            $this->command->warn('Tenant Admin role not found');
        }

        // Create provider user
        $provider = User::firstOrCreate([
            'email' => 'provider@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create provider user profile
        $provider->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Service',
            'last_name' => 'Provider',
            'phone' => '+1234567892'
        ]);

        // Assign provider role (if role exists)
        $providerRole = Role::where('name', 'Provider')->first();
        if ($providerRole) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($providerRole->id);
            if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                // Check if the role is already attached
                if (!$provider->roles()->where('role_id', $freshRole->id)->exists()) {
                    try {
                        $provider->roles()->attach($freshRole, [
                            'id' => Str::uuid()->toString(),
                            'tenant_id' => $tenant->id,
                            'assigned_by' => $superAdmin->id
                        ]);
                        $this->command->info('Attached Provider role to provider@example.com');
                    } catch (\Exception $e) {
                        $this->command->warn('Could not attach Provider role: ' . $e->getMessage());
                        Log::warning('Could not attach Provider role', [
                            'user_id' => $provider->id,
                            'role_id' => $freshRole->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->command->warn('Provider role not found or invalid');
            }
        } else {
            $this->command->warn('Provider role not found');
        }

        // Create operations user
        $ops = User::firstOrCreate([
            'email' => 'ops@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create operations user profile
        $ops->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Operations',
            'last_name' => 'Manager',
            'phone' => '+1234567893'
        ]);

        // Assign operations role (if role exists)
        $opsRole = Role::where('name', 'Operations')->first();
        if ($opsRole) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($opsRole->id);
            if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                // Check if the role is already attached
                if (!$ops->roles()->where('role_id', $freshRole->id)->exists()) {
                    try {
                        $ops->roles()->attach($freshRole, [
                            'id' => Str::uuid()->toString(),
                            'tenant_id' => $tenant->id,
                            'assigned_by' => $superAdmin->id
                        ]);
                        $this->command->info('Attached Operations role to ops@example.com');
                    } catch (\Exception $e) {
                        $this->command->warn('Could not attach Operations role: ' . $e->getMessage());
                        Log::warning('Could not attach Operations role', [
                            'user_id' => $ops->id,
                            'role_id' => $freshRole->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->command->warn('Operations role not found or invalid');
            }
        } else {
            $this->command->warn('Operations role not found');
        }

        // Create customer user
        $customer = User::firstOrCreate([
            'email' => 'customer@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Create customer user profile
        $customer->profile()->firstOrCreate([
            'tenant_id' => $tenant->id
        ], [
            'first_name' => 'Regular',
            'last_name' => 'Customer',
            'phone' => '+1234567894'
        ]);

        // Assign customer role (if role exists)
        $customerRole = Role::where('name', 'Customer')->first();
        if ($customerRole) {
            // Refresh the role to ensure we have the full UUID
            $freshRole = Role::find($customerRole->id);
            if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                // Check if the role is already attached
                if (!$customer->roles()->where('role_id', $freshRole->id)->exists()) {
                    try {
                        $customer->roles()->attach($freshRole, [
                            'id' => Str::uuid()->toString(),
                            'tenant_id' => $tenant->id,
                            'assigned_by' => $superAdmin->id
                        ]);
                        $this->command->info('Attached Customer role to customer@example.com');
                    } catch (\Exception $e) {
                        $this->command->warn('Could not attach Customer role: ' . $e->getMessage());
                        Log::warning('Could not attach Customer role', [
                            'user_id' => $customer->id,
                            'role_id' => $freshRole->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                $this->command->warn('Customer role not found or invalid');
            }
        } else {
            $this->command->warn('Customer role not found');
        }

        // Create additional sample users (increased from 10 to 20)
        for ($i = 1; $i <= 20; $i++) {
            $user = User::firstOrCreate([
                'email' => "user{$i}@example.com"
            ], [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'password' => Hash::make('password'),
                'status' => 'active'
            ]);

            // Create user profile
            $user->profile()->firstOrCreate([
                'tenant_id' => $tenant->id
            ], [
                'first_name' => $this->faker()->firstName,
                'last_name' => $this->faker()->lastName,
                'phone' => $this->faker()->phoneNumber
            ]);
            
            // Randomly assign roles to some users
            if ($i <= 5) {
                // Assign some users to Provider role
                if ($providerRole) {
                    $freshRole = Role::find($providerRole->id);
                    if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                        if (!$user->roles()->where('role_id', $freshRole->id)->exists()) {
                            try {
                                $user->roles()->attach($freshRole, [
                                    'id' => Str::uuid()->toString(),
                                    'tenant_id' => $tenant->id,
                                    'assigned_by' => $superAdmin->id
                                ]);
                            } catch (\Exception $e) {
                                // Ignore errors for sample users
                            }
                        }
                    }
                }
            } elseif ($i <= 10) {
                // Assign some users to Customer role
                if ($customerRole) {
                    $freshRole = Role::find($customerRole->id);
                    if ($freshRole && Role::where('id', $freshRole->id)->exists()) {
                        if (!$user->roles()->where('role_id', $freshRole->id)->exists()) {
                            try {
                                $user->roles()->attach($freshRole, [
                                    'id' => Str::uuid()->toString(),
                                    'tenant_id' => $tenant->id,
                                    'assigned_by' => $superAdmin->id
                                ]);
                            } catch (\Exception $e) {
                                // Ignore errors for sample users
                            }
                        }
                    }
                }
            }
        }
        
        $this->command->info('User seeding completed successfully!');
    }
    
    private function faker()
    {
        static $faker;
        if (!$faker) {
            $faker = \Faker\Factory::create();
        }
        return $faker;
    }
}