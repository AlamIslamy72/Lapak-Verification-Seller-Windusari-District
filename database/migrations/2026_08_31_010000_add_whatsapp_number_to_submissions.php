<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Nullable di level database supaya data lama (sebelum field ini ada)
            // tidak error. Untuk pendaftaran BARU, validasi di controller yang
            // memastikan field ini wajib diisi.
            $table->string('whatsapp_number', 20)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
        });
    }
};
