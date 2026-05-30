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
        Schema::create('spj_details', function (Blueprint $table) {
            $table->uuid('id_spj_detail')->primary();
            $table->foreignUuid('id_rkbu')->references('id_rkbu')->on('rkbus')->onDelete('cascade');
            $table->foreignUuid('id_spj')->references('id_spj')->on('spjs')->onDelete('cascade');
            $table
                ->foreignUuid('id_usulan_barang')
                ->references('id_usulan_barang')
                ->on('usulan_barangs')
                ->onDelete('cascade');
            $table
                ->foreignUuid('id_usulan_barang_detail')
                ->references('id_usulan_barang_detail')
                ->on('usulan_barang_details')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spj_details');
    }
};
