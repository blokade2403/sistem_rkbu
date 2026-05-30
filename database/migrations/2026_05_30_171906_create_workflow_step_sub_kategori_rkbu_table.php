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
        Schema::create('workflow_step_sub_kategori_rkbu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_step_id');
            $table->uuid('sub_kategori_rkbu_id');
            $table->timestamps();
            $table
                ->foreign('workflow_step_id')
                ->references('id')
                ->on('workflow_steps')
                ->cascadeOnDelete();
            $table
                ->foreign('sub_kategori_rkbu_id')
                ->references('id_sub_kategori_rkbu')
                ->on('sub_kategori_rkbus')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_step_sub_kategori_rkbu');
    }
};
