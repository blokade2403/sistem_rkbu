<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('master_spj_payment_transactions')) {
            Schema::create('master_spj_payment_transactions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('master_spj_id')->references('id')->on('master_spjs')->cascadeOnDelete();
                $table->decimal('amount', 20, 2);
                $table->date('tanggal_pembayaran');
                $table->string('bulan_pembayaran', 30)->nullable();
                $table->string('bukti_bayar')->nullable();
                $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->nullOnDelete();
                $table->timestamps();
                $table->index('tanggal_pembayaran');
            });
        }

        if (! Schema::hasTable('master_spjs') || ! Schema::hasColumn('master_spjs', 'pembayaran') || ! Schema::hasColumn('master_spjs', 'tanggal_pembayaran')) {
            return;
        }

        DB::table('master_spjs')
            ->whereNotNull('tanggal_pembayaran')
            ->whereNotNull('pembayaran')
            ->where('pembayaran', '>', 0)
            ->orderBy('id')
            ->chunk(300, function ($masters) {
                foreach ($masters as $master) {
                    $exists = DB::table('master_spj_payment_transactions')->where('master_spj_id', $master->id)->exists();
                    if ($exists) continue;
                    DB::table('master_spj_payment_transactions')->insert([
                        'id' => (string) Str::uuid(),
                        'master_spj_id' => $master->id,
                        'amount' => $master->pembayaran,
                        'tanggal_pembayaran' => $master->tanggal_pembayaran,
                        'bulan_pembayaran' => Schema::hasColumn('master_spjs', 'bulan_pembayaran') ? ($master->bulan_pembayaran ?? null) : null,
                        'bukti_bayar' => Schema::hasColumn('master_spjs', 'bukti_bayar') ? ($master->bukti_bayar ?? null) : null,
                        'user_id' => Schema::hasColumn('master_spjs', 'user_id') ? ($master->user_id ?? null) : null,
                        'created_at' => $master->updated_at ?? now(),
                        'updated_at' => $master->updated_at ?? now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_spj_payment_transactions');
    }
};
