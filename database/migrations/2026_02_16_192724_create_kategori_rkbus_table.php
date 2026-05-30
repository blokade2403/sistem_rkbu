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
        Schema::create('kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id_kategori_rkbu')->primary();
            $table
                ->foreignUuid('id_obyek_belanja')
                ->references('id_obyek_belanja')
                ->on('obyek_belanjas')
                ->onDelete('cascade');
            $table
                ->foreignUuid('id_jenis_kategori_rkbu')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus')
                ->onDelete('cascade');
            $table->string('kode_kategori_rkbu');
            $table->string('nama_kategori_rkbu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_rkbus');
    }
};
