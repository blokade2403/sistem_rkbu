<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->decimal('harga_satuan', 18, 2)->nullable()->after('detail_id');
            $table->decimal('volume', 18, 3)->nullable()->after('harga_satuan');
            $table->decimal('ppn', 5, 2)->nullable()->after('volume');
        });
    }

    public function down(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'volume', 'ppn']);
        });
    }
};
