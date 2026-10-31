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
        if (! Schema::hasTable('rekening_pendapatans')) {
            Schema::create('rekening_pendapatans', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('kode_rekening', 100)->unique();
                $table->string('nama_rekening', 255);
                $table->boolean('is_silpa')->default(false);
                $table->boolean('aktif')->default(true);
                $table->foreignUuid('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('target_pendapatans')) return;

        if (! Schema::hasColumn('target_pendapatans', 'rekening_pendapatan_id')) {
            Schema::table('target_pendapatans', function (Blueprint $table) {
                $table->uuid('rekening_pendapatan_id')->nullable();
            });
        }

        DB::transaction(function () {
            $targets = DB::table('target_pendapatans')->orderBy('created_at')->get();
            $accountIds = [];

            foreach ($targets as $target) {
                $code = trim((string) $target->kode_rekening);
                $key = mb_strtolower($code);
                if (! isset($accountIds[$key])) {
                    $account = DB::table('rekening_pendapatans')->whereRaw('LOWER(kode_rekening) = ?', [$key])->first();
                    if (! $account) {
                        $accountId = (string) Str::uuid();
                        DB::table('rekening_pendapatans')->insert([
                            'id' => $accountId,
                            'kode_rekening' => $code,
                            'nama_rekening' => trim((string) $target->nama_rekening) ?: $code,
                            'is_silpa' => mb_strtoupper($code) === 'SILPA',
                            'aktif' => true,
                            'created_by' => $target->created_by,
                            'created_at' => $target->created_at ?? now(),
                            'updated_at' => $target->updated_at ?? now(),
                        ]);
                    } else {
                        $accountId = $account->id;
                    }
                    $accountIds[$key] = $accountId;
                }
                DB::table('target_pendapatans')->where('id', $target->id)->update(['rekening_pendapatan_id' => $accountIds[$key]]);
            }

            $targets = DB::table('target_pendapatans')->orderBy('created_at')->get();
            foreach ($targets->groupBy(fn ($target) => $target->tahun_anggaran_id . '|' . $target->rekening_pendapatan_id) as $group) {
                $keep = $group->first();
                $ids = $group->pluck('id');
                $targetTotal = DB::table('target_pendapatans')->whereIn('id', $ids)->sum('target_tahunan');
                DB::table('target_pendapatans')->where('id', $keep->id)->update([
                    'target_tahunan' => $targetTotal,
                ]);

                $monthly = DB::table('realisasi_pendapatans')->whereIn('target_pendapatan_id', $ids)
                    ->select('bulan', DB::raw('SUM(jumlah_realisasi) as total'))->groupBy('bulan')->get();
                foreach ($monthly as $row) {
                    $existing = DB::table('realisasi_pendapatans')->where('target_pendapatan_id', $keep->id)->where('bulan', $row->bulan)->first();
                    if ($existing) {
                        DB::table('realisasi_pendapatans')->where('id', $existing->id)->update(['jumlah_realisasi' => $row->total]);
                    } else {
                        DB::table('realisasi_pendapatans')->insert([
                            'id' => (string) Str::uuid(), 'target_pendapatan_id' => $keep->id, 'bulan' => $row->bulan,
                            'jumlah_realisasi' => $row->total, 'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
                if ($ids->count() > 1) DB::table('target_pendapatans')->whereIn('id', $ids->reject(fn ($id) => $id === $keep->id))->delete();
            }
        });

        $hasYearIndex = collect(DB::select('SHOW INDEX FROM target_pendapatans'))
            ->contains(fn ($index) => $index->Key_name === 'target_pendapatan_year_index');
        if (! $hasYearIndex) {
            Schema::table('target_pendapatans', function (Blueprint $table) {
                $table->index('tahun_anggaran_id', 'target_pendapatan_year_index');
            });
        }

        Schema::table('target_pendapatans', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropUnique('target_pendapatan_per_unit_year_code_unique');
            $table->dropColumn(['unit_id', 'kode_rekening', 'nama_rekening']);
            $table->foreign('rekening_pendapatan_id', 'target_pendapatan_account_foreign')
                ->references('id')->on('rekening_pendapatans')->restrictOnDelete();
            $table->unique(['tahun_anggaran_id', 'rekening_pendapatan_id'], 'target_pendapatan_year_account_unique');
        });
    }

    public function down(): void
    {
        // A rollback cannot safely reconstruct the former per-unit targets after global consolidation.
    }
};
