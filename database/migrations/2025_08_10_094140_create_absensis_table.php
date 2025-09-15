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
         Schema::create('absensis', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('jadwal_id', 36);
            $table->string('mapel_id', 36);
            $table->string('guru_id', 36);
            $table->date('tanggal');
            $table->time('jam_absen');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat']);
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->unique(['jadwal_id', 'tanggal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
