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
                                    {{ $title }}
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
                                            action="{{ route($routePrefix . '.update', $anggaran->id_anggaran) }}">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <div class="row g-3">

                                                    {{-- Nama Anggaran --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="nama_anggaran" class="form-label">Nama
                                                                Anggaran</label>
                                                            <input type="text" name="nama_anggaran"
                                                                value="{{ old('nama_anggaran', $anggaran->nama_anggaran) }}"
                                                                class="form-control" id="nama_anggaran">
                                                        </div>
                                                    </div>

                                                    {{-- Jumlah Anggaran --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="jumlah_anggaran" class="form-label">Jumlah
                                                                Anggaran</label>
                                                            <input type="text" name="jumlah_anggaran"
                                                                value="{{ old('jumlah_anggaran', $anggaran->jumlah_anggaran) }}"
                                                                class="form-control" id="jumlah_anggaran">
                                                        </div>
                                                    </div>

                                                    {{-- Tahun Anggaran --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="tahun_anggaran" class="form-label">Tahun
                                                                Anggaran</label>
                                                            <select name="tahun_anggaran" id="tahun_anggaran"
                                                                class="form-select-sm" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('tahun_anggaran', $anggaran->tahun_anggaran) ? '' : 'selected' }}>
                                                                    Pilih Tahun Anggaran
                                                                </option>
                                                                @foreach ($tahun_anggaran as $key)
                                                                    <option value="{{ $key->nama_tahun_anggaran }}"
                                                                        {{ old('tahun_anggaran', $anggaran->tahun_anggaran) == $key->nama_tahun_anggaran ? 'selected' : '' }}>
                                                                        {{ $key->nama_tahun_anggaran }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Sub Kategori Rekening --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="id_kode_rekening_belanja" class="form-label">Sub
                                                                Kategori Rekening</label>
                                                            <select name="id_kode_rekening_belanja"
                                                                id="id_kode_rekening_belanja" class="form-select"
                                                                data-choices data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_kode_rekening_belanja', $anggaran->id_kode_rekening_belanja) ? '' : 'selected' }}>
                                                                    Pilih Sub Kategori Rekening
                                                                </option>
                                                                @foreach ($rekening_belanjas as $key)
                                                                    <option value="{{ $key->id_kode_rekening_belanja }}"
                                                                        {{ old('id_kode_rekening_belanja', $anggaran->id_kode_rekening_belanja) == $key->id_kode_rekening_belanja ? 'selected' : '' }}>
                                                                        {{ $key->kode_rekening_belanja }}.
                                                                        {{ $key->nama_rekening_belanja }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Sumber Dana --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="id_sumber_dana" class="form-label">Sumber
                                                                Dana</label>
                                                            <select name="id_sumber_dana" id="id_sumber_dana"
                                                                class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_sumber_dana', $anggaran->id_sumber_dana) ? '' : 'selected' }}>
                                                                    Pilih Sumber Dana
                                                                </option>
                                                                @foreach ($sumber_danas as $key)
                                                                    <option value="{{ $key->id_sumber_dana }}"
                                                                        {{ old('id_sumber_dana', $anggaran->id_sumber_dana) == $key->id_sumber_dana ? 'selected' : '' }}>
                                                                        {{ $key->nama_sumber_dana }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <hr class="my-4 text-muted">

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
                                                    <label class="form-check-label" for="same-address">Yakin isian anda pada
                                                        form ini</label>
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
                                <h5 class="fs-14 text-primary mb-0"><i class="ri-shopping-cart-fill align-middle me-2"></i>
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
