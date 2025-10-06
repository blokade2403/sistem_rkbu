<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponens', function (Blueprint $table) {
            $table->uuid('id_komponen')->primary();
            $table->foreignUuid('id_jenis_kategori_rkbu')->references('id_jenis_kategori_rkbu')->on('jenis_kategori_rkbus')->onDelete('cascade');
            $table->string('kode_barang')->nullable();
            $table->string('kode_komponen')->nullable();
            $table->string('nama_barang');
            $table->string('satuan')->nullable();
            $table->text('spek')->nullable();
            $table->bigInteger('harga_barang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponens');
    }
};
