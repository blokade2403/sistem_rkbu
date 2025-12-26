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
        Schema::table('master_spjs', function (Blueprint $table) {
            $table->string('no_faktur')->nullable()->after('status_hutang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_spjs', function (Blueprint $table) {
            $table->dropColumn('no_faktur');
        });
    }
};
