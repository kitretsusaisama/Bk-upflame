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
        // Roles table already has tenant_id, so we don't need to add it
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Roles table already has tenant_id, so we don't need to drop it
    }
};
