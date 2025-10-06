<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_pendukungs', function (Blueprint $table) {
            $table->uuid('id_admin_pendukung_ppk')->primary();
            $table->string('nama_pendukung_ppk');
            $table->string('jabatan_pendukung_ppk');
            $table->string('nrk')->unique();
            $table->foreignUuid('id_pejabat_pengadaan')->references('id_pejabat_pengadaan')->on('pejabat_pengadaans')->onDelete('cascade');
            $table->foreignUuid('id_pptk')->references('id_pptk')->on('pptks')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_pendukungs');
    }
};
