<?php

namespace App\Http\Controllers\UsulanBelanja;

use App\Http\Controllers\Controller;
use App\Models\UsulanBelanja\Spj;
use App\Models\UsulanBelanja\UsulanBelanja;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingBelanja\Perusahaan;
use App\Services\Workflow\UsulanBelanjaWorkflowService;
use App\Services\Workflow\UsulanBelanjaBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SpjUsulanBelanjaController extends Controller
{
    public function __construct(private UsulanBelanjaWorkflowService $workflow) {}

    public function index(Request $request)
    {
        $isAdmin = auth()->user()->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists();
        $validatedWorkflowIds = DB::table('approvals')
            ->where('validator_user_id', auth()->id())
            ->distinct()
            ->pluck('workflow_instance_id')
            ->all();
        $query = UsulanBelanja::query()
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
                ->where('status_usulan', 'approved')
                ->whereNotNull('workflow_instance_id')
                ->select('sub_kategori_rkbu_id'))
            ->orderBy('nama_sub_kategori_rkbu')
            ->get(['id_sub_kategori_rkbu', 'nama_sub_kategori_rkbu']);
        $pengusuls = UsulanBelanja::query()
            ->where('status_usulan', 'approved')
            ->whereNotNull('workflow_instance_id')
            ->whereNotNull('user_id')
            ->select('user_id', 'nama_pengusul')
            ->distinct()
            ->orderBy('nama_pengusul')
            ->get();
        $usulans = $query->paginate(15)->withQueryString();

        return view('usulan_belanja.spj.index', compact('usulans', 'isAdmin', 'validatedWorkflowIds', 'subKategoris', 'pengusuls'));
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

        return view('usulan_belanja.spj.show', compact('spj', 'items', 'namaPerusahaan', 'isAdmin', 'availableItems', 'canManageSpj'));
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
        $user = auth()->user();
        $isAdmin = $user->roles()->whereIn('nama_role', ['Administrator', 'Super Admin'])->exists();
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
