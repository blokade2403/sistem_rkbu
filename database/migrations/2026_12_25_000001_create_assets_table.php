<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id_asset')->primary();
            $table->foreignUuid('unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignUuid('id_jenis_kategori_rkbu')->nullable()->references('id_jenis_kategori_rkbu')->on('jenis_kategori_rkbus')->nullOnDelete();
            $table->foreignUuid('rkbu_id')->nullable()->references('id')->on('rkbus')->nullOnDelete();
            $table->foreignUuid('usulan_belanja_item_id')->nullable()->references('id')->on('usulan_belanja_items')->nullOnDelete();
            $table->foreignUuid('spj_detail_id')->nullable()->unique()->references('id')->on('spj_details')->nullOnDelete();
            $table->foreignUuid('spj_id')->nullable()->references('id')->on('spjs')->nullOnDelete();
            $table->foreignUuid('registered_by')->nullable()->references('id')->on('users')->nullOnDelete();

            $table->string('kode_asset')->nullable();
            $table->string('nama_asset');
            $table->string('satuan')->nullable();
            $table->text('spek')->nullable();
            $table->decimal('harga_asset', 18, 2)->nullable();
            $table->unsignedSmallInteger('tahun_perolehan')->nullable();
            $table->date('tgl_asset')->nullable();
            $table->string('penyedia')->nullable();
            $table->string('kondisi_asset', 40)->default('Baik');
            $table->string('pengguna_asset')->nullable();
            $table->string('nip_pengguna')->nullable();
            $table->string('jabatan_pengguna')->nullable();
            $table->string('status_asset', 40)->default('Aktif');
            $table->string('id_barang')->nullable();
            $table->decimal('jumlah_asset', 18, 3)->default(1);
            $table->decimal('total_anggaran_asset', 18, 2)->nullable();
            $table->string('merk')->nullable();
            $table->string('qrcode_path')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('no_register')->nullable()->unique();
            $table->string('type')->nullable();
            $table->date('tgl_bpkb')->nullable();
            $table->string('no_rangka')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('kapitalisasi')->nullable();
            $table->string('link_detail')->nullable();
            $table->string('foto')->nullable();
            $table->string('lokasi_asset')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('hibah')->nullable();
            $table->string('sumber_anggaran')->nullable();
            $table->string('status_reklas_arb')->nullable();
            $table->string('kategori_asset_bergerak')->nullable();
            $table->string('upload_sptjm')->nullable();
            $table->string('status_registrasi', 24)->default('terdaftar');
            $table->timestamps();

            $table->index(['unit_id', 'tahun_perolehan']);
            $table->index(['rkbu_id', 'usulan_belanja_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
