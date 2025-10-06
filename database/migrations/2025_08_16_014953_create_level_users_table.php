<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_users', function (Blueprint $table) {
            $table->uuid('id_level')->primary();
            $table->string('nama_level_user');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('level_users');
    }
};
