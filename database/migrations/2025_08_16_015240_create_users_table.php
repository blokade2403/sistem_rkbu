<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id_user')->primary();
            $table->foreignUuid('id_ksp')->references('id_ksp')->on('ksps')->onDelete('cascade');
            $table->foreignUuid('id_pejabat')->references('id_pejabat')->on('pejabats')->onDelete('cascade');
            $table->foreignUuid('id_admin_pendukung_ppk')->nullable()->references('id_admin_pendukung_ppk')->on('admin_pendukungs')->onDelete('cascade');
            $table->foreignUuid('id_unit')->references('id_unit')->on('units')->onDelete('cascade');
            $table->foreignUuid('id_level')->references('id_level')->on('level_users')->onDelete('cascade');
            $table->foreignUuid('id_fase')->references('id_fase')->on('fases')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('nama_lengkap');
            $table->bigInteger('nip_user')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255); // menentukan panjang string untuk password
            $table->enum('status_user', ['aktif', 'tidak aktif']); // menggunakan enum untuk status_user
            $table->enum('status_edit', ['aktif', 'tidak aktif']); // menggunakan enum untuk status_edit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
