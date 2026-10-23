<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_belanjas', function (Blueprint $table) {
            $table->dropForeign('usulan_belanjas_rkbu_id_foreign');
            $table->dropUnique('usulan_belanjas_rkbu_id_unique');
            $table->index('rkbu_id', 'usulan_belanjas_rkbu_id_index');
            $table->foreign('rkbu_id', 'usulan_belanjas_rkbu_id_foreign')
                ->references('id')->on('rkbus')
                ->restrictOnDelete();
        });

        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->dropUnique('usulan_belanja_item_detail_unique');
        });
    }

    public function down(): void
    {
        // Constraints cannot be restored safely when repeated RKBU/details exist.
    }
};
