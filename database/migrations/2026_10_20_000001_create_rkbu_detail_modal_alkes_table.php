<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rkbu_detail_modal_alkes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('rkbu_id');
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('volume_1')->nullable();
            $table->string('satuan_1')->nullable();
            $table->integer('volume_2')->nullable();
            $table->string('satuan_2')->nullable();
            $table->bigInteger('jumlah_volume')->default(0);
            $table->decimal('harga_satuan', 18, 2);
            $table->decimal('ppn', 5, 2)->nullable();
            $table->decimal('total_anggaran', 18, 2);
            $table->string('rating')->nullable();
            $table->string('link_ekatalog')->nullable();
            $table->string('upload_file_1')->nullable();
            $table->string('upload_file_2')->nullable();
            $table->string('upload_file_3')->nullable();
            $table->string('upload_file_4')->nullable();
            $table->string('upload_file_5')->nullable();
            $table->string('keterangan_status')->nullable();
            $table->string('penempatan')->nullable();
            $table->integer('sisa_vol_rkbu')->nullable();
            $table->decimal('sisa_anggaran_rkbu', 18, 2)->nullable();
            $table->string('status_komponen')->nullable();
            $table->integer('standar_kebutuhan')->nullable();
            $table->integer('eksisting')->nullable();
            $table->string('kondisi_baik')->nullable();
            $table->string('kondisi_rusak_berat')->nullable();
            $table->timestamps();
            $table->foreign('rkbu_id')->references('id')->on('rkbus')->cascadeOnDelete();
        });

        // Pindahkan data alkes lama dari tabel modal umum ke tabel khususnya.
        DB::table('rkbu_detail_modals as detail')
            ->join('rkbus', 'rkbus.id', '=', 'detail.rkbu_id')
            ->join('sub_kategori_rkbus as sub', 'sub.id_sub_kategori_rkbu', '=', 'rkbus.sub_kategori_rkbu_id')
            ->join('kategori_rkbus as kategori', 'kategori.id_kategori_rkbu', '=', 'sub.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'kategori.id_jenis_kategori_rkbu')
            ->where('jenis.nama_jenis_kategori_rkbu', 'Modal Alat Kesehatan')
            ->select('detail.*')
            ->orderBy('detail.id')
            ->chunkById(500, function ($details) {
                $ids = $details->pluck('id');
                $rows = $details->map(fn ($detail) => (array) $detail)->all();
                DB::table('rkbu_detail_modal_alkes')->insert($rows);

                if (Schema::hasTable('usulan_belanja_items')) {
                    DB::table('usulan_belanja_items')
                        ->where('detail_type', 'modal')
                        ->whereIn('detail_id', $ids)
                        ->update(['detail_type' => 'modal_alkes']);
                }

                DB::table('rkbu_detail_modals')->whereIn('id', $ids)->delete();
            }, 'detail.id', 'id');
    }

    public function down(): void
    {
        if (Schema::hasTable('rkbu_detail_modal_alkes')) {
            DB::table('rkbu_detail_modal_alkes')->orderBy('id')->chunkById(500, function ($details) {
                $ids = $details->pluck('id');
                DB::table('rkbu_detail_modals')->insertOrIgnore(
                    $details->map(fn ($detail) => (array) $detail)->all()
                );

                if (Schema::hasTable('usulan_belanja_items')) {
                    DB::table('usulan_belanja_items')
                        ->where('detail_type', 'modal_alkes')
                        ->whereIn('detail_id', $ids)
                        ->update(['detail_type' => 'modal']);
                }
            });
        }

        Schema::dropIfExists('rkbu_detail_modal_alkes');
    }
};
