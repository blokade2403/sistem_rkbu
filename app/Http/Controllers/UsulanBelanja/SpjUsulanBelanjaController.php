<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Models\UsulanBelanja\Spj;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use App\Services\Workflow\UsulanBelanjaBalanceService;
use App\Services\Workflow\PaguAnggaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SpjUsulanBelanjaController extends Controller
{
    public function __construct(private UsulanBelanjaWorkflowService $workflow) {}

    public function index(Request $request)
    {
        $isAdmin = auth()->user()->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists();
        $firstPengadaanStep = $this->firstPengadaanWorkflowStep();
        $query = UsulanBelanja::query()
            ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
            ->where('status_usulan', 'approved')
            ->whereNotNull('workflow_instance_id')
            ->whereHas('workflowInstance', fn($query) => $query->where('status', 'approved'))
            ->with([
                'pengusul',
                'unit',
                'tahunAnggaran',
                'rkbu.subKategoriRkbu',
                'spjs' => fn($query) => $query->withCount('details')->orderBy('nomor_urut'),
            ])
            ->withCount([
                'items as item_count' => fn($query) => $query->whereNotNull('detail_type')->whereNotNull('detail_id'),
            ])
            ->addSelect([
                'unassigned_item_count' => DB::table('usulan_belanja_items as item')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('item.usulan_belanja_id', 'usulan_belanjas.id')
                    ->whereNotNull('item.detail_type')
                    ->whereNotNull('item.detail_id')
                    ->whereRaw('COALESCE(item.volume, 0) > COALESCE((SELECT SUM(spj_details.volume) FROM spj_details WHERE spj_details.usulan_belanja_item_id = item.id), 0)'),
                'unassigned_volume' => DB::table('usulan_belanja_items as item')
                    ->selectRaw('COALESCE(SUM(GREATEST(COALESCE(item.volume, 0) - COALESCE((SELECT SUM(spj_details.volume) FROM spj_details WHERE spj_details.usulan_belanja_item_id = item.id), 0), 0)), 0)')
                    ->whereColumn('item.usulan_belanja_id', 'usulan_belanjas.id')
                    ->whereNotNull('item.detail_type')
                    ->whereNotNull('item.detail_id'),
            ])
            ->when($request->filled('no_usulan'), fn($query) => $query->where('no_usulan_belanja', 'like', '%' . trim($request->input('no_usulan')) . '%'))
            ->when($request->filled('sub_kategori_id'), fn($query) => $query->where('sub_kategori_rkbu_id', $request->input('sub_kategori_id')))
            ->when($request->filled('pengusul_id'), fn($query) => $query->where('user_id', $request->input('pengusul_id')))
            ->latest();

        $subKategoris = SubKategoriRkbu::query()
            ->whereIn('id_sub_kategori_rkbu', UsulanBelanja::query()
                ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
                ->where('status_usulan', 'approved')
                ->whereNotNull('workflow_instance_id')
                ->select('sub_kategori_rkbu_id'))
            ->orderBy('nama_sub_kategori_rkbu')
            ->get(['id_sub_kategori_rkbu', 'nama_sub_kategori_rkbu']);
        $pengusuls = UsulanBelanja::query()
            ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
            ->where('status_usulan', 'approved')
            ->whereNotNull('workflow_instance_id')
            ->whereNotNull('user_id')
            ->select('user_id', 'nama_pengusul')
            ->distinct()
            ->orderBy('nama_pengusul')
            ->get();
        $usulans = $query->paginate(15)->withQueryString();
        $usulans->getCollection()->each(function (UsulanBelanja $usulan) use ($firstPengadaanStep, $isAdmin) {
            $usulan->can_create_spj = $isAdmin
                || ($firstPengadaanStep && $this->userMatchesFirstPengadaanStep(auth()->user(), $firstPengadaanStep, $usulan));
        });

        return view('usulan_belanja.spj.index', compact('usulans', 'isAdmin', 'subKategoris', 'pengusuls'));
    }

    public function byProvider(Request $request)
    {
        $perusahaans = Perusahaan::query()
            ->whereIn('id_perusahaan', DB::table('master_spjs as master')
                ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
                ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
                ->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'))
                ->select('master.perusahaan_id'))
            ->orderBy('nama_perusahaan')->get(['id_perusahaan', 'nama_perusahaan']);
        $query = DB::table('master_spjs as master')
            ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
            ->join('perusahaans as perusahaan', 'perusahaan.id_perusahaan', '=', 'master.perusahaan_id')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'))
            ->select([
                'spj.id as spj_id',
                'spj.nomor_urut',
                'spj.no_usulan_belanja',
                'spj.status_spj',
                'spj.pengadaan_step',
                'spj.pengadaan_workflow_step_id',
                'spj.no_surat_pesanan as pengadaan_no_surat_pesanan',
                'spj.tgl_surat_pesanan as pengadaan_tgl_surat_pesanan',
                'spj.foto_barang_datang as pengadaan_foto_barang_datang',
                'spj.tgl_barang_datang as pengadaan_tgl_barang_datang',
                'spj.status_verifikasi_pengurus_barang as pengadaan_status_pengurus_barang',
                'spj.tgl_verif_pengurus_barang as pengadaan_tgl_verif_pengurus_barang',
                'spj.tgl_bast as pengadaan_tgl_bast',
                'spj.no_ba as pengadaan_no_ba',
                'usulan.nama_pengusul',
                'perusahaan.id_perusahaan',
                'perusahaan.nama_perusahaan',
                'master.id as master_spj_id',
                'master.nomor_spk',
                'master.status_verifikasi_pengurus_barang',
                'master.status_verifikasi_ppk',
                'master.status_verifikasi_ppbj',
                'master.status_verifikasi_pptk',
                'master.status_verifikasi_verifikator',
                'master.status_verifikasi_ppk_keuangan',
                'master.status_verifikasi_direktur',
                'master.status_serah_terima_bendahara',
                'master.status_proses_tukar_faktur',
                'master.tanggal_penyerahan_spj',
                'master.tanggal_faktur',
                'master.tgl_kwitansi',
                'master.no_faktur',
                'master.tgl_proses_faktur',
                'master.keterangan as status_verifikasi_bendahara',
                'master.tanggal_pembayaran',
                'master.pembayaran',
            ])
            ->selectSub(
                DB::table('spj_details')->selectRaw('COUNT(*)')->whereColumn('spj_details.spj_id', 'spj.id'),
                'jumlah_item'
            )
            ->selectSub(
                DB::table('spj_details')->selectRaw('COALESCE(SUM(volume), 0)')->whereColumn('spj_details.spj_id', 'spj.id'),
                'jumlah_volume'
            )
            ->selectSub(
                DB::table('spj_details as detail')
                    ->join('usulan_belanja_items as item', 'item.id', '=', 'detail.usulan_belanja_item_id')
                    ->whereColumn('detail.spj_id', 'spj.id')
                    ->selectRaw('COALESCE(SUM(item.harga_satuan * detail.volume), 0)'),
                'netto'
            )
            ->selectSub(
                DB::table('spj_details as detail')
                    ->join('usulan_belanja_items as item', 'item.id', '=', 'detail.usulan_belanja_item_id')
                    ->whereColumn('detail.spj_id', 'spj.id')
                    ->selectRaw('COALESCE(SUM(item.harga_satuan * detail.volume * COALESCE(item.ppn, 0) / 100), 0)'),
                'ppn'
            )
            ->selectSub(
                DB::table('spj_details as detail')
                    ->join('usulan_belanja_items as item', 'item.id', '=', 'detail.usulan_belanja_item_id')
                    ->whereColumn('detail.spj_id', 'spj.id')
                    ->selectRaw('COALESCE(SUM(item.harga_satuan * detail.volume * (1 + COALESCE(item.ppn, 0) / 100)), 0)'),
                'total'
            );

        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan.id_perusahaan', $request->input('perusahaan_id'));
        }
        if ($request->filled('status_spj')) {
            $query->where('spj.status_spj', $request->input('status_spj'));
        }
        $spjs = $query->orderBy('perusahaan.nama_perusahaan')->orderBy('spj.nomor_urut')->get();
        $workflow = DB::table('workflows')->whereRaw('LOWER(nama_workflow) = ?', ['workflow pengadaan'])->first(['id']);
        $workflowSteps = $workflow ? DB::table('workflow_steps as ws')
            ->leftJoin('positions as p', 'p.id', '=', 'ws.position_id')
            ->leftJoin('roles as r', 'r.id', '=', 'ws.role_id')
            ->leftJoin('validator_roles as vr', 'vr.id', '=', 'ws.validator_role_id')
            ->where('ws.workflow_id', $workflow->id)->orderBy('ws.step_order')
            ->get(['ws.id', 'ws.step_order', 'ws.validator_type', 'ws.is_pptk_step', 'p.level_jabatan', 'r.nama_role', 'vr.nama_validator']) : collect();
        $approvalStatuses = Schema::hasTable('pengadaan_workflow_approvals') && $workflowSteps->isNotEmpty()
            ? DB::table('pengadaan_workflow_approvals')->whereIn('spj_id', $spjs->pluck('spj_id'))
            ->get(['spj_id', 'workflow_step_id', 'status', 'acted_at'])->groupBy('spj_id')
            : collect();
        foreach ($spjs as $spj) {
            $timeline = [];
            $approvalByStep = $approvalStatuses->get($spj->spj_id, collect())->keyBy('workflow_step_id');
            $pengurusOrder = $workflowSteps->first(fn($step) => strtolower(trim($step->level_jabatan ?: $step->nama_role ?: $step->nama_validator ?: '')) === 'pengurus barang')?->step_order;
            foreach ($workflowSteps as $workflowStep) {
                $identity = strtolower(trim($workflowStep->level_jabatan ?: $workflowStep->nama_role ?: $workflowStep->nama_validator ?: ''));
                $nama = $workflowStep->is_pptk_step
                    ? 'Validasi PPTK'
                    : ($identity === 'direktur'
                        ? 'Direktur'
                        : ($workflowStep->validator_type === 'hierarchy'
                        ? 'Validasi Atasan (Hirarki)'
                        : trim($workflowStep->level_jabatan ?: $workflowStep->nama_role ?: $workflowStep->nama_validator ?: 'Validasi')));
                $key = strtolower($nama);
                $status = null;
                $tanggal = null;
                if ($key === 'admin pengadaan' && $pengurusOrder !== null && $workflowStep->step_order < $pengurusOrder) {
                    $nama = 'Input surat pesanan';
                    $status = $spj->pengadaan_no_surat_pesanan ? 'Selesai' : null;
                    $tanggal = $spj->pengadaan_tgl_surat_pesanan;
                } elseif ($key === 'pendukung ppk') {
                    $nama = 'Unggah foto barang datang';
                    $status = $spj->pengadaan_foto_barang_datang ? 'Selesai' : null;
                    $tanggal = $spj->pengadaan_tgl_barang_datang;
                } elseif ($key === 'pengurus barang') {
                    $nama = 'Validasi Pengurus Barang';
                    $status = $spj->pengadaan_status_pengurus_barang;
                    $tanggal = $spj->pengadaan_tgl_verif_pengurus_barang;
                } elseif ($key === 'admin pengadaan' && $pengurusOrder !== null && $workflowStep->step_order > $pengurusOrder) {
                    $nama = 'Input data BAST';
                    $status = ($spj->pengadaan_tgl_bast || $spj->pengadaan_no_ba) ? 'Selesai' : null;
                    $tanggal = $spj->pengadaan_tgl_bast;
                    $timeline[] = ['nama' => $nama, 'status' => $status, 'tanggal' => $tanggal];
                    $fakturSelesai = $spj->status_proses_tukar_faktur
                        ?: (($spj->tanggal_faktur || $spj->tgl_kwitansi || $spj->no_faktur) ? 'Selesai' : null);
                    $timeline[] = [
                        'nama' => 'Update Tukar Faktur',
                        'status' => $fakturSelesai,
                        'tanggal' => $spj->tgl_proses_faktur ?: $spj->tanggal_faktur,
                    ];
                    continue;
                } elseif ($key === 'bendahara') {
                    $timeline[] = ['nama' => 'Serah terima ke Bendahara', 'status' => $spj->status_serah_terima_bendahara, 'tanggal' => null];
                    $timeline[] = ['nama' => 'Verifikasi Bendahara', 'status' => $spj->status_verifikasi_bendahara, 'tanggal' => null];
                    $timeline[] = ['nama' => 'Pembayaran', 'status' => $spj->tanggal_pembayaran ? 'Selesai' : null, 'tanggal' => $spj->tanggal_pembayaran];
                    continue;
                } else {
                    $approval = $approvalByStep->get($workflowStep->id);
                    $status = $approval?->status ? ucfirst($approval->status) : null;
                    $tanggal = $approval?->acted_at;
                }
                $timeline[] = ['nama' => $nama, 'status' => $status, 'tanggal' => $tanggal];
                if ($identity === 'direktur') {
                    $timeline[] = [
                        'nama' => 'Penyerahan Dokumen SPJ oleh Admin Pengadaan',
                        'status' => $spj->status_serah_terima_bendahara,
                        'tanggal' => $spj->tanggal_penyerahan_spj,
                    ];
                }
            }
            $spj->workflow_timeline = $timeline;
        }
        $statuses = DB::table('spjs as spj')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->where('usulan.tahun_anggaran_id', session('tahun_anggaran_id'))
            ->whereNotNull('spj.status_spj')->distinct()->orderBy('spj.status_spj')->pluck('spj.status_spj');

        return view('usulan_belanja.spj.providers', compact('spjs', 'perusahaans', 'statuses'));
    }

    public function create(UsulanBelanja $usulanBelanja)
    {
        $this->assertCanCreate($usulanBelanja);
        $this->assertApproved($usulanBelanja);

        $assignedVolumes = DB::table('spj_details')
            ->where('usulan_belanja_id', $usulanBelanja->id)
            ->select('usulan_belanja_item_id')
            ->selectRaw('SUM(volume) as assigned_volume')
            ->groupBy('usulan_belanja_item_id')
            ->pluck('assigned_volume', 'usulan_belanja_item_id');
        $items = $this->workflow->getSelectedItems($usulanBelanja)
            ->map(function ($item) use ($assignedVolumes) {
                $item->spj_remaining_volume = round((float) $item->volume - (float) $assignedVolumes->get($item->link_id, 0), 3);
                return $item;
            })
            ->filter(fn($item) => $item->spj_remaining_volume > 0)
            ->values();

        $perusahaans = Perusahaan::query()->orderBy('nama_perusahaan')->get(['id_perusahaan', 'nama_perusahaan']);

        return view('usulan_belanja.spj.create', compact('usulanBelanja', 'items', 'perusahaans'));
    }

    public function store(Request $request, UsulanBelanja $usulanBelanja)
    {
        $this->assertCanCreate($usulanBelanja);
        $validated = $request->validate([
            'groups' => ['required', 'array', 'min:1'],
            'groups.*.perusahaan_id' => ['required', 'uuid', 'exists:perusahaans,id_perusahaan'],
            'groups.*.items' => ['required', 'array'],
            'groups.*.items.*' => ['required', 'numeric', 'min:0'],
        ]);

        $groups = collect($validated['groups'])->map(function ($group) {
            return [
                'perusahaan_id' => $group['perusahaan_id'],
                'items' => collect($group['items'])
                    ->map(fn($volume, $itemId) => ['item_id' => $itemId, 'volume' => round((float) $volume, 3)])
                    ->filter(fn($allocation) => $allocation['volume'] > 0)
                    ->values(),
            ];
        });
        if ($groups->contains(fn($group) => $group['items']->isEmpty())) {
            throw ValidationException::withMessages(['groups' => 'Setiap SPJ harus memiliki item dengan volume lebih dari 0.']);
        }

        $spjIds = DB::transaction(function () use ($usulanBelanja, $request, $groups) {
            $lockedUsulan = UsulanBelanja::query()->whereKey($usulanBelanja->id)->lockForUpdate()->firstOrFail();
            $this->assertApproved($lockedUsulan);

            $availableItems = DB::table('usulan_belanja_items as item')
                ->where('item.usulan_belanja_id', $lockedUsulan->id)
                ->whereNotNull('item.detail_type')
                ->whereNotNull('item.detail_id')
                ->get(['item.id', 'item.rkbu_id', 'item.volume'])
                ->keyBy('id')
                ->map(function ($item) {
                    $item->remaining_volume = round((float) $item->volume - (float) DB::table('spj_details')
                        ->where('usulan_belanja_item_id', $item->id)
                        ->sum('volume'), 3);
                    return $item;
                })
                ->filter(fn($item) => $item->remaining_volume > 0);

            $requestedVolumes = $groups->flatMap(fn($group) => $group['items'])->groupBy('item_id')->map(fn($allocations) => round($allocations->sum('volume'), 3));
            foreach ($requestedVolumes as $itemId => $volume) {
                if (! $availableItems->has($itemId) || $volume > $availableItems[$itemId]->remaining_volume) {
                    throw ValidationException::withMessages(['groups' => 'Pembagian volume melebihi sisa volume item usulan. Muat ulang halaman dan coba lagi.']);
                }
            }
            $yearName = DB::table('tahun_anggarans')
                ->where('id', $lockedUsulan->tahun_anggaran_id)
                ->value('nama_tahun_anggaran');
            $lastGroupNumber = (int) DB::table('spjs')
                ->where('usulan_belanja_id', $lockedUsulan->id)
                ->max('nomor_urut');
            $createdSpjIds = [];

            foreach ($groups as $index => $group) {
                $groupAllocations = $group['items'];
                $groupNumber = $lastGroupNumber + $index + 1;
                $spjId = (string) Str::uuid();
                $now = now();

                DB::table('spjs')->insert([
                    'id' => $spjId,
                    'usulan_belanja_id' => $lockedUsulan->id,
                    'user_id' => $request->user()->id,
                    'no_usulan_belanja' => $lockedUsulan->no_usulan_belanja,
                    'nomor_urut' => $groupNumber,
                    'status_spj' => 'Proses SPJ',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('master_spjs')->insert([
                    'id' => (string) Str::uuid(),
                    'spj_id' => $spjId,
                    'perusahaan_id' => $group['perusahaan_id'],
                    'user_id' => $request->user()->id,
                    'tahun_anggaran' => $yearName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $detailRows = $groupAllocations->map(fn($allocation) => [
                    'id' => (string) Str::uuid(),
                    'rkbu_id' => $availableItems[$allocation['item_id']]->rkbu_id,
                    'spj_id' => $spjId,
                    'usulan_belanja_id' => $lockedUsulan->id,
                    'usulan_belanja_item_id' => $allocation['item_id'],
                    'volume' => $allocation['volume'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();
                DB::table('spj_details')->insert($detailRows);
                $createdSpjIds[] = $spjId;
            }

            return $createdSpjIds;
        }, 3);

        return redirect()
            ->route('usulan-belanja.spj.index')
            ->with('success', count($spjIds) . ' SPJ berhasil dibuat dari usulan ' . $usulanBelanja->no_usulan_belanja . '.');
    }

    public function show(Spj $spj)
    {
        $spj->load([
            'usulanBelanja.pengusul',
            'usulanBelanja.tahunAnggaran',
            'usulanBelanja.rkbu.subKegiatan.kegiatan.program',
            'usulanBelanja.rkbu.subKategoriRkbu',
            'usulanBelanja.rkbu.rekeningBelanja',
            'details',
        ]);
        $rkbu = $spj->usulanBelanja?->rkbu;
        $rekeningId = $rkbu ? app(PaguAnggaranService::class)->resolveAccountId($rkbu) : null;
        $rekeningBelanja = $rekeningId
            ? \App\Models\MasterBackend\SettingRkbu\RekeningBelanja::query()->find($rekeningId)
            : $rkbu?->rekeningBelanja;
        $subKegiatan = $rkbu?->subKegiatan;
        $kegiatan = $subKegiatan?->kegiatan;
        $program = $kegiatan?->program;
        $namaPerusahaan = DB::table('master_spjs')
            ->join('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.perusahaan_id')
            ->where('master_spjs.spj_id', $spj->id)
            ->value('perusahaans.nama_perusahaan');
        $selectedItems = $this->workflow->getSelectedItems($spj->usulanBelanja)->keyBy('link_id');
        $items = $spj->details
            ->map(function ($detail) use ($selectedItems) {
                $item = $selectedItems->get($detail->usulan_belanja_item_id);
                if (! $item) {
                    return null;
                }
                $item->spj_detail_id = $detail->id;
                $item->volume = (float) $detail->volume;
                $item->total_anggaran = (float) $item->harga_satuan * (float) $detail->volume;
                $item->total_ppn = $item->total_anggaran * (float) ($item->ppn ?? 0) / 100;
                return $item;
            })
            ->filter()
            ->values();
        $assignedByItem = DB::table('spj_details')
            ->where('usulan_belanja_id', $spj->usulan_belanja_id)
            ->select('usulan_belanja_item_id')
            ->selectRaw('SUM(volume) as assigned_volume')
            ->groupBy('usulan_belanja_item_id')
            ->pluck('assigned_volume', 'usulan_belanja_item_id');
        $currentSpjItemIds = $spj->details->pluck('usulan_belanja_item_id')->all();
        $availableItems = $this->workflow->getSelectedItems($spj->usulanBelanja)
            ->filter(fn($item) => ! in_array($item->link_id, $currentSpjItemIds, true))
            ->map(function ($item) use ($assignedByItem) {
                $item->spj_remaining_volume = round((float) $item->volume - (float) $assignedByItem->get($item->link_id, 0), 3);
                return $item;
            })
            ->filter(fn($item) => $item->spj_remaining_volume > 0)
            ->values();
        $isAdmin = auth()->user()->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists();
        $canManageSpj = $isAdmin
            || $spj->usulanBelanja->user_id === auth()->id()
            || DB::table('approvals')->where('workflow_instance_id', $spj->usulanBelanja->workflow_instance_id)->where('validator_user_id', auth()->id())->exists();

        $hasPengadaanRole = auth()->user()->roles()->whereIn('nama_role', [
            'Administrator',
            'Super Admin',
            'Admin Pengadaan',
            'Pendukung PPK',
            'Pengurus Barang',
        ])->exists() || in_array(auth()->user()->position?->level_jabatan, [
            'PPK',
            'PPBJ',
            'PPTK',
            'Verifikator',
            'PPK Keuangan',
            'Direktur',
            'Bendahara',
        ], true) || app(PengadaanWorkflowController::class)->userCanAccessCurrentWorkflowStep($spj);
        $pengadaanData = $hasPengadaanRole
            ? app(PengadaanWorkflowController::class)->panelData($spj)
            : [];
        return view('usulan_belanja.spj.show', array_merge(
            compact('spj', 'items', 'namaPerusahaan', 'isAdmin', 'availableItems', 'canManageSpj', 'program', 'kegiatan', 'rekeningBelanja') + ['pengadaanPanelData' => $pengadaanData],
            $pengadaanData
        ));
    }

    public function addItem(Request $request, Spj $spj)
    {
        $spj->loadMissing('usulanBelanja');
        $usulan = $spj->usulanBelanja;
        $this->assertCanCreate($usulan);
        $this->assertApproved($usulan);
        $validated = $request->validate([
            'item_id' => ['required', 'uuid'],
            'volume' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
        ]);

        try {
            DB::transaction(function () use ($spj, $usulan, $validated) {
                UsulanBelanja::query()->whereKey($usulan->id)->lockForUpdate()->firstOrFail();
                $item = DB::table('usulan_belanja_items')
                    ->where('id', $validated['item_id'])
                    ->where('usulan_belanja_id', $usulan->id)
                    ->whereNotNull('detail_type')
                    ->whereNotNull('detail_id')
                    ->lockForUpdate()
                    ->first(['id', 'rkbu_id', 'volume']);
                if (! $item) {
                    throw ValidationException::withMessages(['item_id' => 'Item tidak ditemukan pada usulan ini.']);
                }
                if (DB::table('spj_details')->where('spj_id', $spj->id)->where('usulan_belanja_item_id', $item->id)->exists()) {
                    throw ValidationException::withMessages(['item_id' => 'Item ini sudah ada di SPJ.']);
                }
                $alreadyAssigned = (float) DB::table('spj_details')
                    ->where('usulan_belanja_item_id', $item->id)
                    ->sum('volume');
                $remaining = round((float) $item->volume - $alreadyAssigned, 3);
                $volume = round((float) $validated['volume'], 3);
                if ($volume > $remaining) {
                    throw ValidationException::withMessages(['volume' => 'Sisa volume item usulan hanya ' . rtrim(rtrim(number_format($remaining, 3, ',', '.'), '0'), ',') . '.']);
                }

                DB::table('spj_details')->insert([
                    'id' => (string) Str::uuid(),
                    'rkbu_id' => $item->rkbu_id,
                    'spj_id' => $spj->id,
                    'usulan_belanja_id' => $usulan->id,
                    'usulan_belanja_item_id' => $item->id,
                    'volume' => $volume,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }, 3);
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        }

        return back()->with('success', 'Item berhasil ditambahkan ke SPJ.');
    }

    public function updateItem(Request $request, Spj $spj, \App\Models\UsulanBelanja\SpjDetail $detail, UsulanBelanjaBalanceService $balances)
    {
        abort_unless($detail->spj_id === $spj->id, 404);
        $spj->loadMissing('usulanBelanja');
        $usulan = $spj->usulanBelanja;
        $this->assertCanCreate($usulan);
        $this->assertApproved($usulan);

        $validated = $request->validate([
            'spesifikasi' => ['nullable', 'string', 'max:10000'],
            'volume' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'harga_satuan' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ]);

        try {
            DB::transaction(function () use ($spj, $detail, $usulan, $validated, $balances) {
                $lockedUsulan = UsulanBelanja::query()->whereKey($usulan->id)->lockForUpdate()->firstOrFail();
                $lockedDetail = DB::table('spj_details')->where('id', $detail->id)->lockForUpdate()->first();
                abort_unless($lockedDetail && $lockedDetail->spj_id === $spj->id, 404);
                $item = DB::table('usulan_belanja_items')->where('id', $lockedDetail->usulan_belanja_item_id)->lockForUpdate()->first();
                if (! $item || ! $item->detail_type || ! $item->detail_id) {
                    throw ValidationException::withMessages(['volume' => 'Item ini tidak terhubung ke detail RKBU.']);
                }

                $detailTables = [
                    'barjas' => 'rkbu_detail_barjass',
                    'persediaan' => 'rkbu_detail_persediaans',
                    'modal' => 'rkbu_detail_modals',
                    'modal_alkes' => 'rkbu_detail_modal_alkes',
                    'pegawai' => 'rkbu_detail_pegawais',
                ];
                $table = $detailTables[$item->detail_type] ?? null;
                if (! $table) {
                    throw ValidationException::withMessages(['volume' => 'Jenis detail RKBU tidak valid.']);
                }
                $rkbuDetail = DB::table($table)->where('id', $item->detail_id)->lockForUpdate()->first(['jumlah_volume', 'total_anggaran', 'sisa_vol_rkbu', 'sisa_anggaran_rkbu']);
                if (! $rkbuDetail) {
                    throw ValidationException::withMessages(['volume' => 'Detail RKBU tidak ditemukan.']);
                }

                $targetVolume = round((float) $validated['volume'], 3);
                $otherAssigned = (float) DB::table('spj_details')
                    ->where('usulan_belanja_item_id', $item->id)
                    ->where('id', '<>', $detail->id)
                    ->sum('volume');
                $requiredUsulanVolume = round($otherAssigned + $targetVolume, 3);
                $newUsulanVolume = max((float) $item->volume, $requiredUsulanVolume);
                $remaining = $balances->remainingForDetail(
                    $item->detail_type,
                    $item->detail_id,
                    (float) ($rkbuDetail->sisa_vol_rkbu ?? $rkbuDetail->jumlah_volume),
                    (float) ($rkbuDetail->sisa_anggaran_rkbu ?? $rkbuDetail->total_anggaran),
                    $lockedUsulan->id
                );
                if ($newUsulanVolume > $remaining['volume'] + 0.0005) {
                    throw ValidationException::withMessages(['volume' => 'Sisa volume RKBU tidak cukup untuk penambahan volume ini. Sisa volume RKBU: ' . rtrim(rtrim(number_format($remaining['volume'], 3, ',', '.'), '0'), ',') . '.']);
                }

                $unitPrice = (float) $validated['harga_satuan'];
                $ppn = (float) ($item->ppn ?? 0);
                $requestedBudget = $newUsulanVolume * $unitPrice * (1 + $ppn / 100);
                if ($requestedBudget > $remaining['anggaran'] + 0.005) {
                    throw ValidationException::withMessages(['harga_satuan' => 'Sisa anggaran RKBU tidak cukup untuk volume dan harga satuan ini. Sisa anggaran RKBU: Rp ' . number_format($remaining['anggaran'], 0, ',', '.') . '.']);
                }

                DB::table('usulan_belanja_items')->where('id', $item->id)->update([
                    'spesifikasi' => $validated['spesifikasi'] ?? null,
                    'volume' => $newUsulanVolume,
                    'harga_satuan' => $unitPrice,
                    'updated_at' => now(),
                ]);
                DB::table('spj_details')->where('id', $detail->id)->update([
                    'volume' => $targetVolume,
                    'updated_at' => now(),
                ]);
            });
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        }

        return back()->with('success', 'Item SPJ berhasil diperbarui.');
    }

    public function deleteItem(Spj $spj, \App\Models\UsulanBelanja\SpjDetail $detail)
    {
        abort_unless($detail->spj_id === $spj->id, 404);
        abort_unless(auth()->user()->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists(), 403);

        DB::transaction(function () use ($spj, $detail) {
            $lockedDetail = DB::table('spj_details')
                ->where('id', $detail->id)
                ->where('spj_id', $spj->id)
                ->lockForUpdate()
                ->first();
            abort_unless($lockedDetail, 404);

            DB::table('spj_details')->where('id', $detail->id)->delete();
        });

        return redirect()->route('usulan-belanja.spj.index')->with('success', 'Item berhasil dihapus dari SPJ.');
    }

    private function assertCanCreate(UsulanBelanja $usulanBelanja): void
    {
        abort_unless(
            $this->canCreateFor($usulanBelanja),
            403,
            'Hanya Administrator atau petugas pada Workflow Pengadaan langkah pertama yang dapat membuat atau menambah SPJ.'
        );
    }

    public function canCreateFor(UsulanBelanja $usulanBelanja): bool
    {
        $user = auth()->user();
        if ($user->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists()) {
            return true;
        }

        $step = $this->firstPengadaanWorkflowStep();
        return $step && $this->userMatchesFirstPengadaanStep($user, $step, $usulanBelanja);
    }

    private function firstPengadaanWorkflowStep(): ?object
    {
        $workflowId = DB::table('workflows')
            ->whereRaw('LOWER(nama_workflow) = ?', ['workflow pengadaan'])
            ->value('id');

        if (! $workflowId) {
            $workflowId = DB::table('workflows')
                ->whereRaw('LOWER(modul) = ?', ['pengadaan'])
                ->orderByDesc('updated_at')
                ->value('id');
        }

        if (! $workflowId) {
            return null;
        }

        return DB::table('workflow_steps as ws')
            ->leftJoin('roles as r', 'r.id', '=', 'ws.role_id')
            ->leftJoin('validator_roles as vr', 'vr.id', '=', 'ws.validator_role_id')
            ->where('ws.workflow_id', $workflowId)
            ->orderBy('ws.step_order')
            ->select('ws.*', 'r.nama_role as system_role_name', 'vr.nama_validator')
            ->first();
    }

    private function userMatchesFirstPengadaanStep($user, object $step, UsulanBelanja $usulan): bool
    {
        if ($step->is_pptk_step) {
            return DB::table('position_sub_kategori_rkbus as mapping')
                ->join('position_assignments as assignment', 'assignment.position_id', '=', 'mapping.position_id')
                ->join('users as assigned_user', 'assigned_user.id', '=', 'assignment.user_id')
                ->where('mapping.id_sub_kategori_rkbu', $usulan->sub_kategori_rkbu_id)
                ->where('assignment.user_id', $user->id)
                ->where('assignment.is_active', true)
                ->whereDate('assignment.tanggal_mulai', '<=', today())
                ->where(fn($query) => $query->whereNull('assignment.tanggal_selesai')->orWhereDate('assignment.tanggal_selesai', '>=', today()))
                ->where('assigned_user.status_user', 'aktif')
                ->exists();
        }

        if ($step->validator_type === 'position') {
            return (string) $user->position_id === (string) $step->position_id;
        }

        if ($step->validator_type === 'user') {
            return $step->validator_role_id
                && $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists();
        }

        if ($step->validator_type === 'role') {
            if ($step->role_id && $user->roles()->where('roles.id', $step->role_id)->exists()) {
                return true;
            }
            if ($step->validator_role_id && $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists()) {
                return true;
            }

            $roleName = strtolower(trim((string) $step->system_role_name));
            return $roleName !== '' && strtolower(trim((string) $user->position?->level_jabatan)) === $roleName;
        }

        if ($step->validator_type === 'hierarchy') {
            if ($step->validator_role_id && $user->validatorRoles()->where('validator_roles.id', $step->validator_role_id)->exists()) {
                return true;
            }
            $position = $usulan->pengusul()->with('position')->first()?->position;
            for ($i = 0, $levels = max(1, (int) ($step->hierarchy_level ?? 1)); $i < $levels && $position; $i++) {
                $position = $position->parent;
            }
            return $position && (string) $user->position_id === (string) $position->id;
        }

        return false;
    }

    private function assertApproved(UsulanBelanja $usulanBelanja): void
    {
        $workflowStatus = DB::table('workflow_instances')
            ->where('id', $usulanBelanja->workflow_instance_id)
            ->where('dokumen_id', $usulanBelanja->id)
            ->where('dokumen_type', UsulanBelanja::class)
            ->value('status');

        abort_unless($usulanBelanja->status_usulan === 'approved' && $workflowStatus === 'approved', 404);
    }
}
