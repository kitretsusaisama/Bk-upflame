<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('role_id');
            $table->uuid('tenant_id');
            $table->uuid('assigned_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('assigned_by')->references('id')->on('users');
            $table->unique(['user_id', 'role_id', 'tenant_id'], 'unique_user_role_tenant');
            $table->index('user_id');
            $table->index('role_id');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
