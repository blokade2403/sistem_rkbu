<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id_jenis_kategori_rkbu')->primary();
            $table->foreignUuid('id_jenis_belanja')->references('id_jenis_belanja')->on('jenis_belanjas')->onDelete('cascade');
            $table->string('kode_jenis_kategori_rkbu');
            $table->string('nama_jenis_kategori_rkbu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_kategori_rkbus');
    }
};
