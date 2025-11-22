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
        // Check if the column exists before trying to drop it
        if (Schema::hasColumn('otp_requests', 'tenant_id')) {
            Schema::table('otp_requests', function (Blueprint $table) {
                // Drop the index first, then the column
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
        
        // Add the column back with the correct data type
        Schema::table('otp_requests', function (Blueprint $table) {
            $table->char('tenant_id', 36)->nullable();
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the column exists before trying to drop it
        if (Schema::hasColumn('otp_requests', 'tenant_id')) {
            Schema::table('otp_requests', function (Blueprint $table) {
                // Drop the index first, then the column
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
        
        // Add the column back with the original data type
        Schema::table('otp_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->index();
        });
    }
};
