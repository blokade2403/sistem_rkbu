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
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id_asset')->primary();
            $table->foreignUuid('id_jenis_kategori_rkbu')->references('id_jenis_kategori_rkbu')->on('jenis_kategori_rkbus')->onDelete('cascade');
            $table->string('kode_asset')->nullable();
            $table->string('nama_asset')->nullable();
            $table->string('satuan')->nullable();
            $table->text('spek')->nullable();
            $table->string('harga_asset')->nullable();
            $table->string('tahun_perolehan')->nullable();
            $table->string('tgl_asset')->nullable();
            $table->string('penyedia')->nullable();
            $table->string('kondisi_asset')->nullable();
            $table->string('pengguna_asset')->nullable();
            $table->string('nip_pengguna')->nullable();
            $table->string('jabatan_pengguna')->nullable();
            $table->string('status_asset')->nullable();
            $table->string('id_barang')->nullable();
            $table->string('jumlah_asset')->nullable();
            $table->string('total_anggaran_asset')->nullable();
            $table->string('merk')->nullable();
            $table->string('qrcode_path')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('no_register')->nullable();
            $table->string('type')->nullable();
            $table->string('tgl_bpkb')->nullable();
            $table->string('no_rangka')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('kapitalisasi')->nullable();
            $table->string('link_detail')->nullable();
            $table->string('foto')->nullable();
            $table->foreignUuid('id_penempatan')->references('id_penempatan')->on('penempatans')->onDelete('cascade');
            $table->string('penanggung_jawab')->nullable();
            $table->string('hibah')->nullable();
            $table->string('sumber_anggaran')->nullable();
            $table->string('status_reklas_arb')->nullable();
            $table->string('kategori_asset_bergerak')->nullable();
            $table->string('upload_sptjm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
