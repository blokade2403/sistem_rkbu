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
                                            action="{{ route($routePrefix . '.update', $penempatans->id_penempatan) }}">
                                            @csrf
                                            <div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Lokasi
                                                                Barang</label>
                                                            <input type="text" class="form-control" placeholder="Lokasi"
                                                                name="lokasi_barang"
                                                                value="{{ $penempatans->lokasi_barang }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Penanggung
                                                                Jawab</label>
                                                            <input type="text" class="form-control" placeholder="PIC"
                                                                name="penanggung_jawab"
                                                                value="{{ $penempatans->penanggung_jawab }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Lokasi Lantai
                                                                Gedung</label>
                                                            <select id="form-control choices-select" name="tempat_lokasi"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option value="{{ $penempatans->tempat_lokasi }}">
                                                                    {{ $penempatans->tempat_lokasi }}
                                                                </option>
                                                                <option value="Lantai 1">Lantai 1</option>
                                                                <option value="Lantai 2">Lantai 2</option>
                                                                <option value="Lantai 3">Lantai 3</option>
                                                                <option value="Lantai 4">Lantai 4</option>
                                                                <option value="Lantai 5">Lantai 5</option>
                                                                <option value="Lantai 6">Lantai 6</option>
                                                                <option value="Lantai 7">Lantai 7</option>
                                                                <option value="Lantai 8">Lantai 8</option>
                                                                <option value="Lantai 9">Lantai 9</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Lokasi
                                                                Gedung</label>
                                                            <select id="form-control choices-select" name="gedung"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option value="{{ $penempatans->gedung }}">
                                                                    {{ $penempatans->gedung }}
                                                                </option>
                                                                <option value="Gedung A">Gedung A</option>
                                                                <option value="Gedung B">Gedung B</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <hr class="my-4 text-muted">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
                                                    <label class="form-check-label" for="same-address">Yakin isian anda
                                                        pada form ini </label>
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
