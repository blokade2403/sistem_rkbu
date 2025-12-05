<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement("ALTER TABLE rkbus MODIFY created_at TIMESTAMP NULL AFTER total_remunerasi");
        DB::statement("ALTER TABLE rkbus MODIFY updated_at TIMESTAMP NULL AFTER created_at");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rkbus', function (Blueprint $table) {
            DB::statement("ALTER TABLE rkbus MODIFY created_at TIMESTAMP NULL");
            DB::statement("ALTER TABLE rkbus MODIFY updated_at TIMESTAMP NULL");
        });
    }
};
