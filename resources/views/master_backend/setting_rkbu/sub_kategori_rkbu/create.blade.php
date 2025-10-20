@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-body form-steps">
                    <div class="row gy-5">
                        <div class="col-lg-2">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                    </span>
                                    {{ $title }} Info
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-7">
                            <div class="px-lg-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Form {{ $title2 }}</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>
                                        <form id="confirmSubmitForm" method="POST"
                                            action="{{ route($routePrefix . '.store') }}">
                                            @csrf
                                            <div>
                                                <div class="row g-3">

                                                    {{-- Nama Kategori RKBU --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Sub Kategori RKBU</label>
                                                            <input type="text" name="nama_sub_kategori_rkbu"
                                                                value="{{ old('nama_sub_kategori_rkbu') }}"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>

                                                    {{-- Kode Kategori RKBU --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Sub Kategori RKBU</label>
                                                            <input type="text" name="kode_sub_kategori_rkbu"
                                                                value="{{ old('kode_sub_kategori_rkbu') }}"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>

                                                    {{-- Obyek Belanja --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rekening Belanja</label>
                                                            <select name="id_kode_rekening_belanja" class="form-select"
                                                                data-choices data-choices-sorting="true" required>
                                                                <option disabled
                                                                    {{ old('id_kode_rekening_belanja') ? '' : 'selected' }}>
                                                                    Pilih Rekening Belanja
                                                                </option>
                                                                @foreach ($rekening_belanjas as $key)
                                                                    <option value="{{ $key->id_kode_rekening_belanja }}">
                                                                        {{ $key->kode_rekening_belanja }}.
                                                                        {{ $key->nama_rekening_belanja }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    {{-- Obyek Belanja --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kategori RKBU</label>
                                                            <select name="id_kategori_rkbu" class="form-select" data-choices
                                                                data-choices-sorting="true" required>
                                                                <option disabled
                                                                    {{ old('id_kategori_rkbu') ? '' : 'selected' }}>
                                                                    Pilih Kategori RKBU
                                                                </option>
                                                                @foreach ($kategori_rkbus as $key)
                                                                    <option value="{{ $key->id_kategori_rkbu }}"
                                                                        {{ old('id_kategori_rkbu') == $key->id_kategori_rkbu ? 'selected' : '' }}>
                                                                        {{ $key->kode_kategori_rkbu }}.
                                                                        {{ $key->nama_kategori_rkbu }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Obyek Belanja --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kategori RKBU</label>
                                                            <select name="id_sub_kategori_rekening" class="form-select"
                                                                data-choices data-choices-sorting="true" required>
                                                                <option disabled
                                                                    {{ old('id_sub_kategori_rekening') ? '' : 'selected' }}>
                                                                    Pilih Kategori RKBU
                                                                </option>
                                                                @foreach ($sub_kategori_rekenings as $key)
                                                                    <option value="{{ $key->id_sub_kategori_rekening }}"
                                                                        {{ old('id_sub_kategori_rekening') == $key->id_sub_kategori_rekening ? 'selected' : '' }}>
                                                                        {{ $key->kode_sub_kategori_rekening }}.
                                                                        {{ $key->nama_sub_kategori_rekening }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    {{-- Jenis Kategori RKBU --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Admin Pendukung</label>
                                                            <select name="id_admin_pendukung_ppk" class="form-select"
                                                                data-choices data-choices-sorting="true" required>
                                                                <option disabled
                                                                    {{ old('id_admin_pendukung_ppk') ? '' : 'selected' }}>
                                                                    Pilih Admin Pendukung
                                                                </option>
                                                                @foreach ($admin_pendukungs as $key)
                                                                    <option value="{{ $key->id_admin_pendukung_ppk }}"
                                                                        {{ old('id_admin_pendukung_ppk') == $key->id_admin_pendukung_ppk ? 'selected' : '' }}>
                                                                        {{ $key->nama_pendukung_ppk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    {{-- Jenis Kategori RKBU --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">PPTK</label>
                                                            <select name="id_pptk" class="form-select" data-choices
                                                                data-choices-sorting="true" required>
                                                                <option disabled {{ old('id_pptk') ? '' : 'selected' }}>
                                                                    Pilih PPTK
                                                                </option>
                                                                @foreach ($pptk as $key)
                                                                    <option value="{{ $key->id_pptk }}"
                                                                        {{ old('id_pptk') == $key->id_pptk ? 'selected' : '' }}>
                                                                        {{ $key->nama_pptk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Jenis Kategori RKBU --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select" data-choices
                                                                data-choices-sorting="true" required>
                                                                <option disabled {{ old('status') ? '' : 'selected' }}>
                                                                    Pilih Status
                                                                </option>
                                                                <option value="aktif">Aktif</option>
                                                                <option value="non aktif">Non Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-4 text-muted">

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address"
                                                        required>
                                                    <label class="form-check-label" for="same-address">
                                                        Yakin isian anda pada form ini
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                @include('partials.submit_buttons')
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <!-- end tab content -->
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fs-14 text-primary mb-0"><i
                                        class="ri-shopping-cart-fill align-middle me-2"></i>
                                    Your Form</h5>
                                <span class="badge bg-danger rounded-pill">3</span>
                            </div>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Product name</h6>
                                        <small class="text-muted">Brief description</small>
                                    </div>
                                    <span class="text-muted">$12</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
            <!-- end -->
        </div>
        <!-- end col -->
    </div>
@endsection
