<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pejabat_pengadaans', function (Blueprint $table) {
            $table->uuid('id_pejabat_pengadaan')->primary();
            $table->foreignUuid('id_tahun_anggaran')->references('id')->on('tahun_anggarans')->onDelete('cascade');
            $table->foreignUuid('id_ppk_keuangan')->references('id_ppk_keuangan')->on('ppk_keuangans')->onDelete('cascade');
            $table->string('nama_ppk');
            $table->string('nama_ppbj');
            $table->string('nip_ppk')->unique();
            $table->string('nip_ppbj')->unique();
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->string('nama_pengurus_barang');
            $table->string('nip_pengurus_barang');
            $table->string('nama_direktur');
            $table->string('nip_direktur');
            $table->string('nama_bendahara');
            $table->string('nip_bendahara');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_pengadaans');
    }
};
