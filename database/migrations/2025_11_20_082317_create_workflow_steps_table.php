<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id');
            $table->string('step_key', 100);
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('step_type', ['form', 'document', 'verification', 'background_check', 'admin_approval', 'auto']);
            $table->json('config_json')->nullable();
            $table->json('data_json')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'verified', 'approved', 'rejected'])->default('pending');
            $table->uuid('assigned_to')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->index('workflow_id');
            $table->index('step_key');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
