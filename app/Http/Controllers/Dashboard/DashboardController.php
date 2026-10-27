<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rkbu\Rkbu;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const RKBU_DETAIL_TABLES = [
        'rkbu_detail_barjass',
        'rkbu_detail_modals',
        'rkbu_detail_modal_alkes',
        'rkbu_detail_persediaans',
        'rkbu_detail_pegawais',
    ];

    public function index(Request $request, PaguAnggaranService $paguService)
    {

        $tahunAnggarans = DB::table('tahun_anggarans')
            ->orderByDesc('nama_tahun_anggaran')
            ->get(['id', 'nama_tahun_anggaran', 'status']);

        $requestedYearId = $request->input('tahun_anggaran_id');
        $sessionYearId = session('tahun_anggaran_id');
        $tahunAnggaranId = collect([$requestedYearId, $sessionYearId])
            ->first(fn($id) => $id && $tahunAnggarans->contains('id', $id));
        $tahunAnggaranId ??= $tahunAnggarans->firstWhere('status', 'aktif')?->id
            ?? $tahunAnggarans->first()?->id;
        $tahunAnggaran = $tahunAnggarans->firstWhere('id', $tahunAnggaranId);
        if ($tahunAnggaran) {
            $request->session()->put(['tahun_anggaran_id' => $tahunAnggaran->id, 'tahun_anggaran' => $tahunAnggaran->nama_tahun_anggaran]);
        }

        $totalAnggaranRekening = $tahunAnggaranId
            ? (float) DB::table('pagu_anggarans')->where('tahun_anggaran_id', $tahunAnggaranId)->sum('jumlah_pagu')
            : 0.0;

        $rkbuQuery = fn() => DB::table('rkbus')->where('tahun_anggaran_id', $tahunAnggaranId);
        $totalRkbu = (clone $rkbuQuery())->where('status', '<>', 'rejected')->count();
        $approvedRkbu = (clone $rkbuQuery())->where('status', 'approved')->count();

        $rkbuBudget = 0.0;
        $approvedRkbuBudget = 0.0;
        $categoryTotals = [];
        $bludApprovedTotal = 0.0;
        $bludCategoryTotals = [
            'pegawai' => ['label' => 'Belanja Pegawai BLUD', 'total' => 0.0],
            'barjas' => ['label' => 'Belanja Barang dan Jasa BLUD', 'total' => 0.0],
            'modal' => ['label' => 'Belanja Modal BLUD', 'total' => 0.0],
        ];
        foreach (self::RKBU_DETAIL_TABLES as $table) {
            $rkbuBudget += (float) DB::table($table . ' as detail')
                ->join('rkbus', 'rkbus.id', '=', 'detail.rkbu_id')
                ->where('rkbus.tahun_anggaran_id', $tahunAnggaranId)
                ->where('rkbus.status', '<>', 'rejected')
                ->sum('detail.total_anggaran');

            $approvedRkbuBudget += (float) DB::table($table . ' as detail')
                ->join('rkbus', 'rkbus.id', '=', 'detail.rkbu_id')
                ->where('rkbus.tahun_anggaran_id', $tahunAnggaranId)
                ->where('rkbus.status', 'approved')
                ->sum('detail.total_anggaran');

            $grouped = DB::table($table . ' as detail')
                ->join('rkbus', 'rkbus.id', '=', 'detail.rkbu_id')
                ->leftJoin('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'rkbus.jenis_kategori_rkbu_id')
                ->where('rkbus.tahun_anggaran_id', $tahunAnggaranId)
                ->where('rkbus.status', '<>', 'rejected')
                ->groupBy('rkbus.jenis_kategori_rkbu_id', 'jenis.nama_jenis_kategori_rkbu')
                ->selectRaw("rkbus.jenis_kategori_rkbu_id as kategori_id, COALESCE(jenis.nama_jenis_kategori_rkbu, 'Tanpa kategori') as kategori, SUM(detail.total_anggaran) as total")
                ->get();

            foreach ($grouped as $row) {
                $key = $row->kategori_id ?: 'tanpa-kategori';
                $categoryTotals[$key] ??= ['kategori' => $row->kategori, 'total' => 0.0];
                $categoryTotals[$key]['total'] += (float) $row->total;
            }

            $approvedBludRows = DB::table($table . ' as detail')
                ->join('rkbus', 'rkbus.id', '=', 'detail.rkbu_id')
                ->join('sumber_danas as sumber', 'sumber.id_sumber_dana', '=', 'rkbus.sumber_dana_id')
                ->join('jenis_belanjas as jenis_belanja', 'jenis_belanja.id_jenis_belanja', '=', 'rkbus.jenis_belanja_id')
                ->where('rkbus.tahun_anggaran_id', $tahunAnggaranId)
                ->where('rkbus.status', 'approved')
                ->whereRaw('LOWER(TRIM(sumber.nama_sumber_dana)) = ?', ['blud'])
                ->groupBy('jenis_belanja.nama_jenis_belanja')
                ->selectRaw('jenis_belanja.nama_jenis_belanja as jenis, SUM(detail.total_anggaran) as total')
                ->get();

            foreach ($approvedBludRows as $row) {
                $amount = (float) $row->total;
                $bludApprovedTotal += $amount;
                $jenis = mb_strtolower((string) $row->jenis);
                $group = str_contains($jenis, 'pegawai') ? 'pegawai' : (str_contains($jenis, 'modal') ? 'modal' : 'barjas');
                $bludCategoryTotals[$group]['total'] += $amount;
            }
        }

        $bludAccountComparison = collect();
        $bludComparisonCategories = [
            'pegawai' => ['label' => 'Belanja Pegawai BLUD', 'pagu' => 0.0, 'belanja' => 0.0],
            'barjas' => ['label' => 'Belanja Barang dan Jasa BLUD', 'pagu' => 0.0, 'belanja' => 0.0],
            'modal' => ['label' => 'Belanja Modal BLUD', 'pagu' => 0.0, 'belanja' => 0.0],
        ];
        $bludPaguRows = $tahunAnggaranId ? DB::table('pagu_anggarans as pagu')
            ->join('rekening_belanjas as rekening', 'rekening.id_kode_rekening_belanja', '=', 'pagu.id_kode_rekening_belanja')
            ->where('pagu.tahun_anggaran_id', $tahunAnggaranId)
            ->where('pagu.jenis_sumber_dana', 'BLUD')
            ->orderBy('rekening.kode_rekening_belanja')
            ->get(['pagu.id_kode_rekening_belanja', 'pagu.jumlah_pagu', 'rekening.kode_rekening_belanja', 'rekening.nama_rekening_belanja'])
            : collect();
        $comparisonByAccount = [];
        $accountCodeById = DB::table('rekening_belanjas')->pluck('kode_rekening_belanja', 'id_kode_rekening_belanja');
        foreach ($bludPaguRows as $pagu) {
            $code = trim((string) $pagu->kode_rekening_belanja, '.');
            $type = str_starts_with($code, '5.1.01') ? 'pegawai' : (str_starts_with($code, '5.2') ? 'modal' : 'barjas');
            $amount = (float) $pagu->jumlah_pagu;
            $comparisonByAccount[$pagu->id_kode_rekening_belanja] = [
                'code' => $pagu->kode_rekening_belanja,
                'name' => $pagu->nama_rekening_belanja,
                'pagu' => $amount,
                'belanja' => 0.0,
                'type' => $type,
            ];
            $bludComparisonCategories[$type]['pagu'] += $amount;
        }

        if ($tahunAnggaranId && $bludPaguRows->isNotEmpty()) {
            $approvedBludRkbus = Rkbu::query()
                ->where('tahun_anggaran_id', $tahunAnggaranId)
                ->where('status', 'approved')
                ->with(['sumberDana', 'subKegiatan.sumber_danas', 'subKategoriRkbu.sub_kategori_rekening'])
                ->get();

            foreach ($approvedBludRkbus as $rkbu) {
                $fundingSource = mb_strtolower((string) (
                    $rkbu->sumberDana?->nama_sumber_dana
                    ?? $rkbu->subKegiatan?->sumber_danas?->nama_sumber_dana
                ));
                if (! str_contains($fundingSource, 'blud') || str_contains($fundingSource, 'non blud')) continue;

                $accountId = $paguService->resolveAccountId($rkbu);
                $rkbuCode = trim((string) $accountCodeById->get($accountId), '.');
                if ($rkbuCode === '') continue;

                $matchingPaguId = null;
                $longestCode = '';
                foreach ($bludPaguRows as $pagu) {
                    $paguCode = trim((string) $pagu->kode_rekening_belanja, '.');
                    if (($rkbuCode === $paguCode || str_starts_with($rkbuCode, $paguCode . '.')) && strlen($paguCode) > strlen($longestCode)) {
                        $matchingPaguId = $pagu->id_kode_rekening_belanja;
                        $longestCode = $paguCode;
                    }
                }
                if (! $matchingPaguId) continue;

                $amount = $paguService->detailTotal($rkbu);
                $comparisonByAccount[$matchingPaguId]['belanja'] += $amount;
                $bludComparisonCategories[$comparisonByAccount[$matchingPaguId]['type']]['belanja'] += $amount;
            }
        }

        $bludAccountComparison = collect($comparisonByAccount)->map(function ($row) {
            $row['selisih'] = $row['pagu'] - $row['belanja'];
            return $row;
        })->values();
        $bludComparisonTotal = [
            'pagu' => (float) $bludAccountComparison->sum('pagu'),
            'belanja' => (float) $bludAccountComparison->sum('belanja'),
        ];
        $bludComparisonTotal['selisih'] = $bludComparisonTotal['pagu'] - $bludComparisonTotal['belanja'];
        $bludComparisonCategories = collect($bludComparisonCategories)->map(function ($row) {
            $row['selisih'] = $row['pagu'] - $row['belanja'];
            return $row;
        });

        $bludCategoryChart = collect($bludCategoryTotals)->values()->map(function ($row) use ($bludApprovedTotal) {
            $row['persentase'] = $bludApprovedTotal > 0 ? round($row['total'] / $bludApprovedTotal * 100, 1) : 0;
            return $row;
        });

        $categoryChart = collect($categoryTotals)
            ->sortByDesc('total')
            ->values()
            ->map(function ($row) use ($rkbuBudget) {
                $row['persentase'] = $rkbuBudget > 0 ? round($row['total'] / $rkbuBudget * 100, 1) : 0;
                return $row;
            });

        $activeUsulanStatuses = ['proses', 'revision', 'approved'];
        $usulanCount = DB::table('usulan_belanjas')
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->whereIn('status_usulan', $activeUsulanStatuses)
            ->count();
        $usulanBudget = (float) DB::table('usulan_belanja_items as item')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item.usulan_belanja_id')
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->whereIn('usulan.status_usulan', $activeUsulanStatuses)
            ->sum(DB::raw('COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100)'));
        $approvedUsulanCount = DB::table('usulan_belanjas')
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->where('status_usulan', 'approved')
            ->count();
        $approvedUsulanBudget = (float) DB::table('usulan_belanja_items as item')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'item.usulan_belanja_id')
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->where('usulan.status_usulan', 'approved')
            ->sum(DB::raw('COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100)'));

        $unitInputan = DB::table('usulan_belanjas as usulan')
            ->join('units as unit', 'unit.id', '=', 'usulan.unit_id')
            ->leftJoin('usulan_belanja_items as item', 'item.usulan_belanja_id', '=', 'usulan.id')
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->whereIn('usulan.status_usulan', ['proses', 'revision', 'approved', 'rejected'])
            ->groupBy('unit.id', 'unit.nama_unit')
            ->selectRaw("unit.id as unit_id, unit.nama_unit,
                SUM(COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100)) as total_usulan,
                SUM(CASE WHEN usulan.status_usulan IN ('proses', 'revision') THEN COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100) ELSE 0 END) as perlu_validasi,
                SUM(CASE WHEN usulan.status_usulan = 'approved' THEN COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100) ELSE 0 END) as tervalidasi_akhir,
                SUM(CASE WHEN usulan.status_usulan = 'rejected' THEN COALESCE(item.harga_satuan, 0) * COALESCE(item.volume, 0) * (1 + COALESCE(item.ppn, 0) / 100) ELSE 0 END) as ditolak,
                COUNT(DISTINCT usulan.id) as jumlah_usulan")
            ->orderBy('unit.nama_unit')
            ->get();

        $realisasi = (float) DB::table('master_spjs as master')
            ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->whereNotNull('master.tanggal_pembayaran')
            ->whereNotNull('master.pembayaran')
            ->sum('master.pembayaran');

        return view('dashboard.dashboard_index', compact(
            'tahunAnggarans',
            'tahunAnggaran',
            'tahunAnggaranId',
            'totalAnggaranRekening',
            'totalRkbu',
            'approvedRkbu',
            'rkbuBudget',
            'approvedRkbuBudget',
            'categoryChart',
            'bludApprovedTotal',
            'bludCategoryChart',
            'bludComparisonCategories',
            'bludAccountComparison',
            'bludComparisonTotal',
            'unitInputan',
            'usulanCount',
            'usulanBudget',
            'approvedUsulanCount',
            'approvedUsulanBudget',
            'realisasi',
        ));
    }
}
