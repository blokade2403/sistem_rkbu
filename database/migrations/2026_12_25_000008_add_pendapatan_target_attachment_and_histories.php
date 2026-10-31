<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('target_pendapatans', function (Blueprint $table) {
            $table->string('lampiran_surat_path')->nullable();
        });

        Schema::create('target_pendapatan_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('target_pendapatan_id')->constrained('target_pendapatans')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('target_sebelum', 18, 2)->nullable();
            $table->decimal('target_sesudah', 18, 2);
            $table->string('lampiran_surat_path')->nullable();
            $table->timestamps();
            $table->index(['target_pendapatan_id', 'created_at'], 'target_pendapatan_history_target_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_pendapatan_histories');
        Schema::table('target_pendapatans', function (Blueprint $table) {
            $table->dropColumn('lampiran_surat_path');
        });
    }
};
