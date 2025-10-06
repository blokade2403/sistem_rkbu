<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fases', function (Blueprint $table) {
            $table->uuid('id_fase')->primary();
            $table->string('nama_fase');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fases');
    }
};
