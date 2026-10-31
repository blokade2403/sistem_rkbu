<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persediaan_requests', function (Blueprint $table) {
            $table->decimal('jumlah_disetujui', 18, 3)->nullable()->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('persediaan_requests', function (Blueprint $table) {
            $table->dropColumn('jumlah_disetujui');
        });
    }
};
