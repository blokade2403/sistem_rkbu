@extends('layouts.main')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                        <div
                            class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                            <img class="mw-50px mw-lg-75px" src="{{ asset('assets/media/svg/brand-logos/volicity-9.svg') }}"
                                alt="image" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-1">
                                        <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">CRM
                                            Dashboard</a>
                                        <span class="badge badge-light-success me-auto">In Progress</span>
                                    </div>
                                    <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">#1 Tool to get
                                        started with Web Apps any Kind &amp; size</div>
                                </div>
                                <div class="d-flex mb-4">
                                    <a href="#" class="btn btn-sm btn-bg-light btn-active-color-primary me-3"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_users_search">Add User</a>
                                    <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_target">Add Target</a>
                                    <div class="me-0">
                                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="bi bi-three-dots fs-3"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                                            data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                    Payments</div>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Create Invoice</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link flex-stack px-3">Create Payment
                                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                        title="Specify a target name for future usage and reference"></i></a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Generate Bill</a>
                                            </div>
                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                data-kt-menu-placement="right-end">
                                                <a href="#" class="menu-link px-3">
                                                    <span class="menu-title">Subscription</span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Plans</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Billing</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3">Statements</a>
                                                    </div>
                                                    <div class="separator my-2"></div>
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content px-3">
                                                            <label
                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                <input class="form-check-input w-30px h-20px"
                                                                    type="checkbox" value="1" checked="checked"
                                                                    name="notifications" />
                                                                <span
                                                                    class="form-check-label text-muted fs-6">Recuring</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="menu-item px-3 my-1">
                                                <a href="#" class="menu-link px-3">Settings</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="kt_modal_new_target_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                                action="{{ route('rkbu.modal_kantor.update', $barjas->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div
                                    class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-6">
                                    <div class="w-100">

                                        <label class="form-label fw-bold required">
                                            Nama Barang / Komponen
                                        </label>

                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                                                value="{{ old('nama_barang', $barjas->nama_barang) }}"
                                                placeholder="Pilih komponen atau input manual">
                                        </div>
                                        <div class="form-text">
                                            Jika komponen tidak tersedia, silakan input manual pada field di atas.
                                        </div>

                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="separator"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bolder">Project Budget</div>
                </div>
                <div class="card-body">
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Volume</div>
                        </div>
                        <div class="col-xl-2 mt-2">
                            <input type="number" class="form-control form-control-solid" placeholder="Vol"
                                name="volume_1" id="volume_1" value="{{ old('volume_1', $barjas->volume_1) }}" required>
                        </div>
                        <div class="col-xl-4 mt-2 mb-3">
                            <div class="d-flex flex-column">
                                <select class="form-select" name="satuan_1" data-control="select2"
                                    data-placeholder="Pilih Satuan">

                                    <option value="">Pilih Satuan</option>

                                    @foreach ($uraian_satu as $item)
                                        <option value="{{ $item->nama_uraian_1 }}"
                                            {{ old('satuan_1', $barjas->satuan_1) == $item->nama_uraian_1 ? 'selected' : '' }}>
                                            {{ $item->nama_uraian_1 }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Koefisien</div>
                        </div>
                        <div class="col-xl-2 mt-2">
                            <input type="number" class="form-control form-control-solid" placeholder="Koef"
                                name="volume_2" id="volume_2" value="{{ old('volume_2', $barjas->volume_2) }}">
                        </div>
                        <div class="col-xl-4 mt-2 mb-3">
                            <div class="d-flex flex-column">
                                <select class="form-select" name="satuan_2" data-control="select2"
                                    data-placeholder="Pilih Satuan">

                                    <option value="">Pilih Satuan</option>

                                    @foreach ($uraian_dua as $item)
                                        <option value="{{ $item->nama_uraian_2 }}"
                                            {{ old('satuan_2', $barjas->satuan_2) == $item->nama_uraian_2 ? 'selected' : '' }}>
                                            {{ $item->nama_uraian_2 }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Jumlah Volume</div>
                        </div>
                        <div class="col-xl-2">
                            <input type="text" class="form-control form-control-solid" id="jumlah_volume"
                                name="jumlah_volume" value="{{ number_format($barjas->jumlah_volume, 2, '.', '') }}"
                                readonly>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Spesifikasi</div>
                        </div>
                        <div class="col-xl-9">
                            <textarea name="spesifikasi" id="spesifikasi" class="form-control form-control-solid" placeholder="Isi Spesifikasi"
                                rows="5">{{ old('spesifikasi', $barjas->spesifikasi) }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Harga Satuan</div>
                        </div>
                        <div class="col-xl-3">
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Enter Harga Satuan" name="harga_satuan" id="harga_satuan"
                                value="{{ number_format($barjas->harga_satuan, 0, ',', '.') }}">
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">PPN</div>
                        </div>

                        <div class="col-xl-9">
                            <div class="form-check form-switch form-check-custom form-check-solid">

                                <input class="form-check-input" type="checkbox" id="is_ppn" checked>

                                <label class="form-check-label fw-bold text-gray-400 ms-3">
                                    {{ $ppn->ppn }}%
                                </label>

                                <input type="hidden" name="ppn" id="ppn"
                                    value="{{ old('ppn', $barjas->ppn) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Total Anggaran</div>
                        </div>

                        <div class="col-xl-6">
                            <input type="text" class="form-control form-control-solid" id="total_anggaran"
                                name="total_anggaran" value="{{ number_format($barjas->total_anggaran, 0, ',', '.') }}"
                                readonly>
                        </div>
                        <input type="hidden" name="total_anggaran_real" id="total_anggaran_real">
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Penempatan</div>
                        </div>

                        <div class="col-xl-6">
                            <input type="text" class="form-control form-control-solid" name="penempatan"
                                value="{{ old('penempatan', $barjas->penempatan) }}">
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Link Ekatalog</div>
                        </div>

                        <div class="col-xl-9">
                            <input type="text" class="form-control form-control-solid" name="link_ekatalog"
                                value="{{ old('link_ekatalog', $barjas->link_ekatalog) }}">
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">
                                Skala Prioritas
                            </div>
                        </div>

                        <div class="col-xl-9">
                            <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">

                                {{-- Prioritas 1 --}}
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6
                    {{ old('rating', $barjas->rating ?? 1) == 1 ? 'active' : '' }}"
                                        data-kt-button="true">

                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">

                                            <input class="form-check-input" type="radio" name="rating" value="1"
                                                {{ old('rating', $barjas->rating ?? 1) == 1 ? 'checked' : '' }}>
                                        </span>

                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">
                                                Prioritas 1
                                            </span>

                                            <span class="fw-bold fs-8 text-gray-600">
                                                Usulan lebih diutamakan (wajib)
                                            </span>
                                        </span>
                                    </label>
                                </div>

                                {{-- Prioritas 2 --}}
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6
                    {{ old('rating', $barjas->rating ?? 1) == 2 ? 'active' : '' }}"
                                        data-kt-button="true">

                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">

                                            <input class="form-check-input" type="radio" name="rating" value="2"
                                                {{ old('rating', $barjas->rating ?? 1) == 2 ? 'checked' : '' }}>
                                        </span>

                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">
                                                Prioritas 2
                                            </span>

                                            <span class="fw-bold fs-8 text-gray-600">
                                                Usulan diutamakan
                                            </span>
                                        </span>
                                    </label>
                                </div>

                                {{-- Prioritas 3 --}}
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6
                    {{ old('rating', $barjas->rating ?? 1) == 3 ? 'active' : '' }}"
                                        data-kt-button="true">

                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">

                                            <input class="form-check-input" type="radio" name="rating" value="3"
                                                {{ old('rating', $barjas->rating ?? 1) == 3 ? 'checked' : '' }}>
                                        </span>

                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">
                                                Prioritas 3
                                            </span>

                                            <span class="fw-bold fs-8 text-gray-600">
                                                Usulan tidak diutamakan
                                            </span>
                                        </span>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">
                                Sub Kategori RKBU
                            </div>
                        </div>

                        <div class="col-xl-9">
                            <div class="d-flex flex-column">

                                <select class="form-select" name="sub_kategori_rkbu_id" data-control="select2"
                                    data-placeholder="Pilih Sub Kategori RKBU">

                                    <option value="">
                                        Pilih Sub Kategori RKBU
                                    </option>

                                    @foreach ($sub_kategori_rkbu as $item)
                                        <option value="{{ $item->id_sub_kategori_rkbu }}"
                                            {{ old('sub_kategori_rkbu_id', $barjas->rkbu->sub_kategori_rkbu_id) == $item->id_sub_kategori_rkbu ? 'selected' : '' }}>

                                            {{ $item->kode_sub_kategori_rkbu }}
                                            -
                                            {{ $item->nama_sub_kategori_rkbu }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>

                    @if (auth()->user()->roles->contains('nama_role', 'Administrator'))

                        <div class="card card-bordered mb-6 bg-light-primary rounded border-primary border border-dashed">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Pilih Program, Kegiatan dan Sub Kegiatan
                                </h3>
                            </div>

                            <div class="card-body">

                                <div class="row g-5">

                                    {{-- PROGRAM --}}
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Program
                                        </label>

                                        <select id="program" name="program_id" class="form-select"
                                            data-control="select2">

                                            <option value="">Pilih Program</option>

                                            @foreach ($programs as $program)
                                                <option value="{{ $program->id_program }}"
                                                    {{ old('program_id', $barjas->rkbu->program_id ?? '') == $program->id_program ? 'selected' : '' }}>

                                                    {{ $program->kode_program }}
                                                    -
                                                    {{ $program->nama_program }}

                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    {{-- KEGIATAN --}}
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Kegiatan
                                        </label>

                                        <select id="kegiatan" name="kegiatan_id" class="form-select"
                                            data-control="select2">

                                            @if (isset($kegiatans))
                                                @foreach ($kegiatans as $kegiatan)
                                                    <option value="{{ $kegiatan->id_kegiatan }}"
                                                        {{ old('kegiatan_id', $barjas->rkbu->kegiatan_id ?? '') == $kegiatan->id_kegiatan ? 'selected' : '' }}>

                                                        {{ $kegiatan->kode_kegiatan }}
                                                        -
                                                        {{ $kegiatan->nama_kegiatan }}

                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    {{-- SUB KEGIATAN --}}
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Sub Kegiatan
                                        </label>

                                        <select id="sub_kegiatan" name="sub_kegiatan_id" class="form-select"
                                            data-control="select2">

                                            @if (isset($subKegiatans))
                                                @foreach ($subKegiatans as $sub)
                                                    <option value="{{ $sub->id_sub_kegiatan }}"
                                                        {{ old('sub_kegiatan_id', $barjas->rkbu->sub_kegiatan_id ?? '') == $sub->id_sub_kegiatan ? 'selected' : '' }}>

                                                        {{ $sub->kode_sub_kegiatan }}
                                                        -
                                                        {{ $sub->nama_sub_kegiatan }}

                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                </div>

                            </div>
                        </div>

                    @endif

                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-6">

                        <div class="row w-100">

                            {{-- KAP 1 --}}
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">
                                    Upload KAP 1
                                </label>
                            </div>

                            <div class="col-xl-9 mb-3">

                                @if (!empty($barjas->upload_file_1))
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $barjas->upload_file_1) }}" target="_blank"
                                            class="badge badge-light-primary">
                                            Lihat File KAP 1
                                        </a>
                                    </div>
                                @endif

                                <input type="file" name="upload_file_1" class="form-control">

                            </div>

                            {{-- KAP 2 --}}
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">
                                    Upload KAP 2
                                </label>
                            </div>

                            <div class="col-xl-9 mb-3">

                                @if (!empty($barjas->upload_file_2))
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $barjas->upload_file_2) }}" target="_blank"
                                            class="badge badge-light-primary">
                                            Lihat File KAP 2
                                        </a>
                                    </div>
                                @endif

                                <input type="file" name="upload_file_2" class="form-control">

                            </div>

                            {{-- KAP 3 --}}
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">
                                    Upload KAP 3
                                </label>
                            </div>

                            <div class="col-xl-9 mb-3">

                                @if (!empty($barjas->upload_file_3))
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $barjas->upload_file_3) }}" target="_blank"
                                            class="badge badge-light-primary">
                                            Lihat File KAP 3
                                        </a>
                                    </div>
                                @endif

                                <input type="file" name="upload_file_3" class="form-control">

                                <small class="text-muted">
                                    Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX
                                    (Maksimal 5 MB)
                                </small>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6">
                     <a href="{{ route($routePrefix.'.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
                <!--end::Card footer-->
            </div>
            <!--end::Card-->
            </form>
            <!--end:Form-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
@endsection
