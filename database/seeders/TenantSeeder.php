<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Identity\Models\Tenant;

class TenantSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default tenants
        for ($i = 1; $i <= 5; $i++) {
            Tenant::firstOrCreate([
                'domain' => "tenant{$i}.example.com"
            ], [
                'name' => "Tenant {$i}",
                'slug' => "tenant-{$i}",
                'status' => 'active'
            ]);
        }
        
        // Create a specific tenant for testing
        Tenant::firstOrCreate([
            'domain' => 'test.example.com'
        ], [
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'status' => 'active'
        ]);
        
        $this->command->info('Tenant seeding completed successfully!');
    }
}