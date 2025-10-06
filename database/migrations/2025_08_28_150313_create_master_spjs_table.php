<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_spjs', function (Blueprint $table) {
            $table->uuid('id_master_spj')->primary();
            $table->foreignUuid('id_spj')->references('id_spj')->on('spjs')->onDelete('cascade');
            $table->foreignUuid('id_perusahaan')->nullable()->references('id_perusahaan')->on('perusahaans')->onDelete('cascade');
            $table->foreignUuid('id_user')->nullable()->references('id_user')->on('users')->onDelete('cascade');
            $table->foreignUuid('id_admin_pendukung_ppk')->nullable()->references('id_admin_pendukung_ppk')->on('admin_pendukungs')->onDelete('cascade');
            $table->string('idpaket')->nullable();
            $table->date('tanggal_input_spj')->nullable();
            $table->bigInteger('bruto')->nullable();
            $table->bigInteger('pembayaran')->nullable();
            $table->bigInteger('sisa_pembayaran')->nullable();
            $table->string('tahun_anggaran')->nullable();

            $table->date('tanggal_faktur')->nullable();
            $table->string('nomor_spk')->nullable();
            $table->text('rincian_belanja')->nullable();
            $table->bigInteger('harga_dasar')->nullable();
            $table->date('tanggal_penyerahan_spj')->nullable();
            $table->string('bulan_penyerahan_spj')->nullable();
            $table->date('tanggal_revisi_spj')->nullable();
            $table->date('tanggal_input_listing')->nullable();
            $table->decimal('ppn', 5, 2)->nullable();
            $table->decimal('pph21', 5, 2)->nullable();
            $table->decimal('pph22', 5, 2)->nullable();
            $table->decimal('pph23', 5, 2)->nullable();
            $table->decimal('pp05', 5, 2)->nullable();
            $table->integer('jumlah_pajak')->nullable();
            $table->bigInteger('harga_bersih')->nullable();
            $table->integer('admin_bank')->nullable();
            $table->decimal('bpjs_tk', 5, 2)->nullable();
            $table->decimal('bpjs_kes', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('nama_validasi_keuangan')->nullable();
            $table->string('bulan_pembayaran')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('kode_billingppn')->nullable();
            $table->string('kode_billingpph22')->nullable();
            $table->string('no_ba')->nullable();
            $table->string('no_surat_pesanan')->nullable();
            $table->date('tgl_surat_pesanan')->nullable();
            $table->string('no_dpa')->nullable();
            $table->string('jangka_waktu_pekerjaan')->nullable();
            $table->text('keterangan_barang_datang')->nullable();
            $table->string('keterangan_barang_datang_parsial_1')->nullable();
            $table->string('keterangan_barang_datang_parsial_2')->nullable();
            $table->string('keterangan_barang_datang_parsial_3')->nullable();
            $table->string('foto_barang_datang')->nullable();
            $table->string('foto_barang_datang_2')->nullable();
            $table->string('foto_barang_datang_3')->nullable();
            $table->string('foto_barang_datang_1_parsial_1')->nullable();
            $table->string('foto_barang_datang_2_parsial_1')->nullable();
            $table->string('foto_barang_datang_3_parsial_1')->nullable();
            $table->string('foto_barang_datang_1_parsial_2')->nullable();
            $table->string('foto_barang_datang_2_parsial_2')->nullable();
            $table->string('foto_barang_datang_3_parsial_2')->nullable();
            $table->string('foto_barang_datang_1_parsial_3')->nullable();
            $table->string('foto_barang_datang_2_parsial_3')->nullable();
            $table->string('foto_barang_datang_3_parsial_3')->nullable();
            $table->string('upload_spj_1')->nullable();
            $table->string('upload_spj_2')->nullable();
            $table->date('tgl_proses_pemesanan')->nullable();
            $table->date('tgl_barang_datang')->nullable();
            $table->date('tgl_barang_datang_parsial_1');
            $table->date('tgl_barang_datang_parsial_2');
            $table->date('tgl_barang_datang_parsial_3');
            $table->date('tgl_bast')->nullable();
            $table->date('tgl_proses_faktur')->nullable();
            $table->date('tgl_verif')->nullable();
            $table->string('no_ba_hp')->nullable();
            $table->string('no_ba_bp')->nullable();
            $table->date('tgl_kwitansi')->nullable();
            $table->string('status_proses_pesanan')->nullable();
            $table->string('status_proses_pengiriman_barang')->nullable();
            $table->string('status_proses_bast')->nullable();
            $table->string('status_proses_tukar_faktur')->nullable();
            $table->string('status_verifikasi_ppk')->nullable();
            $table->string('status_verifikasi_ppbj')->nullable();
            $table->string('status_verifikasi_pptk')->nullable();
            $table->string('status_verifikasi_verifikator')->nullable();
            $table->string('status_verifikasi_ppk_keuangan')->nullable();
            $table->string('status_verifikasi_direktur')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->date('tgl_verif_ppbj')->nullable();
            $table->date('tgl_verif_pptk')->nullable();
            $table->date('tgl_verif_verifikator')->nullable();
            $table->date('tgl_verif_ppk_keuangan')->nullable();
            $table->date('tgl_verif_direktur')->nullable();
            $table->string('status_serah_terima_bendahara')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->text('ket_verif_ppk')->nullable();
            $table->text('ket_verif_ppbj')->nullable();
            $table->text('ket_verif_verifikator')->nullable();
            $table->text('ket_verif_ppk_keuangan')->nullable();
            $table->text('ket_verif_direktur')->nullable();
            $table->text('ket_verif_pptk')->nullable();
            $table->date('tgl_revisi_spj_ppk_keuangan')->nullable();
            $table->date('tgl_revisi_spj_direktur')->nullable();
            $table->text('ket_bendahara')->nullable();
            $table->date('tgl_verif_pengurus_barang')->nullable();
            $table->text('keterangan_verif_pengurus_barang')->nullable();
            $table->string('keterangan_verif_pengurus_barang_parsial_1')->nullable();
            $table->string('keterangan_verif_pengurus_barang_parsial_2')->nullable();
            $table->string('keterangan_verif_pengurus_barang_parsial_3')->nullable();
            $table->string('status_verifikasi_pengurus_barang')->nullable();
            $table->string('status_verifikasi_pengurus_barang_parsial_1');
            $table->string('status_verifikasi_pengurus_barang_parsial_2');
            $table->string('status_verifikasi_pengurus_barang_parsial_3');
            $table->text('ket_verif_bendahara')->nullable();
            $table->date('tgl_verif_bendahara')->nullable();
            $table->string('status_hutang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_spjs');
    }
};
