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
        Schema::table('menus', function (Blueprint $table) {
            $table->string('group', 100)->nullable()->after('parent_id');
            $table->integer('group_order')->default(0)->after('group');
            $table->enum('scope', ['platform', 'tenant', 'both'])->default('both')->after('group_order');
            $table->json('required_permissions')->nullable()->after('scope');
            
            // Drop old columns
            $table->dropForeign(['permission_id']);
            $table->dropColumn(['permission_id', 'is_separator']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['group', 'group_order', 'scope', 'required_permissions']);
            
            $table->uuid('permission_id')->nullable();
            $table->boolean('is_separator')->default(false);
            
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('set null');
        });
    }
};
