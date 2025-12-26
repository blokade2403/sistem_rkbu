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

            ->addIndexColumn() // otomatis tambahkan nomor urut
            ->addColumn('detail_usulan', function ($row) {
                return '

                    <ul class="list-unstyled product-desc-list">
                    <li><span class="f-12">Status: </span>
                    <span class="f-12"><span class="badge bg-info">' . ($row->status_hutang ?? 'Isi data Terlebih Dahulu') . ' </span></li>
                </ul>
                <div class="d-flex align-items-center">
                     <img src="' . asset('assets/images/companies/img-1.png') . '"
                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                    <div class="flex-1">
                        <h6 class="mt-0 mb-1 fs-14">
                            <a href=""
                                class="fs-16">' . e($row->no_usulan_barang) . '
                            </a>
                        </h6>
                        <p class="mb-0 fs-12 text-muted">
                            <span>' . e($row->created_at) . '</span>
                        </p>
                    </div>
                </div>
                <p>
                 <ul class="list-unstyled product-desc-list">
                    <li class="py-0">
                        <span class="f-12">User: ' . ($row->nama_pengusul_barang) . '</span></br>
                        <span class="f-12">Unit: ' . ($row->unit ?? ' - ') . '</span>
                    </li></br>
                    <li> <span class="f-12">' . $row->kode_kategori_rkbu . '. ' . $row->nama_kategori_rkbu . '</span></li>
                    <li> <h6 class="mt-0 mb-1 fs-14"> ' . ($row->nama_barang === '-' ? $row->nama_pegawai : $row->nama_barang) . '</h6></li>
                    <li> <span class="f-12 text-muted">' . ($row->sub_kategori_rkbu->kode_sub_kategori_rkbu) . '
                                ' . ($row->sub_kategori_rkbu->nama_sub_kategori_rkbu) . '</span>
                    </li>
                    <li>
                            <span class="fs-10 text-primary"># ID RKBU: 
                                ' . ($row->id_rkbu) . '</span>
                        </li>
                </ul></p>';
            })

            ->addColumn('detail_spj', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li>
                            <span class="fs-12">&nbsp;</span>
                        </li>
                        <li>
                            <span class="fs-12">Netto: <strong>Rp.
                                    ' . number_format($row->harga_barang) . '</strong></span>
                        </li>
                        <li>
                            <span class="fs-12">PPN : ' . $row->ppn ?? '0' . '
                                %</span>
                        </li>
                        <li>
                            Bruto :<span class="badge badge-soft-info fs-12"> Rp.
                                ' . number_format($row->bruto) . '</span>
                        </li></br>
                        <li>
                            <span class="fs-12">Pembayaran : Rp.
                                ' . number_format($row->pembayaran) . '</span>
                        </li>
                        <li>
                            <span class="fs-12">Tanggal Pembayaran :<br />
                                ' . $row->tanggal_pembayaran . '</span>
                        </li>
                    </ul>';
            })
            ->addColumn('detail_biaya', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li>
                            <span class="fs-12">' . $row->vol_1_detail . ' ' . $row->satuan_1_detail . ' x ' . $row->vol_2_detail . ' ' . $row->satuan_2_detail . '</span>
                        </li>
                        <li>
                            <span class="fs-12">Rata-rata Pemakaian : ' . $row->rata2_pemakaian . '</span>
                        </li>
                        <li>
                            <span class="fs-12">Sisa Stok : ' . $row->sisa_stok . '</span>
                        </li>
                        <li>
                            <span class="fs-12"> Stok Minimal : ' . $row->stok_minimal . '</span>
                        </li></br>
                        <li>
                            <span class="fs-12">Buffer Stok : ' . $row->buffer_stok . '</span>
                        </li>
                        <li></br>
                            <span class="fs-12">Total Vol Usulan: <strong>' . $row->jumlah_usulan_barang . '</strong></span>
                        </li>
                         <li>
                            <span class="fs-12">Total Anggaran Usulan: 
                            <p><strong> Rp. ' . number_format($row->total_anggaran_usulan_barang) . '</strong></p></span>
                        </li>
                    </ul>';
            })
            ->addColumn('status_spj', function ($row) {
                return '<span class="fs-12"> ' . $row->spek_detail . ' </span>';
            })

            ->addColumn('action', function ($row) {
                $routePrefix = 'rekap_usulan_belanjas'; // pastikan variabel ini ada di class DataTable

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
                $editUrl   = route($routePrefix . '.show', $row->id_usulan_barang);
                $deleteUrl = route($routePrefix . '.destroy', $row->id_usulan_barang);

                return view('components.partials.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['detail_usulan', 'uraian_spj', 'detail_spj', 'detail_biaya', 'status_spj',  'action', 'routePrefix' => 'rekap_usulan_belanjas']);
    }

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
            'rkbus.nama_pegawai',
            'kegiatans.*',
            'aktivitas.*',
            'users.nama_lengkap as nama_pengusul_barang',
            'units.nama_unit as unit',
            'sumber_danas.id_sumber_dana', // Retrieving id_sumber_dana
            'sumber_danas.nama_sumber_dana', // Retrieving sumber_danas field
            'usulan_barangs.no_usulan_barang',
            'usulan_barangs.nama_pengusul_barang',
            'usulan_barangs.status_permintaan_barang',
            'usulan_barangs.tahun_anggaran',
            'usulan_barangs.status_validasi_direktur',
            'usulan_barangs.nama_valid_pptk',
            'usulan_barangs.nama_valid_rka',
            'usulan_barangs.nama_valid_direktur',
            'usulan_barangs.tahun_anggaran as tahun'
        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('rekap-usulan')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1) // urutkan default
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(10)
                ->responsivePriority(1), // selalu tampil

            Column::computed('detail_usulan')
                ->title('Detail Usulan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(90)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('detail_spj')
                ->title('Detail Biaya')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('detail_biaya')
                ->title('Detail Vol Usulan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(90)
                ->responsivePriority(1), // tampil lebih prioritas

            Column::computed('status_spj')
                ->title('Spesifikasi')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->addClass('text-center')
                ->responsivePriority(100), // usahakan selalu tampil

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
