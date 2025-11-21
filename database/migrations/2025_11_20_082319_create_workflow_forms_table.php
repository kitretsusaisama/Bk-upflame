<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('step_id');
            $table->string('field_key', 100);
            $table->enum('field_type', ['text', 'email', 'phone', 'number', 'date', 'select', 'checkbox', 'file']);
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('validation_rules')->nullable();
            $table->json('options_json')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('step_id')->references('id')->on('workflow_steps')->onDelete('cascade');
            $table->index('step_id');
            $table->index('field_key');
            $table->index('is_required');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_forms');
    }
};
