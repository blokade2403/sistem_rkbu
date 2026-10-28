<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rkbu_detail_pegawais', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('nama_pegawai');
            $table->unsignedTinyInteger('bulan')->default(1)->after('nip');
        });
    }

    public function down(): void
    {
        Schema::table('rkbu_detail_pegawais', function (Blueprint $table) {
            $table->dropColumn(['nip', 'bulan']);
        });
    }
};
