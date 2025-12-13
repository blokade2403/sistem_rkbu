<?php

namespace App\DataTables\Administrator;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use App\Models\RkbuPegawaiNonPnsAdmin;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\MasterBackend\SettingRkbu\Rkbu;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use App\Models\MasterBackend\SettingInput\Anggaran;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class RkbuPegawaiNonPnsAdminDataTable extends DataTable
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
            ->addColumn('nama_pegawai', function ($row) {
                $sumberDana = $row->nama_sumber_dana ?? 'Tidak ada data';

                // Tentukan label sesuai kondisi
                $label = $sumberDana === 'BLUD'
                    ? '<span class="badge badge-outline-primary">' . $sumberDana . '</span>'
                    : '<span class="badge badge-outline-danger">' . $sumberDana . '</span>';

                $statusBadge = view('rkbu.partials.status_badge_validasi', [
                    'rkbus' => $row
                ])->render();

                return '
                <ul class="list-unstyled product-desc-list">
                    <li><span class="f-12">Sumber Dana: ' . $label . '</span>
                    <span class="f-12"><span class="badge bg-info"> ' . e($row->rating) . '</span>' . $statusBadge . '</span></li>
                </ul>
                <div class="d-flex align-items-center">
                     <img src="' . asset('assets/images/companies/img-2.png') . '"
                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                    <div class="flex-1">
                        <h6 class="mt-0 mb-1 fs-14">
                            <a href=""
                                class="fs-16">' . e($row->nama_pegawai) . '
                            </a>
                        </h6>
                        <p class="mb-0 fs-12 text-muted">
                            <span>' . ($row->tempat_lahir) . ', ' . ($row->tanggal_lahir) . '</span>
                        </p>
                    </div>
                </div>
                 <p>
                 <ul class="list-unstyled product-desc-list">
                    <li><span class="f-12">Jabatan : ' . ($row->pendidikan) . '</span></li>
                    <li><span class="f-12">Pendidikan : ' . ($row->jabatan) . '</span></li><p>
                    <li class="py-0">
                       <span class="f-12">User: ' . ($row->user->nama_lengkap) . '</span></br>
                        <span class="f-12">KSP: ' . ($row->user->ksp->nama_ksp) . '</span>
                    </li></br>
                    <li> <span class="f-12">' . ($row->subKategoriRkbu->kode_sub_kategori_rkbu) . '.
                                ' . ($row->subKategoriRkbu->nama_sub_kategori_rkbu) . '</span>
                                </li>
                    </li>
                </ul></p>
            ';
            })
            ->addColumn('profil_pegawai', function ($row) {
                return '
                    <ul class="list list-unstyled">
                        <li><span class="f-12">Nomor Kontrak : ' . ($row->nomor_kontrak) . '</span></li>
                        <li><span class="f-12">Tmt Pegawai : ' . ($row->tmt_pegawai) . '</span></li>
                    </ul>
                    ';
            })
            ->addColumn('gaji_pokok', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li><span class="f-12">Gaji Pokok Rp. ' . number_format($row->gaji_pokok, 0, ',', ',') . ' x ' . $row->koefisien_gaji . ' bulan </span></li>
                        <li><span class="f-12">Remunerasi Rp. ' . number_format($row->remunerasi, 0, ',', ',') . ' x ' . $row->koefisien_remunerasi . ' bulan </span></li>
                    </ul>
                    ';
            })

            ->addColumn('total_anggaran', function ($row) {
                return '<span class="f-12">Rp. ' . number_format($row->total_anggaran) . '</span>';
            })
            ->addColumn('pengusul', function ($row) {
                return '<span class="f-12">User: ' . ($row->user->nama_lengkap) . '</span></br>
                        <span class="f-12">KSP: ' . ($row->user->ksp->nama_ksp) . '</span>';
            })
            ->addColumn('detail_anggaran', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li><span class="f-12">Sisa Volume:&nbsp;' . ($row->sisa_vol_rkbu) . '
                                ' . ($row->satuan_1) . '/' . ($row->satuan_2) . '
                                </br><span class="f-12"> Sisa Anggaran:&nbsp;Rp.
                                    ' . number_format($row->sisa_anggaran_rkbu) . '</span>
                            </span>
                        </li>
                    </ul>
                    ';
            })
            ->addColumn('detail_id', function ($row) {
                return '
                     <ul class="list list-unstyled">
                        <li>
                            <span class="f-12">&nbsp;' . ($row->subKategoriRkbu->kode_sub_kategori_rkbu) . '.
                                ' . ($row->subKategoriRkbu->nama_sub_kategori_rkbu) . '</span><p><span class="f-12">&nbsp;#' . ($row->subKategoriRkbu->id_sub_kategori_rkbu) . '</span></p>
                        </li>
                        <li>
                            <span class="f-12">&nbsp;' . ($row->rekening_belanjas->kode_rekening_belanja) . '.
                                ' . ($row->rekening_belanjas->nama_rekening_belanja) . '</span><p><span class="f-12">&nbsp;#' . ($row->rekening_belanjas->id_kode_rekening_belanja) . '</span></p>
                        </li>
                    </ul>
                    ';
            })
            ->addColumn('create', function ($row) {
                return '<span class="f-12"> ' . ($row->created_at) . '</span>';
            })
            ->addColumn('dokumen', function ($row) {
                $html = '';

                // Link e-katalog
                $html .= '<span class="f-12">link e-kat: ';
                if (strlen($row->link_ekatalog) <= 3) {
                    $html .= e($row->link_ekatalog);
                } else {
                    $html .= '<a href="' . e($row->link_ekatalog) . '" target="_blank">
                                <span class="badge label-primary">Lihat Link e-katalog</span>
                            </a>';
                }
                $html .= '</span><br/>';

                // Dokumen KAP
                if (!empty($row->upload_file_1)) {
                    $html .= '<span class="f-12">Dok. KAP :
                    <a href="' . asset('storage/uploads/' . basename($row->upload_file_1)) . '" target="_blank">
                        <span class="badge bg-primary">Lihat Dokumen KAP</span>
                    </a>
                  </span><br/>';
                } else {
                    $html .= '<span class="f-12 text-danger">Dok. KAP : No File.</span><br/>';
                }

                // Dokumen SPH1
                if (!empty($row->upload_file_2)) {
                    $html .= '<span class="f-12">Dok. SPH1 :
                    <a href="' . asset('storage/uploads/' . basename($row->upload_file_2)) . '" target="_blank">
                        <span class="badge bg-primary">Lihat Dokumen SPH 1</span>
                    </a>
                  </span><br/>';
                } else {
                    $html .= '<span class="f-12 text-danger">Dok. SPH1 : No File.</span><br/>';
                }

                // Dokumen SPH2
                if (!empty($row->upload_file_3)) {
                    $html .= '<span class="f-12">Dok. SPH2 :
                    <a href="' . asset('storage/uploads/' . basename($row->upload_file_3)) . '" target="_blank">
                        <span class="badge bg-primary">Lihat Dokumen SPH 2</span>
                    </a>
                  </span><br/>';
                } else {
                    $html .= '<span class="f-12 text-danger">Dok. SPH2 : No File.</span><br/>';
                }

                // Dokumen SPH3
                if (!empty($row->upload_file_4)) {
                    $html .= '<span class="f-12">Dok. SPH3 :
                    <a href="' . asset('storage/uploads/' . basename($row->upload_file_4)) . '" target="_blank">
                        <span class="badge bg-primary">Lihat Dokumen SPH 3</span>
                    </a>
                  </span><br/>';
                } else {
                    $html .= '<span class="f-12 text-danger">Dok. SPH3 : No File.</span><br/>';
                }

                return $html;
            })
            ->addColumn('status', function ($rkbus) {
                return view('rkbu.partials.status_rkbu', compact('rkbus'))->render();
            })
            ->addColumn('action', function ($row) {
                $routePrefix = 'rkbu_pegawai_pns_admins'; // pastikan variabel ini ada di class DataTable

                $disabledBtn = '<button class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No Action</button>';

                // Atur default
                $editUrl = null;
                $deleteUrl = null;

                $level_view = [
                    'PPK',
                    'PPBJ',
                    'Pengurus Barang',
                    'PPK Keuangan',
                    'PPTK',
                    'PPTK Alkes',
                    'PPTK Umum',
                    'Validasi Keuangan',
                    'Verifikator',
                    'Direktur',
                    'Admin PPK',
                    'Bendahara',
                    'Pendukung PPK',
                ];

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
                } elseif (in_array(session('nama_level_user'), $level_view)) {
                    return $disabledBtn;
                }

                // Kalau lolos kondisi -> kasih tombol edit & delete
                $editUrl   = route($routePrefix . '.edit', $row->id_rkbu);
                $deleteUrl = route($routePrefix . '.destroy', $row->id_rkbu);

                return view('components.partials.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['nama_pegawai', 'profil_pegawai', 'gaji_pokok', 'total_vol', 'total_anggaran', 'detail_id', 'detail_anggaran', 'pengusul', 'spek', 'dokumen', 'create', 'status', 'action', 'routePrefix' => 'rkbu_pegawai_pns_admins']);
    }

    public function query(Rkbu $model): QueryBuilder
    {
        $tahunAnggaran          = Session::get('tahun_anggaran');
        $currentJenisKategori   = request()->input('jenis_kategori_rkbu', '9cdfd354-e43a-4461-9747-e15fb74038ac');
        $search                 = request()->input('search.value');  // Ambil parameter pencarian dari DataTable
        $subKategoriFilter      = request()->input('sub_kategori_rkbu');
        $statusValidasiFilter   = request()->input('id_status_validasi_rka');
        $sumberDanaFilter       = request()->input('id_sumber_dana');

        $id_pejabat          = session('id_pejabat');
        $id_user             = session('id_user');
        $faseTahun           = TahunAnggaran::where('nama_tahun_anggaran', $tahunAnggaran)->value('fase_tahun');


        $sumber_dana    = [
            '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3', // BLUD
            '9cdfced0-87ce-49dc-8268-25ed56420bf7', // APBD
            '9cf6ed93-0b31-4941-a31a-82d07eb81873' // DAK
        ];

        $id_kode_rekening_belanja_non_barjas_non_blud = [
            '9cf60434-5a17-4cb0-9114-1ca4b138c01e',
            '9cf60466-6250-4911-809c-e55ce54880b9',
            '9cf6048a-fa04-40ec-b310-c03d12b2b760',
        ];

        $id_kode_rekening_belanja_barjas_non_blud = [
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
        ];

        $idKodeRekeningBelanja_blud  = '9cf6040a-2759-4d16-a3cf-3eee5194a2d5';
        $idSumberDana           = '9cdfcedb-cc19-4b65-b889-4a5e2dc0ebe3';

        $kode_rek_belanja_barjas = [
            '9cf6025b-eb56-4d10-9e00-17e74056b7f5',
            '9cf60308-4f19-4180-a2f1-87f7ed723add',
            '9cf60336-8954-46af-8d7d-dd32dd46b8a1',
        ];

        $anggaran = Anggaran::where('id_kode_rekening_belanja', $idKodeRekeningBelanja_blud)
            ->where('id_sumber_dana', $idSumberDana)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->select('jumlah_anggaran')
            ->first();

        $jumlah_anggaran = $anggaran ? $anggaran->jumlah_anggaran : 0;
        $query_admin = function ($id_kode_rekening_belanja) use ($tahunAnggaran, $sumber_dana) {
            return Rkbu::join('users', 'rkbus.id_user', '=', 'users.id_user')
                ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
                ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
                ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
                ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
                ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
                ->whereIn('sumber_danas.id_sumber_dana', $sumber_dana)
                ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
                ->where('rkbus.id_kode_rekening_belanja', $id_kode_rekening_belanja)
                ->where('rkbus.id_sub_kategori_rkbu', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
                // ->where('id_status_validasi_rka', '9cfb1f87-238b-4ea2-98f0-4255e578b1d1')
                ->sum('total_anggaran');
        };

        $total_anggaran_pegawai_admin  = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_modal_admin  = $query_admin('9cf6040a-2759-4d16-a3cf-3eee5194a2d5');
        $total_anggaran_barjas_admin = $query_admin($idKodeRekeningBelanja_blud);
        $total_anggaran_admin = $total_anggaran_pegawai_admin + $total_anggaran_modal_admin + $total_anggaran_barjas_admin;

        $selisih = $jumlah_anggaran - $total_anggaran_barjas_admin;

        // Ambil sub kategori rkbu berdasarkan id_user dari session dan id_jenis_kategori_rkbu
        $sub_kategori_rkbus = SubKategoriRkbu::join('rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            // ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_sub_kategori_rkbu', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu',  $currentJenisKategori)
            ->select('sub_kategori_rkbus.*') // Pilih data sub_kategori_rkbu
            ->distinct() // Hilangkan duplikasi
            ->get();

        $query = $model->newQuery()
            ->join('users', 'rkbus.id_user', '=', 'users.id_user')
            ->join('ksps', 'users.id_ksp', '=', 'ksps.id_ksp')
            ->join('pejabats', 'users.id_pejabat', '=', 'pejabats.id_pejabat')
            ->join('sub_kategori_rkbus', 'rkbus.id_sub_kategori_rkbu', '=', 'sub_kategori_rkbus.id_sub_kategori_rkbu')
            ->join('kategori_rkbus', 'sub_kategori_rkbus.id_kategori_rkbu', '=', 'kategori_rkbus.id_kategori_rkbu')
            ->join('jenis_kategori_rkbus', 'kategori_rkbus.id_jenis_kategori_rkbu', '=', 'jenis_kategori_rkbus.id_jenis_kategori_rkbu')
            ->join('rekening_belanjas', 'rekening_belanjas.id_kode_rekening_belanja', '=', 'rkbus.id_kode_rekening_belanja')
            ->join('aktivitas', 'aktivitas.id_aktivitas', '=', 'rekening_belanjas.id_aktivitas')
            ->join('sub_kegiatans', 'sub_kegiatans.id_sub_kegiatan', '=', 'aktivitas.id_sub_kegiatan')
            ->join('sumber_danas', 'sumber_danas.id_sumber_dana', '=', 'sub_kegiatans.id_sumber_dana')
            // ->where('pejabats.id_pejabat', $id_pejabat)
            ->where('rkbus.nama_tahun_anggaran', $tahunAnggaran)
            ->where('rkbus.id_sub_kategori_rkbu', '9d03d062-b7d6-490e-ad71-10d92c5628cb')
            ->where('jenis_kategori_rkbus.id_jenis_kategori_rkbu', $currentJenisKategori);

        // 🔄 Filter Berdasarkan Sub Kategori
        if (!empty($subKategoriFilter)) {
            $query->where('sub_kategori_rkbus.id_sub_kategori_rkbu', $subKategoriFilter);
        }

        // 🔄 Filter Berdasarkan Status Validasi
        if (!empty($statusValidasiFilter)) {
            $query->where('rkbus.id_status_validasi_rka', $statusValidasiFilter);
        }

        if (!empty($sumberDanaFilter)) {
            $query->where('sumber_danas.id_sumber_dana', $sumberDanaFilter);
        }

        // 🔍 Pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('rkbus.nama_pegawai', 'like', "%{$search}%")
                    ->orWhere('sub_kategori_rkbus.nama_sub_kategori_rkbu', 'like', "%{$search}%")
                    ->orWhere('kategori_rkbus.nama_kategori_rkbu', 'like', "%{$search}%")
                    ->orWhere('users.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('ksps.nama_ksp', 'like', "%{$search}%");
            });
        }

        // Dapatkan data rkbu setelah filter
        $rkbus = $query->select('rkbus.*')->get();

        // Hitung total anggaran dari data yang diambil
        $total_anggaran = $rkbus->sum('total_anggaran');

        return $query->select([
            'rkbus.*',
            'users.nama_lengkap as user_name',
            'ksps.nama_ksp as ksp',
            'sub_kategori_rkbus.nama_sub_kategori_rkbu',
            'kategori_rkbus.nama_kategori_rkbu',
            'jenis_kategori_rkbus.nama_jenis_kategori_rkbu',
            'sumber_danas.nama_sumber_dana',
            'total_anggaran',
            DB::raw("CONCAT(sub_kategori_rkbus.kode_sub_kategori_rkbu,'. ', sub_kategori_rkbus.nama_sub_kategori_rkbu) as kategori_kode")
        ]);
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('barang-table')
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
                ->width(40)
                ->responsivePriority(1), // selalu tampil

            Column::computed('nama_pegawai')
                ->title('Name Barang')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('profil_pegawai')
                ->title('Profil Pegawai')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('gaji_pokok')
                ->title('Detail Anggaran')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(50)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::make('total_anggaran')->title('Total Anggaran')->responsivePriority(100),
            Column::computed('pengusul')
                ->title('Pengusul')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(1), // tampil lebih prioritas

            Column::computed('detail_anggaran')
                ->title('Detail Kegiatan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(1), // tampil lebih prioritas

            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->responsivePriority(1), // usahakan selalu tampil

            Column::computed('detail_id')
                ->title('Detail ID')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('dokumen')
                ->title('Dokumen Usulan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->addClass('text-break')
                ->width(50)
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('status')
                ->title('Status Usulan')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->addClass('text-break')
                ->width(50)
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('create')
                ->title('Creates At')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->addClass('text-break')
                ->width(50)
                ->responsivePriority(100), // tampil lebih prioritas

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RkbuPegawaiNonPnsAdmin_' . date('YmdHis');
    }
}
