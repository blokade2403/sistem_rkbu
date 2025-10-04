<?php

namespace App\DataTables;

use App\Models\MasterBackend\SettingInput\Komponen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KomponenDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('nama_komponen', function ($row) {
                return '
                        <ul class="list list-unstyled">
                            <li><span class="f-16">#&nbsp;' . e($row->nama_barang) . '</span></li>
                            <li><span class="f-12">Kode:' . e($row->kode_komponen) . '</span></li>
                            <li><span class="f-12">ID:' . e($row->id_komponen) . '</span></li>
                        </ul>
                    ';
            })
            ->addColumn('harga_barang', function ($row) {
                return '
                        ' . e(number_format($row->harga_barang, 0, ',', ',')) . '
                        
                    ';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('komponens.edit', $row->id_komponen);
                $deleteUrl = route('komponens.destroy', $row->id_komponen);

                return view('components.partials.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['nama_komponen', 'action']);
    }

    public function query(Komponen $model): QueryBuilder
    {
        // ambil kolom yang dipakai
        return $model->newQuery()->select(
            'id_komponen',
            'kode_barang',
            'kode_komponen',
            'nama_barang',
            'satuan',
            'spek',
            'harga_barang',
            'created_at'
        );
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('komponen-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->responsive(true);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(50)
                ->responsivePriority(1), // selalu tampil

            Column::computed('nama_komponen')
                ->title('Name Komponen')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-left')
                ->addClass('text-break')
                ->responsivePriority(1), // tampil lebih prioritas

            Column::make('kode_barang')->title('Kode Barang')->width(100)->responsivePriority(1),
            Column::make('satuan')->title('Satuan')->width(100)->responsivePriority(1),
            Column::computed('harga_barang')
                ->title('Harga Barang')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-left')
                ->addClass('text-break')
                ->responsivePriority(1), // tampil lebih prioritas
            Column::make('created_at')->title('Created At')->width(70)->responsivePriority(1),
            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->responsivePriority(1), // usahakan selalu tampil
            Column::make('spek')->title('Spek')
                ->addClass('text-break')
                ->width(50)
                ->responsivePriority(100), // sering masuk expand
        ];
    }


    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Komponen_' . date('YmdHis');
    }
}
