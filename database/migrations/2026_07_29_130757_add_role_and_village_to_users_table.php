<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->text('google2fa_secret')->nullable();
            $table->boolean('google2fa_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['role_id', 'village_id', 'google2fa_secret', 'google2fa_enabled']);
        });
    }
};
