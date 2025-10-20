<?php

namespace App\DataTables\Assets;

use App\Models\Asset\Asset;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class AssetsDataTable extends DataTable
{

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn() // otomatis tambahkan nomor urut
            ->addColumn('nama_barang', function ($row) {
                return '
                     <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded p-1 me-2">
                            <a href="' . asset("storage/foto_asset/" . basename($row->foto)) . '" target="_blank">
                                <img src="' . asset("storage/foto_asset/" . basename($row->foto)) . '" alt="" class="img-fluid d-block">
                            </a>
                        </div>
                        <div>
                            <h3 class="fs-14 my-1"><a href="apps-ecommerce-product-details.html" class="text-reset">' . e($row->nama_asset) . '</a></h3>
                            <span class="text-muted">' . e($row->tahun_perolehan) . '</span>
                        </div>
                    </div>
                    ';
            })
            ->addColumn('kode_asset', function ($row) {
                return '<span class="badge bg-soft-info text-info">' . e($row->kode_asset) . '-' . e($row->no_register) . '</span>';
            })

            ->addColumn('spek', function ($row) {
                return ($row->spek);
            })
            ->addColumn('aksi_asset', function ($row) {
                $html = '
                        <div class="hstack gap-2 mt-4 mt-sm-0">
                            <a href="' . route('barang_assets.details', ['id' => $row->id_asset]) . '" 
                                class="btn btn-outline-primary btn-icon waves-effect waves-light shadow-none" 
                                title="Detail Asset">
                                <i class="ri-restart-fill"></i>
                            </a>
                            
                            <a href="' . route('barang_assets.generate_qrcode', ['id_asset' => $row->id_asset]) . '" 
                                class="btn btn-outline-info btn-icon waves-effect waves-light shadow-none" 
                                title="Generate QR Code">
                                <i class="ri-qr-code-line"></i>
                            </a>
                            ';

                // Tambahkan tombol reklas hanya jika kondisi_asset == 'Rusak Berat'
                if ($row->kondisi_asset === 'Rusak Berat') {
                    $html .= '
                            <a href="' . route('barang_assets.reklas_update', ['id_asset' => $row->id_asset]) . '" 
                                class="btn btn-outline-danger btn-icon waves-effect waves-light shadow-none" 
                                title="Reklas Asset Rusak Berat">
                                <i class="ri-tools-fill"></i>
                            </a>
                                ';
                }

                $html .= '</div>';

                return $html;
            })
            ->addColumn('qrcode_path', function ($row) {
                return $row->qrcode_path
                    ? '<a href="' . asset("storage/qrcode_assets/" . basename($row->qrcode_path)) . '" target="_blank">
                        <img src="' . asset("storage/qrcode_assets/" . basename($row->qrcode_path)) . '" width="80" height="80">
                   </a>'
                    : '<span class="text-muted">Belum Digenerate</span>';
            })

            ->addColumn('harga_asset', function ($row) {
                return ' Rp.' . number_format($row->harga_asset);
            })
            ->addColumn('status_asset', function ($row) {
                return '<span class="fs-14">' . $row->status_asset . '</span>';
            })
            ->addColumn('kondisi_asset', function ($row) {
                $badgeClass = match ($row->kondisi_asset) {
                    'Baik' => 'badge badge-outline-success',
                    'Rusak Ringan' => 'badge badge-outline-warning',
                    'Rusak Berat' => 'badge badge-outline-danger',
                    default => 'badge badge-outline-secondary',
                };
                return '<span class="' . $badgeClass . '">' . $row->kondisi_asset . '</span>';
            })

            ->addColumn('penempatan', function ($row) {
                return '<span class="fs-14">' . ($row->Penempatan->lokasi_barang ?? 'Belum Ditempatkan') . '</span>';
            })

            ->addColumn('status_asset', function ($row) {
                return '<span class="fs-14">' . $row->status_asset . '</span>';
            })

            ->addColumn('pengguna', function ($row) {
                return '
                <p class="fs-14">' . ($row->pengguna_asset ?? 'Belum di Set') . '</p>
                <p class="fs-14 mb-0">' . ($row->nip_pengguna ?? 'Belum di Set') . '</p>
                <p class="fs-14 mb-0">' . ($row->jabatan_pengguna ?? 'Belum di Set') . '</p>';
            })

            ->addColumn('bpkb', function ($row) {
                return '
                <p class="fs-14">' . ($row->tgl_bpkb ?? 'Belum di Set') . '</p>
                <p class="fs-14 mb-0">' . ($row->no_rangka ?? 'Belum di Set') . '</p>
                <p class="fs-14 mb-0">' . ($row->no_polisi ?? 'Belum di Set') . '</p>
                <p class="fs-14 mb-0">' . ($row->no_mesin ?? 'Belum di Set') . '</p>';
            })

            ->filter(function ($query) {
                if (request()->has('search') && $search = request('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_asset', 'like', "%{$search}%")
                            ->orWhere('spek', 'like', "%{$search}%")
                            ->orWhere('tahun_perolehan', 'like', "%{$search}%")
                            ->orWhere('kondisi_asset', 'like', "%{$search}%")
                            ->orWhere('lokasi_barang', 'like', "%{$search}%")
                            ->orWhere('pengguna_asset', 'like', "%{$search}%")
                            ->orWhere('kode_asset', 'like', "%{$search}%")
                            ->orWhere('harga_asset', 'like', "%{$search}%")
                            ->orWhere('status_asset', 'like', "%{$search}%")
                            ->orWhere('merk', 'like', "%{$search}%")
                            ->orWhere('no_register', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%");
                    });
                }
            })

            ->addColumn('action', function ($row) {
                $routePrefix = 'assets'; // pastikan variabel ini ada di class DataTable

                $disabledBtn = '<button class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No Action</button>';

                // Atur default
                $editUrl = null;
                $deleteUrl = null;

                // Kalau lolos kondisi -> kasih tombol edit & delete
                $editUrl   = route($routePrefix . '.edit', $row->id_asset);
                $deleteUrl = route($routePrefix . '.destroy', $row->id_asset);

                return view('components.partials.action-buttons', compact('editUrl', 'deleteUrl'))->render();
            })
            ->rawColumns(['qrcode_path', 'nama_barang', 'kode_asset', 'filter', 'penempatan', 'bpkb', 'pengguna', 'aksi_asset', 'spek', 'harga_asset', 'status_asset', 'kondisi_asset', 'action', 'routePrefix' => 'assets']);
    }

    public function query(Asset $model): QueryBuilder
    {
        $tahunOptions = request('tahun_perolehan');
        $kondisi      = request('kondisi_asset');
        $idPenempatan = request('id_penempatan'); // ambil filter dari request

        $query = $model->newQuery()
            ->join(
                'jenis_kategori_rkbus',
                'jenis_kategori_rkbus.id_jenis_kategori_rkbu',
                '=',
                'assets.id_jenis_kategori_rkbu'
            )
            ->leftJoin(
                'penempatans',
                'penempatans.id_penempatan',
                '=',
                'assets.id_penempatan'
            )
            ->where(function ($query) {
                $query->where('assets.status_reklas_arb', '!=', 'Reklas')
                    ->orWhereNull('assets.status_reklas_arb');
            });

        // 🔎 Filter Tahun Perolehan
        if (!empty($tahunOptions)) {
            $query->where('assets.tahun_perolehan', $tahunOptions);
        }

        // 🔎 Filter Kondisi Asset
        if (!empty($kondisi)) {
            $query->where('assets.kondisi_asset', $kondisi);
        }

        // 🔎 Filter Lokasi Penempatan
        if ($idPenempatan === 'null') {
            // khusus pilih "Belum Ditempatkan"
            $query->whereNull('assets.id_penempatan');
        } elseif (!empty($idPenempatan)) {
            // pilih lokasi tertentu
            $query->where('assets.id_penempatan', $idPenempatan);
        }
        // kalau kosong, tampilkan semua

        return $query->select([
            'assets.*',
            'jenis_kategori_rkbus.nama_jenis_kategori_rkbu',
            'penempatans.lokasi_barang as lokasi_barang'

        ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('assets-table')
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

            Column::computed('nama_barang')
                ->title('Name Barang')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(2), // tampil lebih prioritas

            Column::make('kode_asset')->title('Kode Asset')->responsivePriority(100),
            Column::computed('aksi_asset')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(100)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('qrcode_path')
                ->title('QRCode')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(100)
                ->responsivePriority(2), // tampil lebih prioritas

            Column::make('harga_asset')->title('Harga Asset')->responsivePriority(100),

            Column::computed('action')
                ->title('Action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->responsivePriority(1), // usahakan selalu tampil

            Column::computed('kondisi_asset')
                ->title('Kondisi Asset')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(2), // tampil lebih prioritas

            Column::computed('penempatan')
                ->title('Lokasi Asset')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(1), // tampil lebih prioritas

            Column::computed('status_asset')
                ->title('Status Asset')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('spek')
                ->title('Spek Komponen')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(100)
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('pengguna')
                ->title('PIC')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(100)
                ->responsivePriority(100), // tampil lebih prioritas

            Column::computed('bpkb')
                ->title('No. Mesin')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-left')
                ->width(100)
                ->responsivePriority(100), // tampil lebih prioritas

        ];
    }


    protected function filename(): string
    {
        return 'Assets_' . date('YmdHis');
    }
}
