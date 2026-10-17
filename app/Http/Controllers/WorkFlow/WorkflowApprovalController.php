<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use App\Models\MasterBackend\SettingRkbu\SubKegiatan;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use App\Models\Rkbu\Rkbu;
use App\Models\WorkFlow\Approval;
use App\Models\WorkFlow\ApprovalLog;
use App\Models\WorkFlow\WorkflowStep;
use App\Services\Workflow\PositionAssignmentService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

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

    public function index(Request $request)
    {
        $this->abortAdministrator();
        $module = $request->query('module', 'all');
        abort_unless(in_array($module, ['all', 'barjas', 'pegawai', 'persediaan', 'modal', 'modal-kantor', 'modal-alkes'], true), 404);

        return view(
            'workflow.approval.index',
            [
                'title' => 'Approval Saya',
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
        return auth()->user()->hasRole('Super Admin');
    }

    public function getData(Request $request)
    {
        $this->abortAdministrator();

        $isSuperAdmin = $this->isSuperAdmin();
        $assignment = $isSuperAdmin
            ? null
            : app(PositionAssignmentService::class)->getCurrentAssignment();

        if (! $assignment && ! $isSuperAdmin) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'notice' => 'Akun Anda tidak memiliki assignment jabatan yang aktif. Hubungi administrator untuk memperbarui penugasan pejabat.',
            ]);
        }

        $filters = $request->validate([
            'sumber_dana_id' => [
                'nullable',
                'uuid',
                Rule::exists('sumber_danas', 'id_sumber_dana'),
            ],
            'sub_kategori_rkbu_id' => [
                'nullable',
                'uuid',
                Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu'),
            ],
            'approval_status' => [
                'nullable',
                Rule::in(['pending', 'approved', 'rejected', 'revision']),
            ],
        ]);

        $data = ($isSuperAdmin
            ? $this->approvalsForCurrentYear()
            : $this->approvalsForCurrentPosition($assignment))->with([
            'workflowInstance.rkbu.creator',
            'workflowInstance.rkbu.unit',
            'workflowInstance.rkbu.sumberDana',
            'workflowInstance.rkbu.detailBarjas',
            'workflowInstance.rkbu.detailPegawai',
            'workflowInstance.rkbu.detailPersediaan',
            'workflowInstance.rkbu.detailModal',
            'workflowInstance.rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'workflowInstance.rkbu.subKategoriRkbu',
            'workflowInstance.approvals.position',
            'workflowInstance.approvals.validatorUser',
            'workflowInstance.approvals.workflowStep.validatorRole',
            'workflowInstance.approvals.workflowStep.position',
            'workflowStep',
            'validatorUser',
        ]);

        if (! empty($filters['approval_status'])) {
            $data->where('status', $filters['approval_status']);
        } elseif (! $isSuperAdmin) {
            $data->whereIn('status', ['pending', 'approved']);
        }

        $module = $request->query('module', 'all');
        abort_unless(in_array($module, ['all', 'barjas', 'pegawai', 'persediaan', 'modal', 'modal-kantor', 'modal-alkes'], true), 404);
        if ($module !== 'all' || ! empty($filters['sumber_dana_id']) || ! empty($filters['sub_kategori_rkbu_id'])) {
            $data->whereHas('workflowInstance.rkbu', function ($rkbuQuery) use ($module, $filters) {
                if (! empty($filters['sumber_dana_id'])) {
                    $rkbuQuery->where('sumber_dana_id', $filters['sumber_dana_id']);
                }

                if (! empty($filters['sub_kategori_rkbu_id'])) {
                    $rkbuQuery->where('sub_kategori_rkbu_id', $filters['sub_kategori_rkbu_id']);
                }

                if ($module !== 'all') {
                    $this->filterByModule($rkbuQuery, $module);
                }
            });
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->filterColumn('nama_komponen', function ($query, $keyword) {
                $query->whereHas('workflowInstance.rkbu', function ($rkbuQuery) use ($keyword) {
                    $rkbuQuery->where('nomor_rkbu', 'like', "%{$keyword}%")
                        ->orWhereHas('detailBarjas', function ($detailQuery) use ($keyword) {
                            $detailQuery->where('nama_barang', 'like', "%{$keyword}%")
                                ->orWhere('spesifikasi', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('detailPersediaan', function ($detailQuery) use ($keyword) {
                            $detailQuery->where('nama_barang', 'like', "%{$keyword}%")
                                ->orWhere('spesifikasi', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('detailModal', function ($detailQuery) use ($keyword) {
                            $detailQuery->where('nama_barang', 'like', "%{$keyword}%")
                                ->orWhere('spesifikasi', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('detailPegawai', function ($detailQuery) use ($keyword) {
                            $detailQuery->where('nama_pegawai', 'like', "%{$keyword}%")
                                ->orWhere('jabatan', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('creator', function ($creatorQuery) use ($keyword) {
                            $creatorQuery->where('nama', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('unit', function ($unitQuery) use ($keyword) {
                            $unitQuery->where('nama_unit', 'like', "%{$keyword}%");
                        });
                });
            })
            ->addColumn('checkbox', function ($row) {
                return '
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input approval-row-checkbox" type="checkbox" value="' . e($row->id) . '" ' .
                    ($row->status === 'pending' ? '' : 'disabled') . '>
                    </div>
                ';
            })
            ->addColumn('nama_komponen', function ($row) {
                $rkbu = $row->workflowInstance?->rkbu;
                $details = $this->detailsForRkbu($rkbu);
                $detail = $details->first();
                $moduleLabel = $this->moduleLabel($rkbu, $details);
                $subcategory = $rkbu?->subKategoriRkbu;
                $subcategoryLabel = $subcategory
                    ? trim($subcategory->kode_sub_kategori_rkbu . ' - ' . $subcategory->nama_sub_kategori_rkbu)
                    : '-';
                $validationStatuses = ($row->workflowInstance?->approvals ?? collect())
                    ->sortBy('sequence')
                    ->map(function ($approval) {
                        $status = strtolower($approval->status);
                        $statusDisplay = [
                            'pending' => ['label' => 'Pending', 'class' => 'primary'],
                            'approved' => ['label' => 'Disetujui', 'class' => 'success'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'danger'],
                            'revision' => ['label' => 'Revisi', 'class' => 'warning'],
                        ][$status] ?? ['label' => ucfirst($status), 'class' => 'secondary'];
                        $validator = $approval->validatorUser?->nama
                            ?? $approval->position?->nama_jabatan
                            ?? $approval->workflowStep?->position?->nama_jabatan
                            ?? $approval->workflowStep?->validatorRole?->nama_validator
                            ?? 'Validator';

                        return '<span class="badge badge-light-' . $statusDisplay['class'] . ' me-2 mb-1">' .
                            e($validator . ': ' . $statusDisplay['label']) .
                            '</span>';
                    })
                    ->implode('');
                $statusIcon = $row->status === 'approved'
                    ? '<span class="svg-icon svg-icon-1 svg-icon-success" title="Disetujui atasan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
                            <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black"></path>
                        </svg>
                    </span>'
                    : '';

                return '
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                            <span class="badge badge-light-primary">Nomor RKBU: ' . e($rkbu?->nomor_rkbu ?? '-') . '</span>
                            <span class="badge badge-light-info">' . e($moduleLabel) . '</span>
                            <span class="badge badge-light-secondary">Subkategori: ' . e($subcategoryLabel) . '</span>
                            ' . $statusIcon . '
                        </div>
                        <div class="mb-3">
                            <span class="text-gray-700 fw-bold fs-7 me-2">Status Validasi:</span>
                            ' . ($validationStatuses !== '' ? $validationStatuses : '<span class="text-muted fs-7">Belum ada data approval.</span>') . '
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="fw-bold fs-5 text-gray-900">' . e($detail?->nama_barang ?? $detail?->nama_pegawai ?? '-') . '</div>
                                <div class="text-gray-700 mt-2">Spesifikasi / Jabatan: ' . e($detail?->spesifikasi ?? $detail?->jabatan ?? '-') . '</div>
                                <div class="text-gray-400 fw-bold fs-7 mt-2">Tanggal Dibuat: ' . e($detail?->created_at ?? $rkbu?->created_at ?? '-') . '</div>
                            </div>
                             <div class="mb-7 ps-10">
                                    <!--begin::Reply-->
                                    <div class="d-flex">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px me-3">
                                            <img src="' . asset('assets/media/avatars/150-8.jpg') . '"
                                                alt="" />
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column flex-row-fluid">
                                            <!--begin::Info-->
                                            <div class="d-flex align-items-center flex-wrap mb-1">
                                                <a href="#"
                                                    class="text-gray-800 text-hover-primary fw-bolder me-2">' . e($detail?->created_at ?? $rkbu?->created_at ?? '-') . '</a>
                                                <span class="text-gray-400 fw-bold fs-7">(Unit:
                                                    ' . e($rkbu?->unit?->nama_unit ?? '-') . ')</span>
                                                <a href="#"
                                                    class="ms-auto text-gray-400 text-hover-primary fw-bold fs-7">Reply</a>
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Post-->
                                            <span class="text-gray-800 fs-7 fw-normal pt-1">ID : ' . e($row->id) . '</span>
                                            <!--end::Post-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Reply-->
                                </div>
                                 <div class="d-flex flex-stack position-relative mt-8 mb-8">
                                <div class="position-absolute h-100 w-4px bg-warning rounded top-0 start-0">
                                </div>
                                <div class="fw-bold ms-6 w-100 text-gray-600">
                                    <div class="text-gray-800 mb-1">
                                        Sisa Volume : <span class="text-hover-primary">' . e($detail?->sisa_vol_rkbu ?? '-') . ' ' . e($detail?->satuan_1 ?? '') . ' x ' . e($detail?->volume_2 ?? '') . ' ' . e($detail?->satuan_2 ?? '') . '</span>
                                    </div>
                                    <div class="fw-bolder text-gray-800 text-hover-primary d-block">
                                        Sisa Anggaran :
                                        <span class="text-primary">Rp. ' . e(number_format((float) ($detail?->sisa_anggaran_rkbu ?? 0), 0, ',', '.')) . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->addColumn('satuan', function ($row) {
                $details = $this->detailsForRkbu($row->workflowInstance?->rkbu);
                $detail = $details->first();

                if ($detail && $detail->getTable() === 'rkbu_detail_pegawais') {
                    return '<div class="text-end text-dark fs-6">' .
                        e($detail->jumlah_volume ?? '-') . ' bulan</div>';
                }

                if ($detail && $detail->getTable() === 'rkbu_detail_persediaans') {
                    return '<div class="text-end text-dark fs-6">' .
                        e($detail->jumlah_volume ?? '-') . ' ' . e($detail->satuan_1 ?? '') . '</div>';
                }

                return '<div class="text-end text-dark fs-6">' .
                    e($detail?->volume_1 ?? $detail?->jumlah_volume ?? '-') . ' ' . e($detail?->satuan_1 ?? '') .
                    ($detail?->volume_2 ? ' x ' . e($detail->volume_2) . ' ' . e($detail->satuan_2 ?? '') : '') .
                    '</div>';
            })
            ->addColumn('harga_barang', function ($row) {
                $detail = $this->detailsForRkbu($row->workflowInstance?->rkbu)->first();
                $harga = $detail?->harga_satuan;
                if ($harga === null && (float) ($detail?->jumlah_volume ?? 0) > 0) {
                    $harga = (float) ($detail?->total_anggaran ?? 0) / (float) $detail->jumlah_volume;
                }

                return '<div class="text-end text-dark fw-bold fs-6">Rp.' .
                    number_format((float) $harga, 0, ',', '.') . '</div>';
            })
            ->addColumn('total', function ($row) {
                $total = $this->detailsForRkbu($row->workflowInstance?->rkbu)->sum('total_anggaran');

                return '<div class="text-end text-dark fw-bold fs-6">Rp.' .
                    number_format((float) $total, 0, ',', '.') . '</div>';
            })
            ->addColumn('action', function ($row) use ($isSuperAdmin) {
                $actions = '
                    <button type="button"
                        class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1 btn-detail"
                        data-id="' . e($row->id) . '"
                        data-url="' . e(route('workflowapproval.show', $row->id)) . '"
                        title="Detail">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11Z" fill="black"/>
                                <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22Z" fill="black"/>
                            </svg>
                        </span>
                    </button>';

                if ($row->status === 'pending' && ! $isSuperAdmin) {
                    $actions .= '
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 btn-approve" data-id="' . e($row->id) . '" data-url="' . e(route('workflowapproval.approve', $row->id)) . '" title="Approve">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 btn-revision" data-id="' . e($row->id) . '" data-url="' . e(route('workflowapproval.revision', $row->id)) . '" title="Revision">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm btn-reject" data-id="' . e($row->id) . '" data-url="' . e(route('workflowapproval.reject', $row->id)) . '" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>';
                }

                return '<div class="d-flex justify-content-end flex-shrink-0">' . $actions . '</div>';
            })
            ->rawColumns([
                'checkbox',
                'nama_komponen',
                'satuan',
                'harga_barang',
                'total',
                'action',
            ])
            ->make(true);
    }

    public function show($id)
    {
        $this->abortAdministrator();

        $isSuperAdmin = $this->isSuperAdmin();
        $approvalQuery = $isSuperAdmin
            ? $this->approvalsForCurrentYear()
            : $this->approvalsForCurrentPosition($this->currentValidatorAssignment());
        $approval = $approvalQuery->with([
            'workflowInstance.rkbu.detailBarjas',
            'workflowInstance.rkbu.detailPegawai',
            'workflowInstance.rkbu.detailPersediaan',
            'workflowInstance.rkbu.detailModal',
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
            'subKategoriRkbu.kategori.jenis_kategori_rkbu',
        ])->findOrFail(
            $approval->workflowInstance->dokumen_id
        );
        $details = $this->detailsForRkbu($rkbu);
        $module = $this->moduleKey($rkbu, $details);

        $detailRows = $details->map(fn($detail) => $this->approvalRow($module, $detail));
        $editableDetails = $isSuperAdmin
            ? $details->map(fn($detail) => [
                'id' => $detail->getKey(),
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
        if ($isSuperAdmin && $approval->status === 'pending' && $nextStep && $currentPosition?->parent_id) {
            $nextOfficials = $this->activeAssignmentsForPosition($currentPosition->parent_id)
                ->map(fn($official) => [
                    'id' => $official->id,
                    'label' => $official->user->nama . ' - ' . $official->position->nama_jabatan,
                ])
                ->values();
        }

        return response()->json([
            'approval' => $approval,
            'rkbu' => $rkbu,
            'module' => $this->moduleLabel($rkbu, $details),
            'detailColumns' => $detailRows->isNotEmpty() ? array_keys($detailRows->first()) : [],
            'details' => $detailRows->values(),
            'attachments' => $attachments,
            'catatan' => $approval->catatan,
            'canApprove' => $approval->status === 'pending',
            'canEdit' => $isSuperAdmin,
            'editableDetails' => $editableDetails,
            'sumberDanas' => $isSuperAdmin
                ? SumberDana::query()->orderBy('nama_sumber_dana')->get([
                    'id_sumber_dana',
                    'nama_sumber_dana',
                ])
                : collect(),
            'subKegiatans' => $isSuperAdmin
                ? SubKegiatan::query()->orderBy('kode_sub_kegiatan')->get([
                    'id_sub_kegiatan',
                    'kode_sub_kegiatan',
                    'nama_sub_kegiatan',
                    'id_sumber_dana',
                ])
                : collect(),
            'nextOfficials' => $nextOfficials,
            'requiresNextOfficial' => $isSuperAdmin && $approval->status === 'pending' && $nextStep && $currentPosition?->parent_id,
            'updateUrl' => route('workflowapproval.update-rkbu', $approval->id),
        ]);
    }

    private function activeAssignmentsForPosition(string $positionId)
    {
        return PositionAssignment::query()
            ->with(['user', 'position'])
            ->where('position_id', $positionId)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', today())
            ->where(function (Builder $query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', today());
            })
            ->whereHas('user', fn(Builder $query) => $query->where('status_user', 'aktif'))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('created_at')
            ->get();
    }

    private function editableDetailFields($detail): array
    {
        return collect($detail->getFillable())
            ->reject(fn($field) => in_array($field, ['id', 'rkbu_id', 'status_komponen'], true)
                || str_starts_with($field, 'upload_file_'))
            ->mapWithKeys(fn($field) => [$field => $detail->{$field}])
            ->all();
    }

    public function updateRkbu(Request $request, $id)
    {
        abort_unless($this->isSuperAdmin(), 403, 'Hanya Super Admin yang dapat mengubah data RKBU dari halaman approval.');

        $validated = $request->validate([
            'sumber_dana_id' => ['required', 'uuid', Rule::exists('sumber_danas', 'id_sumber_dana')],
            'sub_kegiatan_id' => ['required', 'uuid', Rule::exists('sub_kegiatans', 'id_sub_kegiatan')],
            'details' => ['required', 'array'],
            'details.*.id' => ['required', 'uuid', 'distinct'],
            'details.*.fields' => ['required', 'array'],
            'details.*.fields.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $subKegiatan = SubKegiatan::query()
            ->where('id_sub_kegiatan', $validated['sub_kegiatan_id'])
            ->where('id_sumber_dana', $validated['sumber_dana_id'])
            ->first();
        abort_unless($subKegiatan, 422, 'Sub kegiatan harus menggunakan sumber dana yang sama.');

        $approval = $this->approvalsForCurrentYear()->with('workflowInstance.rkbu')->findOrFail($id);
        $rkbu = $approval->workflowInstance?->rkbu;
        abort_unless($rkbu, 404, 'Data RKBU tidak ditemukan.');

        $rkbuDetails = $this->detailsForRkbu($rkbu)->keyBy(fn($detail) => (string) $detail->getKey());
        DB::transaction(function () use ($validated, $rkbu, $rkbuDetails) {
            $rkbu->update([
                'sumber_dana_id' => $validated['sumber_dana_id'],
                'sub_kegiatan_id' => $validated['sub_kegiatan_id'],
            ]);

            foreach ($validated['details'] as $submittedDetail) {
                $detail = $rkbuDetails->get($submittedDetail['id']);
                abort_unless($detail, 422, 'Detail RKBU yang dikirim tidak sesuai dengan dokumen ini.');

                $fields = $submittedDetail['fields'];
                $allowedFields = array_keys($this->editableDetailFields($detail));
                abort_if(array_diff(array_keys($fields), $allowedFields), 422, 'Terdapat field detail RKBU yang tidak diizinkan.');
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

                $detail->fill($fields)->save();
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

    private function detailsForRkbu(?Rkbu $rkbu)
    {
        if (! $rkbu) {
            return collect();
        }

        return $rkbu->detailBarjas
            ->concat($rkbu->detailPegawai)
            ->concat($rkbu->detailPersediaan)
            ->concat($rkbu->detailModal)
            ->values();
    }

    private function moduleKey(?Rkbu $rkbu, $details): string
    {
        if ($details->first()?->getTable() === 'rkbu_detail_pegawais') {
            return 'pegawai';
        }
        if ($details->first()?->getTable() === 'rkbu_detail_persediaans') {
            return 'persediaan';
        }
        if ($details->first()?->getTable() === 'rkbu_detail_modals') {
            $labels = strtolower(implode(' ', [
                $rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '',
                $rkbu?->subKategoriRkbu?->kategori?->nama_kategori_rkbu ?? '',
                $rkbu?->subKategoriRkbu?->kategori?->jenis_kategori_rkbu?->nama_jenis_kategori_rkbu ?? '',
            ]));

            return str_contains($labels, 'alkes') || str_contains($labels, 'alat kesehatan')
                ? 'modal-alkes'
                : 'modal-kantor';
        }

        return 'barjas';
    }

    private function moduleLabel(?Rkbu $rkbu, $details): string
    {
        return [
            'pegawai' => 'RKBU Pegawai',
            'persediaan' => 'RKBU Persediaan',
            'modal-alkes' => 'RKBU Modal Alkes',
            'modal-kantor' => 'RKBU Modal Kantor',
            'barjas' => 'RKBU Barjas',
        ][$this->moduleKey($rkbu, $details)];
    }

    private function filterByModule($query, string $module): void
    {
        match ($module) {
            'barjas' => $query->whereHas('detailBarjas'),
            'pegawai' => $query->whereHas('detailPegawai'),
            'persediaan' => $query->whereHas('detailPersediaan'),
            'modal' => $query->whereHas('detailModal'),
            'modal-kantor' => $query->whereHas('detailModal')
                ->whereHas('subKategoriRkbu', fn($subcategoryQuery) => $this->filterLabel($subcategoryQuery, ['kantor', 'peralatan kantor'])),
            'modal-alkes' => $query->whereHas('detailModal')
                ->whereHas('subKategoriRkbu', fn($subcategoryQuery) => $this->filterLabel($subcategoryQuery, ['alkes', 'alat kesehatan'])),
            default => null,
        };
    }

    private function approvalsForCurrentYear(): Builder
    {
        return Approval::query()->whereHas(
            'workflowInstance.rkbu',
            fn(Builder $query) => $query->where(
                'tahun_anggaran_id',
                session('tahun_anggaran_id')
            )
        );
    }

    private function currentValidatorAssignment(): PositionAssignment
    {
        $this->abortAdministrator();

        $assignment = app(PositionAssignmentService::class)->getCurrentAssignment();

        abort_unless($assignment, 403, 'User tidak memiliki assignment jabatan yang aktif.');

        return $assignment;
    }

    private function approvalsForCurrentPosition(PositionAssignment $assignment): Builder
    {
        return $this->approvalsForCurrentYear()->where(function (Builder $query) use ($assignment) {
            $query->where('position_id', $assignment->position_id)
                ->orWhere(function (Builder $legacyQuery) use ($assignment) {
                    $legacyQuery->whereNull('position_id')
                        ->whereHas(
                            'workflowInstance',
                            fn(Builder $instanceQuery) => $instanceQuery
                                ->where('current_position_id', $assignment->position_id)
                        );
                });
        });
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
            'position_id' => $assignment?->position_id,
            'position_assignment_id' => $assignment?->id,
            'aksi' => $action,
            'catatan' => $note,
            'aksi_at' => now(),
        ]);
    }

    private function filterLabel($query, array $labels): void
    {
        $query->where(function ($labelQuery) use ($labels) {
            foreach ($labels as $label) {
                $pattern = '%' . $label . '%';
                $labelQuery->orWhere('nama_sub_kategori_rkbu', 'like', $pattern)
                    ->orWhereHas('kategori', function ($categoryQuery) use ($pattern) {
                        $categoryQuery->where('nama_kategori_rkbu', 'like', $pattern)
                            ->orWhereHas('jenis_kategori_rkbu', function ($typeQuery) use ($pattern) {
                                $typeQuery->where('nama_jenis_kategori_rkbu', 'like', $pattern);
                            });
                    });
            }
        });
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
                ? $this->approvalsForCurrentYear()
                : $this->approvalsForCurrentPosition($assignment);
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
            if ($currentPosition->parent_id && $nextStep) {
                if ($isSuperAdmin) {
                    $nextAssignmentId = $request->input('next_assignment_id');
                    if (! $nextAssignmentId) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Pilih pejabat pada jabatan hirarki berikutnya.',
                        ], 422);
                    }

                    $nextAssignment = $this->activeAssignmentsForPosition($currentPosition->parent_id)
                        ->firstWhere('id', $nextAssignmentId);
                    if (! $nextAssignment) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Pejabat yang dipilih bukan pejabat aktif pada hirarki berikutnya.',
                        ], 422);
                    }
                } else {
                    $nextPosition = Position::find($currentPosition->parent_id);
                    if (! $nextPosition) {
                        throw new Exception('Parent Position tidak ditemukan.');
                    }

                    $nextAssignment = app(PositionAssignmentService::class)
                        ->getAssignmentByPosition($nextPosition->id);
                    if (! $nextAssignment) {
                        throw new Exception(
                            'Belum ada pejabat aktif pada jabatan ' . $nextPosition->nama_jabatan . '.'
                        );
                    }
                }
            }

            /**
             * Approval sekarang
             */
            $approval->update([
                'status' => 'approved',
                'catatan' => $request->note,
                'approved_at' => now(),
            ]);
            $this->logApprovalAction($approval, $assignment, 'approved', $request->note);

            /**
             * Cari parent
             */
            if (! $currentPosition->parent_id) {
                /**
                 * Sudah Direktur
                 */
                $approval->workflowInstance->update([
                    'status' => 'approved',
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
             * Parent Position
             */
            /**
             * Tidak ada step lagi
             */
            if (! $nextStep) {
                $approval->workflowInstance->update([
                    'status' => 'approved',
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
                ? $this->approvalsForCurrentYear()
                : $this->approvalsForCurrentPosition($assignment);
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
                ? $this->approvalsForCurrentYear()
                : $this->approvalsForCurrentPosition($assignment);
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
                ? $this->approvalsForCurrentYear()
                : $this->approvalsForCurrentPosition($assignment))->where('id', $id)
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

    // public function getData()
    // {
    //     $data = Approval::with([
    //         'workflowInstance.rkbu',
    //         'workflowStep',
    //         'validatorUser'
    //     ])
    //         ->where('status', 'pending')
    //         ->where('validator_user_id', auth()->id());

    //     return DataTables::of($data)

    //         ->addIndexColumn()

    //         ->addColumn('dokumen', function ($row) {

    //             return strtoupper(
    //                 $row->workflowInstance->dokumen_type
    //             );
    //         })

    //         ->addColumn('status', function ($row) {

    //             return '<span class="badge badge-warning">
    //                     Pending
    //                 </span>';
    //         })

    //         ->addColumn('nomor_dokumen', function ($row) {

    //             return optional(
    //                 $row->workflowInstance->rkbu
    //             )->nomor_rkbu;
    //         })

    //         ->addColumn('action', function ($row) {

    //             return '

    //             <button
    //                 class="btn btn-icon btn-bg-light btn-active-color-info btn-sm btn-detail"
    //                 data-id="' . $row->id . '"
    //                 data-url="' . route('workflowapproval.show', $row->id) . '">

    //                 Detail
    //             </button>
    //             <button
    //                 class="btn btn-success btn-approve"
    //                 data-id="' . $row->id . '">
    //                 Approve
    //             </button>
    //             <button
    //                 class="btn btn-warning btn-revision"
    //                 data-id="' . $row->id . '">
    //                 Revision
    //             </button>
    //             <button
    //                 class="btn btn-danger btn-reject"
    //                 data-id="' . $row->id . '">
    //                 Reject
    //             </button>
    //                         ';
    //         })

    //         ->rawColumns([
    //             'status',
    //             'action',
    //             'nomor_dokumen'
    //         ])

    //         ->make(true);
    // }

    // public function show($id)
    // {
    //     $approval = Approval::with([
    //         'workflowInstance.rkbu.detailBarjas',
    //         'workflowStep',
    //         'validatorRole'
    //     ])->findOrFail($id);

    //     $rkbu = Rkbu::with([
    //         'unit',
    //         'tahunAnggaran',
    //         'detailBarjas'
    //     ])->findOrFail(
    //         $approval->workflowInstance->dokumen_id
    //     );

    //     return response()->json([
    //         'approval' => $approval,
    //         'rkbu' => $rkbu,
    //         'details' => $rkbu->detailBarjas,
    //         'catatan' => $approval->catatan
    //     ]);
    // }

    // public function approve(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {

    //         $approval = Approval::with([
    //             'workflowInstance.rkbu',
    //             'workflowStep.validatorRole'
    //         ])->findOrFail($id);

    //         if ($approval->status !== 'pending') {
    //             throw new \Exception(
    //                 'Approval sudah diproses.'
    //             );
    //         }

    //         $approval->update([
    //             'status'      => 'approved',
    //             'catatan'     => $request->note,
    //             'approved_at' => now()
    //         ]);

    //         $nextStep = WorkflowStep::with('validatorRole')
    //             ->where(
    //                 'workflow_id',
    //                 $approval->workflowStep->workflow_id
    //             )
    //             ->where(
    //                 'step_order',
    //                 '>',
    //                 $approval->workflowStep->step_order
    //             )
    //             ->orderBy('step_order')
    //             ->first();

    //         /**
    //          * Masih ada step berikutnya
    //          */
    //         if ($nextStep) {

    //             $validator = UserValidatorRole::where(
    //                 'validator_role_id',
    //                 $nextStep->validator_role_id
    //             )
    //                 ->where('is_active', 1)
    //                 ->first();

    //             if (!$validator) {

    //                 throw new \Exception(
    //                     'Belum ada validator aktif untuk role : ' .
    //                         optional($nextStep->validatorRole)->nama_validator
    //                 );
    //             }

    //             Approval::create([
    //                 'workflow_instance_id' => $approval->workflow_instance_id,
    //                 'workflow_step_id'     => $nextStep->id,
    //                 'validator_role_id'    => $nextStep->validator_role_id,
    //                 'validator_user_id'    => $validator->user_id,
    //                 'status'               => 'pending'
    //             ]);

    //             $approval->workflowInstance->update([
    //                 'current_step_id' => $nextStep->id,
    //                 'status'          => 'proses'
    //             ]);
    //         } else {

    //             /**
    //              * Step terakhir
    //              */
    //             $approval->workflowInstance->update([
    //                 'status' => 'approved'
    //             ]);

    //             if ($approval->workflowInstance->rkbu) {

    //                 $approval->workflowInstance
    //                     ->rkbu
    //                     ->update([
    //                         'status' => 'approved'
    //                     ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Approval berhasil.'
    //         ]);
    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

}
