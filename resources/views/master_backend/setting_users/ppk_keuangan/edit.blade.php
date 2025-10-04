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
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step create
                                    </span>
                                    {{ $title }} Info
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-6">
                            <div class="px-lg-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Form {{ $title2 }}</h5>
                                            <p class="text-muted">Fill all information below</p>
                                        </div>
                                        <form id="confirmSubmitForm" method="POST"
                                            action="{{ route('ppk_keuangans.update', $ppk_keuangans->id_ppk_keuangan) }}">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <div class="row g-3">

                                                    {{-- Nama PPK Keuangan --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama PPK Keuangan</label>
                                                            <input type="text" class="form-control" placeholder="nama"
                                                                name="nama_ppk_keuangan"
                                                                value="{{ old('nama_ppk_keuangan', $ppk_keuangans->nama_ppk_keuangan ?? '') }}">
                                                        </div>
                                                    </div>

                                                    {{-- NIP PPK Keuangan --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">NIP PPK Keuangan</label>
                                                            <input type="text" class="form-control" placeholder="nip"
                                                                name="nip_ppk_keuangan"
                                                                value="{{ old('nip_ppk_keuangan', $ppk_keuangans->nip_ppk_keuangan ?? '') }}">
                                                        </div>
                                                    </div>

                                                    {{-- Status --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('status', $ppk_keuangans->status ?? '') == '' ? 'selected' : '' }}>
                                                                    Pilih Status
                                                                </option>
                                                                <option value="aktif"
                                                                    {{ old('status', $ppk_keuangans->status ?? '') == 'aktif' ? 'selected' : '' }}>
                                                                    Aktif
                                                                </option>
                                                                <option value="tidak aktif"
                                                                    {{ old('status', $ppk_keuangans->status ?? '') == 'tidak aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <hr class="my-4 text-muted">

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
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
