
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usulan_belanjas', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            // Satu usulan untuk satu RKBU
            $table->char('rkbu_id', 36)->unique();

            // Nomor usulan dan urutan per tahun anggaran
            $table->string('no_usulan_belanja')->unique();
            $table->unsignedInteger('nomor_urut');

            // Pengusul
            $table->char('user_id', 36);
            $table->string('nama_pengusul');
            $table->char('unit_id', 36);

            // Klasifikasi
            $table->char('sub_kategori_rkbu_id', 36);
            $table->char('jenis_kategori_rkbu_id', 36);
            $table->char('tahun_anggaran_id', 36);

            // Status dokumen
            $table->string('status_usulan', 30)
                ->default('draft');

            // Validator terakhir yang menyelesaikan validasi
            $table->timestamp('tanggal_validasi')->nullable();
            $table->char('validator_user_id', 36)->nullable();
            $table->string('nama_validasi')->nullable();
            $table->text('catatan')->nullable();

            // Satu instance workflow untuk satu usulan
            $table->char('workflow_instance_id', 36)
                ->nullable()
                ->unique();

            $table->timestamps();

            $table->unique(
                ['tahun_anggaran_id', 'nomor_urut'],
                'usulan_belanja_tahun_urut_unique'
            );

            $table->index(
                ['unit_id', 'tahun_anggaran_id', 'status_usulan'],
                'usulan_belanja_filter_index'
            );

            $table->foreign('rkbu_id')
                ->references('id')->on('rkbus')
                ->restrictOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->foreign('unit_id')
                ->references('id')->on('units')
                ->restrictOnDelete();

            $table->foreign('sub_kategori_rkbu_id')
                ->references('id_sub_kategori_rkbu')
                ->on('sub_kategori_rkbus')
                ->restrictOnDelete();

            $table->foreign('jenis_kategori_rkbu_id')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus')
                ->restrictOnDelete();

            $table->foreign('tahun_anggaran_id')
                ->references('id')->on('tahun_anggarans')
                ->restrictOnDelete();

            $table->foreign('validator_user_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('workflow_instance_id')
                ->references('id')->on('workflow_instances')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_belanjas');
    }
};
