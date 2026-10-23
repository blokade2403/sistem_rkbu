<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagu_anggarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->cascadeOnDelete();
            $table->foreignUuid('id_kode_rekening_belanja')
                ->references('id_kode_rekening_belanja')->on('rekening_belanjas')->cascadeOnDelete();
            $table->enum('jenis_sumber_dana', ['BLUD', 'Non BLUD']);
            $table->decimal('jumlah_pagu', 18, 2);
            $table->timestamps();
            $table->unique(['tahun_anggaran_id', 'id_kode_rekening_belanja'], 'pagu_anggaran_tahun_rekening_unique');
            $table->index(['tahun_anggaran_id', 'jenis_sumber_dana'], 'pagu_anggaran_tahun_sumber_index');
        });

        Schema::create('pagu_anggaran_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pagu_anggaran_id')->nullable()->references('id')->on('pagu_anggarans')->nullOnDelete();
            $table->foreignUuid('tahun_anggaran_id')->references('id')->on('tahun_anggarans')->cascadeOnDelete();
            $table->foreignUuid('id_kode_rekening_belanja')
                ->references('id_kode_rekening_belanja')->on('rekening_belanjas')->cascadeOnDelete();
            $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignUuid('rkbu_id')->nullable()->references('id')->on('rkbus')->nullOnDelete();
            $table->char('usulan_belanja_id', 36)->nullable()->index();
            $table->unsignedSmallInteger('fase_urutan')->default(1);
            $table->string('jenis_perubahan', 30);
            $table->text('alasan');
            $table->string('lampiran')->nullable();
            $table->decimal('nilai_sebelum', 18, 2)->default(0);
            $table->decimal('nilai_sesudah', 18, 2)->default(0);
            $table->decimal('selisih', 18, 2)->default(0);
            $table->json('detail_sebelum')->nullable();
            $table->json('detail_sesudah')->nullable();
            $table->timestamps();
            $table->index(['tahun_anggaran_id', 'id_kode_rekening_belanja', 'created_at'], 'pagu_history_rekening_date_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagu_anggaran_histories');
        Schema::dropIfExists('pagu_anggarans');
    }
};
