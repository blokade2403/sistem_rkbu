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
        Schema::table('sub_kategori_rkbus', function (Blueprint $table) {
            Schema::table('sub_kategori_rkbus', function (Blueprint $table) {
                $table->dropForeign(['id_kode_rekening_belanja']);
                $table->dropColumn('id_kode_rekening_belanja');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kategori_rkbus', function (Blueprint $table) {
            $table->foreignUuid('id_kode_rekening_belanja')
                ->nullable()
                ->after('id_sub_kategori_rekening')
                ->references('id_kode_rekening_belanja')
                ->on('rekening_belanjas')
                ->onDelete('cascade');
        });
    }
};
