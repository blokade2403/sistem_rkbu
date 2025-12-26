@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header">
                    <div class="col-xl-12">
                        <div class="card-body p-0">
                            <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center"
                                role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-alert-triangle text-warning me-2 icon-sm">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <div class="flex-grow-1 text-truncate">
                                    Your free trial expired in <b>17</b> days.
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="pages-pricing.html"
                                        class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                                </div>
                            </div>
                        </div> <!-- end card-body-->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fs-15 fw-semibold">No. Invoice</h5>
                                <p class="text-primary fs-20">#<strong>{{ $no_inv->no_usulan_barang ?? 'N/A' }}</strong></p>
                                <div class="d-flex flex-wrap justify-content-evenly">
                                    <p class="text-muted mb-0"><i
                                            class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2 rounded-circle shadow"></i>Completed
                                    </p>
                                    <p class="text-muted mb-0"><i
                                            class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2 rounded-circle shadow"></i>In
                                        Progress</p>
                                    <p class="text-muted mb-0"><i
                                            class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2 rounded-circle shadow"></i>To
                                        Do</p>
                                </div>
                            </div>
                            <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0"
                                style="height: 6px;">
                                <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%"
                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
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
                                        @php
                                            $isPegawai = Route::is('usulan_spj_pegawai.*');
                                        @endphp
                                        @if ($isPegawai)
                                            <th>No</th>
                                            <th>Nama Pegawai</th>
                                            <th>Status</th>
                                            <th>Harga Remunerasi</th>
                                            <th>Vol</th>
                                            <th>PPN</th>
                                            <th>Total Remunerasi</th>
                                            <th>Action</th>
                                        @else
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Status</th>
                                            <th>Harga Satuan</th>
                                            <th>Vol</th>
                                            <th>PPN</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        @endif

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($keranjang as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}
                                            </td>
                                            <td class="text-wrap">
                                                @if ($isPegawai)
                                                    <span
                                                        class="text-primary f-16">#&nbsp;{{ $item->nama_pegawai }}</span><br />
                                                @else
                                                    <span
                                                        class="text-primary f-16">#&nbsp;{{ $item->nama_barang }}</span><br />
                                                @endif
                                                <span class="fs-10">ID RKBU:
                                                    {{ $item->id_rkbu }}</span><br />
                                                <span class="fs-10 text-muted">Spesifikasi:
                                                    {{ $item->spek_detail }}</span>

                                            </td>
                                            <td class="text-wrap" style="white-space: normal; word-wrap: break-word;">
                                                @php
                                                    $status = $item->status_usulan_barang_detail ?? 'Menunggu Proses';

                                                    switch ($status) {
                                                        case 'Menunggu Proses':
                                                            $badgeClass = 'badge-soft-warning';
                                                            break;
                                                        case 'Proses Pesanan':
                                                            $badgeClass = 'badge-soft-success';
                                                            break;
                                                        case 'Ditolak':
                                                            $badgeClass = 'badge-soft-danger';
                                                            break;
                                                        default:
                                                            $badgeClass = 'badge-soft-secondary';
                                                            break;
                                                    }
                                                @endphp

                                                <span class="badge {{ $badgeClass }}">
                                                    {{ $status }}
                                                </span>
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
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        <a href="" class="text-primary d-inline-block">
                                                            <i class="ri-eye-fill fs-16"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="{{ route($routePrefix . '.edit', $item->id_usulan_barang_detail) }}"
                                                            class="text-primary d-inline-block edit-item-btn">
                                                            <i class="ri-pencil-fill fs-16"></i>
                                                        </a>
                                                    </li>
                                                    <form
                                                        action="{{ route($routePrefix . '.destroy', $item->id_spj_detail) }}"
                                                        method="POST" class="delete-form mb-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-danger d-inline-block remove-item-btn"
                                                            data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Remove"><i
                                                                class="ri-delete-bin-5-fill fs-16"></i>
                                                        </button>
                                                    </form>
                                                </ul>
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
                                                            <i
                                                                class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            Kembali
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="{{ route('cek_usulan_barang_barjass.downloadPDF', ['id_usulan_barang' => $invoice->id_usulan_barang]) }}"
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
                        <p class="text-muted mb-0">ID: {{ $no_inv->no_usulan_barang ?? 'N/A' }}</p>
                        <p class="text-muted mb-0">Tanggal :{{ $no_inv->created_at ?? 'N/A' }}</p>
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
                                    <h6 class="fs-14 mb-1">{{ $no_inv->nama_pengusul_barang }}</h6>
                                    <p class="text-muted mb-0">{{ $no_inv->unit }}</p>
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
            <div class="card border card-border-danger card ribbon-box border mb-lg-0 right">
                <div class="card-header">
                    <div class="d-flex">
                        <h5 class="card-title flex-grow-1 mb-0">Status Validasi Usulan</h5>
                    </div>
                </div>
                <div class="ribbon ribbon-danger ribbon-shape">Validasi</div>
                <form action="{{ route($routePrefix . '.updateStatus', $no_inv->id_spj) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{ $no_inv->tanggal_validasi_pengadaan }}"
                        name="tanggal_validasi_pengadaan">
                    <input type="hidden" name="id_usulan_barang" value="{{ $no_inv->id_usulan_barang }}">

                    <div class="card-body">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Pilih Status Validasi</label>

                            @if ($no_inv->status_validasi_pengadaan == 'Proses Pengadaan Barang')
                                {{-- Select dibuat disabled agar tidak bisa diubah --}}
                                <select class="form-select" disabled>
                                    <option selected>{{ $no_inv->status_validasi_pengadaan }}</option>
                                </select>
                                {{-- Hidden input supaya value tetap terkirim ke backend --}}
                                <input type="hidden" name="status_validasi_pengadaan"
                                    value="{{ $no_inv->status_validasi_pengadaan }}">
                            @else
                                <select name="status_validasi_pengadaan" class="form-select" data-choices
                                    data-choices-sorting="true">
                                    <option value="{{ $no_inv->status_validasi_pengadaan }}" selected>
                                        {{ $no_inv->status_validasi_pengadaan }}
                                    </option>
                                    <option value="Proses Pengadaan Barang">Proses Pengadaan Barang</option>
                                    <option value="Pending Pengadaan">Di Pending</option>
                                </select>
                            @endif
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Keterangan Validasi</label>
                            <textarea name="keterangan_validasi_pengadaan" class="form-control" rows="3"
                                @if ($no_inv->status_validasi_pengadaan == 'Proses Pengadaan Barang') readonly @endif>{{ $no_inv->keterangan_validasi_pengadaan }}</textarea>
                        </div>

                        <div class="col-lg-12">
                            @if ($no_inv->status_validasi_pengadaan == 'Proses Pengadaan Barang')
                                <button type="button" class="btn btn-outline-warning btn-load" disabled>
                                    <span class="d-flex align-items-center">
                                        <span class="flex-grow-1 me-2">Loading...</span>
                                        <span class="spinner-grow flex-shrink-0" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </span>
                                    </span>
                                </button>
                            @else
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div><!--end card-->
            <div class="card"></div>
            @include('cek_usulan_belanja.status_validasi_cek_usulan_belanja')
        </div><!--end col-->
    </div><!--end row-->
    <style>
        button.remove {
            border: none;
            background: transparent;
            padding: 0;
            outline: none;
        }

        button.remove:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
