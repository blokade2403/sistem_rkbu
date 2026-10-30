<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('penempatans')) {
            Schema::create('penempatans', function (Blueprint $table) {
                // `id_penempatan` mirrors `id` for existing asset/location screens.
                $table->uuid('id')->primary();
                $table->uuid('id_penempatan')->unique();
                $table->string('lokasi_barang', 500);
                $table->string('tempat_lokasi', 500);
                $table->string('gedung', 255)->nullable();
                $table->string('penanggung_jawab')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('penempatans', function (Blueprint $table) {
                if (! Schema::hasColumn('penempatans', 'id')) {
                    $table->uuid('id')->nullable()->unique();
                }
                if (! Schema::hasColumn('penempatans', 'lokasi_barang')) {
                    $table->string('lokasi_barang', 500)->nullable();
                }
                if (! Schema::hasColumn('penempatans', 'tempat_lokasi')) {
                    $table->string('tempat_lokasi', 500)->nullable();
                }
                if (! Schema::hasColumn('penempatans', 'gedung')) {
                    $table->string('gedung', 255)->nullable();
                }
                if (! Schema::hasColumn('penempatans', 'penanggung_jawab')) {
                    $table->string('penanggung_jawab')->nullable();
                }
            });

            if (Schema::hasColumn('penempatans', 'id_penempatan')) {
                DB::table('penempatans')->whereNull('id')->update(['id' => DB::raw('id_penempatan')]);
            }
        }

        Schema::table('assets', function (Blueprint $table) {
            if (! Schema::hasColumn('assets', 'id_penempatan')) {
                $table->uuid('id_penempatan')->nullable()->index();
            }
            if (! Schema::hasColumn('assets', 'penanggung_jawab_user_id')) {
                $table->uuid('penanggung_jawab_user_id')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        // Preserve penempatans and asset references on rollback: the location
        // table may predate this migration and is shared with legacy screens.
    }
};
