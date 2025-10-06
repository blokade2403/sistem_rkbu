<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ksps', function (Blueprint $table) {
            $table->uuid('id_ksp')->primary();
            $table->bigInteger('nip_ksp')->nullable(); // Mengganti 'number' dengan 'bigInteger' yang sesuai untuk angka besar seperti NIP
            $table->string('nama_ksp');
            $table->foreignUuid('id_pejabat')->references('id_pejabat')->on('pejabats')->onDelete('cascade');
            $table->foreignUuid('id_fungsional')->references('id_fungsional')->on('fungsionals')->onDelete('cascade');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ksps');
    }
};
