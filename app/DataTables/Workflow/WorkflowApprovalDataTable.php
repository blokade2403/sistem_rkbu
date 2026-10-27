<?php

namespace App\DataTables\Workflow;

use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\Rkbu\Rkbu;
use App\Models\Rkbu\RkbuDetailPersediaan;
use App\Models\WorkFlow\Approval;
use App\Support\Rkbu\PersediaanListPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class WorkflowApprovalDataTable
{
    public function render(Request $request, bool $isSuperAdmin, ?Collection $assignments)
    {
        if ((! $assignments || $assignments->isEmpty()) && ! $isSuperAdmin) {
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
                'string',
                'max:36',
                function ($attribute, $value, $fail) {
                    if ($value !== '__none' && ! DB::table('sumber_danas')->where('id_sumber_dana', $value)->exists()) {
                        $fail('Sumber dana filter tidak valid.');
                    }
                },
            ],
            'sub_kategori_rkbu_id' => [
                'nullable',
                'uuid',
                Rule::exists('sub_kategori_rkbus', 'id_sub_kategori_rkbu'),
            ],
            'sub_kegiatan_id' => ['nullable', 'uuid', Rule::exists('sub_kegiatans', 'id_sub_kegiatan')],
            'approval_status' => [
                'nullable',
                Rule::in(['pending', 'approved', 'rejected', 'revision']),
            ],
        ]);

        $data = ($isSuperAdmin
            ? $this->approvalsForCurrentYear()
            : $this->approvalsForCurrentPosition($assignments))->with([
            'workflowInstance.rkbu.creator',
            'workflowInstance.rkbu.unit',
            'workflowInstance.rkbu.sumberDana',
            'workflowInstance.rkbu.detailBarjas',
            'workflowInstance.rkbu.detailPegawai',
            'workflowInstance.rkbu.detailPersediaan',
            'workflowInstance.rkbu.detailModal',
            'workflowInstance.rkbu.detailModalAlkes',
            'workflowInstance.rkbu.subKategoriRkbu.kategori.jenis_kategori_rkbu',
            'workflowInstance.rkbu.subKategoriRkbu',
            'workflowInstance.rkbu.tahunAnggaran',
            'workflowInstance.rkbu.rekeningBelanja',
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
        if ($module !== 'all' || ! empty($filters['sumber_dana_id']) || ! empty($filters['sub_kategori_rkbu_id']) || ! empty($filters['sub_kegiatan_id'])) {
            $data->whereHas('workflowInstance.rkbu', function ($rkbuQuery) use ($module, $filters) {
                if (! empty($filters['sumber_dana_id'])) {
                    $filters['sumber_dana_id'] === '__none'
                        ? $rkbuQuery->whereNull('sumber_dana_id')
                        : $rkbuQuery->where('sumber_dana_id', $filters['sumber_dana_id']);
                }

                if (! empty($filters['sub_kategori_rkbu_id'])) {
                    $rkbuQuery->where('sub_kategori_rkbu_id', $filters['sub_kategori_rkbu_id']);
                }
                if (! empty($filters['sub_kegiatan_id'])) {
                    $rkbuQuery->where('sub_kegiatan_id', $filters['sub_kegiatan_id']);
                }

                if ($module !== 'all') {
                    $this->filterByModule($rkbuQuery, $module);
                }
            });
        }

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->filterColumn('nama_komponen', function ($query, $keyword) {
                $like = "%{$keyword}%";

                $query->whereHas('workflowInstance.rkbu', function ($rkbuQuery) use ($like) {
                    $rkbuQuery->where(function ($searchQuery) use ($like) {
                        $searchQuery->where('nomor_rkbu', 'like', $like)
                            ->orWhereHas('detailBarjas', function ($detailQuery) use ($like) {
                                $detailQuery->where('nama_barang', 'like', $like)
                                    ->orWhere('spesifikasi', 'like', $like);
                            })
                            ->orWhereHas('detailPersediaan', function ($detailQuery) use ($like) {
                                $detailQuery->where('nama_barang', 'like', $like)
                                    ->orWhere('spesifikasi', 'like', $like);
                            })
                            ->orWhereHas('detailModal', function ($detailQuery) use ($like) {
                                $detailQuery->where('nama_barang', 'like', $like)
                                    ->orWhere('spesifikasi', 'like', $like);
                            })
                            ->orWhereHas('detailModalAlkes', function ($detailQuery) use ($like) {
                                $detailQuery->where('nama_barang', 'like', $like)
                                    ->orWhere('spesifikasi', 'like', $like);
                            })
                            ->orWhereHas('detailPegawai', function ($detailQuery) use ($like) {
                                $detailQuery->where('nama_pegawai', 'like', $like)
                                    ->orWhere('jabatan', 'like', $like);
                            })
                            ->orWhereHas('creator', function ($creatorQuery) use ($like) {
                                $creatorQuery->where('nama', 'like', $like);
                            })
                            ->orWhereHas('unit', function ($unitQuery) use ($like) {
                                $unitQuery->where('nama_unit', 'like', $like);
                            });
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
                        return '
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                            <span class="badge badge-light-primary">Nomor RKBU: ' . e($rkbu?->nomor_rkbu ?? '-') . '</span>
                            <span class="badge badge-light-info">' . e($moduleLabel) . '</span>
                            ' . ($detail instanceof RkbuDetailPersediaan && $detail->rating
                                ? '<span class="badge badge-light-success">Prioritas ' . e($detail->rating) . '</span>'
                                : '') . '
                            <span class="badge badge-light-secondary">Subkategori: ' . e($subcategoryLabel) . '</span>
                            <span class="badge badge-light-warning">Sumber Dana: ' . e($rkbu?->sumberDana?->nama_sumber_dana ?? '-') . '</span>
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
                             <div class="mb-3 ps-10">
                                    <!--begin::Reply-->
                                    <div class="d-flex">
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px me-3">
                                            <img src="' . asset('assets/media/avatars/blank.png') . '"
                                                alt="" />
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column flex-row-fluid">
                                            <!--begin::Info-->
                                            <div class="d-flex align-items-center flex-wrap mb-1">
                                                <span class="text-gray-800 fw-bolder me-2">' . e($rkbu?->creator?->nama ?? '-') . '</span>
                                                <span class="text-gray-400 fw-bold fs-7">(Unit:
                                                    ' . e($rkbu?->unit?->nama_unit ?? '-') . ')</span>
                                                <a href="#"
                                                    class="ms-auto text-gray-400 text-hover-primary fw-bold fs-7"></a>
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
                    ->addColumn('persediaan_info', function ($row) use ($module) {
                        if ($module !== 'persediaan') {
                            return '';
                        }

                        $rkbu = $row->workflowInstance?->rkbu;
                        $detail = $this->detailsForRkbu($rkbu)->first();

                        return $detail instanceof RkbuDetailPersediaan
                            ? PersediaanListPresenter::renderInfo($detail, $rkbu)
                            : '';
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
                                e($detail->jumlah_volume ?? '-') . ' ' . e($detail->satuan_1 ?? '') .
                                ' / ' . e($detail->sisa_vol_rkbu ?? '-') . '</div>';
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

                        $priceHtml = '<div class="text-end text-dark fw-bold fs-6">Rp. ' .
                            number_format((float) $harga, 0, ',', '.') . '</div>';
                        if ($detail instanceof RkbuDetailPersediaan) {
                            $priceHtml .= '<div class="text-end text-muted fs-7">PPN: ' . e($detail->ppn ?? 0) . '%</div>';
                        }

                        return $priceHtml;
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
            ->rawColumns(['checkbox', 'nama_komponen', 'persediaan_info', 'satuan', 'harga_barang', 'total', 'action'])
            ->make(true);
    }

    public function detailsForRkbu(?Rkbu $rkbu)
    {
        if (! $rkbu) {
            return collect();
        }

        $details = $rkbu->detailBarjas
            ->concat($rkbu->detailPegawai)
            ->concat($rkbu->detailPersediaan)
            ->concat($rkbu->detailModal)
            ->concat($rkbu->detailModalAlkes)
            ->values();

        return app(\App\Services\Workflow\UsulanBelanjaBalanceService::class)
            ->applyToCollection($details);
    }

    public function moduleKey(?Rkbu $rkbu, $details): string
    {
        if ($details->first()?->getTable() === 'rkbu_detail_pegawais') {
            return 'pegawai';
        }
        if ($details->first()?->getTable() === 'rkbu_detail_persediaans') {
            return 'persediaan';
        }
        if ($details->first()?->getTable() === 'rkbu_detail_modal_alkes') {
            return 'modal-alkes';
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

    public function moduleLabel(?Rkbu $rkbu, $details): string
    {
        return [
            'pegawai' => 'RKBU Pegawai',
            'persediaan' => 'RKBU Persediaan',
            'modal-alkes' => 'RKBU Modal Alkes',
            'modal-kantor' => 'RKBU Modal Kantor',
            'barjas' => 'RKBU Barjas',
        ][$this->moduleKey($rkbu, $details)];
    }

    public function filterByModule($query, string $module): void
    {
        match ($module) {
            'barjas' => $query->whereHas('detailBarjas'),
            'pegawai' => $query->whereHas('detailPegawai'),
            'persediaan' => $query->whereHas('detailPersediaan'),
            'modal' => $query->where(fn ($modalQuery) => $modalQuery
                ->whereHas('detailModal')
                ->orWhereHas('detailModalAlkes')),
            'modal-kantor' => $query->whereHas('detailModal')
                ->whereHas('subKategoriRkbu', fn($subcategoryQuery) => $this->filterLabel($subcategoryQuery, ['kantor', 'peralatan kantor'])),
            'modal-alkes' => $query->whereHas('detailModalAlkes'),
            default => null,
        };
    }

    public function approvalsForCurrentYear(): Builder
    {
        return $this->approvalsForCurrentYearHistory()->whereNotExists(function ($query) {
            $query->selectRaw('1')
                ->from('approvals as later_approval')
                ->whereColumn('later_approval.workflow_instance_id', 'approvals.workflow_instance_id')
                ->where(function ($query) {
                    $query->whereColumn('later_approval.sequence', '>', 'approvals.sequence')
                        ->orWhere(function ($sameSequenceQuery) {
                            $sameSequenceQuery->whereColumn('later_approval.sequence', '=', 'approvals.sequence')
                                ->where(function ($laterQuery) {
                                    $laterQuery->whereColumn('later_approval.created_at', '>', 'approvals.created_at')
                                        ->orWhere(function ($sameTimestampQuery) {
                                            $sameTimestampQuery->whereColumn('later_approval.created_at', '=', 'approvals.created_at')
                                                ->whereColumn('later_approval.id', '>', 'approvals.id');
                                        });
                                });
                        });
                });
        });
    }

    public function approvalsForCurrentYearHistory(): Builder
    {
        return Approval::query()->whereHas(
            'workflowInstance.rkbu',
            fn(Builder $query) => $query
                ->where('tahun_anggaran_id', session('tahun_anggaran_id'))
                ->whereColumn('rkbus.workflow_instance_id', 'workflow_instances.id')
        );
    }

    public function approvalsForCurrentPosition(PositionAssignment|Collection $assignments): Builder
    {
        $positionIds = $assignments instanceof PositionAssignment
            ? [$assignments->position_id]
            : $assignments->pluck('position_id')->filter()->unique()->values()->all();

        return $this->approvalsForCurrentYearHistory()->where(function (Builder $query) use ($positionIds) {
            $query->whereIn('position_id', $positionIds)
                ->orWhere(function (Builder $legacyQuery) use ($positionIds) {
                    $legacyQuery->whereNull('position_id')
                        ->whereHas(
                            'workflowInstance',
                            fn(Builder $instanceQuery) => $instanceQuery
                                ->whereIn('current_position_id', $positionIds)
                        );
                });
        });
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

}
