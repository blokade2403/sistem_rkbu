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
            $table->char('id_kode_rekening_belanja', 36)
                ->nullable()
                ->after('sumber_dana_id');

            // Relasi ke rekening belanja
            $table->foreign(
                'id_kode_rekening_belanja',
                'rkbus_rekening_belanja_fk'
            )
                ->references('id_kode_rekening_belanja')
                ->on('rekening_belanjas')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rkbus', function (Blueprint $table) {
            $table->dropForeign('rkbus_rekening_belanja_fk');
            $table->dropColumn([
                'id_kode_rekening_belanja',
            ]);
        });
    }
};
