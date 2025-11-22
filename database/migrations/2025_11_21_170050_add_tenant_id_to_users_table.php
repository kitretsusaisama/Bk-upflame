<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if tenant_id column exists before adding
            if (!Schema::hasColumn('users', 'external_id')) {
                $table->string('external_id')->nullable()->index(); // for SSO external unique id
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'external_id')) {
                $table->dropColumn('external_id');
            }
        });
    }
};
