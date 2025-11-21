<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_domains', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('UUID for the domain record');
            $table->char('tenant_id', 36)->comment('Reference to tenants.id');
            $table->string('domain')->unique()->comment('Domain name');
            $table->boolean('is_primary')->default(false)->comment('Whether this is the primary domain');
            $table->boolean('is_verified')->default(false)->comment('Whether domain ownership is verified');
            $table->string('verification_token')->nullable()->comment('Token for domain verification');
            $table->timestamp('verified_at')->nullable()->comment('When domain was verified');
            $table->timestamp('created_at')->useCurrent()->comment('Record creation timestamp');
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->index('tenant_id', 'idx_tenant_domains_tenant');
            $table->index('is_verified', 'idx_tenant_domains_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_domains');
    }
};
