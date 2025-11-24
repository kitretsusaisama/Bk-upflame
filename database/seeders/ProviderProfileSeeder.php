<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProviderProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if provider profile already exists
        $existingProvider = DB::table('providers')->where('user_id', '63fdbb15-75b8-44a3-9052-8a70e5ab68ae')->first();
        
        if (!$existingProvider) {
            // Create provider profile for provider user
            DB::table('providers')->insert([
                'id' => Str::uuid()->toString(),
                'tenant_id' => '019ab4c5-0f16-73ff-b715-3b3f9b079cdd',
                'user_id' => '63fdbb15-75b8-44a3-9052-8a70e5ab68ae',
                'provider_type' => 'vendor',
                'first_name' => 'Provider',
                'last_name' => 'User',
                'email' => 'provider@example.com',
                'status' => 'active',
                'profile_json' => json_encode([
                    'experience' => '5 years',
                    'specialization' => 'General Services'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}