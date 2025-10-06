<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id_pptk')->nullable()->after('id_kode_rekening_belanja');

            $table->foreign('id_pptk')
                ->references('id_pptk')
                ->on('pptks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kategori_rkbus', function (Blueprint $table) {
            //
        });
    }
};
