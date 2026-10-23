<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Models\UsulanBelanja\Spj;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SpjUsulanBelanjaController extends Controller
{
    public function __construct(private UsulanBelanjaWorkflowService $workflow) {}

    public function index()
    {
        $isAdmin = auth()->user()->roles()->where('nama_role', 'Administrator')->exists();
        $validatedWorkflowIds = DB::table('approvals')
            ->where('validator_user_id', auth()->id())
            ->distinct()
            ->pluck('workflow_instance_id')
            ->all();
        $usulans = UsulanBelanja::query()
            ->where('status_usulan', 'approved')
            ->whereNotNull('workflow_instance_id')
            ->whereHas('workflowInstance', fn ($query) => $query->where('status', 'approved'))
            ->with([
                'pengusul',
                'unit',
                'tahunAnggaran',
                'rkbu.subKategoriRkbu',
                'spjs' => fn ($query) => $query->withCount('details')->orderBy('nomor_urut'),
            ])
            ->withCount([
                'items as item_count' => fn ($query) => $query->whereNotNull('detail_type')->whereNotNull('detail_id'),
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
            ->latest()
            ->paginate(15);

        return view('usulan_belanja.spj.index', compact('usulans', 'isAdmin', 'validatedWorkflowIds'));
    }

    public function byProvider(Request $request)
    {
        $perusahaans = Perusahaan::query()->orderBy('nama_perusahaan')->get(['id_perusahaan', 'nama_perusahaan']);
        $query = DB::table('master_spjs as master')
            ->join('spjs as spj', 'spj.id', '=', 'master.spj_id')
            ->join('perusahaans as perusahaan', 'perusahaan.id_perusahaan', '=', 'master.perusahaan_id')
            ->join('usulan_belanjas as usulan', 'usulan.id', '=', 'spj.usulan_belanja_id')
            ->select([
                'spj.id as spj_id',
                'spj.nomor_urut',
                'spj.no_usulan_belanja',
                'spj.status_spj',
                'usulan.nama_pengusul',
                'perusahaan.id_perusahaan',
                'perusahaan.nama_perusahaan',
            ])
            ->selectSub(
                DB::table('spj_details')->selectRaw('COUNT(*)')->whereColumn('spj_details.spj_id', 'spj.id'),
                'jumlah_item'
            )
            ->selectSub(
                DB::table('spj_details')->selectRaw('COALESCE(SUM(volume), 0)')->whereColumn('spj_details.spj_id', 'spj.id'),
                'jumlah_volume'
            );

        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan.id_perusahaan', $request->input('perusahaan_id'));
        }
        if ($request->filled('status_spj')) {
            $query->where('spj.status_spj', $request->input('status_spj'));
        }
        if ($request->filled('q')) {
            $term = '%' . trim($request->input('q')) . '%';
            $query->where(function ($filter) use ($term) {
                $filter->where('spj.no_usulan_belanja', 'like', $term)
                    ->orWhere('usulan.nama_pengusul', 'like', $term)
                    ->orWhere('perusahaan.nama_perusahaan', 'like', $term);
            });
        }

        $spjs = $query->orderBy('perusahaan.nama_perusahaan')->orderBy('spj.nomor_urut')->paginate(20)->withQueryString();
        $statuses = DB::table('spjs')->whereNotNull('status_spj')->distinct()->orderBy('status_spj')->pluck('status_spj');

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
            ->filter(fn ($item) => $item->spj_remaining_volume > 0)
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
                ->map(fn ($volume, $itemId) => ['item_id' => $itemId, 'volume' => round((float) $volume, 3)])
                ->filter(fn ($allocation) => $allocation['volume'] > 0)
                ->values(),
            ];
        });
        if ($groups->contains(fn ($group) => $group['items']->isEmpty())) {
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
                ->filter(fn ($item) => $item->remaining_volume > 0);

            $requestedVolumes = $groups->flatMap(fn ($group) => $group['items'])->groupBy('item_id')->map(fn ($allocations) => round($allocations->sum('volume'), 3));
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

                $detailRows = $groupAllocations->map(fn ($allocation) => [
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
        $spj->load(['usulanBelanja.pengusul', 'usulanBelanja.tahunAnggaran', 'details']);
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
                $item->volume = (float) $detail->volume;
                $item->total_anggaran = (float) $item->harga_satuan * (float) $detail->volume;
                $item->total_ppn = $item->total_anggaran * (float) ($item->ppn ?? 0) / 100;
                return $item;
            })
            ->filter()
            ->values();

        return view('usulan_belanja.spj.show', compact('spj', 'items', 'namaPerusahaan'));
    }

    private function assertCanCreate(UsulanBelanja $usulanBelanja): void
    {
        $user = auth()->user();
        $isAdmin = $user->roles()->where('nama_role', 'Administrator')->exists();
        $isValidator = DB::table('approvals')
            ->where('workflow_instance_id', $usulanBelanja->workflow_instance_id)
            ->where('validator_user_id', $user->id)
            ->exists();
        abort_unless($isAdmin || $usulanBelanja->user_id === $user->id || $isValidator, 403);
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
