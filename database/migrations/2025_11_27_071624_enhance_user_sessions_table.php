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
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('id');
            $table->timestamp('last_activity')->nullable()->after('expires_at');
            $table->index('session_id');
            $table->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->dropIndex(['session_id']);
            $table->dropIndex(['last_activity']);
            $table->dropColumn(['session_id', 'last_activity']);
        });
    }
};
