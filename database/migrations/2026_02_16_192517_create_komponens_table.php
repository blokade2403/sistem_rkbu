<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('komponens', function (Blueprint $table) {
            $table->uuid('id_komponen')->primary();
            $table
                ->foreignUuid('id_jenis_kategori_rkbu')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus')
                ->onDelete('cascade');
            $table->string('kode_barang');
            $table->string('kode_komponen')->unique();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->text('spek');
            $table->bigInteger('harga_barang');

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
