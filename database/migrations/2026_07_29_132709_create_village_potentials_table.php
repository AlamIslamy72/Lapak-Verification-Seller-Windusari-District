<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('village_potentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained('villages')->cascadeOnDelete();
            $table->string('potential');
            $table->string('level'); // misal: rendah, sedang, tinggi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_potentials');
    }
};
