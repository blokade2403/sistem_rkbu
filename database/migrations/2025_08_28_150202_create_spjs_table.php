<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spjs', function (Blueprint $table) {
            $table->uuid('id_spj')->primary();
            $table->foreignUuid('id_usulan_barang')->references('id_usulan_barang')->on('usulan_barangs')->onDelete('cascade');
            $table->foreignUuid('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('no_usulan_barang');
            $table->string('status_spj');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
