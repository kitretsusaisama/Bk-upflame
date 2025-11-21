<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('policy_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('policy_id');
            $table->enum('assignee_type', ['user', 'role', 'group']);
            $table->uuid('assignee_id');
            $table->uuid('tenant_id');
            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->index('policy_id');
            $table->index(['assignee_type', 'assignee_id']);
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policy_assignments');
    }
};
