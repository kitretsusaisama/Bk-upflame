<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('definition_id');
            $table->string('entity_type', 100);
            $table->uuid('entity_id');
            $table->string('current_step_key', 100)->nullable();
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected'])->default('pending');
            $table->json('context_json')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('definition_id')->references('id')->on('workflow_definitions');
            $table->index('tenant_id');
            $table->index('definition_id');
            $table->index(['entity_type', 'entity_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
