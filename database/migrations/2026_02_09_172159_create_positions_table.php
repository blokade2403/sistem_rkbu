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
        Schema::create('positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_jabatan');
            $table->uuid('parent_id')->nullable()->index();
            $table->uuid('unit_id')->index();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('positions')->nullOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['unit_id']);
        });

        Schema::dropIfExists('positions');
    }
};
