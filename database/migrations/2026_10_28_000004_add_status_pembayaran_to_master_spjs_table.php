<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('master_spjs') && ! Schema::hasColumn('master_spjs', 'status_pembayaran')) {
            Schema::table('master_spjs', function (Blueprint $table) {
                $table->string('status_pembayaran')->nullable()->after('tanggal_pembayaran');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_spjs') && Schema::hasColumn('master_spjs', 'status_pembayaran')) {
            Schema::table('master_spjs', function (Blueprint $table) {
                $table->dropColumn('status_pembayaran');
            });
        }
    }
};
