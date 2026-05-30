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
        Schema::create('dokumen_rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tahun_anggaran_id');
            $table->uuid('unit_id');
            $table->uuid('created_by');

            $table
                ->enum('status', ['draft', 'proses_validasi', 'disetujui', 'ditolak'])
                ->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_rkbus');
    }
};
