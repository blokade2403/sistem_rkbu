<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            if (! Schema::hasColumn('usulan_belanja_items', 'detail_type')) {
                $table->string('detail_type', 30)->nullable()->after('rkbu_id');
            }

            if (! Schema::hasColumn('usulan_belanja_items', 'detail_id')) {
                $table->char('detail_id', 36)->nullable()->after('detail_type');
            }

            $table->index('rkbu_id', 'usulan_belanja_items_rkbu_id_index');
            $table->dropUnique('usulan_belanja_items_rkbu_id_unique');
            $table->unique(
                ['detail_type', 'detail_id'],
                'usulan_belanja_item_detail_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->dropUnique('usulan_belanja_item_detail_unique');
            $table->dropColumn(['detail_type', 'detail_id']);
        });
    }
};
