<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persediaan_items', function (Blueprint $table) {
            $table->string('kode_barang', 20)->nullable()->unique()->after('id');
        });

        DB::table('persediaan_items')->select('id')->orderBy('id')->chunk(500, function ($items) {
            foreach ($items as $item) {
                $code = 'PS-' . strtoupper(substr(str_replace('-', '', $item->id), 0, 12));
                DB::table('persediaan_items')->where('id', $item->id)->update(['kode_barang' => $code]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('persediaan_items', function (Blueprint $table) {
            $table->dropUnique(['kode_barang']);
            $table->dropColumn('kode_barang');
        });
    }
};
