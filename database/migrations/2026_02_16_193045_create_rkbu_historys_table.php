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
        Schema::create('rkbu_historys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_rkbu')->references('id_rkbu')->on('rkbus')->onDelete('cascade');
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table
                ->foreignUuid('id_jenis_kategori_rkbu')
                ->references('id_jenis_kategori_rkbu')
                ->on('jenis_kategori_rkbus')
                ->onDelete('cascade');
            $table->text('data_sebelum');
            $table->text('data_sesudah');
            $table->string('keterangan_status')->nullable();
            $table->string('upload_file_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbu_historys');
    }
};
