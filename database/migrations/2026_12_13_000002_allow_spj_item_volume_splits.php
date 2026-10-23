<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('spj_details', 'volume')) {
            Schema::table('spj_details', function (Blueprint $table) {
                $table->decimal('volume', 18, 3)->default(0)->after('usulan_belanja_item_id');
            });
        }

        $indexes = collect(DB::select('SHOW INDEX FROM spj_details'));
        if (! $indexes->contains(fn ($index) => $index->Key_name === 'spj_details_item_lookup_idx')) {
            Schema::table('spj_details', function (Blueprint $table) {
                $table->index('usulan_belanja_item_id', 'spj_details_item_lookup_idx');
            });
        }

        if ($indexes->contains(fn ($index) => $index->Key_name === 'spj_details_item_unique')) {
            Schema::table('spj_details', function (Blueprint $table) {
                $table->dropUnique('spj_details_item_unique');
            });
        }

        DB::table('spj_details as detail')
            ->join('usulan_belanja_items as item', 'item.id', '=', 'detail.usulan_belanja_item_id')
            ->where('detail.volume', 0)
            ->update(['detail.volume' => DB::raw('COALESCE(item.volume, 0)')]);
    }

    public function down(): void
    {
        Schema::table('spj_details', function (Blueprint $table) {
            $table->dropIndex('spj_details_item_lookup_idx');
            $table->dropColumn('volume');
        });
    }
};
