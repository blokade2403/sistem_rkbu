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
        Schema::table('rkbus', function (Blueprint $table) {
            $table->char('sumber_dana_id', 36)->nullable()->change();
            $table->char('sub_kegiatan_id', 36)
                ->nullable()
                ->after('sub_kategori_rkbu_id');

            $table->foreign('sub_kegiatan_id')
                ->references('id_sub_kegiatan')
                ->on('sub_kegiatans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rkbus', function (Blueprint $table) {
            //
        });
    }
};
