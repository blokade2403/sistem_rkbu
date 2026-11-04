<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Keep existing data when this table was created before the migration was recorded.
        if (Schema::hasTable('dokumen_perencanaans')) {
            return;
        }

        Schema::create('dokumen_perencanaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tahun_anggaran_id')->constrained('tahun_anggarans')->cascadeOnDelete();
            $table->foreignUuid('fase_id')->constrained('fases')->cascadeOnDelete();
            $table->string('judul');
            $table->string('keterangan', 1000)->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['tahun_anggaran_id', 'fase_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_perencanaans');
    }
};
