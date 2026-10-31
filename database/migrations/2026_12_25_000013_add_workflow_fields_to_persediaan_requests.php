<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persediaan_requests', function (Blueprint $table) {
            $table->string('nomor_permintaan', 40)->nullable()->after('id');
            $table->timestamp('picked_up_at')->nullable()->after('reviewed_at');
            $table->foreignUuid('picked_up_by')->nullable()->after('picked_up_at')->constrained('users')->nullOnDelete();
            $table->index('nomor_permintaan');
        });

        DB::table('persediaan_requests')->orderBy('created_at')->orderBy('id')->get(['id', 'created_at'])->each(function ($request): void {
            $date = $request->created_at ? date('Ymd', strtotime($request->created_at)) : now()->format('Ymd');
            DB::table('persediaan_requests')->where('id', $request->id)->update([
                'nomor_permintaan' => 'PB-' . $date . '-' . strtoupper(substr(str_replace('-', '', $request->id), 0, 8)),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('persediaan_requests', function (Blueprint $table) {
            $table->dropIndex(['nomor_permintaan']);
            $table->dropConstrainedForeignId('picked_up_by');
            $table->dropColumn(['nomor_permintaan', 'picked_up_at']);
        });
    }
};
