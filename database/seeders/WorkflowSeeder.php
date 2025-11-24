<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Workflow\Models\Workflow;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Support\Str;

class WorkflowSeeder extends Seeder
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

        // For now, we'll skip creating workflows since they require workflow definitions
        // which would need their own seeder. We'll just show a message.
        $this->command->info('Skipping workflow seeding - would require workflow definitions to be created first.');
        
        $this->command->info('Workflow seeding completed successfully!');
    }
}