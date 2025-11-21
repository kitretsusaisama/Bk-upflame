<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 36)->primary()->comment('UUID for the user');
            $table->char('tenant_id', 36)->comment('Reference to tenants.id');
            $table->string('email')->comment('User email address');
            $table->string('password')->comment('Hashed password');
            $table->enum('status', ['active', 'inactive', 'banned', 'hold', 'pending', 'suspended'])->default('pending')->comment('User account status');
            $table->char('primary_role_id', 36)->nullable()->comment('Reference to user primary role');
            $table->boolean('mfa_enabled')->default(false)->comment('Whether MFA is enabled');
            $table->boolean('email_verified')->default(false)->comment('Whether email is verified');
            $table->boolean('phone_verified')->default(false)->comment('Whether phone is verified');
            $table->timestamp('last_login')->nullable()->comment('Last successful login timestamp');
            $table->integer('failed_login_attempts')->default(0)->comment('Count of failed login attempts');
            $table->timestamp('locked_until')->nullable()->comment('Account lockout expiration');
            $table->timestamp('created_at')->useCurrent()->comment('Record creation timestamp');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->comment('Record update timestamp');
            
            $table->foreign('tenant_id')->references('id')->on('tenants');
            
            $table->unique(['tenant_id', 'email'], 'unique_email_per_tenant');
            $table->index('status', 'idx_users_status');
            $table->index('tenant_id', 'idx_users_tenant');
            $table->index('email', 'idx_users_email');
            $table->index('last_login', 'idx_users_last_login');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
