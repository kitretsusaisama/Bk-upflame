<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('tenant_id');
            $table->string('notification_type', 100);
            $table->enum('channel', ['email', 'sms', 'push']);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unique(['user_id', 'notification_type', 'channel'], 'unique_preference');
            $table->index('user_id');
            $table->index('notification_type');
            $table->index('channel');
            $table->index('is_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
