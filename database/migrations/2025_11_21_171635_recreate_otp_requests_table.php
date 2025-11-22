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
        Schema::dropIfExists('otp_requests');
        
        Schema::create('otp_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('tenant_id', 36)->nullable();
            $table->string('recipient');
            $table->string('otp_hash');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_requests');
        
        Schema::create('otp_requests', function (Blueprint $table) {
            $table->id();
            $table->char('tenant_id', 36)->nullable();
            $table->string('recipient');
            $table->string('otp_hash');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }
};