<?php

namespace App\DataTables\Administrator;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\RekapUsulanBelanja;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use App\Models\MasterBackend\SettingBelanja\UsulanBarang;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RekapUsulanBelanjaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'rekapusulanbelanja.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(UsulanBarang $model): QueryBuilder
    {
        $tahunAnggaran = Session::get('tahun_anggaran');
        $currentJenisKategori = request()->input('jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac');
        $search = request()->input('search.value');  // Ambil parameter pencarian dari DataTable
        $subKategoriFilter = request()->input('sub_kategori_rkbu');
        $statusValidasiFilter = request()->input('id_status_validasi_rka');

        $query = $model->newQuery()
            ->join('sub_kategori_rkbus', 'sub_kategori_rkbus.id_sub_kategori_rkbu', '=', 'usulan_barangs.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('usulan_barang_details', 'usulan_barang_details.id_usulan_barang', '=', 'usulan_barangs.id_usulan_barang')
            ->join('rkbus', 'rkbus.id_rkbu', '=', 'usulan_barang_details.id_rkbu')
            ->join('sub_kategori_rekenings', 'sub_kategori_rekenings.id_sub_kategori_rekening', '=', 'sub_kategori_rkbus.id_sub_kategori_rekening')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('kegiatans', 'kegiatans.id_kegiatan', '=', 'sub_kegiatans.id_kegiatan')
            ->join('programs', 'programs.id_program', '=', 'kegiatans.id_program')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            ->join('users', 'users.id_user', '=', 'usulan_barangs.id_user')
            ->join('units', 'users.id_unit', '=', 'units.id_unit')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('status_usulan_barang', 'Selesai');

        // 🔍 Pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('rkbus.nama_barang', 'like', "%{$search}%")
                    ->orWhere('sub_kategori_rkbus.nama_sub_kategori_rkbu', 'like', "%{$search}%")
                    ->orWhere('kategori_rkbus.nama_kategori_rkbu', 'like', "%{$search}%")
                    ->orWhere('users.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('usulan_barangs.no_usulan_barang', 'like', "%{$search}%");
            });
        }

        return $query->select([
            'usulan_barang_details.*',
            'sub_kategori_rkbus.nama_sub_kategori_rkbu',
            'sub_kategori_rkbus.kode_sub_kategori_rkbu',
            'kategori_rkbus.kode_kategori_rkbu',
            'kategori_rkbus.nama_kategori_rkbu',
            'rekening_belanjas.*',
            'sub_kegiatans.*',
            'programs.*',
            'rkbus.nama_barang',
            'rkbus.sisa_vol_rkbu',
            'rkbus.sisa_anggaran_rkbu',
            'kegiatans.*',
            'aktivitas.*',
            'users.nama_lengkap as nama_pengusul_barang',
            'units.nama_unit as unit',
            'sumber_danas.id_sumber_dana', // Retrieving id_sumber_dana
            'sumber_danas.nama_sumber_dana', // Retrieving sumber_danas field
            'usulan_barangs.*',
            'usulan_barangs.tahun_anggaran as tahun'
        ]);
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rekapusulanbelanja-table')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RekapUsulanBelanja_' . date('YmdHis');
    }
}
