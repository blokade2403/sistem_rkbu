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
        Schema::create('target_sps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->tinyInteger('bulan'); // 1 = Januari ... 12 = Desember
            $table->bigInteger('target')->default(0);
            $table->string('nama_tahun_anggaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_sps');
    }
};
