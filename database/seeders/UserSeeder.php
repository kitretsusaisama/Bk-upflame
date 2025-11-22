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

        // Create sample users
        for ($i = 1; $i <= 10; $i++) {
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