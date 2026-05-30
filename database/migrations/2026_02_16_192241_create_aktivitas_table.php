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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->uuid('id_aktivitas')->primary();
            $table->string('kode_aktivitas');
            $table->string('nama_aktivitas');
            $table
                ->foreignUuid('id_program')
                ->references('id_program')
                ->on('programs')
                ->onDelete('cascade');
            $table
                ->foreignUuid('id_sub_kegiatan')
                ->references('id_sub_kegiatan')
                ->on('sub_kegiatans')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
