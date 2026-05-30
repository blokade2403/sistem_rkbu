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
        Schema::create('gambar_logins', function (Blueprint $table) {
            $table->uuid('id_gambar_login')->primary();
            $table->string('gambar_login')->nullable();
            $table->string('tahun_anggaran')->nullable();
            $table->enum('status_gambar', ['Aktif', 'Non Aktif'])->default('Non Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_logins');
    }
};
