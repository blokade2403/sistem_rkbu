<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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

    public function index(Request $request)
    {
        $tahunAnggarans = DB::table('tahun_anggarans')
            ->orderByDesc('nama_tahun_anggaran')
            ->get(['id', 'nama_tahun_anggaran', 'status']);

        $tahunAnggaranId = $request->input('tahun_anggaran_id');
        if (! $tahunAnggaranId || ! $tahunAnggarans->contains('id', $tahunAnggaranId)) {
            $tahunAnggaranId = $tahunAnggarans->firstWhere('status', 'aktif')?->id
                ?? $tahunAnggarans->first()?->id;
        }
        $tahunAnggaran = $tahunAnggarans->firstWhere('id', $tahunAnggaranId);

        $rkbuQuery = fn () => DB::table('rkbus')->where('tahun_anggaran_id', $tahunAnggaranId);
        $totalRkbu = (clone $rkbuQuery())->where('status', '<>', 'rejected')->count();
        $approvedRkbu = (clone $rkbuQuery())->where('status', 'approved')->count();

        $rkbuBudget = 0.0;
        $approvedRkbuBudget = 0.0;
        $categoryTotals = [];
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
        }

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
            'totalRkbu',
            'approvedRkbu',
            'rkbuBudget',
            'approvedRkbuBudget',
            'categoryChart',
            'usulanCount',
            'usulanBudget',
            'approvedUsulanCount',
            'approvedUsulanBudget',
            'realisasi',
        ));
    }
}
