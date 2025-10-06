<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggarans', function (Blueprint $table) {
            $table->uuid('id_anggaran')->primary();
            $table->foreignUuid('id_kode_rekening_belanja')->references('id_kode_rekening_belanja')->on('rekening_belanjas')->onDelete('cascade');
            $table->foreignUuid('id_sumber_dana')->references('id_sumber_dana')->on('sumber_danas')->onDelete('cascade');
            $table->string('nama_anggaran');
            $table->bigInteger('jumlah_anggaran');
            $table->string('tahun_anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
