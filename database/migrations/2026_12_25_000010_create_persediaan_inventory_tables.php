<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persediaan_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('unit_id')->constrained('units')->restrictOnDelete();
            $table->uuid('usulan_belanja_item_id')->nullable()->unique();
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('stok', 18, 3)->default(0);
            $table->timestamps();
            $table->foreign('usulan_belanja_item_id')->references('id')->on('usulan_belanja_items')->nullOnDelete();
            $table->index(['unit_id', 'nama_barang']);
        });

        Schema::create('persediaan_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('persediaan_item_id')->constrained('persediaan_items')->restrictOnDelete();
            $table->foreignUuid('unit_peminta_id')->constrained('units')->restrictOnDelete();
            $table->foreignUuid('requested_by')->constrained('users')->restrictOnDelete();
            $table->decimal('jumlah', 18, 3);
            $table->text('keperluan');
            $table->string('status', 24)->default('Menunggu');
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
            $table->index(['unit_peminta_id', 'status', 'created_at']);
        });

        Schema::create('persediaan_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('persediaan_item_id')->constrained('persediaan_items')->restrictOnDelete();
            $table->foreignUuid('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignUuid('unit_peminta_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignUuid('spj_detail_id')->nullable()->constrained('spj_details')->nullOnDelete();
            $table->foreignUuid('persediaan_request_id')->nullable()->constrained('persediaan_requests')->nullOnDelete();
            $table->string('jenis', 20);
            $table->decimal('jumlah', 18, 3)->comment('Positif untuk barang masuk, negatif untuk barang keluar/opname minus.');
            $table->decimal('stok_sebelum', 18, 3);
            $table->decimal('stok_sesudah', 18, 3);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['persediaan_item_id', 'tanggal']);
            $table->index(['spj_detail_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persediaan_transactions');
        Schema::dropIfExists('persediaan_requests');
        Schema::dropIfExists('persediaan_items');
    }
};
