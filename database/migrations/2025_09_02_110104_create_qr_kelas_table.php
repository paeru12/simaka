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
        Schema::create('qr_kelas', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('ruangan_id', 36);
            $table->string('token', 100)->unique(); 
            $table->boolean('aktif')->default(true);
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_kelas');
    }
};
