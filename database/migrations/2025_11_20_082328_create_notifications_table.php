<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('template_id')->nullable();
            $table->uuid('recipient_user_id')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->enum('channel', ['email', 'sms', 'push']);
            $table->string('subject')->nullable();
            $table->text('body');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered', 'opened'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('template_id')->references('id')->on('notification_templates');
            $table->foreign('recipient_user_id')->references('id')->on('users');
            $table->index('tenant_id');
            $table->index('template_id');
            $table->index('recipient_user_id');
            $table->index('channel');
            $table->index('status');
            $table->index('priority');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
