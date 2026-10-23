@extends('layouts.main')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                        <div
                            class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                            <img class="mw-50px mw-lg-75px" src="assets/media/svg/brand-logos/volicity-9.svg"
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
                            </div>
                            @include('rkbu.rkbu_barjas.modal_komponen')
                            <form id="kt_modal_new_target_form"
                                class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                                action="{{ route($routePrefix . '.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div
                                    class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-6">
                                    <div class="w-100">

                                        <label class="form-label fw-bold required">
                                            Nama Barang / Komponen
                                        </label>

                                        <div class="input-group mb-3">
                                            <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                                                value="{{ old('nama_barang') }}"
                                                placeholder="Pilih komponen atau input manual">

                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_new_komponen">
                                                Pilih Komponen
                                            </button>
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
                                name="volume_1" id="volume_1" required>
                        </div>
                        <div class="col-xl-4 mt-2 mb-3">
                            <div class="d-flex flex-column">
                                <select class="form-select" name="satuan_1" data-control="select2"
                                    data-placeholder="Select an option">
                                    <option value="" data-select2-id="select2-data-63-6qoa">Pilih uraian 1
                                    </option>
                                    @foreach ($uraian_satu as $item)
                                        <option value="{{ $item->nama_uraian_1 }}">
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
                                name="volume_2" id="volume_2" required>
                        </div>
                        <div class="col-xl-4 mt-2 mb-3">
                            <div class="d-flex flex-column">
                                <select class="form-select" name="satuan_2" data-control="select2"
                                    data-placeholder="Select an option">
                                    <option value="" data-select2-id="select2-data-63-6qoa">Pilih uraian 2
                                    </option>
                                    @foreach ($uraian_dua as $item)
                                        <option value="{{ $item->nama_uraian_2 }}">
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
                                name="jumlah_volume" readonly>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Spesifikasi</div>
                        </div>
                        <div class="col-xl-9">
                            <textarea name="spesifikasi" id="spesifikasi" class="form-control form-control-solid" placeholder="Isi Spesifikasi"
                                rows="5">{{ old('spesifikasi') }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Harga Satuan</div>
                        </div>
                        <div class="col-xl-3">
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Enter Harga Satuan" name="harga_satuan" id="harga_satuan"
                                value="{{ old('harga_satuan') }}">
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

                                <input type="hidden" name="ppn" id="ppn" value="{{ $ppn->ppn }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Total Anggaran</div>
                        </div>

                        <div class="col-xl-6">
                            <input type="text" class="form-control form-control-solid" id="total_anggaran"
                                name="total_anggaran" readonly>
                        </div>
                        <input type="hidden" name="total_anggaran_real" id="total_anggaran_real">
                    </div>
                     <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Penempatan</div>
                        </div>

                        <div class="col-xl-6">
                            <input type="text" class="form-control form-control-solid"
                                name="penempatan">
                        </div>
                    </div>
                     <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Link Ekatalog</div>
                        </div>

                        <div class="col-xl-9">
                            <input type="text" class="form-control form-control-solid"
                                name="link_ekatalog">
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Skala Prioritas</div>
                        </div>
                        <div class="col-xl-9">
                            <div class="row g-9" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default active d-flex text-start p-6"
                                        data-kt-button="true">
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="rating" value="1"
                                                checked="checked" />
                                        </span>
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">Prioritas 1</span>
                                            <span class="fw-bold fs-8 text-gray-600">Usulan lebih diutamakan
                                                (wajib)</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                        data-kt-button="true">
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="rating"
                                                value="2" />
                                        </span>
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">Prioritas 2</span>
                                            <span class="fw-bold fs-8 text-gray-600">Usulan diutamakan</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-md-4 col-lg-12 col-xxl-4">
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6"
                                        data-kt-button="true">
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="rating"
                                                value="3" />
                                        </span>
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bolder mb-1 d-block">Prioritas 3</span>
                                            <span class="fw-bold fs-8 text-gray-600">Usulan tidak diutamakan</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">Sub Kategori RKBU</div>
                        </div>
                        <div class="col-xl-6">
                            <div class="d-flex flex-column">
                                <select class="form-select" name="sub_kategori_rkbu_id" data-control="select2"
                                    data-placeholder="Select an option">
                                    <option value="" data-select2-id="select2-data-63-6qoa">Pilih uraian 2
                                    </option>
                                    @foreach ($sub_kategori_rkbu as $item)
                                        <option value="{{ $item->id_sub_kategori_rkbu }}"
                                            {{ old('sub_kategori_rkbu_id') == $item->id_sub_kategori_rkbu ? 'selected' : '' }}>
                                            {{ $item->kode_sub_kategori_rkbu }}. {{ $item->nama_sub_kategori_rkbu }}
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
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Program
                                        </label>
                                        <select id="program" name="program_id" class="form-select"
                                            data-control="select2" data-placeholder="Pilih Program">
                                            <option></option>
                                            @foreach ($programs as $program)
                                                <option value="{{ $program->id_program }}">
                                                    {{ $program->kode_program }} -
                                                    {{ $program->nama_program }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Kegiatan
                                        </label>
                                        <select id="kegiatan" name="kegiatan_id" class="form-select"
                                            data-control="select2" data-placeholder="Pilih Kegiatan">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="required form-label fw-semibold fs-6 mb-2">
                                            Sub Kegiatan
                                        </label>
                                        <select id="sub_kegiatan" name="sub_kegiatan_id" class="form-select"
                                            data-control="select2" data-placeholder="Pilih Sub Kegiatan">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="notice d-flex bg-light-warning rounded border-primary border border-dashed p-6 mb-6">
                        <div class="row w-100">
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">Upload KAP 1</label>
                            </div>
                            <div class="col-xl-9 mb-3">
                                <input type="file" name="upload_file_1" class="form-control">
                            </div>
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">Upload KAP 2</label>
                            </div>
                            <div class="col-xl-9 mb-3">
                                <input type="file" name="upload_file_2" class="form-control">
                            </div>
                            <div class="col-xl-3 mb-3">
                                <label class="fs-6 fw-bold mt-2">Upload KAP 3</label>
                            </div>
                            <div class="col-xl-9 mb-3">
                                <input type="file" name="upload_file_3" class="form-control">
                                <small class="text-muted">
                                    Format yang diperbolehkan: PDF, JPG, PNG, DOC, XLS
                                </small>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6">
                    <a href="{{ route($routePrefix.'.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
