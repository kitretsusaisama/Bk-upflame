<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order
        $this->call(TenantSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(WorkflowSeeder::class);
        $this->call(MenuSeeder::class);
        
        // Run the production seeder (optional - for development)
        // $this->call(ProductionSeeder::class);
    }
}