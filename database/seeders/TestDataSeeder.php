<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use App\Domains\Access\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Create Tenants
            $tenant1 = Tenant::create([
                'id' => Str::uuid(),
                'name' => 'Acme Corporation',
                'slug' => 'acme-corp',
                'status' => 'active',
                'tier' => 'premium',
                'timezone' => 'UTC',
                'locale' => 'en-US',
            ]);

            $tenant2 = Tenant::create([
                'id' => Str::uuid(),
                'name' => 'Globex Inc',
                'slug' => 'globex',
                'status' => 'active',
                'tier' => 'standard',
                'timezone' => 'UTC',
                'locale' => 'en-US',
            ]);

            $this->command->info("✓ Created tenants: {$tenant1->name}, {$tenant2->name}");

            // Ensure roles exist with priorities
            DB::table('roles')->where('name', 'Super Admin')->update(['priority' => 10]);
            DB::table('roles')->where('name', 'Tenant Admin')->update(['priority' => 20]);
            DB::table('roles')->where('name', 'Provider')->update(['priority' => 40]);
            DB::table('roles')->where('name', 'Ops')->update(['priority' => 50]);
            DB::table('roles')->where('name', 'Customer')->update(['priority' => 70]);

            // Get roles
            $superAdminRole = Role::where('name', 'Super Admin')->where('tenant_id', $tenant1->id)->first();
            $tenantAdminRole = Role::where('name', 'Tenant Admin')->where('tenant_id', $tenant1->id)->first();
            $providerRole = Role::where('name', 'Provider')->where('tenant_id', $tenant1->id)->first();
            $opsRole = Role::where('name', 'Ops')->where('tenant_id', $tenant1->id)->first();
            $customerRole = Role::where('name', 'Customer')->where('tenant_id', $tenant1->id)->first();

            $this->command->info('✓ Updated role priorities');

            // Create Users
            $users = [];

            // Super Admin
            $superAdmin = User::create([
                'id' => Str::uuid(),
                'tenant_id' => $tenant1->id,
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified' => true,
                'primary_role_id' => $superAdminRole?->id,
            ]);
            if ($superAdminRole) {
                DB::table('user_roles')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $superAdmin->id,
                    'role_id' => $superAdminRole->id,
                    'tenant_id' => $tenant1->id,
                    'assigned_by' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $users[] = "Super Admin: superadmin@example.com";

            // Tenant Admin
            if ($tenantAdminRole) {
                $tenantAdmin = User::create([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant1->id,
                    'email' => 'admin@acme.com',
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified' => true,
                    'primary_role_id' => $tenantAdminRole->id,
                ]);
                DB::table('user_roles')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $tenantAdmin->id,
                    'role_id' => $tenantAdminRole->id,
                    'tenant_id' => $tenant1->id,
                    'assigned_by' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $users[] = "Tenant Admin (Acme): admin@acme.com";
            }

            // Provider
            if ($providerRole) {
                $provider = User::create([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant1->id,
                    'email' => 'provider@acme.com',
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified' => true,
                    'primary_role_id' => $providerRole->id,
                ]);
                DB::table('user_roles')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $provider->id,
                    'role_id' => $providerRole->id,
                    'tenant_id' => $tenant1->id,
                    'assigned_by' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $users[] = "Provider (Acme): provider@acme.com";
            }

            // Ops
            if ($opsRole) {
                $ops = User::create([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant1->id,
                    'email' => 'ops@acme.com',
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified' => true,
                    'primary_role_id' => $opsRole->id,
                ]);
                DB::table('user_roles')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $ops->id,
                    'role_id' => $opsRole->id,
                    'tenant_id' => $tenant1->id,
                    'assigned_by' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $users[] = "Ops (Acme): ops@acme.com";
            }

            // Customer
            if ($customerRole) {
                $customer = User::create([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant1->id,
                    'email' => 'customer@acme.com',
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified' => true,
                    'primary_role_id' => $customerRole->id,
                ]);
                DB::table('user_roles')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $customer->id,
                    'role_id' => $customerRole->id,
                    'tenant_id' => $tenant1->id,
                    'assigned_by' => $superAdmin->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $users[] = "Customer (Acme): customer@acme.com";
            }

            DB::commit();

            $this->command->info('✓ Created test users (all passwords: "password"):');
            foreach ($users as $user) {
                $this->command->info("  - {$user}");
            }

            $this->command->newLine();
            $this->command->info('🎉 Test data seeding completed successfully!');
            $this->command->info('📧 All user passwords are: password');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed test data: ' . $e->getMessage());
            throw $e;
        }
    }
}
