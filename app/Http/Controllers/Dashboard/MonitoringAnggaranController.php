<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringAnggaranController extends Controller
{
    public function blud(Request $request, PaguAnggaranService $paguService)
    {
        return $this->show($request, $paguService, 'BLUD');
    }

    public function keseluruhan(Request $request, PaguAnggaranService $paguService)
    {
        return $this->show($request, $paguService, null);
    }

    private function show(Request $request, PaguAnggaranService $paguService, ?string $source)
    {
        $years = DB::table('tahun_anggarans')->orderByDesc('nama_tahun_anggaran')->get(['id', 'nama_tahun_anggaran', 'status']);
        $requestedYearId = $request->input('tahun_anggaran_id');
        $sessionYearId = $request->session()->get('tahun_anggaran_id');
        $yearId = collect([$requestedYearId, $sessionYearId])->first(fn ($id) => $id && $years->contains('id', $id));
        $yearId ??= $years->firstWhere('status', 'aktif')?->id ?? $years->first()?->id;
        $year = $years->firstWhere('id', $yearId);
        if ($year) {
            $request->session()->put(['tahun_anggaran_id' => $year->id, 'tahun_anggaran' => $year->nama_tahun_anggaran]);
        }

        $accounts = DB::table('rekening_belanjas')
            ->orderBy('kode_rekening_belanja')
            ->get(['id_kode_rekening_belanja', 'kode_rekening_belanja', 'nama_rekening_belanja']);
        $codeById = $accounts->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');
        $paguRows = $yearId ? DB::table('pagu_anggarans as pagu')
            ->join('rekening_belanjas as rekening', 'rekening.id_kode_rekening_belanja', '=', 'pagu.id_kode_rekening_belanja')
            ->where('pagu.tahun_anggaran_id', $yearId)
            ->when($source, fn ($query) => $query->where('pagu.jenis_sumber_dana', $source))
            ->get([
                'pagu.id_kode_rekening_belanja',
                'pagu.jenis_sumber_dana',
                'pagu.jumlah_pagu',
                'rekening.kode_rekening_belanja',
                'rekening.nama_rekening_belanja',
            ])
            : collect();

        $dataByCode = [];
        foreach ($paguRows as $pagu) {
            $code = trim((string) $pagu->kode_rekening_belanja, '.');
            if ($code === '') continue;
            $dataByCode[$code] ??= ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
            $dataByCode[$code]['anggaran'] += (float) $pagu->jumlah_pagu;
        }

        $itemRows = $yearId ? DB::table('usulan_belanja_items as item')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item.usulan_belanja_id')
            ->join('rkbus', 'rkbus.id', '=', 'item.rkbu_id')
            ->leftJoin('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'rkbus.sumber_dana_id')
            ->where('usulan.tahun_anggaran_id', $yearId)
            ->where('usulan.status_usulan', 'approved')
            ->when($source === 'BLUD', fn ($query) => $query->whereRaw("LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) LIKE '%blud%' AND LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) NOT LIKE '%non blud%'"))
            ->when($source === 'Non BLUD', fn ($query) => $query->whereRaw("LOWER(TRIM(COALESCE(sumber.nama_sumber_dana, ''))) NOT LIKE '%blud%'"))
            ->get([
                'item.id as item_id', 'item.usulan_belanja_id', 'item.rkbu_id', 'rkbus.sub_kategori_rkbu_id', 'item.harga_satuan', 'item.volume', 'item.ppn',
            ])
            : collect();

        $rkbuAccountIds = Rkbu::query()->whereIn('id', $itemRows->pluck('rkbu_id')->unique())
            ->with(['subKategoriRkbu.sub_kategori_rekening', 'subKegiatan'])
            ->get()->mapWithKeys(fn (Rkbu $rkbu) => [$rkbu->id => $paguService->resolveAccountId($rkbu)]);

        $spjByUsulan = $yearId ? DB::table('spjs as spj')
            ->leftJoin('master_spjs as master', 'master.spj_id', '=', 'spj.id')
            ->whereIn('spj.usulan_belanja_id', DB::table('usulan_belanjas')->where('tahun_anggaran_id', $yearId)->where('status_usulan', 'approved')->select('id'))
            ->orderBy('spj.created_at')
            ->get([
                'spj.usulan_belanja_id', 'spj.pengadaan_step', 'master.bruto', 'master.pembayaran',
                'master.status_serah_terima_bendahara',
            ])->keyBy('usulan_belanja_id')
            : collect();

        $subCategoryTransactions = [];
        foreach ($itemRows->groupBy('usulan_belanja_id') as $usulanId => $items) {
            $usulanTotal = $items->sum(fn ($item) => (float) $item->harga_satuan * (float) $item->volume * (1 + (float) $item->ppn / 100));
            if ($usulanTotal <= 0) continue;
            $spj = $spjByUsulan->get($usulanId);
            $paid = (float) ($spj->pembayaran ?? 0);
            $acceptedByFinance = ($spj->status_serah_terima_bendahara ?? null) === 'Selesai';
            $isCompleted = ($spj->pengadaan_step ?? null) === 'selesai';
            $financeTotal = $acceptedByFinance ? max(0, (float) ($spj->bruto ?? $usulanTotal) - $paid) : 0.0;

            foreach ($items as $item) {
                $accountId = $rkbuAccountIds->get($item->rkbu_id);
                $rkbuCode = trim((string) $codeById->get($accountId), '.');
                if ($rkbuCode === '') continue;
                $itemAmount = (float) $item->harga_satuan * (float) $item->volume * (1 + (float) $item->ppn / 100);
                $share = $itemAmount / $usulanTotal;
                $dataByCode[$rkbuCode] ??= ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                $dataByCode[$rkbuCode]['realisasi'] += $paid * $share;
                if ($acceptedByFinance) {
                    $dataByCode[$rkbuCode]['keuangan'] += $financeTotal * $share;
                } elseif (! $isCompleted) {
                    $dataByCode[$rkbuCode]['pengadaan'] += $itemAmount;
                }

                $subCategoryId = $item->sub_kategori_rkbu_id;
                if ($subCategoryId) {
                    $subCategoryTransactions[$subCategoryId] ??= ['pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                    $subCategoryTransactions[$subCategoryId]['realisasi'] += $paid * $share;
                    if ($acceptedByFinance) {
                        $subCategoryTransactions[$subCategoryId]['keuangan'] += $financeTotal * $share;
                    } elseif (! $isCompleted) {
                        $subCategoryTransactions[$subCategoryId]['pengadaan'] += $itemAmount;
                    }
                }
            }
        }

        $ledgerRows = $accounts->map(function ($account) use ($dataByCode) {
            $code = trim((string) $account->kode_rekening_belanja, '.');
            $totals = ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
            foreach ($dataByCode as $detailCode => $values) {
                if ($detailCode !== $code && ! str_starts_with($detailCode, $code . '.')) continue;
                foreach ($totals as $column => $_) $totals[$column] += $values[$column];
            }
            if (array_sum($totals) <= 0) return null;
            $totals['sisa'] = $totals['anggaran'] - $totals['pengadaan'] - $totals['keuangan'] - $totals['realisasi'];
            $totals['persentase'] = $totals['anggaran'] > 0 ? round($totals['realisasi'] / $totals['anggaran'] * 100, 2) : 0;
            $totals['kode'] = $account->kode_rekening_belanja;
            $totals['nama'] = $account->nama_rekening_belanja;
            $totals['level'] = max(0, substr_count($code, '.') - 1);
            return $totals;
        })->filter()->values();

        if ($source === 'BLUD') {
            $approvedRkbus = Rkbu::query()
                ->where('tahun_anggaran_id', $yearId)
                ->where('status', 'approved')
                ->whereNotNull('sub_kategori_rkbu_id')
                ->where(function ($query) {
                    $query->whereHas('sumberDana', fn ($sourceQuery) => $sourceQuery
                        ->whereRaw("LOWER(TRIM(nama_sumber_dana)) LIKE '%blud%'")
                        ->whereRaw("LOWER(TRIM(nama_sumber_dana)) NOT LIKE '%non blud%'"))
                        ->orWhereHas('subKegiatan.sumber_danas', fn ($sourceQuery) => $sourceQuery
                            ->whereRaw("LOWER(TRIM(nama_sumber_dana)) LIKE '%blud%'")
                            ->whereRaw("LOWER(TRIM(nama_sumber_dana)) NOT LIKE '%non blud%'"));
                })
                ->with('subKategoriRkbu.kategori.jenis_kategori_rkbu.jenis_belanja')
                ->get();

            $approvedRkbuIds = $approvedRkbus->pluck('id');
            $budgetBySubCategory = [];
            foreach ([
                'rkbu_detail_barjass',
                'rkbu_detail_modals',
                'rkbu_detail_modal_alkes',
                'rkbu_detail_persediaans',
                'rkbu_detail_pegawais',
            ] as $detailTable) {
                DB::table($detailTable)
                    ->whereIn('rkbu_id', $approvedRkbuIds)
                    ->select('rkbu_id')
                    ->selectRaw('SUM(total_anggaran) as total')
                    ->groupBy('rkbu_id')
                    ->get()
                    ->each(function ($detailTotal) use (&$budgetBySubCategory, $approvedRkbus) {
                        $rkbu = $approvedRkbus->firstWhere('id', $detailTotal->rkbu_id);
                        $subcategoryId = $rkbu?->sub_kategori_rkbu_id;
                        if ($subcategoryId) {
                            $budgetBySubCategory[$subcategoryId] = ($budgetBySubCategory[$subcategoryId] ?? 0)
                                + (float) $detailTotal->total;
                        }
                    });
            }

            $subcategoryMetadata = DB::table('sub_kategori_rkbus as sub')
                ->join('kategori_rkbus as kategori', 'kategori.id_kategori_rkbu', '=', 'sub.id_kategori_rkbu')
                ->join('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'kategori.id_jenis_kategori_rkbu')
                ->join('jenis_belanjas as belanja', 'belanja.id_jenis_belanja', '=', 'jenis.id_jenis_belanja')
                ->whereIn('sub.id_sub_kategori_rkbu', $approvedRkbus->pluck('sub_kategori_rkbu_id')->unique())
                ->get([
                    'sub.id_sub_kategori_rkbu', 'sub.kode_sub_kategori_rkbu', 'sub.nama_sub_kategori_rkbu',
                    'kategori.id_kategori_rkbu', 'kategori.kode_kategori_rkbu', 'kategori.nama_kategori_rkbu',
                    'jenis.id_jenis_kategori_rkbu', 'jenis.kode_jenis_kategori_rkbu', 'jenis.nama_jenis_kategori_rkbu',
                    'belanja.id_jenis_belanja', 'belanja.kode_jenis_belanja', 'belanja.nama_jenis_belanja',
                ])->keyBy('id_sub_kategori_rkbu');

            $addMonitoringRow = function (string $code, string $name, int $level, float $budget, array $activity) {
                $activity['anggaran'] = $budget;
                $activity['sisa'] = $budget - $activity['pengadaan'] - $activity['keuangan'] - $activity['realisasi'];
                $activity['persentase'] = $budget > 0 ? round($activity['realisasi'] / $budget * 100, 2) : 0;
                $activity['kode'] = $code;
                $activity['nama'] = $name;
                $activity['level'] = $level;

                return $activity;
            };
            $sumActivity = function ($subcategories) use ($subCategoryTransactions) {
                $sum = ['pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                foreach ($subcategories as $subcategory) {
                    $activity = $subCategoryTransactions[$subcategory->id_sub_kategori_rkbu] ?? [];
                    foreach ($sum as $field => $_) {
                        $sum[$field] += (float) ($activity[$field] ?? 0);
                    }
                }

                return $sum;
            };

            $subcategoryRows = collect();
            $subcategoriesByType = $subcategoryMetadata->groupBy('id_jenis_belanja');
            foreach ($paguRows as $pagu) {
                $typeCode = trim((string) $pagu->kode_rekening_belanja, '.');
                $type = DB::table('jenis_belanjas')->where('kode_jenis_belanja', $typeCode)->first();
                if (! $type) {
                    continue;
                }
                $subcategories = $subcategoriesByType->get($type->id_jenis_belanja, collect())->values();
                $rootActivity = $sumActivity($subcategories);
                $subcategoryRows->push($addMonitoringRow($typeCode, $pagu->nama_rekening_belanja, 0, (float) $pagu->jumlah_pagu, $rootActivity));

                foreach ($subcategories->groupBy('id_jenis_kategori_rkbu') as $typeGroup) {
                    $typeInfo = $typeGroup->first();
                    $typeBudget = (float) $typeGroup->sum(fn ($subcategory) => (float) ($budgetBySubCategory[$subcategory->id_sub_kategori_rkbu] ?? 0));
                    $subcategoryRows->push($addMonitoringRow(
                        (string) $typeInfo->kode_jenis_kategori_rkbu,
                        (string) $typeInfo->nama_jenis_kategori_rkbu,
                        1,
                        $typeBudget,
                        $sumActivity($typeGroup)
                    ));

                    foreach ($typeGroup->groupBy('id_kategori_rkbu') as $categoryGroup) {
                        $category = $categoryGroup->first();
                        $categoryBudget = (float) $categoryGroup->sum(fn ($subcategory) => (float) ($budgetBySubCategory[$subcategory->id_sub_kategori_rkbu] ?? 0));
                        $subcategoryRows->push($addMonitoringRow(
                            (string) $category->kode_kategori_rkbu,
                            (string) $category->nama_kategori_rkbu,
                            2,
                            $categoryBudget,
                            $sumActivity($categoryGroup)
                        ));

                        foreach ($categoryGroup as $subcategory) {
                            $subcategoryBudget = (float) ($budgetBySubCategory[$subcategory->id_sub_kategori_rkbu] ?? 0);
                            $subcategoryActivity = $subCategoryTransactions[$subcategory->id_sub_kategori_rkbu]
                                ?? ['pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                            $subcategoryRows->push($addMonitoringRow(
                                (string) $subcategory->kode_sub_kategori_rkbu,
                                (string) $subcategory->nama_sub_kategori_rkbu,
                                3,
                                $subcategoryBudget,
                                $subcategoryActivity
                            ));
                        }
                    }
                }
            }

            if ($subcategoryRows->isNotEmpty()) {
                $ledgerRows = $subcategoryRows;
            }
        }

        $summary = [
            'anggaran' => (float) $paguRows->sum('jumlah_pagu'),
            'pengadaan' => (float) collect($dataByCode)->sum('pengadaan'),
            'keuangan' => (float) collect($dataByCode)->sum('keuangan'),
            'realisasi' => (float) collect($dataByCode)->sum('realisasi'),
        ];
        $summary['sisa'] = $summary['anggaran'] - $summary['pengadaan'] - $summary['keuangan'] - $summary['realisasi'];
        $summary['persentase'] = $summary['anggaran'] > 0 ? round($summary['realisasi'] / $summary['anggaran'] * 100, 2) : 0;

        $programAccountMap = DB::table('rekening_belanjas as rekening')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening.id_aktivitas')
            ->join('programs as program', 'program.id_program', '=', 'aktivitas.id_program')
            ->join('sub_kegiatans as sub_kegiatan', 'sub_kegiatan.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans as kegiatan', 'kegiatan.id_kegiatan', '=', 'sub_kegiatan.id_kegiatan')
            ->get([
                'rekening.kode_rekening_belanja', 'rekening.nama_rekening_belanja',
                'program.id_program', 'program.kode_program', 'program.nama_program',
                'aktivitas.id_aktivitas', 'aktivitas.kode_aktivitas', 'aktivitas.nama_aktivitas',
                'sub_kegiatan.id_sub_kegiatan', 'sub_kegiatan.kode_sub_kegiatan', 'sub_kegiatan.nama_sub_kegiatan',
                'kegiatan.id_kegiatan', 'kegiatan.kode_kegiatan', 'kegiatan.nama_kegiatan',
            ])->keyBy('kode_rekening_belanja');

        $programRows = [];
        foreach ($dataByCode as $code => $values) {
            $account = $programAccountMap->get($code);
            if (! $account) continue;
            $path = [
                ['key' => 'program:' . $account->id_program, 'level' => 0, 'label' => $account->kode_program . ' ' . $account->nama_program],
                ['key' => 'kegiatan:' . $account->id_kegiatan, 'level' => 1, 'label' => $account->kode_kegiatan . ' ' . $account->nama_kegiatan],
                ['key' => 'sub:' . $account->id_sub_kegiatan, 'level' => 2, 'label' => $account->kode_sub_kegiatan . ' ' . $account->nama_sub_kegiatan],
                ['key' => 'rekening:' . $code, 'level' => 3, 'label' => $code . ' ' . $account->nama_rekening_belanja],
            ];
            foreach ($path as $node) {
                $programRows[$node['key']] ??= $node + ['anggaran' => 0.0, 'pengadaan' => 0.0, 'keuangan' => 0.0, 'realisasi' => 0.0];
                foreach (['anggaran', 'pengadaan', 'keuangan', 'realisasi'] as $column) {
                    $programRows[$node['key']][$column] += (float) $values[$column];
                }
            }
        }
        $programRows = collect($programRows)->map(function ($row) {
            $row['persentase'] = $row['anggaran'] > 0 ? round($row['realisasi'] / $row['anggaran'] * 100, 2) : 0;
            return $row;
        })->values();

        $typeBudgets = ['pegawai' => 0.0, 'barjas' => 0.0, 'modal' => 0.0];
        foreach ($paguRows as $pagu) {
            $code = trim((string) $pagu->kode_rekening_belanja, '.');
            $type = str_starts_with($code, '5.1.01') ? 'pegawai' : (str_starts_with($code, '5.2') ? 'modal' : 'barjas');
            $typeBudgets[$type] += (float) $pagu->jumlah_pagu;
        }

        return view('dashboard.monitoring_anggaran', [
            'years' => $years,
            'year' => $year,
            'yearId' => $yearId,
            'source' => $source,
            'ledgerRows' => $ledgerRows,
            'programRows' => $programRows,
            'summary' => $summary,
            'typeBudgets' => $typeBudgets,
        ]);
    }
}
