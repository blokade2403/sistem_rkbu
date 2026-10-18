<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usulan_belanja_items', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('usulan_belanja_id', 36);
            $table->char('rkbu_id', 36)->unique();
            $table->timestamps();

            $table->foreign('usulan_belanja_id')
                ->references('id')->on('usulan_belanjas')
                ->cascadeOnDelete();

            $table->foreign('rkbu_id')
                ->references('id')->on('rkbus')
                ->restrictOnDelete();
        });

        DB::table('usulan_belanjas')
            ->whereNotNull('rkbu_id')
            ->orderBy('id')
            ->get(['id', 'rkbu_id'])
            ->each(function ($usulan) {
                DB::table('usulan_belanja_items')->insert([
                    'id' => (string) Str::uuid(),
                    'usulan_belanja_id' => $usulan->id,
                    'rkbu_id' => $usulan->rkbu_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('usulan_belanja_items');
    }
};
