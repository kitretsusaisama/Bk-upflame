<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkMigrationAsRanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the migration record already exists
        $exists = DB::table('migrations')
            ->where('migration', '2025_11_20_081425_create_tenants_table')
            ->exists();

        // If it doesn't exist, insert it
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => '2025_11_20_081425_create_tenants_table',
                'batch' => 1
            ]);
        }
    }
}