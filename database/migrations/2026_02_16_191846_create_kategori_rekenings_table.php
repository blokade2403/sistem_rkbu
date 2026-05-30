<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori_rekenings', function (Blueprint $table) {
            $table->uuid('id_kategori_rekening')->primary();
            $table->string('kode_kategori_rekening')->unique();
            $table->string('nama_kategori_rekening');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_rekenings');
    }
};
