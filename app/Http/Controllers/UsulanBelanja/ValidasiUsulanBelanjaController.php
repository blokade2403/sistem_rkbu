<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsulanBelanja\ValidasiUsulanBelanjaRequest;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiUsulanBelanjaController extends Controller
{
    public function __construct(
        private UsulanBelanjaWorkflowService $workflow
    ) {}

    public function index(Request $request)
    {
        $userId = auth()->id();
        $tahunAnggaranId = session('tahun_anggaran_id');

        $ids = DB::table('approvals')
            ->where('validator_user_id', $userId)
            ->where('status', 'pending')
            ->where('is_current', true)
            ->pluck('workflow_instance_id')
            ->unique();

        $baseQuery = UsulanBelanja::query()
            ->whereIn('workflow_instance_id', $ids)
            ->where('tahun_anggaran_id', $tahunAnggaranId)
            ->where('status_usulan', 'proses');

        $jenisKategoriRkbus = DB::table('usulan_belanjas as usulan')
            ->join('jenis_kategori_rkbus as jenis', 'jenis.id_jenis_kategori_rkbu', '=', 'usulan.jenis_kategori_rkbu_id')
            ->whereIn('usulan.workflow_instance_id', $ids)
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->where('usulan.status_usulan', 'proses')
            ->select('jenis.id_jenis_kategori_rkbu', 'jenis.nama_jenis_kategori_rkbu')
            ->distinct()->orderBy('jenis.nama_jenis_kategori_rkbu')->get();
        $subKategoriRkbus = DB::table('usulan_belanjas as usulan')
            ->join('sub_kategori_rkbus as subkategori', 'subkategori.id_sub_kategori_rkbu', '=', 'usulan.sub_kategori_rkbu_id')
            ->whereIn('usulan.workflow_instance_id', $ids)
            ->where('usulan.tahun_anggaran_id', $tahunAnggaranId)
            ->where('usulan.status_usulan', 'proses')
            ->select('subkategori.id_sub_kategori_rkbu', 'subkategori.nama_sub_kategori_rkbu', 'usulan.jenis_kategori_rkbu_id')
            ->distinct()->orderBy('subkategori.nama_sub_kategori_rkbu')->get();
        $pengusuls = (clone $baseQuery)->select('user_id', 'nama_pengusul')->distinct()->orderBy('nama_pengusul')->get();

        if ($request->filled('jenis_kategori_rkbu_id')) {
            $baseQuery->where('jenis_kategori_rkbu_id', $request->input('jenis_kategori_rkbu_id'));
        }
        if ($request->filled('sub_kategori_rkbu_id')) {
            $baseQuery->where('sub_kategori_rkbu_id', $request->input('sub_kategori_rkbu_id'));
        }
        if ($request->filled('pengusul_id')) {
            $baseQuery->where('user_id', $request->input('pengusul_id'));
        }

        $usulans = $baseQuery->with([
            'pengusul',
            'rkbu.subKategoriRkbu',
            'tahunAnggaran',
        ])
            ->addSelect([
                'total_nominal' => DB::table('usulan_belanja_items')
                    ->selectRaw('COALESCE(SUM(harga_satuan * volume * (1 + COALESCE(ppn, 0) / 100)), 0)')
                    ->whereColumn('usulan_belanja_items.usulan_belanja_id', 'usulan_belanjas.id')
                    ->whereNotNull('detail_type')
                    ->whereNotNull('detail_id'),
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('usulan_belanja.validasi', compact('usulans', 'jenisKategoriRkbus', 'subKategoriRkbus', 'pengusuls'));
    }

    public function proses(
        ValidasiUsulanBelanjaRequest $request,
        UsulanBelanja $usulanBelanja
    ) {
        $this->workflow->prosesKeputusan(
            $usulanBelanja->id,
            $request->validated('aksi'),
            $request->validated('catatan'),
            $request->user()
        );

        return redirect()
            ->route('usulan-belanja.show', $usulanBelanja->id)
            ->with('success', 'Keputusan validasi berhasil diproses.');
    }
}
