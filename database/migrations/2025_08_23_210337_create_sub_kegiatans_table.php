<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kegiatans', function (Blueprint $table) {
            $table->uuid('id_sub_kegiatan')->primary();
            $table->foreignUuid('id_kegiatan')->references('id_kegiatan')->on('kegiatans')->onDelete('cascade');
            $table->string('kode_sub_kegiatan');
            $table->string('nama_sub_kegiatan');
            $table->string('tujuan_sub_kegiatan')->nullable();
            $table->string('indikator_sub_kegiatan')->nullable();
            $table->foreignUuid('id_sumber_dana')->references('id_sumber_dana')->on('sumber_danas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatans');
    }
};
