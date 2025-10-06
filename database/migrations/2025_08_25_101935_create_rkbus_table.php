<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbus', function (Blueprint $table) {
            $table->uuid('id_rkbu')->primary();
            $table->foreignUuid('id_sub_kategori_rkbu')->references('id_sub_kategori_rkbu')->on('sub_kategori_rkbus')->onDelete('cascade');
            $table->foreignUuid('id_sub_kategori_rekening')->references('id_sub_kategori_rekening')->on('sub_kategori_rekenings')->onDelete('cascade');
            $table->foreignUuid('id_kode_rekening_belanja')->references('id_kode_rekening_belanja')->on('rekening_belanjas')->onDelete('cascade');
            $table->foreignUuid('id_status_validasi')->references('id_status_validasi')->on('status_validasis')->onDelete('cascade');
            $table->foreignUuid('id_status_validasi_rka')->references('id_status_validasi_rka')->on('status_validasi_rkas')->onDelete('cascade');
            $table->foreignUuid('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('nama_tahun_anggaran');
            $table->string('nama_barang');
            $table->integer('vol_1')->nullable();
            $table->string('satuan_1')->nullable();
            $table->integer('vol_2')->nullable();
            $table->string('satuan_2')->nullable();
            $table->text('spek')->nullable();
            $table->bigInteger('jumlah_vol');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('ppn')->nullable();
            $table->bigInteger('total_anggaran');
            $table->string('rating')->nullable();
            $table->string('link_ekatalog')->nullable();
            $table->string('upload_file_1')->nullable();
            $table->string('upload_file_2')->nullable();
            $table->string('upload_file_3')->nullable();
            $table->string('upload_file_4')->nullable();
            $table->string('upload_file_5')->nullable();
            $table->string('keterangan_status')->nullable();
            $table->string('penempatan')->nullable();
            $table->integer('stok');
            $table->integer('rata_rata_pemakaian');
            $table->integer('kebutuhan_per_bulan');
            $table->integer('buffer');
            $table->integer('pengadaan_sebelumnya');
            $table->integer('proyeksi_sisa_stok');
            $table->integer('kebutuhan_plus_buffer');
            $table->integer('kebutuhan_tahun_x1');
            $table->integer('rencana_pengadaan_tahun_x1');
            $table->string('nama_pegawai');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('pendidikan');
            $table->string('jabatan');
            $table->string('status_kawin')->nullable();
            $table->string('nomor_kontrak')->nullable();
            $table->string('tmt_pegawai')->nullable();
            $table->string('bulan_tmt')->nullable();
            $table->string('tahun_tmt')->nullable();
            $table->bigInteger('gaji_pokok');
            $table->bigInteger('remunerasi');
            $table->Integer('koefisien_remunerasi');
            $table->Integer('koefisien_gaji');
            $table->Integer('bpjs_kesehatan')->nullable();
            $table->Integer('bpjs_tk')->nullable();
            $table->Integer('bpjs_jht')->nullable();
            $table->bigInteger('total_gaji_pokok');
            $table->bigInteger('total_remunerasi');
            $table->integer('sisa_vol_rkbu');
            $table->bigInteger('sisa_anggaran_rkbu');
            $table->string('status_komponen')->nullable();
            $table->integer('standar_kebutuhan')->nullable();
            $table->integer('eksisting')->nullable();
            $table->string('kondisi_baik')->nullable();
            $table->string('kondisi_rusak_berat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbus');
    }
};
