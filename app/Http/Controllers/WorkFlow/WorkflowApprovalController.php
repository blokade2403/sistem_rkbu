<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\DataTables\Workflow\WorkflowApprovalDataTable;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\Rkbu\Rkbu;
use App\Models\WorkFlow\Approval;
use App\Models\WorkFlow\ApprovalLog;
use App\Models\WorkFlow\WorkflowStep;
use App\Services\Workflow\PositionAssignmentService;
use App\Services\Workflow\PaguAnggaranService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkflowApprovalController extends Controller
{
    private const NUMERIC_DETAIL_FIELDS = [
        'jumlah_volume',
        'bulan_tmt',
        'tahun_tmt',
        'volume_1',
        'volume_2',
        'harga_satuan',
        'ppn',
        'total_anggaran',
        'rating',
        'sisa_vol_rkbu',
        'sisa_anggaran_rkbu',
        'stok',
        'rata_rata_pemakaian',
        'kebutuhan_per_bulan',
        'buffer',
        'pengadaan_sebelumnya',
        'proyeksi_sisa_stok',
        'kebutuhan_plus_buffer',
        'kebutuhan_tahun_x1',
        'rencana_pengadaan_tahun_x1',
        'gaji_pokok',
        'remunerasi',
        'koefisien_remunerasi',
        'koefisien_gaji',
        'bpjs_kesehatan',
        'bpjs_tk',
        'bpjs_jht',
        'total_gaji_pokok',
        'total_remunerasi',
        'eksisting',
        'kondisi_baik',
        'kondisi_rusak_berat',
    ];

    public function index(Request $request, PaguAnggaranService $paguService)
    {
        $this->abortAdministrator();
        $module = $request->query('module', 'all');
        abort_unless(in_array($module, ['all', 'barjas', 'pegawai', 'persediaan', 'modal', 'modal-kantor', 'modal-alkes'], true), 404);

        return view(
            'workflow.approval.index',
            [
                'title' => 'Approval Saya',
                'paguAccountSummary' => $paguService->accountSummary(session('tahun_anggaran_id'), $module),
                'module' => $module,
                'isSuperAdmin' => $this->isSuperAdmin(),
                'sumberDanas' => SumberDana::query()->orderBy('nama_sumber_dana')->get(),
                'subKategoris' => SubKategoriRkbu::query()
                    ->orderBy('kode_sub_kategori_rkbu')
                    ->get(),
            ]
        );
    }

    private function abortAdministrator(): void
    {
        abort_if(
            auth()->user()->hasRole('Administrator') && ! $this->isSuperAdmin(),
            403,
            'Administrator hanya dapat melihat data RKBU dan tidak dapat melakukan approval.'
        );
    }

    private function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Administrator');
    }

    public function getData(Request $request)
    {
        $this->abortAdministrator();

        $isSuperAdmin = $this->isSuperAdmin();
        $assignment = $isSuperAdmin
            ? null
            : app(PositionAssignmentService::class)->getCurrentAssignment();

        return app(WorkflowApprovalDataTable::class)->render($request, $isSuperAdmin, $assignment);
    }

    public function show($id)
    {
        $this->abortAdministrator();

        $isSuperAdmin = $this->isSuperAdmin();
        $approvalQuery = $isSuperAdmin
            ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
            : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($this->currentValidatorAssignment());
        $approval = $approvalQuery->with([
            'workflowInstance.rkbu.detailBarjas',
            'workflowInstance.rkbu.detailPegawai',
            'workflowInstance.rkbu.detailPersediaan',
            'workflowInstance.rkbu.detailModal',
            'workflowInstance.rkbu.detailModalAlkes',
            'workflowInstance.rkbu.sumberDana',
            'workflowInstance.rkbu.subKegiatan',
            'workflowInstance.rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'workflowStep',
            'validatorUser',
            'validatorRole',
            'position',
            'workflowInstance.currentPosition',
        ])
            ->findOrFail($id);

        $rkbu = Rkbu::with([
            'unit',
            'tahunAnggaran',
            'sumberDana',
            'subKegiatan',
            'detailBarjas',
            'detailPegawai',
            'detailPersediaan',
            'detailModal',
            'detailModalAlkes',
            'subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'workflowInstances.approvals.validatorUser',
            'workflowInstances.approvals.position',
            'workflowInstances.approvals.workflowStep.position',
            'workflowInstances.approvals.workflowStep.validatorRole',
            'workflowInstances.approvals.logs.user',
            'workflowInstances.approvals.logs.position',
        ])->findOrFail(
            $approval->workflowInstance->dokumen_id
        );
        $paguService = app(PaguAnggaranService::class);
        $phaseOrder = $paguService->phaseOrder($rkbu->tahun_anggaran_id);
        $canEditRkbu = $paguService->isAdministrator()
            || ($phaseOrder === 1 && $approval->status === 'pending' && ! $isSuperAdmin);
        $canEditHeader = auth()->user()->hasRole('Super Admin');
        $details = app(WorkflowApprovalDataTable::class)->detailsForRkbu($rkbu);
        $module = app(WorkflowApprovalDataTable::class)->moduleKey($rkbu, $details);

        $detailRows = $details->map(fn($detail) => $this->approvalRow($module, $detail));
        $editableDetails = $canEditRkbu
            ? $details->map(fn($detail) => [
                'id' => $detail->getKey(),
                'locked' => $this->isDetailLockedByUsulan($detail),
                'fields' => $this->editableDetailFields($detail),
            ])->values()
            : collect();
        $attachments = $details->flatMap(function ($detail) {
            return collect(range(1, 5))
                ->map(function ($number) use ($detail) {
                    $path = $detail->{'upload_file_' . $number} ?? null;

                    return $path ? [
                        'label' => 'Lampiran KAP ' . $number,
                        'url' => asset('storage/' . $path),
                    ] : null;
                })
                ->filter();
        })->values();

        $currentPosition = $approval->position
            ?? $approval->validatorUser?->position
            ?? $approval->workflowInstance?->currentPosition;
        $nextStep = $approval->workflowStep
            ? WorkflowStep::query()
            ->where('workflow_id', $approval->workflowStep->workflow_id)
            ->where('step_order', '>', $approval->workflowStep->step_order)
            ->orderBy('step_order')
            ->first()
            : null;
        $nextOfficials = collect();
        if ($isSuperAdmin && $approval->status === 'pending' && $nextStep && $currentPosition) {
            $nextOfficials = $this->activeAssignmentsForWorkflowStep($nextStep, $currentPosition)
                ->map(fn($official) => [
                    'id' => $official->id,
                    'label' => $official->user->nama . ' - ' . $official->position->nama_jabatan,
                ])
                ->values();
        }

        return response()->json([
            'approval' => $approval,
            'rkbu' => $rkbu,
            'printUrl' => route('workflowapproval.print', $approval->id),
            'module' => app(WorkflowApprovalDataTable::class)->moduleLabel($rkbu, $details),
            'detailColumns' => $detailRows->isNotEmpty() ? array_keys($detailRows->first()) : [],
            'details' => $detailRows->values(),
            'attachments' => $attachments,
            'catatan' => $approval->catatan,
            'canApprove' => $approval->status === 'pending',
            'canEdit' => $canEditRkbu,
            'canEditHeader' => $canEditRkbu && $canEditHeader,
            'phaseOrder' => $phaseOrder,
            'editableDetails' => $editableDetails,
            'sumberDanas' => $canEditHeader
                ? SumberDana::query()->orderBy('nama_sumber_dana')->get([
                    'id_sumber_dana',
                    'nama_sumber_dana',
                ])
                : collect(),
            'subKegiatans' => $canEditHeader
                ? SubKegiatan::query()->orderBy('kode_sub_kegiatan')->get([
                    'id_sub_kegiatan',
                    'kode_sub_kegiatan',
                    'nama_sub_kegiatan',
                    'id_sumber_dana',
                ])
                : collect(),
            'nextOfficials' => $nextOfficials,
            'requiresNextOfficial' => $isSuperAdmin && $approval->status === 'pending' && (bool) $nextStep,
            'updateUrl' => route('workflowapproval.update-rkbu', $approval->id),
        ]);
    }

    public function print(string $id)
    {
        $this->abortAdministrator();

        $isSuperAdmin = $this->isSuperAdmin();
        $approvalQuery = $isSuperAdmin
            ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
            : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($this->currentValidatorAssignment());
        $approval = $approvalQuery->with('workflowInstance')->findOrFail($id);

        $rkbu = Rkbu::with([
            'unit',
            'tahunAnggaran',
            'creator',
            'sumberDana',
            'subKegiatan',
            'subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'detailBarjas',
            'detailPegawai',
            'detailPersediaan',
            'detailModal',
            'detailModalAlkes',
            'workflowInstances.approvals.validatorUser',
            'workflowInstances.approvals.position',
            'workflowInstances.approvals.workflowStep.position',
            'workflowInstances.approvals.workflowStep.validatorRole',
            'workflowInstances.approvals.logs.user',
            'workflowInstances.approvals.logs.position',
        ])->findOrFail($approval->workflowInstance->dokumen_id);

        $details = app(WorkflowApprovalDataTable::class)->detailsForRkbu($rkbu);
        $dataTable = app(WorkflowApprovalDataTable::class);
        $moduleKey = $dataTable->moduleKey($rkbu, $details);
        $module = $dataTable->moduleLabel($rkbu, $details);
        $detailColumns = $details->isNotEmpty() ? array_keys($this->approvalRow($moduleKey, $details->first())) : [];
        $detailRows = $details->map(fn ($detail) => $this->approvalRow($moduleKey, $detail));
        $attachments = $details->flatMap(function ($detail) {
            return collect(range(1, 5))->map(function ($number) use ($detail) {
                $path = $detail->{'upload_file_' . $number} ?? null;

                return $path ? [
                    'label' => 'Lampiran ' . $number,
                    'name' => basename($path),
                    'url' => asset('storage/' . $path),
                ] : null;
            })->filter();
        })->values();

        return Pdf::loadView('workflow.approval.pdf', [
            'approval' => $approval,
            'rkbu' => $rkbu,
            'module' => $module,
            'detailColumns' => $detailColumns,
            'detailRows' => $detailRows,
            'attachments' => $attachments,
            'workflowInstances' => $rkbu->workflowInstances->sortBy('created_at'),
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape')
            ->stream('approval-rkbu-' . $rkbu->nomor_rkbu . '.pdf');
    }

    public function printAll(Request $request)
    {
        $this->abortAdministrator();

        $filters = $request->validate([
            'sumber_dana_id' => ['nullable', 'uuid', Rule::exists('sumber_danas', 'id_sumber_dana')],
            'sub_kategori_rkbu_id' => ['nullable', 'uuid', Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu')],
            'approval_status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'revision'])],
        ]);
        $module = $request->query('module', 'all');
        abort_unless(in_array($module, ['all', 'barjas', 'pegawai', 'persediaan', 'modal', 'modal-kantor', 'modal-alkes'], true), 404);

        $isSuperAdmin = $this->isSuperAdmin();
        $dataTable = app(WorkflowApprovalDataTable::class);
        $approvalQuery = $isSuperAdmin
            ? $dataTable->approvalsForCurrentYear()
            : $dataTable->approvalsForCurrentPosition($this->currentValidatorAssignment());

        if (! empty($filters['approval_status'])) {
            $approvalQuery->where('status', $filters['approval_status']);
        } elseif (! $isSuperAdmin) {
            $approvalQuery->whereIn('status', ['pending', 'approved']);
        }

        if ($module !== 'all' || ! empty($filters['sumber_dana_id']) || ! empty($filters['sub_kategori_rkbu_id'])) {
            $approvalQuery->whereHas('workflowInstance.rkbu', function ($rkbuQuery) use ($module, $filters, $dataTable) {
                if (! empty($filters['sumber_dana_id'])) {
                    $rkbuQuery->where('sumber_dana_id', $filters['sumber_dana_id']);
                }
                if (! empty($filters['sub_kategori_rkbu_id'])) {
                    $rkbuQuery->where('sub_kategori_rkbu_id', $filters['sub_kategori_rkbu_id']);
                }
                if ($module !== 'all') {
                    $dataTable->filterByModule($rkbuQuery, $module);
                }
            });
        }

        $rkbuIds = $approvalQuery->with('workflowInstance')
            ->get()
            ->map(fn ($approval) => $approval->workflowInstance?->dokumen_id)
            ->filter()
            ->unique()
            ->values();

        $records = Rkbu::query()->with([
            'unit',
            'tahunAnggaran',
            'creator',
            'sumberDana',
            'subKegiatan.kegiatan.program',
            'subKegiatan.aktivitas.program',
            'subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'subKategoriRkbu.sub_kategori_rekening.kategori_rekening',
            'jenisBelanja',
            'detailBarjas',
            'detailPegawai',
            'detailPersediaan',
            'detailModal',
            'detailModalAlkes',
            'workflowInstances.approvals.validatorUser',
            'workflowInstances.approvals.position',
            'workflowInstances.approvals.workflowStep.position',
            'workflowInstances.approvals.workflowStep.validatorRole',
            'workflowInstances.approvals.logs.user',
            'workflowInstances.approvals.logs.position',
        ])
            ->whereIn('id', $rkbuIds)
            ->orderBy('nomor_rkbu')
            ->get();

        $items = $records->map(function (Rkbu $rkbu) use ($dataTable) {
            $details = $dataTable->detailsForRkbu($rkbu);
            $moduleKey = $dataTable->moduleKey($rkbu, $details);
            $moduleLabel = $dataTable->moduleLabel($rkbu, $details);
            $rows = $details->map(fn ($detail) => $this->approvalRow($moduleKey, $detail));
            $attachments = $details->flatMap(function ($detail) {
                return collect(range(1, 5))->map(function ($number) use ($detail) {
                    $path = $detail->{'upload_file_' . $number} ?? null;

                    return $path ? [
                        'label' => 'Lampiran ' . $number,
                        'name' => basename($path),
                        'url' => asset('storage/' . $path),
                    ] : null;
                })->filter();
            })->values();

            return [
                'rkbu' => $rkbu,
                'module' => $moduleLabel,
                'columns' => $rows->isNotEmpty() ? array_keys($rows->first()) : [],
                'details' => $rows,
                'workflowInstances' => $rkbu->workflowInstances->sortBy('created_at'),
                'attachments' => $attachments,
            ];
        });

        return Pdf::loadView('workflow.approval.print_all', [
            'items' => $items,
            'filters' => $filters,
            'module' => $module,
            'judulHeader' => JudulHeader::query()->first(),
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape')
            ->stream('daftar-approval-rkbu-' . now()->format('Ymd-His') . '.pdf');
    }

    private function activeAssignmentsForWorkflowStep(WorkflowStep $step, Position $currentPosition)
    {
        $query = $this->activeAssignmentsQuery();
        $validatorType = $step->validator_type
            ?? ($step->position_id ? WorkflowStep::TYPE_POSITION : WorkflowStep::TYPE_HIERARCHY);

        if ($step->position_id) {
            return $query->where('position_id', $step->position_id)->get();
        }

        if ($validatorType === WorkflowStep::TYPE_HIERARCHY) {
            $position = $currentPosition;
            $hierarchyLevel = max(1, (int) $step->hierarchy_level);

            for ($level = 0; $level < $hierarchyLevel; $level++) {
                if (! $position->parent_id) {
                    return collect();
                }

                $position = $position->parent;
            }

            return $query->where('position_id', $position->id)->get();
        }

        if ($validatorType === WorkflowStep::TYPE_ROLE && $step->role_id) {
            return $query->whereHas(
                'user.roles',
                fn(Builder $roleQuery) => $roleQuery->whereKey($step->role_id)
            )->get();
        }

        if ($validatorType === WorkflowStep::TYPE_USER && $step->validator_role_id) {
            return $query->whereHas(
                'user.validatorRoles',
                fn(Builder $roleQuery) => $roleQuery->whereKey($step->validator_role_id)
            )->get();
        }

        return collect();
    }

    private function activeAssignmentsQuery(): Builder
    {
        return PositionAssignment::query()
            ->with(['user', 'position'])
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', today())
            ->where(function (Builder $query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            })
            ->whereHas('user', fn(Builder $query) => $query->where('status_user', 'aktif'))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('created_at');
    }

    private function editableDetailFields($detail): array
    {
        return collect($detail->getFillable())
            ->reject(fn($field) => in_array($field, ['id', 'rkbu_id'], true)
                || str_starts_with($field, 'upload_file_'))
            ->mapWithKeys(fn($field) => [$field => $detail->{$field}])
            ->all();
    }

    private function isDetailLockedByUsulan($detail): bool
    {
        $isSelectedDirectly = DB::table('usulan_belanja_items')
            ->where('detail_id', $detail->getKey())
            ->exists();

        if ($isSelectedDirectly) {
            return true;
        }

        return DB::table('usulan_belanja_items')
                ->where('rkbu_id', $detail->rkbu_id)
                ->whereNull('detail_id')
                ->exists()
            || DB::table('usulan_belanjas')
                ->where('rkbu_id', $detail->rkbu_id)
                ->exists();
    }

    private function sameDetailValue(string $field, $submitted, $current): bool
    {
        if (in_array($field, self::NUMERIC_DETAIL_FIELDS, true)) {
            return (float) ($submitted === '' || $submitted === null ? 0 : $submitted)
                === (float) ($current === '' || $current === null ? 0 : $current);
        }

        return (string) ($submitted ?? '') === (string) ($current ?? '');
    }

    public function updateRkbu(Request $request, $id)
    {
        $paguService = app(PaguAnggaranService::class);
        $isAdministrator = $paguService->isAdministrator();
        $approvalQuery = $isAdministrator
            ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
            : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($this->currentValidatorAssignment());
        $approval = $approvalQuery->with('workflowInstance.rkbu')->findOrFail($id);
        $rkbu = $approval->workflowInstance?->rkbu;
        abort_unless($rkbu, 404, 'Data RKBU tidak ditemukan.');
        $phaseOrder = $paguService->phaseOrder($rkbu->tahun_anggaran_id);
        $canEditHeader = auth()->user()->hasRole('Super Admin');
        if ($phaseOrder >= 2) {
            abort_unless($isAdministrator, 403, 'Perubahan RKBU fase ini hanya dapat dilakukan Administrator.');
        } else {
            abort_unless($isAdministrator || $approval->status === 'pending', 403, 'RKBU hanya dapat diubah oleh validator aktif pada fase perencanaan.');
        }

        $rules = [
            'details' => ['required', 'array'],
            'details.*.id' => ['required', 'uuid', 'distinct'],
            'details.*.fields' => ['required', 'array'],
            'details.*.fields.*' => ['nullable', 'string', 'max:5000'],
        ];
        if ($phaseOrder >= 2) {
            $rules['alasan_pergeseran'] = ['required', 'string', 'max:5000'];
            $rules['lampiran_pergeseran'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
        }
        if ($canEditHeader) {
            $rules['sumber_dana_id'] = ['required', 'uuid', Rule::exists('sumber_danas', 'id_sumber_dana')];
            $rules['sub_kegiatan_id'] = ['required', 'uuid', Rule::exists('sub_kegiatans', 'id_sub_kegiatan')];
        }
        $validated = $request->validate($rules);

        if ($canEditHeader) {
            $subKegiatan = SubKegiatan::query()
                ->where('id_sub_kegiatan', $validated['sub_kegiatan_id'])
                ->where('id_sumber_dana', $validated['sumber_dana_id'])
                ->first();
            abort_unless($subKegiatan, 422, 'Sub kegiatan harus menggunakan sumber dana yang sama.');
        }

        $rkbuDetails = app(WorkflowApprovalDataTable::class)->detailsForRkbu($rkbu)->keyBy(fn($detail) => (string) $detail->getKey());
        $snapshot = fn () => $rkbuDetails->map(fn ($detail) => [
            'id' => $detail->getKey(),
            'nama_barang' => $detail->nama_barang ?? $detail->nama_pegawai ?? null,
            'volume' => $detail->jumlah_volume ?? null,
            'volume_1' => $detail->volume_1 ?? null,
            'volume_2' => $detail->volume_2 ?? null,
            'harga_satuan' => $detail->harga_satuan ?? null,
            'ppn' => $detail->ppn ?? null,
            'total_anggaran' => $detail->total_anggaran ?? null,
        ])->values()->all();
        $before = $snapshot();
        $beforeTotal = (float) collect($before)->sum('total_anggaran');
        $attachment = $request->hasFile('lampiran_pergeseran')
            ? $request->file('lampiran_pergeseran')->store('pagu/pergeseran', 'public')
            : null;

        DB::transaction(function () use ($validated, $rkbu, $rkbuDetails, $paguService, $phaseOrder, $before, $beforeTotal, $attachment, $canEditHeader) {
            if ($canEditHeader) {
                $rkbu->update([
                    'sumber_dana_id' => $validated['sumber_dana_id'],
                    'sub_kegiatan_id' => $validated['sub_kegiatan_id'],
                ]);
            }

            foreach ($validated['details'] as $submittedDetail) {
                $detail = $rkbuDetails->get($submittedDetail['id']);
                abort_unless($detail, 422, 'Detail RKBU yang dikirim tidak sesuai dengan dokumen ini.');

                $fields = $submittedDetail['fields'];
                $allowedFields = array_keys($this->editableDetailFields($detail));
                abort_if(array_diff(array_keys($fields), $allowedFields), 422, 'Terdapat field detail RKBU yang tidak diizinkan.');
                if ($this->isDetailLockedByUsulan($detail)) {
                    $changedFields = collect($fields)->filter(function ($value, $field) use ($detail) {
                        return ! $this->sameDetailValue($field, $value, $detail->{$field});
                    });
                    abort_if($changedFields->isNotEmpty(), 422, 'Item "' . ($detail->nama_barang ?? $detail->nama_pegawai ?? 'RKBU') . '" sudah masuk Usulan Belanja dan terkunci.');

                    continue;
                }
                foreach ($fields as $field => $value) {
                    abort_if(
                        in_array($field, self::NUMERIC_DETAIL_FIELDS, true)
                            && $value !== null
                            && $value !== ''
                            && ! is_numeric($value),
                        422,
                        'Field ' . str_replace('_', ' ', $field) . ' harus berupa angka.'
                    );
                }

                $detail->fill($fields);

                if ($detail->getTable() === 'rkbu_detail_pegawais') {
                    $koefisienGaji = (float) ($detail->koefisien_gaji ?? 0);
                    $koefisienRemunerasi = (float) ($detail->koefisien_remunerasi ?? 0);
                    $totalGajiPokok = (float) ($detail->gaji_pokok ?? 0) * $koefisienGaji;
                    $totalRemunerasi = (float) ($detail->remunerasi ?? 0) * $koefisienRemunerasi;
                    $bpjsTotal = (float) ($detail->gaji_pokok ?? 0) * (
                        (float) ($detail->bpjs_kesehatan ?? 0)
                        + (float) ($detail->bpjs_tk ?? 0)
                        + (float) ($detail->bpjs_jht ?? 0)
                    ) / 100 * $koefisienGaji;

                    $detail->jumlah_volume = $koefisienRemunerasi;
                    $detail->total_gaji_pokok = $totalGajiPokok;
                    $detail->total_remunerasi = $totalRemunerasi;
                    $detail->total_anggaran = $totalGajiPokok + $totalRemunerasi + $bpjsTotal;
                } else {
                    $quantity = (float) ($detail->jumlah_volume ?? 0);
                    if ($detail->volume_1 !== null && $detail->volume_2 !== null) {
                        $quantity = (float) $detail->volume_1 * (float) $detail->volume_2;
                        $detail->jumlah_volume = $quantity;
                    }

                    if ($detail->harga_satuan !== null) {
                        $subtotal = $quantity * (float) $detail->harga_satuan;
                        $detail->total_anggaran = $subtotal * (1 + (float) ($detail->ppn ?? 0) / 100);
                    }
                }

                $detail->sisa_vol_rkbu = $detail->jumlah_volume;
                $detail->sisa_anggaran_rkbu = $detail->total_anggaran;
                $detail->save();
            }

            $rkbu->refresh();
            $paguService->assertRkbuWithinPagu($rkbu);
            if ($phaseOrder >= 2) {
                $beforeById = collect($before)->keyBy('id');
                $after = $rkbuDetails->map(function ($detail) use ($beforeById) {
                    $total = (float) ($detail->total_anggaran ?? 0);
                    $beforeTotalItem = (float) ($beforeById->get($detail->getKey())['total_anggaran'] ?? 0);

                    return [
                        'id' => $detail->getKey(),
                        'nama_barang' => $detail->nama_barang ?? $detail->nama_pegawai ?? null,
                        'volume' => $detail->jumlah_volume ?? null,
                        'volume_1' => $detail->volume_1 ?? null,
                        'volume_2' => $detail->volume_2 ?? null,
                        'harga_satuan' => $detail->harga_satuan ?? null,
                        'ppn' => $detail->ppn ?? null,
                        'total_anggaran' => $total,
                        'selisih_total' => $total - $beforeTotalItem,
                    ];
                })->values()->all();
                $afterTotal = (float) collect($after)->sum('total_anggaran');
                $accountId = $paguService->resolveAccountId($rkbu);
                $pagu = \App\Models\MasterBackend\SettingInput\PaguAnggaran::query()
                    ->where('tahun_anggaran_id', $rkbu->tahun_anggaran_id)
                    ->where('id_kode_rekening_belanja', $accountId)->first();
                $paguService->recordChange([
                    'pagu_anggaran_id' => $pagu?->id,
                    'tahun_anggaran_id' => $rkbu->tahun_anggaran_id,
                    'id_kode_rekening_belanja' => $accountId,
                    'user_id' => auth()->id(),
                    'rkbu_id' => $rkbu->id,
                    'fase_urutan' => $phaseOrder,
                    'jenis_perubahan' => 'pergeseran_rkbu',
                    'alasan' => $validated['alasan_pergeseran'],
                    'lampiran' => $attachment,
                    'nilai_sebelum' => $beforeTotal,
                    'nilai_sesudah' => $afterTotal,
                    'selisih' => $afterTotal - $beforeTotal,
                    'detail_sebelum' => $before,
                    'detail_sesudah' => $after,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Data RKBU berhasil diperbarui.',
        ]);
    }

    private function approvalRow(string $module, $detail): array
    {
        $fields = match ($module) {
            'pegawai' => [
                'Nama Pegawai' => 'nama_pegawai',
                'Tempat Lahir' => 'tempat_lahir',
                'Tanggal Lahir' => 'tanggal_lahir',
                'Pendidikan' => 'pendidikan',
                'Jabatan' => 'jabatan',
                'Status Kawin' => 'status_kawin',
                'Nomor Kontrak' => 'nomor_kontrak',
                'TMT Pegawai' => 'tmt_pegawai',
                'Bulan TMT' => 'bulan_tmt',
                'Tahun TMT' => 'tahun_tmt',
                'Jumlah Bulan' => 'jumlah_volume',
                'Gaji Pokok' => 'gaji_pokok',
                'Koefisien Gaji' => 'koefisien_gaji',
                'Total Gaji Pokok' => 'total_gaji_pokok',
                'Remunerasi' => 'remunerasi',
                'Koefisien Remunerasi' => 'koefisien_remunerasi',
                'Total Remunerasi' => 'total_remunerasi',
                'BPJS Kesehatan (%)' => 'bpjs_kesehatan',
                'BPJS TK (%)' => 'bpjs_tk',
                'BPJS JHT (%)' => 'bpjs_jht',
                'Sisa Volume RKBU' => 'sisa_vol_rkbu',
                'Sisa Anggaran RKBU' => 'sisa_anggaran_rkbu',
                'Total Anggaran' => 'total_anggaran',
                'Status Komponen' => 'status_komponen',
            ],
            'persediaan' => [
                'Nama Barang' => 'nama_barang',
                'Spesifikasi' => 'spesifikasi',
                'Satuan' => 'satuan_1',
                'Jumlah Volume' => 'jumlah_volume',
                'Harga Satuan' => 'harga_satuan',
                'PPN (%)' => 'ppn',
                'Total Anggaran' => 'total_anggaran',
                'Rating' => 'rating',
                'Link E-Katalog' => 'link_ekatalog',
                'Keterangan Status' => 'keterangan_status',
                'Penempatan' => 'penempatan',
                'Stok' => 'stok',
                'Rata-rata Pemakaian' => 'rata_rata_pemakaian',
                'Kebutuhan per Bulan' => 'kebutuhan_per_bulan',
                'Buffer' => 'buffer',
                'Pengadaan Sebelumnya' => 'pengadaan_sebelumnya',
                'Proyeksi Sisa Stok' => 'proyeksi_sisa_stok',
                'Kebutuhan + Buffer' => 'kebutuhan_plus_buffer',
                'Kebutuhan Tahun X1' => 'kebutuhan_tahun_x1',
                'Rencana Pengadaan Tahun X1' => 'rencana_pengadaan_tahun_x1',
                'Sisa Volume RKBU' => 'sisa_vol_rkbu',
                'Sisa Anggaran RKBU' => 'sisa_anggaran_rkbu',
                'Status Komponen' => 'status_komponen',
            ],
            'modal-kantor', 'modal-alkes' => [
                'Nama Barang' => 'nama_barang',
                'Spesifikasi' => 'spesifikasi',
                'Volume 1' => 'volume_1',
                'Satuan 1' => 'satuan_1',
                'Volume 2' => 'volume_2',
                'Satuan 2' => 'satuan_2',
                'Jumlah Volume' => 'jumlah_volume',
                'Harga Satuan' => 'harga_satuan',
                'PPN (%)' => 'ppn',
                'Total Anggaran' => 'total_anggaran',
                'Rating' => 'rating',
                'Link E-Katalog' => 'link_ekatalog',
                'Keterangan Status' => 'keterangan_status',
                'Penempatan' => 'penempatan',
                'Sisa Volume RKBU' => 'sisa_vol_rkbu',
                'Sisa Anggaran RKBU' => 'sisa_anggaran_rkbu',
                'Standar Kebutuhan' => 'standar_kebutuhan',
                'Eksisting' => 'eksisting',
                'Kondisi Baik' => 'kondisi_baik',
                'Kondisi Rusak Berat' => 'kondisi_rusak_berat',
                'Status Komponen' => 'status_komponen',
            ],

            default => [
                'Nama Barang' => 'nama_barang',
                'Spesifikasi' => 'spesifikasi',
                'Volume 1' => 'volume_1',
                'Satuan 1' => 'satuan_1',
                'Volume 2' => 'volume_2',
                'Satuan 2' => 'satuan_2',
                'Jumlah Volume' => 'jumlah_volume',
                'Harga Satuan' => 'harga_satuan',
                'PPN (%)' => 'ppn',
                'Total Anggaran' => 'total_anggaran',
                'Rating' => 'rating',
                'Link E-Katalog' => 'link_ekatalog',
                'Keterangan Status' => 'keterangan_status',
                'Penempatan' => 'penempatan',
                'Sisa Volume RKBU' => 'sisa_vol_rkbu',
                'Sisa Anggaran RKBU' => 'sisa_anggaran_rkbu',
                'Status Komponen' => 'status_komponen',
            ],
        };

        return collect($fields)
            ->mapWithKeys(fn($attribute, $label) => [$label => $detail->{$attribute}])
            ->all();
    }

    private function currentValidatorAssignment(): PositionAssignment
    {
        $this->abortAdministrator();

        $assignment = app(PositionAssignmentService::class)->getCurrentAssignment();

        abort_unless($assignment, 403, 'User tidak memiliki assignment jabatan yang aktif.');

        return $assignment;
    }

    private function logApprovalAction(
        Approval $approval,
        ?PositionAssignment $assignment,
        string $action,
        ?string $note
    ): void {
        ApprovalLog::create([
            'approval_id' => $approval->id,
            'workflow_instance_id' => $approval->workflow_instance_id,
            'user_id' => auth()->id(),
            'position_id' => $assignment?->position_id ?? $approval->position_id,
            'position_assignment_id' => $assignment?->id ?? $approval->position_assignment_id,
            'aksi' => $action,
            'catatan' => $note,
            'aksi_at' => now(),
        ]);
    }

    public function approve(Request $request, $id)
    {
        $this->abortAdministrator();

        DB::beginTransaction();
        try {
            $isSuperAdmin = $this->isSuperAdmin();
            $assignment = $isSuperAdmin
                ? app(PositionAssignmentService::class)->getCurrentAssignment()
                : $this->currentValidatorAssignment();
            $approvalQuery = $isSuperAdmin
                ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
                : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($assignment);
            $approval = $approvalQuery->with([
                'workflowInstance.rkbu',
                'workflowStep',
                'validatorUser.position',
                'position',
                'workflowInstance.currentPosition',
            ])
                ->findOrFail($id);
            if ($approval->status != 'pending') {
                throw new Exception('Approval sudah diproses.');
            }

            $approvalRkbu = $approval->workflowInstance?->rkbu;
            if ($approvalRkbu) {
                $paguService = app(PaguAnggaranService::class);
                if ($paguService->phaseOrder($approvalRkbu->tahun_anggaran_id) >= 2) {
                    abort_unless($paguService->isAdministrator(), 403, 'Approval RKBU pada fase perubahan hanya dapat dilakukan Administrator.');
                    $paguService->assertRkbuWithinPagu($approvalRkbu);
                }
            }

            $currentPosition = $approval->position
                ?? $approval->validatorUser?->position
                ?? $assignment?->position
                ?? $approval->workflowInstance?->currentPosition;
            if (! $currentPosition) {
                throw new Exception('Posisi validator tidak ditemukan.');
            }

            $nextStep = WorkflowStep::where(
                'workflow_id',
                $approval->workflowStep->workflow_id
            )
                ->where(
                    'step_order',
                    '>',
                    $approval->workflowStep->step_order
                )
                ->orderBy('step_order')
                ->first();

            $nextAssignment = null;
            if ($nextStep) {
                $nextAssignments = $this->activeAssignmentsForWorkflowStep($nextStep, $currentPosition);
                if ($nextAssignments->isEmpty()) {
                    throw new Exception('Tidak ada pejabat aktif yang sesuai dengan workflow step berikutnya.');
                }

                if ($isSuperAdmin) {
                    $nextAssignmentId = $request->input('next_assignment_id');
                    if (! $nextAssignmentId) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Pilih pejabat pada jabatan hirarki berikutnya.',
                        ], 422);
                    }

                    $nextAssignment = $nextAssignments->firstWhere('id', $nextAssignmentId);
                    if (! $nextAssignment) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Pejabat yang dipilih tidak sesuai dengan workflow step berikutnya.',
                        ], 422);
                    }
                } else {
                    $nextAssignment = $nextAssignments->first();
                }
            }

            /**
             * Approval sekarang
             */
            $approval->update([
                'status' => 'approved',
                'catatan' => $request->note,
                'approved_at' => now(),
                'closed_at' => now(),
                'is_current' => ! $nextStep,
            ]);
            $this->logApprovalAction($approval, $assignment, 'approved', $request->note);

            if (! $nextStep) {
                $approval->workflowInstance->update([
                    'status' => 'approved',
                    'current_position_id' => null,
                ]);
                $approval->workflowInstance
                    ->rkbu
                    ->update([
                        'status' => 'approved',
                    ]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Approval selesai.',

                ]);
            }

            /**
             * Approval Baru
             */
            Approval::create([
                'workflow_instance_id' => $approval->workflow_instance_id,
                'workflow_step_id' => $nextStep->id,
                'validator_user_id' => $nextAssignment->user_id,
                'position_id' => $nextAssignment->position_id,
                'position_assignment_id' => $nextAssignment->id,
                'sequence' => (int) $approval->sequence + 1,
                'is_current' => true,
                'received_at' => now(),
                'status' => 'pending',
            ]);

            /**
             * Update Workflow
             */
            $approval->workflowInstance->update([
                'status' => 'proses',
                'current_step_id' => $nextStep->id,
                'current_position_id' => $nextAssignment->position_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Approval berhasil.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $this->abortAdministrator();

        DB::beginTransaction();

        try {
            $isSuperAdmin = $this->isSuperAdmin();
            $assignment = $isSuperAdmin
                ? app(PositionAssignmentService::class)->getCurrentAssignment()
                : $this->currentValidatorAssignment();
            $approvalQuery = $isSuperAdmin
                ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
                : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($assignment);
            $approval = $approvalQuery->with('workflowInstance')
                ->where('status', 'pending')
                ->findOrFail($id);

            $approval->update([
                'status' => 'rejected',
                'catatan' => $request->note,
                'approved_at' => now(),
            ]);
            $this->logApprovalAction($approval, $assignment, 'rejected', $request->note);

            $approval->workflowInstance->update([
                'status' => 'rejected',
            ]);

            $approval->workflowInstance->rkbu()->update([
                'status' => 'rejected',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function revision(Request $request, $id)
    {
        $this->abortAdministrator();

        DB::beginTransaction();

        try {

            $request->validate([
                'note' => 'required',
            ]);

            $isSuperAdmin = $this->isSuperAdmin();
            $assignment = $isSuperAdmin
                ? app(PositionAssignmentService::class)->getCurrentAssignment()
                : $this->currentValidatorAssignment();
            $approvalQuery = $isSuperAdmin
                ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
                : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($assignment);
            $approval = $approvalQuery->with([
                'workflowInstance.rkbu',
            ])
                ->where('status', 'pending')
                ->findOrFail($id);

            $approval->update([
                'status' => 'revision',
                'catatan' => $request->note,
                'approved_at' => now(),
            ]);
            $this->logApprovalAction($approval, $assignment, 'revision', $request->note);

            $approval->workflowInstance->update([
                'status' => 'revision',
            ]);

            if ($approval->workflowInstance->rkbu) {

                $approval->workflowInstance
                    ->rkbu
                    ->update([
                        'status' => 'revision',
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen dikembalikan untuk revisi',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $this->abortAdministrator();

        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|string|distinct',
            'action' => 'required|in:approve,revision,reject',
            'note' => 'nullable|string|max:5000',
        ]);

        if (in_array($validated['action'], ['revision', 'reject'], true) && trim($validated['note'] ?? '') === '') {
            return response()->json([
                'success' => false,
                'message' => 'Catatan wajib diisi untuk revision atau reject.',
            ], 422);
        }

        if ($this->isSuperAdmin() && $validated['action'] === 'approve') {
            return response()->json([
                'success' => false,
                'message' => 'Approval Super Admin harus dilakukan dari detail agar pejabat hirarki berikutnya dapat dipilih.',
            ], 422);
        }

        $results = [];
        $isSuperAdmin = $this->isSuperAdmin();
        $assignment = $isSuperAdmin
            ? app(PositionAssignmentService::class)->getCurrentAssignment()
            : $this->currentValidatorAssignment();
        foreach ($validated['ids'] as $id) {
            $approval = ($isSuperAdmin
                ? app(WorkflowApprovalDataTable::class)->approvalsForCurrentYear()
                : app(WorkflowApprovalDataTable::class)->approvalsForCurrentPosition($assignment))->where('id', $id)
                ->where('status', 'pending')
                ->first();

            if (! $approval) {
                $results[] = [
                    'id' => $id,
                    'success' => false,
                    'message' => 'Approval tidak ditemukan, bukan milik Anda, atau sudah diproses.',
                ];

                continue;
            }

            $response = match ($validated['action']) {
                'approve' => $this->approve($request, $id),
                'revision' => $this->revision($request, $id),
                'reject' => $this->reject($request, $id),
            };
            $payload = $response->getData(true);
            $results[] = [
                'id' => $id,
                'success' => $response->getStatusCode() < 400 && ($payload['success'] ?? false),
                'message' => $payload['message'] ?? (($payload['success'] ?? false) ? 'Berhasil diproses.' : 'Gagal diproses.'),
            ];
        }

        $successCount = collect($results)->where('success', true)->count();

        return response()->json([
            'success' => $successCount === count($results),
            'message' => "{$successCount} dari " . count($results) . ' approval berhasil diproses.',
            'results' => $results,
        ]);
    }
}
