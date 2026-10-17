<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_rkbu')->unique();
            $table->foreignUuid('tahun_anggaran_id');
            $table->foreignUuid('unit_id');
            $table->foreignUuid('sumber_dana_id');
            $table->foreignUuid('jenis_belanja_id');
            $table->foreignUuid('jenis_kategori_rkbu_id');
            $table->foreignUuid('sub_kategori_rkbu_id');
            $table->foreignUuid('created_by');
            $table->foreignUuid('workflow_instance_id')
                ->nullable();
            $table->enum('status', [
                'draft',
                'proses',
                'approved',
                'rejected',
                'revision'
            ])->default('draft');
            $table->timestamps();
            $table->foreign('tahun_anggaran_id')
                ->references('id')
                ->on('tahun_anggarans');
            $table->foreign('unit_id')
                ->references('id')
                ->on('units');
            $table->foreign('sumber_dana_id')
                ->references('id_sumber_dana')
                ->on('sumber_danas');
            $table->foreign('jenis_belanja_id')
                ->references('id_jenis_belanja')
                ->on('jenis_belanjas');
            $table->foreign('jenis_kategori_rkbu_id')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus');
            $table->foreign('sub_kategori_rkbu_id')
                ->references('id_sub_kategori_rkbu')
                ->on('sub_kategori_rkbus');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->foreign('workflow_instance_id')
                ->references('id')
                ->on('workflow_instances')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbus');
    }
};
