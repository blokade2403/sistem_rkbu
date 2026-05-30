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
        Schema::create('position_sub_kategori_rkbus', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table
                ->foreignUuid('id_sub_kategori_rkbu')
                ->references('id_sub_kategori_rkbu')
                ->on('sub_kategori_rkbus')
                ->onDelete('cascade');

            $table
                ->foreignUuid('position_id')
                ->references('id')
                ->on('positions')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_sub_kategori_rkbus');
    }
};
