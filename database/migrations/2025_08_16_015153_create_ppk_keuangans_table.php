<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppk_keuangans', function (Blueprint $table) {
            $table->uuid('id_ppk_keuangan')->primary();
            $table->string('nama_ppk_keuangan');
            $table->string('nip_ppk_keuangan');
            $table->enum('status', ['aktif', 'tidak aktif']); // menggunakan enum untuk status_edit

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppk_keuangans');
    }
};
