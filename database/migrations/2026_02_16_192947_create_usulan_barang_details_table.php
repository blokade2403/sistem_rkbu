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
        Schema::create('usulan_barang_details', function (Blueprint $table) {
            $table->uuid('id_usulan_barang_detail')->primary();
            $table
                ->foreignUuid('id_usulan_barang')
                ->references('id_usulan_barang')
                ->on('usulan_barangs')
                ->onDelete('cascade');
            $table->foreignUuid('id_rkbu')->references('id_rkbu')->on('rkbus')->onDelete('cascade');
            $table
                ->foreignUuid('id_sub_kategori_rkbu')
                ->references('id_sub_kategori_rkbu')
                ->on('sub_kategori_rkbus')
                ->onDelete('cascade');
            $table->decimal('harga_barang', 15, 2)->nullable();
            $table->decimal('ppn', 5, 2)->nullable();
            $table->decimal('total_ppn', 15, 2)->nullable();
            $table->bigInteger('jumlah_usulan_barang')->nullable();
            $table->decimal('total_anggaran_usulan_barang', 15, 2)->nullable();
            $table->integer('rata2_pemakaian')->nullable();
            $table->integer('sisa_stok')->nullable();
            $table->integer('pengkali')->nullable();
            $table->integer('stok_minimal')->nullable();
            $table->integer('buffer_stok')->nullable();
            $table->integer('jumlah_vol_rkbu')->nullable();
            $table->integer('vol_1_detail')->nullable();
            $table->string('satuan_1_detail')->nullable();
            $table->integer('vol_2_detail')->nullable();
            $table->string('satuan_2_detail')->nullable();
            $table->text('spek_detail')->nullable();
            $table->string('status_usulan_barang_detail')->nullable();
            $table->text('keterangan_update_usulan_barang_detail')->nullable();
            $table->string('created_by')->nullable();
            $table->string('status_komponen_spj')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_barang_details');
    }
};
