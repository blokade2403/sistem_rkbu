<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('master_spj_payment_transactions')) return;

        if (! Schema::hasColumn('master_spj_payment_transactions', 'bulan_pembayaran')) {
            Schema::table('master_spj_payment_transactions', function (Blueprint $table) {
                $table->string('bulan_pembayaran', 30)->nullable()->after('tanggal_pembayaran');
            });
        }

        if (! Schema::hasTable('master_spjs') || ! Schema::hasColumn('master_spjs', 'bulan_pembayaran')) return;
        DB::table('master_spj_payment_transactions as payment')
            ->join('master_spjs as master', 'master.id', '=', 'payment.master_spj_id')
            ->whereNull('payment.bulan_pembayaran')
            ->update(['payment.bulan_pembayaran' => DB::raw('master.bulan_pembayaran')]);
    }

    public function down(): void
    {
        if (Schema::hasTable('master_spj_payment_transactions') && Schema::hasColumn('master_spj_payment_transactions', 'bulan_pembayaran')) {
            Schema::table('master_spj_payment_transactions', fn (Blueprint $table) => $table->dropColumn('bulan_pembayaran'));
        }
    }
};
