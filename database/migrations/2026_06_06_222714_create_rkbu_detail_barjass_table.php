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
        Schema::create('rkbu_detail_barjass', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id');
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('volume_1')->nullable();
            $table->string('satuan_1')->nullable();
            $table->integer('volume_2')->nullable();
            $table->string('satuan_2')->nullable();
            $table->bigInteger('jumlah_volume')->default(0);
            $table->decimal('harga_satuan', 18, 2);
            $table->decimal('ppn', 5, 2)->nullable();
            $table->decimal('total_anggaran', 18, 2);
            $table->string('rating')->nullable();
            $table->string('link_ekatalog')->nullable();
            $table->string('upload_file_1')->nullable();
            $table->string('upload_file_2')->nullable();
            $table->string('upload_file_3')->nullable();
            $table->string('upload_file_4')->nullable();
            $table->string('upload_file_5')->nullable();
            $table->string('keterangan_status')->nullable();
            $table->string('penempatan')->nullable();
            $table->integer('sisa_vol_rkbu')->nullable();
            $table->decimal('sisa_anggaran_rkbu', 18, 2)->nullable();
            $table->string('status_komponen')->nullable();
            $table->integer('standar_kebutuhan')->nullable();
            $table->integer('eksisting')->nullable();
            $table->string('kondisi_baik')->nullable();
            $table->string('kondisi_rusak_berat')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')
                ->references('id')
                ->on('rkbus')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbu_detail_barjass');
    }
};
