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
            $table->uuid('parent_id')->nullable();
            $table->string('label', 100);
            $table->string('route', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('icon', 100)->nullable();
            $table->uuid('permission_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_separator')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('set null');
            
            $table->index('parent_id');
            $table->index('permission_id');
            $table->index(['is_active', 'sort_order']);
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
