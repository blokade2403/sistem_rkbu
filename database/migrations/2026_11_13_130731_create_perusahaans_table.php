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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_perusahaan');
            $table->text('alamat_perusahaan');
            $table->string('email_perusahaan');
            $table->string('tlp_perusahaan');
            $table->string('nama_direktur_perusahaan');
            $table->string('jabatan_perusahaan');
            $table->string('no_npwp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
