<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus index lama yang cuma index biasa (tidak benar-benar mencegah duplikat)
        Schema::table('submissions', function ($table) {
            $table->dropIndex('submissions_nik_product_name_index');
        });

        // Tambahkan kolom "kunci" yang otomatis NULL kalau statusnya ditolak,
        // supaya submission yang ditolak tidak menghalangi pendaftaran ulang.
        // Tapi kalau statusnya aktif (pending/diproses/disetujui), kolom ini
        // berisi gabungan NIK+produk, dan diberi UNIQUE index sungguhan.
        DB::statement("
            ALTER TABLE submissions
            ADD COLUMN active_duplicate_key VARCHAR(600)
            GENERATED ALWAYS AS (
                CASE
                    WHEN status IN ('rejected_by_village', 'rejected_by_district') THEN NULL
                    ELSE CONCAT(nik, '|', product_name)
                END
            ) STORED
        ");

        Schema::table('submissions', function ($table) {
            $table->unique('active_duplicate_key', 'submissions_active_duplicate_unique');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function ($table) {
            $table->dropUnique('submissions_active_duplicate_unique');
            $table->dropColumn('active_duplicate_key');
            $table->index(['nik', 'product_name'], 'submissions_nik_product_name_index');
        });
    }
};
