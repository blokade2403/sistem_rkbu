@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header">
                    @include('partials.animate_progress_form')
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
            </div><!--end card-->

            <div class="card">
                <div class="card-block">
                    <div class="card-body">
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
                                        <th>Status</th>
                                        <th>Harga Satuan</th>
                                        <th>Vol</th>
                                        <th>PPN</th>
                                        <th>Action</th>
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
                                                <span class="fs-10">ID RKBU:
                                                    {{ $item->id_rkbu }}</span><br />
                                                <span class="fs-10 text-muted">Spesifikasi:
                                                    {{ $item->spek_detail }}</span>

                                            </td>
                                            <td class="text-wrap" style="white-space: normal; word-wrap: break-word;">

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
                                            <td>
                                                @if ($master_spj->status_serah_terima_bendahara == 'Selesai')
                                                    <button
                                                        class="btn btn-sm btn-outline-success btn-load right ms-auto nexttab nexttab"
                                                        data-nexttab="pills-success-tab"><span
                                                            class="d-flex align-items-center">
                                                            <span class="flex-grow-1 me-2">
                                                                Selesai
                                                            </span>
                                                            <span class="spinner-grow flex-shrink-0" role="status">
                                                                <span class="visually-hidden">Selesai</span>
                                                            </span>
                                                        </span></button>
                                                @else
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                            <a href="" class="text-primary d-inline-block">
                                                                <i class="ri-eye-fill fs-16"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                            <a href="{{ route($routePrefix . '.editkomponen', $item->id_usulan_barang_detail) }}"
                                                                class="text-primary d-inline-block edit-item-btn">
                                                                <i class="ri-pencil-fill fs-16"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item remove" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                            <a data-bs-toggle="modal" href="#deleteOrder"
                                                                class="text-danger d-inline-block remove-item-btn">
                                                                <i class="ri-delete-bin-5-fill fs-16"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endif
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
                                    <div class="row">
                                        <div class="text-end">
                                            <div class="demo-inline-spacing">
                                                <a href="{{ route($routePrefix . '.index') }}"
                                                    class="btn btn-primary btn-label waves-effect waves-light">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            Kembali
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="{{ route($routePrefix . '.downloadPDF', ['id_usulan_barang' => $no_inv->id_usulan_barang]) }}"
                                                    class="btn btn-info btn-label waves-effect waves-light">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="ri-printer-fill label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            Print
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0"><i
                                class="mdi mdi-truck-fast-outline align-middle me-1 text-muted"></i> Proses Pengadaan</h5>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="badge badge-soft-primary fs-11">Track Order</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/uetqnvvg.json" trigger="loop"
                            colors="primary:#4b38b3,secondary:#0ab39c" style="width:80px;height:80px"></lord-icon>
                        <h5 class="fs-16 mt-2">Proses</h5>
                        @php
                            $firstKeranjang = $keranjang->first();
                        @endphp

                        <p class="text-muted mb-0">ID: {{ $firstKeranjang->no_usulan_barang ?? 'N/A' }}</p>
                        <p class="text-muted mb-0">Tanggal: {{ $firstKeranjang->created_at ?? 'N/A' }}</p>
                    </div>
                </div>
            </div><!--end card-->

            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Pengusul Detail</h5>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="link-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}" alt=""
                                        class="avatar-sm rounded shadow">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    @php $first = $keranjang->first(); @endphp
                                    <h6 class="fs-14 mb-1">{{ $first->nama_pengusul_barang ?? '-' }}</h6>
                                    <p class="text-muted mb-0">{{ $first->nama_unit ?? '-' }}</p>
                                </div>
                            </div>
                        </li>
                        <li class=""><i class="ri-bank-fill me-2 align-middle text-muted fs-16"></i>
                            <span class="badge badge-label bg-secondary">
                                <i class="mdi mdi-circle-medium"></i>
                                {{ $no_inv->nama_sumber_dana }}</span>
                        </li>
                        <li><i class="ri-money-dollar-circle-line me-2 align-middle text-muted fs-16"></i>Total:
                            <span
                                class="fs-20">Rp.{{ number_format($get_total ?? collect($keranjang)->sum('total_anggaran_usulan_barang'), 0, ',', '.') }}
                        </li>
                    </ul>
                </div>
            </div><!--end card-->
            @include('cek_usulan_belanja.status_validasi_cek_usulan_belanja')
        </div><!--end col-->
        @include('master_spj.partials.ceklis_verifikasi')
        <div class="vertical-overlay"></div>
        @php
            $nama_level = ['Administartor'];
            $level_ksp = ['Validasi'];
            $level_kabag = ['Validasi RKA'];
            $level_admin_ppk = ['Admin PPK'];
            $level_pptk = ['PPTK', 'PPTK Umum', 'PPTK Yanmed'];
            $level_pengadaan_ppk = [
                'Administrator',
                'Pendukung PPK',
                'Admin PPK',
                'PPK',
                'PPBJ',
                'Pengurus Barang',
                'PPK Keuangan',
                'PPTK',
                'Validasi Keuangan',
                'Verifikator',
                'Direktur',
                'Bendahara',
                'PPTK',
                'PPTK Umum',
                'PPTK Yanmed',
                'Validasi RKA',
            ];
        @endphp


        @if (session('nama_level_user') == 'Admin PPK')
            @include('master_spj.admin_pengadaan.proses_pesanan')
            @include('master_spj.admin_pengadaan.proses_bast_pengadaan')
            @include('master_spj.admin_pengadaan.proses_tukar_faktur')
            @include('master_spj.admin_pengadaan.proses_serah_terima_bendahara')
        @endif
        @if (session('nama_level_user') == 'Pendukung PPK')
            @include('master_spj.foto_barang.proses_barang_datang')
        @endif
        @if (in_array(session('nama_level_user'), $level_pengadaan_ppk))
            @include('master_spj.foto_barang.detail_foto_barang_datang')
        @endif
        @if (session('nama_level_user') == 'PPBJ')
            @include('master_spj.ppbj.form_validasi_ppbj')
        @endif
        @if (session('nama_level_user') == 'PPK')
            @include('master_spj.ppk.form_validasi_ppk')
        @endif
        @if (in_array(session('nama_level_user'), $level_pptk))
            @include('master_spj.pptk.form_validasi_pptk')
        @endif
        @if (session('nama_level_user') == 'Verifikator')
            @include('master_spj.verifikator.form_validasi_verifikator')
        @endif
        @if (session('nama_level_user') == 'PPK Keuangan')
            @include('master_spj.ppk_keuangan.form_validasi_ppk_keuangan')
        @endif
        @if (session('nama_level_user') == 'Direktur')
            @include('master_spj.direktur.form_validasi_direktur')
        @endif
        @if (session('nama_level_user') == 'Bendahara')
            @include('master_spj.bendahara.proses_terima_spj')
            @include('master_spj.bendahara.proses_pembayaran')
        @endif

        @if (session('nama_level_user') == 'Administrator')
            @include('master_spj.administrator.edit_admin_spj')
        @endif

    </div><!--end row-->
@endsection
