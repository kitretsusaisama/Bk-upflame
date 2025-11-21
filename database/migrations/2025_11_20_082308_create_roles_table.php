<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('role_family', ['Provider', 'Internal', 'Customer']);
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unique(['tenant_id', 'name'], 'unique_role_per_tenant');
            $table->index('tenant_id');
            $table->index('role_family');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
