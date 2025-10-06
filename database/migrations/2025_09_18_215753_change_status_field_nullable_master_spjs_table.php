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
        Schema::table('master_spjs', function (Blueprint $table) {
            $table->date('tgl_barang_datang_parsial_1')->nullable()->change();
            $table->date('tgl_barang_datang_parsial_2')->nullable()->change();
            $table->date('tgl_barang_datang_parsial_3')->nullable()->change();
            $table->string('status_verifikasi_pengurus_barang_parsial_1')->nullable()->change();
            $table->string('status_verifikasi_pengurus_barang_parsial_2')->nullable()->change();
            $table->string('status_verifikasi_pengurus_barang_parsial_3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_spjs', function (Blueprint $table) {});
    }
};
