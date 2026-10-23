<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table) {
            $table->unsignedInteger('nomor_urut')->nullable()->after('no_usulan_belanja');
            $table->unique(['usulan_belanja_id', 'nomor_urut'], 'spjs_usulan_nomor_urut_unique');
        });

        Schema::table('spj_details', function (Blueprint $table) {
            $table->unique('usulan_belanja_item_id', 'spj_details_item_unique');
        });
    }

    public function down(): void
    {
        Schema::table('spj_details', function (Blueprint $table) {
            $table->dropUnique('spj_details_item_unique');
        });

        Schema::table('spjs', function (Blueprint $table) {
            $table->dropUnique('spjs_usulan_nomor_urut_unique');
            $table->dropColumn('nomor_urut');
        });
    }
};
