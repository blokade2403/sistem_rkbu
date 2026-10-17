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
        Schema::create('rkbu_detail_pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id');
            $table->bigInteger('jumlah_volume')->default(0);
            $table->decimal('total_anggaran', 18, 2);
            $table->string('nama_pegawai')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('status_kawin')->nullable();
            $table->string('nomor_kontrak')->nullable();
            $table->string('tmt_pegawai')->nullable();
            $table->string('bulan_tmt')->nullable();
            $table->string('tahun_tmt')->nullable();
            $table->decimal('gaji_pokok', 18, 2)->nullable();
            $table->decimal('remunerasi', 18, 2)->nullable();
            $table->decimal('koefisien_remunerasi', 10, 2)->nullable();
            $table->decimal('koefisien_gaji', 10, 2)->nullable();
            $table->decimal('bpjs_kesehatan', 18, 2)->nullable();
            $table->decimal('bpjs_tk', 18, 2)->nullable();
            $table->decimal('bpjs_jht', 18, 2)->nullable();
            $table->decimal('total_gaji_pokok', 18, 2)->nullable();
            $table->decimal('total_remunerasi', 18, 2)->nullable();
            $table->integer('sisa_vol_rkbu')->nullable();
            $table->decimal('sisa_anggaran_rkbu', 18, 2)->nullable();
            $table->string('status_komponen')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')
                ->references('id')
                ->on('rkbus')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbu_detail_pegawais');
    }
};
