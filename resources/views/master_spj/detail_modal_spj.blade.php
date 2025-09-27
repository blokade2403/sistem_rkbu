<div class="container">
    <div class="pb-3 rounded-top">
        <h6 class="text-center mb-3 bg-label-info" style="padding: 10px 20px; font-size: 20px;">
            DETAIL RINCIAN MASTER SPJ
        </h6>
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap">
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
                                            {{ number_format($get_total, 0, '.', '.') }}</span></strong></td>
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
                                    <span class="badge bg-secondary">{{ $no_inv->nama_sumber_dana }} </span>
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
                        <td class="text-nowrap"><small class="text-info">{{ $no++ }}</small></td>
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
                                {{ number_format($item->total_anggaran_usulan_barang, 0, '.', ',') }}</small></td>
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
    </div>
</div>
