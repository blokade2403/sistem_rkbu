<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('master_spjs') && ! Schema::hasColumn('master_spjs', 'no_faktur')) {
            Schema::table('master_spjs', function (Blueprint $table) {
                $table->string('no_faktur')->nullable()->after('tanggal_faktur');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_spjs') && Schema::hasColumn('master_spjs', 'no_faktur')) {
            Schema::table('master_spjs', function (Blueprint $table) {
                $table->dropColumn('no_faktur');
            });
        }
    }
};
