<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Access\Models\Role;

class RolePrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePriorities = [
            'Super Admin' => 10,
            'Admin' => 20,
            'Tenant Admin' => 20,
            'Vendor' => 30,
            'Provider' => 40,
            'Ops' => 50,
            'Premium Customer' => 60,
            'Customer' => 70,
        ];

        foreach ($rolePriorities as $roleName => $priority) {
            Role::where('name', $roleName)->update(['priority' => $priority]);
        }

        $this->command->info('Role priorities updated successfully.');
    }
}
