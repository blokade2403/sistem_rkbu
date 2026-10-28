<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_edit_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id')->references('id')->on('rkbus')->cascadeOnDelete();
            $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('approval_id')->nullable()->index();
            $table->unsignedSmallInteger('fase_urutan')->default(1);
            $table->text('alasan')->nullable();
            $table->string('lampiran')->nullable();
            $table->decimal('nilai_sebelum', 18, 2)->default(0);
            $table->decimal('nilai_sesudah', 18, 2)->default(0);
            $table->decimal('selisih', 18, 2)->default(0);
            $table->json('header_sebelum')->nullable();
            $table->json('header_sesudah')->nullable();
            $table->json('detail_sebelum')->nullable();
            $table->json('detail_sesudah')->nullable();
            $table->timestamps();
            $table->index(['rkbu_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rkbu_edit_histories');
    }
};
