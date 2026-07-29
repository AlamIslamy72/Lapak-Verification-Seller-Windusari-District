<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->string('nik');
            $table->string('kk_number');
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->cascadeOnDelete();

            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->string('product_photo_url')->nullable();
            $table->string('nib_url')->nullable();
            $table->string('category')->nullable();

            $table->enum('status', [
                'pending',
                'verified_village',
                'verified_district',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('village_notes')->nullable();
            $table->text('district_notes')->nullable();
            $table->string('survey_photo_url')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('visited')->default(false);

            $table->foreignId('verified_by_village_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by_district_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
