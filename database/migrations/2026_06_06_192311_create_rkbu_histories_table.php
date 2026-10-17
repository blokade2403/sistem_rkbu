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
        Schema::create('rkbu_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id');
            $table->foreignUuid('approval_id')->nullable();
            $table->foreignUuid('user_id');
            $table->string('aksi');
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->text('catatan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')
                ->references('id')
                ->on('rkbus')
                ->cascadeOnDelete();
            $table->foreign('approval_id')
                ->references('id')
                ->on('approvals')
                ->nullOnDelete();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkbu_histories');
    }
};
