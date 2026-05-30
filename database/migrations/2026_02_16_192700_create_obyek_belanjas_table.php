<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('obyek_belanjas', function (Blueprint $table) {
            $table->uuid('id_obyek_belanja')->primary();
            $table->string('kode_obyek_belanja');
            $table->string('nama_obyek_belanja');
            $table
                ->foreignUuid('id_jenis_kategori_rkbu')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obyek_belanjas');
    }
};
