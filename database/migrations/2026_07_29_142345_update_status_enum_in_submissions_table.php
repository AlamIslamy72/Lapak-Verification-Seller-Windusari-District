<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE submissions MODIFY status ENUM(
            'pending',
            'verified_village',
            'verified_district',
            'approved',
            'rejected_by_village',
            'rejected_by_district'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE submissions MODIFY status ENUM(
            'pending',
            'verified_village',
            'verified_district',
            'approved',
            'rejected'
        ) DEFAULT 'pending'");
    }
};
