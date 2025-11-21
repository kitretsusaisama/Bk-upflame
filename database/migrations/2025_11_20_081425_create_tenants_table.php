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
        Schema::create('tenants', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('UUID for the tenant');
            $table->char('parent_tenant_id', 36)->nullable()->comment('Reference to parent tenant for hierarchical structure');
            $table->string('name', 255)->notNull()->comment('Tenant name');
            $table->string('slug', 100)->notNull()->comment('URL-friendly tenant identifier');
            $table->string('domain', 255)->unique()->nullable()->comment('Primary domain for the tenant');
            $table->json('config_json')->nullable()->comment('Tenant configuration as JSON');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending_setup'])->default('pending_setup')->comment('Tenant status');
            $table->enum('tier', ['free', 'basic', 'premium', 'enterprise'])->default('free')->comment('Service tier');
            $table->string('timezone', 50)->default('UTC')->comment('Tenant timezone');
            $table->string('locale', 10)->default('en-US')->comment('Tenant locale');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('deleted_at')->nullable()->comment('Soft delete timestamp');
            
            $table->index('status', 'idx_tenant_status');
            $table->index('domain', 'idx_tenant_domain');
            $table->index('slug', 'idx_tenant_slug');
            $table->index('tier', 'idx_tenant_tier');
            $table->index('parent_tenant_id', 'idx_tenant_parent');
            
            $table->foreign('parent_tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};