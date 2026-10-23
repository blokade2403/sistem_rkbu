<?php

namespace App\DataTables\Rkbu;

use App\Models\Rkbu\RkbuDetailPegawai;
use App\Support\Rkbu\RkbuListFilters;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RkbuPegawaiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function (RkbuDetailPegawai $row) {
                $status = $row->rkbu?->status;
                $disabled = in_array($status, ['draft', 'revision', 'rejected'], true) ? '' : 'disabled';

                return '<div class="form-check form-check-sm form-check-custom form-check-solid">'
                    . '<input class="form-check-input pegawai-row-checkbox" type="checkbox" value="' . e($row->id) . '" ' . $disabled . '></div>';
            })
            ->addColumn('detail_pegawai', function (RkbuDetailPegawai $row) {
                $rkbu = $row->rkbu;
                $subcategory = $rkbu?->subKategoriRkbu;
                $subcategoryLabel = $subcategory
                    ? trim($subcategory->kode_sub_kategori_rkbu . ' - ' . $subcategory->nama_sub_kategori_rkbu)
                    : '-';
                $approvalBadges = ($rkbu?->workflowInstance?->approvals ?? collect())
                    ->sortBy('sequence')
                    ->map(function ($approval) {
                        $styles = [
                            'pending' => ['Pending', 'primary'],
                            'approved' => ['Disetujui', 'success'],
                            'revision' => ['Revisi', 'warning'],
                            'rejected' => ['Ditolak', 'danger'],
                        ];
                        [$label, $color] = $styles[strtolower($approval->status)] ?? [ucfirst($approval->status), 'secondary'];
                        $validator = $approval->validatorUser?->nama
                            ?? $approval->position?->nama_jabatan
                            ?? 'Validator';

                        return '<span class="badge badge-light-' . $color . ' me-2 mb-1">'
                            . e($validator . ': ' . $label) . '</span>';
                    })
                    ->implode('');

                return '<div class="d-flex flex-column">'
                    . '<div class="d-flex flex-wrap gap-2 mb-2">'
                    . '<span class="badge badge-light-primary">Nomor RKBU: ' . e($rkbu?->nomor_rkbu ?? '-') . '</span>'
                    . '<span class="badge badge-light-secondary">Subkategori: ' . e($subcategoryLabel) . '</span>'
                    . '<span class="badge badge-light-info">' . e(ucfirst($rkbu?->status ?? 'draft')) . '</span>'
                    . '</div>'
                    . '<div class="card"><div class="card-body py-4">'
                    . '<div class="text-gray-900 fw-bolder fs-6">' . e($row->nama_pegawai ?: '-') . '</div>'
                    . '<div class="text-gray-600 mt-1">' . e($row->jabatan ?: '-') . '</div>'
                    . '<div class="text-gray-400 fs-7 mt-1">Unit: ' . e($rkbu?->unit?->nama_unit ?? '-')
                    . ' · Dibuat: ' . e(optional($row->created_at)->format('d-m-Y H:i') ?? '-') . '</div>'
                    . ($approvalBadges !== '' ? '<div class="mt-3">' . $approvalBadges . '</div>' : '')
                    . '</div></div></div>';
            })
            ->addColumn('jumlah_bulan', fn (RkbuDetailPegawai $row) =>
                '<div class="text-end">' . e($row->jumlah_volume ?? 0) . '</div>')
            ->addColumn('total_anggaran_format', fn (RkbuDetailPegawai $row) =>
                '<div class="text-end fw-bold">Rp ' . number_format((float) $row->total_anggaran, 0, ',', '.') . '</div>')
            ->addColumn('action', function (RkbuDetailPegawai $row) {
                $actions = '<div class="d-flex justify-content-end flex-shrink-0">'
                    . '<a class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" href="'
                    . e(route('rkbu.pegawai.show', $row->id)) . '" title="Detail"><i class="fas fa-eye"></i></a>';
                if (in_array($row->rkbu?->status, ['draft', 'revision', 'rejected'], true)) {
                    $actions .= '<a class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" href="'
                        . e(route('rkbu.pegawai.edit', $row->id)) . '" title="Edit"><i class="fas fa-edit"></i></a>'
                        . '<button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 pegawai-submit" type="button" data-url="'
                        . e(route('rkbu.pegawai.submit', $row->id)) . '" title="Ajukan"><i class="fas fa-paper-plane"></i></button>'
                        . '<button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm pegawai-delete" type="button" data-url="'
                        . e(route('rkbu.pegawai.destroy', $row->id)) . '" title="Hapus"><i class="fas fa-trash"></i></button>';
                }

                return $actions . '</div>';
            })
            ->filterColumn('detail_pegawai', function ($query, $keyword) {
                $like = '%' . $keyword . '%';
                $query->where(function ($employeeQuery) use ($like) {
                    $employeeQuery->where('nama_pegawai', 'like', $like)
                        ->orWhere('jabatan', 'like', $like)
                        ->orWhereHas('rkbu', fn ($rkbuQuery) => $rkbuQuery
                            ->where('nomor_rkbu', 'like', $like)
                            ->orWhereHas('subKategoriRkbu', fn ($subcategoryQuery) => $subcategoryQuery
                                ->where('nama_sub_kategori_rkbu', 'like', $like)));
                });
            })
            ->orderColumn('detail_pegawai', fn ($query, $order) => $query->orderBy('nama_pegawai', $order))
            ->rawColumns(['checkbox', 'detail_pegawai', 'jumlah_bulan', 'total_anggaran_format', 'action']);
    }

    public function query(RkbuDetailPegawai $model): QueryBuilder
    {
        $filters = RkbuListFilters::validate(request());
        $query = $model->newQuery()
            ->with([
                'rkbu.unit',
                'rkbu.sumberDana',
                'rkbu.subKategoriRkbu',
                'rkbu.workflowInstance.approvals.validatorUser',
                'rkbu.workflowInstance.approvals.position',
            ])
            ->whereHas('rkbu', function ($rkbuQuery) {
                $rkbuQuery->where('tahun_anggaran_id', session('tahun_anggaran_id'));
                if (! auth()->user()->hasRole('Administrator')) {
                    $rkbuQuery->where('created_by', auth()->id());
                }
            });

        return RkbuListFilters::apply($query, $filters);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rkbupegawai-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')->title('<input type="checkbox" class="form-check-input" id="pegawai-select-all">')
                ->width(30)->exportable(false)->printable(false)->searchable(false)->orderable(false),
            Column::computed('detail_pegawai')->title('Detail Pegawai')->searchable(true)->orderable(true),
            Column::computed('jumlah_bulan')->title('Jumlah Bulan')->searchable(false),
            Column::computed('total_anggaran_format')->title('Total Anggaran')->searchable(false),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->searchable(false)->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'RKBU_Pegawai_' . date('YmdHis');
    }
}
