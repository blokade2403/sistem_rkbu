<?php

namespace App\DataTables\MasterSpj;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use App\Models\MasterBackend\SettingBelanja\MasterSpj;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class MasterSpjDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn() // otomatis tambahkan nomor urut
            ->addColumn('detail_usulan', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li>
                            <span class="f-12">&nbsp;' . ($row->no_usulan_barang) . '</span>
                        </li>
                       
                    </ul>
                    ';
            })
            ->addColumn('uraian_spj', function ($row) {
                return '
                    <span class="f-12"></span>';
            })
            ->addColumn('detail_spj', function ($row) {
                return '
                     <ul class="list list-unstyled">
                       
                    </ul>
                    ';
            })
            ->addColumn('detail_biaya', function ($row) {
                return '<span class="f-12"></span>';
            })
            ->addColumn('status_spj', function ($row) {
                return '<span class="f-12">Rp. </span>';
            })

            ->addColumn('action', function ($row) {
                $routePrefix = 'master_spjs'; // pastikan variabel ini ada di class DataTable

                $disabledBtn = '<button class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No Action</button>';

                // Atur default
                $editUrl = null;
                $deleteUrl = null;

                if (session('nama_level_user') == 'User') {
                    if ($row->id_status_validasi == '9cfb1edc-2263-401f-b249-361db4017932' || session('status_edit') == 'tidak aktif') {
                        return $disabledBtn;
                    }
                } elseif (session('nama_level_user') == 'Validasi') {
                    if ($row->id_status_validasi_rka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' || session('status_edit') == 'tidak aktif') {
                        return $disabledBtn;
                    }
                } elseif (session('nama_level_user') == 'Validasi RKA') {
                    if (session('status_edit') == 'tidak aktif') {
                        return $disabledBtn;
                    }
                }

                // Kalau lolos kondisi -> kasih tombol edit & delete
                $editUrl   = route($routePrefix . '.edit', $row->id_rkbu);
                $deleteUrl = route($routePrefix . '.destroy', $row->id_rkbu);

                return view('components.partials.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['detail_usulan', 'uraian_spj', 'detail_spj', 'detail_biaya', 'status_spj',  'action', 'routePrefix' => 'master_spjs']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(MasterSpj $model): \Illuminate\Database\Eloquent\Builder
    {
        $tahunAnggaran = Session::get('tahun_anggaran');
        $nama_level_user = session('nama_level_user') ?? 'Guest';
        $idAdminPendukungPpk = session('id_admin_pendukung_ppk');

        $query = $model->newQuery()
            ->join('spjs', 'spjs.id_spj', '=', 'master_spjs.id_spj')
            ->leftJoin('perusahaans', 'perusahaans.id_perusahaan', '=', 'master_spjs.id_perusahaan')
            ->join('spj_details', 'spj_details.id_spj', '=', 'spjs.id_spj')
            ->join('usulan_barangs', 'usulan_barangs.id_usulan_barang', '=', 'spjs.id_usulan_barang')
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('master_spjs.tahun_anggaran', $tahunAnggaran)
            ->when(request('sub_kategori_rkbu'), function ($query_sub_kategori_rkbus) {
                $query_sub_kategori_rkbus->where('sub_kategori_rkbus.id_sub_kategori_rkbu', request('sub_kategori_rkbu'));
            }) // Tambahkan filter dinamis untuk sub_kategori_rkbu
            ->when(request('status_pembayaran'), function ($query) {
                $query->where('master_spjs.status_pembayaran', request('status_pembayaran'));
            }) // Tambahkan filter dinamis untuk status_pembayaran
            ->distinct()
            ->select('master_spjs.*', 'usulan_barangs.*', 'sub_kategori_rkbus.*')
            ->get();

        // Filter role
        if ($nama_level_user === 'Pendukung PPK') {
            $query->where('master_spjs.id_admin_pendukung_ppk', $idAdminPendukungPpk)
                ->where('master_spjs.status_proses_pesanan', 'Selesai');
        }

        if ($nama_level_user === 'Bendahara') {
            $query->where('master_spjs.status_serah_terima_bendahara', 'Selesai');
        }

        if ($nama_level_user === 'Direktur') {
            $query->where('master_spjs.status_verifikasi_ppk_keuangan', 'Selesai');
        }

        // filter tambahan dari request (dropdown/filter form)
        if (request('sub_kategori_rkbu')) {
            $query->where('sub_kategori_rkbus.id_sub_kategori_rkbu', request('sub_kategori_rkbu'));
        }

        if (request('status_pembayaran')) {
            $query->where('master_spjs.status_pembayaran', request('status_pembayaran'));
        }

        return $query->distinct();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('masterspj-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(40)
                ->responsivePriority(1), // selalu tampil

            Column::computed('detail_usulan')
                ->title('Detail Usulan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(40)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('uraian_spj')
                ->title('Uraian SPJ')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('detail_spj')
                ->title('Detail SPJ')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('detail_biaya')
                ->title('Detail Biaya')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('status')
                ->title('Status')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->responsivePriority(100), // usahakan selalu tampil

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'MasterSpj_' . date('YmdHis');
    }
}
