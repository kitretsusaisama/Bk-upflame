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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('tenant_id', 36)->nullable()->index();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->string('permission')->nullable();
            $table->integer('order')->default(0);
            $table->uuid('parent_id')->nullable()->index();
            $table->enum('type', ['link', 'heading', 'separator', 'module'])->default('link');
            $table->boolean('is_enabled')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'is_enabled', 'order']);
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};