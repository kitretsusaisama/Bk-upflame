<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant or create one if none exist
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->warn('No tenant found. Skipping role seeding.');
            return;
        }

        // Create default roles
        $roles = [
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'name' => 'Super Admin',
                'description' => 'Full system access',
                'is_system' => true,
                'role_family' => 'Internal'
            ],
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'name' => 'Tenant Admin',
                'description' => 'Tenant administrator access',
                'is_system' => true,
                'role_family' => 'Internal'
            ],
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'name' => 'Provider',
                'description' => 'Service provider access',
                'is_system' => true,
                'role_family' => 'Provider'
            ],
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'name' => 'Customer',
                'description' => 'Customer access',
                'is_system' => true,
                'role_family' => 'Customer'
            ],
            [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'name' => 'Operations',
                'description' => 'Operations team access',
                'is_system' => true,
                'role_family' => 'Internal'
            ]
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'tenant_id' => $roleData['tenant_id']],
                $roleData
            );
        }
    }
}