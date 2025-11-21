<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the default tenant ID
        $tenant = DB::table('tenants')->where('domain', 'default.local')->first();
        
        if ($tenant) {
            // Add localhost domain
            $localhostExists = DB::table('tenant_domains')->where('domain', 'localhost')->exists();
            if (!$localhostExists) {
                DB::table('tenant_domains')->insert([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant->id,
                    'domain' => 'localhost',
                    'is_primary' => false,
                    'is_verified' => true,
                    'created_at' => now(),
                ]);
            }
            
            // Add 127.0.0.1 domain
            $ipExists = DB::table('tenant_domains')->where('domain', '127.0.0.1')->exists();
            if (!$ipExists) {
                DB::table('tenant_domains')->insert([
                    'id' => Str::uuid(),
                    'tenant_id' => $tenant->id,
                    'domain' => '127.0.0.1',
                    'is_primary' => false,
                    'is_verified' => true,
                    'created_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the added domains
        DB::table('tenant_domains')->where('domain', 'localhost')->delete();
        DB::table('tenant_domains')->where('domain', '127.0.0.1')->delete();
    }
};