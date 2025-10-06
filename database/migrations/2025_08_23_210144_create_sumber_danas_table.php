<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sumber_danas', function (Blueprint $table) {
            $table->uuid('id_sumber_dana')->primary();
            $table->string('nama_sumber_dana');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sumber_danas');
    }
};
