<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_barang_details', function (Blueprint $table) {
            $table->bigInteger('jumlah_usulan_barang')->nullable()->after('total_ppn');
            $table->bigInteger('total_anggaran_usulan_barang')->nullable()->after('jumlah_usulan_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usulan_barang_details', function (Blueprint $table) {
            //
        });
    }
};
