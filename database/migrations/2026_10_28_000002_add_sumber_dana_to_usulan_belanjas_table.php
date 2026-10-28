<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_belanjas', function (Blueprint $table) {
            $table->uuid('sumber_dana_id')->nullable()->after('jenis_kategori_rkbu_id');
            $table->foreign('sumber_dana_id')
                ->references('id_sumber_dana')->on('sumber_danas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usulan_belanjas', function (Blueprint $table) {
            $table->dropForeign(['sumber_dana_id']);
            $table->dropColumn('sumber_dana_id');
        });
    }
};
