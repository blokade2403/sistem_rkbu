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
        Schema::create('sub_kategori_rekenings', function (Blueprint $table) {
            $table->uuid('id_sub_kategori_rekening')->primary();
            $table
                ->foreignUuid('id_kategori_rekening')
                ->references('id_kategori_rekening')
                ->on('kategori_rekenings')
                ->onDelete('cascade');
            $table->string('kode_sub_kategori_rekening');
            $table->string('nama_sub_kategori_rekening');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_rekenings');
    }
};
