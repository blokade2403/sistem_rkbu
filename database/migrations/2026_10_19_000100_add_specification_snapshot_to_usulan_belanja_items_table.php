<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->text('spesifikasi')
                ->nullable()
                ->after('detail_id');

            $table->decimal('total_anggaran_usulan_barang', 20, 2)
                ->nullable()
                ->after('spesifikasi');

            $table->decimal('rata2_pemakaian', 15, 2)
                ->nullable()
                ->after('total_anggaran_usulan_barang');

            $table->decimal('sisa_stok', 15, 2)
                ->nullable()
                ->after('rata2_pemakaian');

            $table->decimal('pengkali', 15, 2)
                ->nullable()
                ->after('sisa_stok');

            $table->decimal('stok_minimal', 15, 2)
                ->nullable()
                ->after('pengkali');

            $table->decimal('buffer_stok', 15, 2)
                ->nullable()
                ->after('stok_minimal');

            $table->decimal('vol_1_detail', 15, 2)
                ->nullable()
                ->after('buffer_stok');

            $table->string('satuan_1_detail', 100)
                ->nullable()
                ->after('vol_1_detail');

            $table->decimal('vol_2_detail', 15, 2)
                ->nullable()
                ->after('satuan_1_detail');

            $table->string('satuan_2_detail', 100)
                ->nullable()
                ->after('vol_2_detail');
        });
    }

    /**
     * Menghapus seluruh kolom yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('usulan_belanja_items', function (Blueprint $table) {
            $table->dropColumn([
                'spesifikasi',
                'total_anggaran_usulan_barang',
                'rata2_pemakaian',
                'sisa_stok',
                'pengkali',
                'stok_minimal',
                'buffer_stok',
                'vol_1_detail',
                'satuan_1_detail',
                'vol_2_detail',
                'satuan_2_detail',
            ]);
        });
    }
};
