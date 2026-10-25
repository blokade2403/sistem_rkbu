<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->string('pengadaan_step', 32)->default('input_pesanan')->after('status_spj');
            $table->string('idpaket')->nullable();
            $table->string('no_surat_pesanan')->nullable();
            $table->date('tgl_surat_pesanan')->nullable();
            $table->string('foto_barang_datang')->nullable();
            $table->string('foto_barang_datang_2')->nullable();
            $table->string('foto_barang_datang_3')->nullable();
            $table->date('tgl_barang_datang')->nullable();
            $table->text('keterangan_barang_datang')->nullable();
            $table->string('status_verifikasi_pengurus_barang', 24)->nullable();
            $table->text('keterangan_verif_pengurus_barang')->nullable();
            $table->foreignUuid('validator_pengurus_barang_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tgl_verif_pengurus_barang')->nullable();
            $table->date('tgl_bast')->nullable();
            $table->string('no_ba')->nullable();
            $table->string('no_ba_hp')->nullable();
            $table->string('no_ba_bp')->nullable();
            $table->string('no_dpa')->nullable();
            $table->index('pengadaan_step');
        });

        Schema::create('sub_kategori_pendukung_ppk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sub_kategori_rkbu_id')->unique();
            $table->uuid('user_id');
            $table->timestamps();
            $table->foreign('sub_kategori_rkbu_id')->references('id_sub_kategori_rkbu')->on('sub_kategori_rkbus')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('pengadaan_workflow_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spj_id');
            $table->uuid('user_id')->nullable();
            $table->string('aksi', 40);
            $table->string('step_dari', 32)->nullable();
            $table->string('step_ke', 32)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('spj_id')->references('id')->on('spjs')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['spj_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_pendukung_ppk');
        Schema::dropIfExists('pengadaan_workflow_logs');
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropForeign(['validator_pengurus_barang_id']);
            $table->dropIndex(['pengadaan_step']);
            $table->dropColumn([
                'pengadaan_step', 'idpaket', 'no_surat_pesanan', 'tgl_surat_pesanan',
                'foto_barang_datang', 'foto_barang_datang_2', 'foto_barang_datang_3',
                'tgl_barang_datang', 'keterangan_barang_datang',
                'status_verifikasi_pengurus_barang', 'keterangan_verif_pengurus_barang',
                'validator_pengurus_barang_id', 'tgl_verif_pengurus_barang', 'tgl_bast',
                'no_ba', 'no_ba_hp', 'no_ba_bp', 'no_dpa',
            ]);
        });
    }
};
