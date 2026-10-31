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
            $table->foreignUuid('sub_kategori_rkbu_id')->nullable()->after('unit_id')
                ->constrained('sub_kategori_rkbus', 'id_sub_kategori_rkbu')->nullOnDelete();
            $table->decimal('harga_satuan', 18, 2)->nullable()->after('satuan');
            $table->decimal('ppn', 5, 2)->default(0)->after('harga_satuan');
        });

        DB::table('persediaan_items as stock')
            ->join('usulan_belanja_items as source', 'source.id', '=', 'stock.usulan_belanja_item_id')
            ->join('rkbus as rkbu', 'rkbu.id', '=', 'source.rkbu_id')
            ->update([
                'stock.sub_kategori_rkbu_id' => DB::raw('rkbu.sub_kategori_rkbu_id'),
                'stock.harga_satuan' => DB::raw('source.harga_satuan'),
                'stock.ppn' => DB::raw('COALESCE(source.ppn, 0)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('persediaan_items', function (Blueprint $table) {
            $table->dropForeign(['sub_kategori_rkbu_id']);
            $table->dropColumn(['sub_kategori_rkbu_id', 'harga_satuan', 'ppn']);
        });
    }
};
