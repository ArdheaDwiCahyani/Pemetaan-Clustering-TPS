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
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tps_id')->nullable();
            $table->foreign('tps_id')->references('id')->on('tps')->onDelete('cascade');
            $table->decimal('normalized_volume');
            $table->decimal('normalized_jarak');
            $table->decimal('normalized_rata_rata_jarak');
            $table->integer('cluster');
            $table->string('prioritas');
            $table->year('tahun');
            $table->unique(['tps_id', 'tahun']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};
