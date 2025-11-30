<?php

namespace App\Http\Controllers\Rkbu\KertasKerja;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class GrafikController extends Controller
{
    public function index(Request $request)
    {
        $bulanList = collect([
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ]);

        $filterBulan = (int) ($request->input('bulan') ?? now()->month);
        // $namaTahunAnggaran = session('nama_tahun_anggaran');
        $namaTahunAnggaran = Session::get('tahun_anggaran');

        // === Ambil data REALISASI ===
        $realisasi = DB::table('master_spjs')
            ->selectRaw('MONTH(tanggal_pembayaran) as bulan_ke, SUM(pembayaran) as total')
            ->where('tahun_anggaran', $namaTahunAnggaran)
            ->whereNotNull('tanggal_pembayaran')
            ->whereNotNull('pembayaran')
            ->groupBy('bulan_ke')
            ->pluck('total', 'bulan_ke');

        // === Ambil data TARGET ===
        $target = DB::table('target_sps')
            ->selectRaw('bulan, target')
            ->when($namaTahunAnggaran, function ($query, $tahun) {
                return $query->where('nama_tahun_anggaran', $tahun);
            })
            ->pluck('target', 'bulan');

        // Pastikan semua bulan ada, isi 0 jika kosong
        foreach (range(1, 12) as $i) {
            if (!isset($realisasi[$i])) $realisasi[$i] = 0;
            if (!isset($target[$i])) $target[$i] = 0;
        }

        // === Hitung kumulatif realisasi ===
        $realisasiKumulatif = collect();
        $totalRealisasiTemp = 0;
        foreach (range(1, 12) as $i) {
            $totalRealisasiTemp += $realisasi[$i];
            $realisasiKumulatif[$i] = $totalRealisasiTemp;
        }

        // === Hitung kumulatif target ===
        $targetKumulatif = collect();
        $totalTargetTemp = 0;
        foreach (range(1, 12) as $i) {
            $totalTargetTemp += $target[$i];
            $targetKumulatif[$i] = $totalTargetTemp;
        }

        // === Total s.d bulan tertentu ===
        $totalRealisasi = collect($realisasi)
            ->filter(fn($v, $k) => $k <= $filterBulan)
            ->sum();

        $totalTarget = collect($target)
            ->filter(fn($v, $k) => $k <= $filterBulan)
            ->sum();

        $persentase = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0;

        // === Siapkan data untuk grafik ===
        $dataGabungan = $bulanList->map(function ($nama, $index) use ($realisasi, $target, $realisasiKumulatif, $targetKumulatif) {
            return [
                'bulan' => $index,
                'realisasi' => (int) $realisasi[$index],
                'target' => (int) $target[$index],
                'realisasi_kumulatif' => (int) $realisasiKumulatif[$index],
                'target_kumulatif' => (int) $targetKumulatif[$index],
            ];
        })->values();

        return view('rkbu.kertas_kerja.grafik', [
            'bulanList' => $bulanList,
            'filterBulan' => $filterBulan,
            'dataGabungan' => $dataGabungan,
            'persentase' => $persentase,
            'totalRealisasi' => $totalRealisasi,
            'totalTarget' => $totalTarget,
        ]);
    }
}
