<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('user_id');
            $table->enum('provider_type', ['pandit', 'vendor', 'partner', 'astrologer', 'tutor']);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['pending', 'verified', 'active', 'suspended', 'rejected'])->default('pending');
            $table->uuid('workflow_id')->nullable();
            $table->json('profile_json')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->index('tenant_id');
            $table->index('provider_type');
            $table->index('status');
            $table->index('workflow_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
