<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('usulan_barang_details', function (Blueprint $table) {
            $table->string('status_usulan_barang_detail')->nullable()->after('spek_detail');
            $table->text('keterangan_update_usulan_barang_detail')->nullable()->after('status_usulan_barang_detail');
        });
    }

    public function down(): void
    {
        Schema::table('usulan_barang_details', function (Blueprint $table) {
            $table->dropColumn('status_usulan_barang_detail');
        });
    }
};
