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
        Schema::create('user_pptk', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            $table->char('user_id', 36);
            $table->char('pptk_kategori_id', 36);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table
                ->foreign('pptk_kategori_id')
                ->references('id')
                ->on('pptk_kategoris')
                ->cascadeOnDelete();

            $table->unique(['user_id', 'pptk_kategori_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pptk');
    }
};
