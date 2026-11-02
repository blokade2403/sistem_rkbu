<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['rkbu_detail_modals', 'rkbu_detail_modal_alkes'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->text('latar_belakang')->nullable();
                $table->text('pembahasan_analisis')->nullable();
                $table->text('maksud_tujuan')->nullable();
                $table->text('target_sasaran')->nullable();
                $table->text('user_pengguna')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['rkbu_detail_modals', 'rkbu_detail_modal_alkes'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'latar_belakang',
                    'pembahasan_analisis',
                    'maksud_tujuan',
                    'target_sasaran',
                    'user_pengguna',
                ]);
            });
        }
    }
};