@endsection

{{-- <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <div class="col-lg-12 col-12 mb-lg-0 mb-4">
                            <div class="card invoice-preview-card">
                                <div class="card-body">

                                    <!-- Header Instansi -->
                                    <div class="row align-items-center">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2 mt-4 mb-3">
                                            <img src="{{ asset('storage/judul_header/' . basename($gambar2)) }}"
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
                                            <img src="{{ asset('storage/judul_header/' . basename($gambar1)) }}"
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
                                                            <td class="col-md-9">{{ $no_inv->kode_program }}.
                                                                {{ $no_inv->nama_program }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Kegiatan</td>
                                                            <td class="col-md-9">{{ $no_inv->kode_kegiatan }}.
                                                                {{ $no_inv->nama_kegiatan }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Rekening Belanja</td>
                                                            <td class="col-md-9">{{ $no_inv->kode_rekening_belanja }}.
                                                                {{ $no_inv->nama_rekening_belanja }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Kategori</td>
                                                            <td class="col-md-9">{{ $no_inv->kode_kategori_rkbu }}.
                                                                {{ $no_inv->nama_kategori_rkbu }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Sub Kategori</td>
                                                            <td class="col-md-9">{{ $no_inv->kode_sub_kategori_rkbu }}.
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
                                                            <td class="col-md-5">{{ $no_inv->nama_pengusul_barang }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Unit</td>
                                                            <td class="col-md-5">{{ $no_inv->unit }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="pe-3 fw-medium">Sumber Dana</td>
                                                            <td class="col-md-5">{{ $no_inv->nama_sumber_dana }}</td>
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
                                                        class="btn btn-sm btn-mit btn-warning">
                                                        <i class="icofont icofont-download-alt"></i>
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
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($keranjang as $key => $item)
                                                            <tr>
                                                                <td><span class="f-16 text-info">{{ $key + 1 }}</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="f-16 text-info">{{ $item->nama_barang }}</span><br />
                                                                    <span class="fa-xs">ID RKBU:
                                                                        {{ $item->id_rkbu }}</span>
                                                                </td>
                                                                <td class="text-wrap"
                                                                    style="white-space: normal; word-wrap: break-word;">
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
                                                                <td class="action-icon d-flex align-items-center">
                                                                    <a href="{{ route($routePrefix . '.edit', $item->id_usulan_barang_detail) }}"
                                                                        class="m-r-15 text-info" data-toggle="tooltip"
                                                                        data-placement="top" title=""
                                                                        data-original-title="Edit"><i
                                                                            class="icofont icofont-ui-edit"></i></a>
                                                                    <form
                                                                        action="{{ route($routePrefix . '.destroy', $item->id_spj_detail) }}"
                                                                        method="POST" class="delete-form mb-0">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-danger"
                                                                            style="background: none; border: none; padding: 0; cursor: pointer;"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="" data-original-title="Delete"><i
                                                                                class="icofont icofont-delete-alt"></i></button>
                                                                    </form>
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
                                                                            <span class="fa-xs">Tanggal Upload:
                                                                            </span>
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
                                    <form action="{{ route($routePrefix . '.updateStatus', $no_inv->id_spj) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" value="{{ $no_inv->tanggal_validasi_pengadaan }}"
                                            name="tanggal_validasi_pengadaan">
                                        <input type="hidden" name="id_usulan_barang"
                                            value="{{ $no_inv->id_usulan_barang }}">
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
                                                                    <option
                                                                        value="{{ $no_inv->status_validasi_pengadaan }}">
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
                                                <a href="{{ route($routePrefix . '.index') }}"
                                                    class="btn btn-mit btn-inverse">
                                                    <i class="icofont icofont-ui-reply"></i>Kembali
                                                </a>
                                                <a href="{{ route('cek_usulan_barang_barjass.downloadPDF', ['id_usulan_barang' => $invoice->id_usulan_barang]) }}"
                                                    class="btn btn-mit btn-info">
                                                    <i class="icofont icofont-printer"></i>Print
                                                </a>
                                            </div>
                                        </div>
                                    </form>
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
    </div> --}}
