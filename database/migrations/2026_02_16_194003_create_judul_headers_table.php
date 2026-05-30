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
        Schema::create('judul_headers', function (Blueprint $table) {
            $table->uuid('id_judul_header')->primary();
            $table->string('nama_rs');
            $table->string('alamat_rs');
            $table->string('tlp_rs');
            $table->string('email_rs');
            $table->string('wilayah');
            $table->string('gambar1');
            $table->string('gambar2');
            $table->string('gambar3');
            $table->string('gambar4');
            $table->string('kode_pos')->nullable();
            $table->string('header1')->nullable();
            $table->string('header2')->nullable();
            $table->string('header3')->nullable();
            $table->string('header4')->nullable();
            $table->string('header5')->nullable();
            $table->string('header6')->nullable();
            $table->string('header7')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judul_headers');
    }
};
