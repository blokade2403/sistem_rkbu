<!-- Konten Detail akan diisi oleh JavaScript -->
<div class="container">
    <!-- Header Instansi -->
    <div class="row align-items-center">
        <div class="col-md-1"></div>
        <div class="col-md-2 mt-4 mb-3">
            <img src="{{ asset('storage/judul_header/' . basename($gambar2)) }}" width="105" height="75"
                class="img-fluid" />
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
            <img src="{{ asset('storage/judul_header/' . basename($gambar1)) }}" width="145" height="105"
                class="img-fluid" />
        </div>
    </div>

    <hr>

    <!-- Judul -->
    <div class="row">
        <div class="col-12 text-center">
            <h6 class="fw-bold my-2">DETAIL RINCIAN MASTER SPJ</h6>
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
                            <td class="pe-3 fw-medium"><span class="f-12"> Program</span></td>
                            <td class="col-md-9"><span class="f-12">: {{ $no_inv->kode_program }}.
                                    {{ $no_inv->nama_program }}</span></td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Kegiatan</span></td>
                            <td class="col-md-9"><span class="f-12">: {{ $no_inv->kode_kegiatan }}.
                                    {{ $no_inv->nama_kegiatan }}</span></td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Rekening Belanja</span></td>
                            <td class="col-md-9"><span class="f-12">: {{ $no_inv->kode_rekening_belanja }}.
                                    {{ $no_inv->nama_rekening_belanja }}</span></td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Kategori</span></td>
                            <td class="col-md-9"><span class="f-12">: {{ $no_inv->kode_kategori_rkbu }}.
                                    {{ $no_inv->nama_kategori_rkbu }}</span></td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Sub Kategori</span></td>
                            <td class="col-md-9"><span class="f-12">: {{ $no_inv->kode_sub_kategori_rkbu }}.
                                    {{ $no_inv->nama_sub_kategori_rkbu }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-3 col-sm-12">
                <h6 class="m-b-20">
                    <span class="text-primary f-18">
                        #<strong>{{ $no_inv->no_usulan_barang ?? 'N/A' }}</strong></span>
                </h6>
                <span class="f-14 text-primary">Total Usulan :
                    <span>Rp.
                        {{ number_format($get_total ?? collect($keranjang)->sum('total_anggaran_usulan_barang'), 0, ',', '.') }}</span>
                </span>
                <table>
                    <tbody>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Pengusul</span></td>
                            <td class="col-md-5"><span class="f-12">: {{ $no_inv->nama_pengusul_barang }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Unit</span></td>
                            <td class="col-md-5"><span class="f-12">: {{ $no_inv->unit }}</span></td>
                        </tr>
                        <tr>
                            <td class="pe-3 fw-medium"><span class="f-12">Sumber Dana</span></td>
                            <td class="col-md-5"><span class="f-12">: {{ $no_inv->nama_sumber_dana }}</span></td>
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
                                <td><span class="f-16 text-info">{{ $key + 1 }}</span>
                                </td>
                                <td>
                                    <span class="f-16 text-info">{{ $item->nama_barang }}</span><br />
                                    <span class="fa-xs">ID RKBU:
                                        {{ $item->id_rkbu }}</span>
                                </td>
                                <td class="text-wrap" style="white-space: normal; word-wrap: break-word;">
                                    <span class="fa-sm">{{ $item->spek_detail }}</span>
                                </td>
                                <td><span class="f-14">Rp.
                                        {{ number_format($item->harga_barang, 0, ',', '.') }}</span>
                                </td>
                                <td><span class="f-14">{{ $item->jumlah_usulan_barang }}
                                        {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}</span>
                                </td>
                                <td><span class="f-14">Rp.
                                        {{ number_format(($item->harga_barang * $item->ppn) / 100, 0, ',', '.') }}</span>
                                </td>
                                <td><span class="f-14">Rp.
                                        {{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}</span>
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
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pe-3 fw-medium"><span class="f-14">Sub Total</span></td>
                                        <td class="col-md-5"><span class="f-14">:
                                                Rp. {{ number_format($get_total - $ppn, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium"><span class="f-14">PPN</span></td>
                                        <td class="col-md-5"><span class="f-14">:
                                                Rp. {{ number_format($ppn, 0, ',', '.') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium"><span class="f-14">Total</span></td>
                                        <td class="col-md-5"><span class="f-14">:
                                                Rp. {{ number_format($get_total, 0, ',', '.') }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="panel panel-success">
                <div class="panel-heading bg-success">
                    Success Panel
                </div>
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices
                        accumsan.</p>
                </div>
                <div class="panel-footer text-success">
                    Panel Footer
                </div>
            </div>
        </div>
    </div>

    {{-- <form action="{{ route($routePrefix . '.updateStatus', $no_inv->id_spj) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{ $no_inv->tanggal_validasi_pengadaan }}"
                            name="tanggal_validasi_pengadaan">
                        <input type="hidden" name="id_usulan_barang" value="{{ $no_inv->id_usulan_barang }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="userName-2" class="block">Status Proses
                                                    Pengadaan
                                                    Barang</label>
                                            </div>
                                            <div class="col-sm-12">
                                                <select name="status_validasi_pengadaan"
                                                    id="status_validasi_pengadaan"
                                                    class="form-control js-example-basic-single">
                                                    <option value="">Pilih Status Pengadaan
                                                    </option>
                                                    <option value="{{ $no_inv->status_validasi_pengadaan }}">
                                                        {{ $no_inv->status_validasi_pengadaan }}
                                                    </option>
                                                    <option value="Proses Pengadaan Barang">Proses
                                                        Pengadaan
                                                        Barang</option>
                                                    <option value="Pending Pengadaan">Di Pending
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="note" class="form-label fw-bold">Catatan:</label>
                                        <textarea class="form-control" rows="2" id="note" name="keterangan_validasi_pengadaan"
                                            placeholder="Invoice note">{{ $no_inv->keterangan_validasi_pengadaan }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-mit btn-primary">Kirim
                                    Usualan</button>
                                <a href="{{ route($routePrefix . '.index') }}" class="btn btn-mit btn-inverse">
                                    <i class="icofont icofont-ui-reply"></i>Kembali
                                </a>
                                <a href="{{ route('cek_usulan_barang_barjass.downloadPDF', ['id_usulan_barang' => $invoice->id_usulan_barang]) }}"
                                    class="btn btn-mit btn-info">
                                    <i class="icofont icofont-printer"></i>Print
                                </a>
                            </div>
                        </div>
                    </form> --}}
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
    {{-- <div class="pb-3 rounded-top">
        <h6 class="text-center mb-3 bg-label-info" style="padding: 10px 20px; font-size: 20px;">
            DETAIL RINCIAN MASTER SPJ
        </h6>
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap">
                <dd></dd>
                <div class="my-3">
                    <table>
                        <tbody>
                            <tr>
                                <td class="pe-3 fw-medium">No Usulan Barang</td>
                                <td>:</td>
                                @if ($keranjang->isNotEmpty())
                                    <td>{{ $keranjang->first()->no_usulan_barang }}</td>
                                @else
                                    <td>No data available</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium">Kategori RKBU</td>
                                <td>:</td>
                                @if ($keranjang->isNotEmpty())
                                    <td>{{ $keranjang->first()->kode_kategori_rkbu }}.
                                        {{ $keranjang->first()->nama_kategori_rkbu }}</td>
                                @else
                                    <td>No data available</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium text-wrap">Sub Kategori RKBU</td>
                                <td>:</td>
                                @if ($keranjang->isNotEmpty())
                                    <td>{{ $keranjang->first()->kode_sub_kategori_rkbu }}.
                                        {{ $keranjang->first()->nama_sub_kategori_rkbu }}</td>
                                @else
                                    <td>No data available</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium">Pengusul</td>
                                <td>:</td>
                                <td>{{ $no_inv->nama_pengusul_barang }}</td>
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium">Unit</td>
                                <td>:</td>
                                <td>{{ $no_inv->unit }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between flex-wrap">
                <div class="my-3">
                    <table>
                        <tbody>
                            <tr>
                                <td class="pe-3 fw-medium">Total Anggaran</td>
                                <td>:</td>
                                <td><strong> <span class="badge bg-label-danger"> Rp.
                                            {{ number_format($get_total, 0, '.', '.') }}</span></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium text-wrap"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="my-3">
                    <table>
                        <tbody>
                            <tr>
                                <td class="pe-3 fw-medium">Tanggal Input</td>
                                <td>:</td>
                                <td>
                                    {{ $no_inv->created_at }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-3 fw-medium text-wrap">Sumber Dana</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $no_inv->nama_sumber_dana }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <table>
                <tr>
                    <td class="pe-3 fw-medium">Perusahaan</td>
                    <td>:</td>
                    <td><strong> {{ $detail_master_spj->nama_perusahaan }}</strong></td>
                </tr>
                <tr>
                    <td class="pe-3 fw-medium">Alamat</td>
                    <td>:</td>
                    <td>{{ $detail_master_spj->alamat_perusahaan }}</td>
                </tr>
                <tr>
                    <td class="pe-3 fw-medium">Telp</td>
                    <td>:</td>
                    <td>{{ $detail_master_spj->tlp_perusahaan }}</td>
                </tr>
                <tr>
                    <td class="pe-3 fw-medium">Email</td>
                    <td>:</td>
                    <td>{{ $detail_master_spj->email_perusahaan }}</td>
                </tr>
                <tr>
                    <td class="pe-3 fw-medium">Pimpinan</td>
                    <td>:</td>
                    <td>{{ $detail_master_spj->nama_direktur_perusahaan }}</td>
                </tr>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table m-0">
                <thead class="table-light-primary bg-warning border-top">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Spesifikasi</th>
                        <th>harga Satuan</th>
                        <th>Vol</th>
                        <th>PPN</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                @php
                    $no = 1;
                @endphp
                @foreach ($keranjang as $item)
                    <tr>
                        <td class="text-nowrap"><small class="text-info">{{ $no++ }}</small>
                        </td>
                        <td class="text-nowrap">
                            <small class="text-info">{{ $item->nama_barang }}</small><br />
                            <span class="fa-xs">ID: {{ $item->id_rkbu }} </span>
                        </td>
                        <td class="fy-2 text-wrap"><span class="fa-sm">{{ $item->spek_detail }}</span></td>
                        <td class="text-nowrap"><small>Rp.
                                {{ number_format($item->harga_barang, 0, '.', ',') }}</small></td>
                        <td class="text-nowrap"><small>{{ $item->jumlah_usulan_barang }} x
                                {{ $item->satuan_1_detail }}</small></td>
                        <td class="text-nowrap"><small>{{ $item->total_ppn }}</small></td>
                        <td class="text-nowrap"><small>Rp.
                                {{ number_format($item->total_anggaran_usulan_barang, 0, '.', ',') }}</small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table class="table m-0">
                <thead class="table-light border-top">
                </thead>

                <tbody>
                    <tr>
                        <td colspan="4" class="align-top px-4 py-3">
                            <p class="mb-2">
                                <span class="me-1 fw-semibold">Status Sebelumnya: </span>
                            </p>
                            <span class="badge bg-warning mb-3"></span>
                            <p class="mb-2">
                                <span class="me-1 fw-semibold">Dokumen Pendukung: </span>
                            </p>

                        </td>
                        <td class="text-end px-4 py-3">
                            <p class="mb-2">
                                <span class="me-1 fw-semibold">Sub Total:</span>
                                {{ number_format($get_total - $ppn, 0, ',', '.') }}
                            </p>
                            <p class="mb-2">
                                <span class="me-1 fw-semibold">PPN:</span>
                                {{ number_format($ppn, 0, ',', '.') }}
                            </p>
                            <p class="mb-2">
                                <span class="me-1 fw-semibold">Total:</span>
                                {{ number_format($get_total, 0, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="fw-semibold mb-2 text-end"></p>
                            <p class="fw-semibold mb-2 text-end"></p>
                            <p class="fw-semibold mb-0 text-end"></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @include('backend.pengadaan.sub_detail_master_spj.sub_data_spj_pesanan_barang')
        @include('backend.pengadaan.sub_detail_master_spj.sub_data_spj_pengiriman_barang')
        @include('backend.pengadaan.sub_detail_master_spj.sub_data_bast')
        @if ($master_spj->status_pembayaran === 'Sudah di Bayar')
            @include('backend.pengadaan.sub_detail_master_spj.sub_data_pembayaran')
        @endif
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <span class="fw-bold">Note:</span>
                    <span>Thank you for working with us!</span>
                </div>
            </div>
        </div>

    </div> --}}
</div>
test
