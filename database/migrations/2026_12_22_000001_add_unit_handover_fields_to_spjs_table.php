<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->string('no_bast_serah_terima_unit')->nullable()->after('keterangan_verif_pengurus_barang');
            $table->string('nama_penerima_barang')->nullable()->after('no_bast_serah_terima_unit');
        });
    }

    public function down(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->dropColumn(['no_bast_serah_terima_unit', 'nama_penerima_barang']);
        });
    }
};
