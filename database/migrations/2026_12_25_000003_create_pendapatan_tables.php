<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('target_pendapatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tahun_anggaran_id')->constrained('tahun_anggarans')->cascadeOnDelete();
            $table->foreignUuid('unit_id')->constrained('units')->cascadeOnDelete();
            $table->string('kode_rekening', 100);
            $table->string('nama_rekening', 255);
            $table->decimal('target_tahunan', 18, 2)->default(0);
            $table->foreignUuid('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['tahun_anggaran_id', 'unit_id', 'kode_rekening'], 'target_pendapatan_per_unit_year_code_unique');
        });

        Schema::create('realisasi_pendapatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('target_pendapatan_id')->constrained('target_pendapatans')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->decimal('jumlah_realisasi', 18, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->foreignUuid('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['target_pendapatan_id', 'bulan'], 'realisasi_pendapatan_target_month_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisasi_pendapatans');
        Schema::dropIfExists('target_pendapatans');
    }
};
