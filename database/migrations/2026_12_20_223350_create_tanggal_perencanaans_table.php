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
        Schema::create('tanggal_perencanaans', function (Blueprint $table) {
            $table->uuid('id_tanggal_perencanaan')->primary();
            $table->foreignUuid('id_tahun_anggaran')->references('id')->on('tahun_anggarans')->onDelete('cascade');
            $table->foreignUuid('id_fase')->references('id_fase')->on('fases')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('no_dpa')->nullable();
            $table->string('kota')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif']); // menggunakan enum untuk status_user

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggal_perencanaans');
    }
};
