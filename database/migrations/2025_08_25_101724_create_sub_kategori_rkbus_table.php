<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id_sub_kategori_rkbu')->primary();
            $table->foreignUuid('id_kategori_rkbu')->references('id_kategori_rkbu')->on('kategori_rkbus')->onDelete('cascade');
            $table->foreignUuid('id_admin_pendukung_ppk')->references('id_admin_pendukung_ppk')->on('admin_pendukungs')->onDelete('cascade');
            $table->foreignUuid('id_sub_kategori_rekening')
                ->references('id_sub_kategori_rekening')
                ->on('sub_kategori_rekenings')
                ->onDelete('cascade');
            $table->foreignUuid('id_kode_rekening_belanja')->references('id_kode_rekening_belanja')->on('rekening_belanjas')->onDelete('cascade');
            $table->string('kode_sub_kategori_rkbu');
            $table->string('nama_sub_kategori_rkbu');
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_rkbus');
    }
};
