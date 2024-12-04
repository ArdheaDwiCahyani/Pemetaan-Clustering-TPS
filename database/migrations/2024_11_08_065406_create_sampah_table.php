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
        Schema::create('sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tps_id')->nullable()->constrained('tps')->onDelete('cascade');
            $table->year('tahun');
            $table->timestamps();

            // Menambahkan unique constraint pada kombinasi tps_id dan tahun
            $table->unique(['tps_id', 'tahun'], 'unique_tps_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah');
    }
};
