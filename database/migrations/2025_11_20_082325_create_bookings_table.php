<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('user_id');
            $table->uuid('provider_id')->nullable();
            $table->uuid('service_id')->nullable();
            $table->string('booking_reference', 100)->unique();
            $table->enum('status', ['processing', 'confirmed', 'completed', 'cancelled', 'rejected'])->default('processing');
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('INR');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('service_id')->references('id')->on('services');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('provider_id');
            $table->index('service_id');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('booking_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
