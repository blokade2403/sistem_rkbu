<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pejabats', function (Blueprint $table) {
            $table->uuid('id_pejabat')->primary();
            $table->string('nama_pejabat');
            $table->bigInteger('nip_pejabat')->nullable();
            $table->foreignUuid('id_jabatan')->references('id_jabatan')->on('jabatans')->onDelete('cascade');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabats');
    }
};
