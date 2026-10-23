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
        Schema::create('spj_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id')->references('id')->on('rkbus')->onDelete('cascade');
            $table->foreignUuid('spj_id')->references('id')->on('spjs')->onDelete('cascade');
            $table->foreignUuid('usulan_belanja_id')->references('id')->on('usulan_belanjas')->onDelete('cascade');
            $table->foreignUuid('usulan_belanja_item_id')->references('id')->on('usulan_belanja_items')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spj_details');
    }
};
