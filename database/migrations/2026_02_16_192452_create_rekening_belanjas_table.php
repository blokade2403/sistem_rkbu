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
        Schema::create('rekening_belanjas', function (Blueprint $table) {
            $table->uuid('id_kode_rekening_belanja')->primary();
            $table
                ->foreignUuid('id_aktivitas')
                ->references('id_aktivitas')
                ->on('aktivitas')
                ->onDelete('cascade');
            $table->string('kode_rekening_belanja')->unique();
            $table->string('nama_rekening_belanja')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_belanjas');
    }
};
