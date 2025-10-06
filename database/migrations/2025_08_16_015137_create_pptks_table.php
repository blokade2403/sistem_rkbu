<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pptks', function (Blueprint $table) {
            $table->uuid('id_pptk')->primary();
            $table->string('nama_pptk');
            $table->string('nip_pptk');
            $table->foreignUuid('id_tahun_anggaran')->references('id')->on('tahun_anggarans')->onDelete('cascade');
            $table->enum('status_pptk', ['aktif', 'tidak aktif']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pptks');
    }
};
