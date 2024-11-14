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
        Schema::create('jaraks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tps_asal_id')->constrained('tps')->onDelete('cascade');
            $table->foreignId('tps_tujuan_id')->constrained('tps')->onDelete('cascade');
            $table->decimal('jarak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaraks');
    }
};
