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
        Schema::create("tahun_anggarans", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("nama_tahun_anggaran"); // 2026, 2027

            $table->uuid("fase_id")->nullable(); // fase aktif tahun ini
            $table->enum("status", ["aktif", "nonaktif"])->default("nonaktif");

            $table->timestamps();

            $table
                ->foreign("fase_id")
                ->references("id")
                ->on("fases")
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tahun_anggarans");
    }
};
