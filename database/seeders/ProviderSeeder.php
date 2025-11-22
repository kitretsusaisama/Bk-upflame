<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Provider\Models\Provider;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ProviderSeeder extends Seeder
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

        // Create sample providers
        for ($i = 1; $i <= 20; $i++) {
            // Create a user for the provider
            $user = User::firstOrCreate([
                'email' => "provider{$i}@example.com"
            ], [
                'id' => Str::uuid()->toString(),
                'tenant_id' => $tenant->id,
                'password' => Hash::make('password'),
                'status' => 'active'
            ]);
            
            Provider::firstOrCreate([
                'tenant_id' => $tenant->id,
                'email' => "provider{$i}@example.com"
            ], [
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'provider_type' => $this->faker()->randomElement(['pandit', 'vendor', 'partner', 'astrologer', 'tutor']),
                'first_name' => $this->faker()->firstName,
                'last_name' => $this->faker()->lastName,
                'phone' => $this->faker()->phoneNumber,
                'status' => 'active',
                'profile_json' => json_encode([
                    'experience' => $this->faker()->numberBetween(1, 20) . ' years',
                    'specialization' => $this->faker()->sentence(3)
                ])
            ]);
        }

        // Create specific providers for testing
        // Pandit
        $panditUser = User::firstOrCreate([
            'email' => 'pandit@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        
        Provider::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'pandit@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'user_id' => $panditUser->id,
            'provider_type' => 'pandit',
            'first_name' => 'Pandit',
            'last_name' => 'Sharma',
            'status' => 'active'
        ]);

        // Vendor
        $vendorUser = User::firstOrCreate([
            'email' => 'vendor@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        
        Provider::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'vendor@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'user_id' => $vendorUser->id,
            'provider_type' => 'vendor',
            'first_name' => 'Vendor',
            'last_name' => 'Singh',
            'status' => 'active'
        ]);

        // Tutor
        $tutorUser = User::firstOrCreate([
            'email' => 'tutor@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);
        
        Provider::firstOrCreate([
            'tenant_id' => $tenant->id,
            'email' => 'tutor@example.com'
        ], [
            'id' => Str::uuid()->toString(),
            'user_id' => $tutorUser->id,
            'provider_type' => 'tutor',
            'first_name' => 'Tutor',
            'last_name' => 'Patel',
            'status' => 'active'
        ]);
        
        $this->command->info('Provider seeding completed successfully!');
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