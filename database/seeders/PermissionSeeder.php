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