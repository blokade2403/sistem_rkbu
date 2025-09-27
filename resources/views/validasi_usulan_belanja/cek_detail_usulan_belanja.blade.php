<x-layouts.main class="main">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <x-partials.breadcrumb />
                <div class="page-body">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="col-lg-12 col-12 mb-lg-0 mb-4">
                            <div class="card invoice-preview-card">
                                <div class="card-body">

                                    <!-- Header Instansi -->
                                    <div class="row align-items-center">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2 mt-4 mb-3">
                                            <img src="{{ asset('storage/uploads/' . basename($gambar2)) }}"
                                                width="105" height="75" class="img-fluid" />
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <p class="mt-4 mb-1">{{ $judul_header1 }}</p>
                                            <h4 class="mt-1 mb-1">{{ $nama }}</h4>
                                            <p class="mb-1">{{ $alamat }} Telepon: {{ $tlp }}
                                                Faksimile: {{ $tlp }}</p>
                                            <p class="mt-1">Website: {{ $website }} | Email:
                                                {{ $email }}</p>
                                        </div>
                                        <div class="col-md-2 mt-4 mb-3 text-end">
                                            <img src="{{ asset('storage/uploads/' . basename($gambar1)) }}"
                                                width="145" height="105" class="img-fluid" />
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Judul -->
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h5 class="fw-bold my-2">DATA USULAN BELANJA BARANG / JASA</h5>
                                        </div>
                                    </div>

                                    <!-- Detail Usulan -->
                                    <div class="card-body">
                                        <div class="row invoive-info">
                                            <div class="col-md-9 col-xs-12 invoice-client-info">
                                                <h6></h6>
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Program</td>
                                                            <td class="col-md-9">: {{ $no_inv->kode_program }}.
                                                                {{ $no_inv->nama_program }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Kegiatan</td>
                                                            <td class="col-md-9">: {{ $no_inv->kode_kegiatan }}.
                                                                {{ $no_inv->nama_kegiatan }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Rekening Belanja</td>
                                                            <td class="col-md-9">:
                                                                {{ $no_inv->kode_rekening_belanja }}.
                                                                {{ $no_inv->nama_rekening_belanja }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Kategori</td>
                                                            <td class="col-md-9">: {{ $no_inv->kode_kategori_rkbu }}.
                                                                {{ $no_inv->nama_kategori_rkbu }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Sub Kategori</td>
                                                            <td class="col-md-9">:
                                                                {{ $no_inv->kode_sub_kategori_rkbu }}.
                                                                {{ $no_inv->nama_sub_kategori_rkbu }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-3 col-sm-12">
                                                <h6 class="m-b-20">Invoice :
                                                    <span class="text-primary f-18">
                                                        #<strong>{{ $no_inv->no_usulan_barang ?? 'N/A' }}</strong></span>
                                                </h6>
                                                <h6 class="text-uppercase text-primary">Total Usulan :
                                                    <span>Rp.{{ number_format($get_total ?? collect($keranjang)->sum('total_anggaran_usulan_barang'), 0, ',', '.') }}</span>
                                                </h6>
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Pengusul</td>
                                                            <td class="col-md-5">: {{ $no_inv->nama_pengusul_barang }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Unit</td>
                                                            <td class="col-md-5">: {{ $no_inv->unit }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Sumber Dana</td>
                                                            <td class="col-md-5">: {{ $no_inv->nama_sumber_dana }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabel Barang -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive mb-4 text-nowrap">
                                                <div class="mb-2">
                                                    <a href="{{ route('usulan_barang_details.downloadReportExport', $no_inv->id_usulan_barang) }}"
                                                        class="btn btn-xs rounded-pill btn-outline-secondary">
                                                        <span class="tf-icons mdi mdi-download-outline me-1"></span>
                                                        Download Excel
                                                    </a>
                                                </div>

                                                <table id="example" class="table table-striped">
                                                    <thead class="table-light border-top">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Spesifikasi</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Vol</th>
                                                            <th>PPN</th>
                                                            <th>Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($keranjang as $key => $item)
                                                            <tr>
                                                                <td>{{ $key + 1 }}
                                                                </td>
                                                                <td class="text-wrap">
                                                                    <span
                                                                        class="text-primary f-16">#&nbsp;{{ $item->nama_barang }}</span><br />
                                                                    <span class="f-12">ID RKBU:
                                                                        {{ $item->id_rkbu }}</span>
                                                                </td>
                                                                <td class="text-wrap"
                                                                    style="white-space: normal; word-wrap: break-word;">
                                                                    <span
                                                                        class="fa-sm">{{ $item->spek_detail }}</span>
                                                                </td>
                                                                <td>Rp.
                                                                    {{ number_format($item->harga_barang, 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ $item->jumlah_usulan_barang }}
                                                                    {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}
                                                                </td>
                                                                <td>Rp.
                                                                    {{ number_format(($item->harga_barang * $item->ppn) / 100, 0, ',', '.') }}
                                                                </td>
                                                                <td>Rp.
                                                                    {{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                <!-- Total -->
                                                <div class="card-body">
                                                    <div class="row invoive-info">
                                                        <div class="col-md-9 col-xs-12 invoice-client-info">
                                                            <h6></h6>
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="4" class="align-top px-2 py-3">
                                                                            <p><span class="fw-semibold">Status
                                                                                    Sebelumnya:</span></p>
                                                                            <span class="badge bg-warning mb-3"></span>
                                                                            <span class="badge bg-success mb-3"></span>
                                                                            <span class="badge bg-info mb-3"></span>
                                                                            <p><span class="fw-semibold">Dokumen
                                                                                    Pendukung:</span></p>
                                                                            <span>Tidak Ada File Pendukung</span><br />
                                                                            <span class="fa-xs">Tanggal Upload: </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="col-md-3 col-sm-12">
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-end px-4 py-3">
                                                                            <p><span class="fw-semibold">Sub
                                                                                    Total:</span>
                                                                                {{ number_format($get_total - $ppn, 0, ',', '.') }}
                                                                            </p>
                                                                            <p><span class="fw-semibold">PPN:</span>
                                                                                {{ number_format($ppn, 0, ',', '.') }}
                                                                            </p>
                                                                            <p><span class="fw-semibold">Total:</span>
                                                                                {{ number_format($get_total, 0, ',', '.') }}
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="row">
                                        <div class="col-md-4 m-3">
                                            <div class="demo-inline-spacing">
                                                <a href="{{ route($routePrefix . '.index') }}"
                                                    class="btn btn-info btn-outline-info">
                                                    <i class="icofont icofont-cart-alt"></i>Kembali
                                                </a>
                                                <a href="{{ route($routePrefix . '.downloadPDF', ['id_usulan_barang' => $invoice->id_usulan_barang]) }}"
                                                    class="btn btn-info btn-outline-info">
                                                    <i class="icofont icofont-cart-alt"></i>Print
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Note -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="fw-bold">Note:</span>
                                                <span>Harap Menunggu, Barang Pesanan anda sedang dalam proses.
                                                    Terima Kasih.</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.main>
