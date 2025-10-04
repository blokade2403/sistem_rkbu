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
                                            action="{{ route($routePrefix . '.store') }}">
                                            @csrf
                                            <div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput"
                                                                class="form-label">Username</label>
                                                            <input type="text" name="username" class="form-control"
                                                                id="nameInput" placeholder="Enter your username">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Nama
                                                                Lengkap</label>
                                                            <input type="text" name="nama_lengkap" class="form-control"
                                                                placeholder="Enter your name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">NIP
                                                                User</label>
                                                            <input type="text" name="nip_user" class="form-control"
                                                                placeholder="Enter your nip">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Email</label>
                                                            <input type="text" name="email" class="form-control"
                                                                placeholder="Enter your email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput"
                                                                class="form-label">Password</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="Password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Unit</label>
                                                            <select id="form-control choices-select" name="id_unit"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Unit
                                                                </option>
                                                                @foreach ($units as $key)
                                                                    <option value="{{ $key->id_unit }}">
                                                                        {{ $key->nama_unit }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Level
                                                                User</label>
                                                            <select id="form-control choices-select" name="id_level"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Level
                                                                </option>
                                                                @foreach ($level_users as $key)
                                                                    <option value="{{ $key->id_level }}">
                                                                        {{ $key->nama_level_user }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Fase
                                                                User</label>
                                                            <select id="form-control choices-select" name="id_fase"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Fase
                                                                </option>
                                                                @foreach ($fases as $key)
                                                                    <option value="{{ $key->id_fase }}">
                                                                        {{ $key->nama_fase }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">KSP</label>
                                                            <select id="form-control choices-select" name="id_ksp"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih KSP
                                                                </option>
                                                                @foreach ($ksps as $key)
                                                                    <option value="{{ $key->id_ksp }}">
                                                                        {{ $key->nama_ksp }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput"
                                                                class="form-label">Pejabat</label>
                                                            <select id="form-control choices-select" name="id_pejabat"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Atasan
                                                                </option>
                                                                @foreach ($pejabats as $key)
                                                                    <option value="{{ $key->id_pejabat }}">
                                                                        {{ $key->nama_pejabat }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Pendukung
                                                                PPK</label>
                                                            <select id="form-control choices-select"
                                                                name="id_admin_pendukung_ppk" id="satuan_1"
                                                                class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Atasan
                                                                </option>
                                                                @foreach ($admin_pendukungs as $key)
                                                                    <option value="{{ $key->id_admin_pendukung_ppk }}">
                                                                        {{ $key->nama_pendukung_ppk }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">PPTK</label>
                                                            <select id="form-control choices-select" name="id_pptk"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Atasan
                                                                </option>
                                                                @foreach ($pptks as $key)
                                                                    <option value="{{ $key->id_pptk }}">
                                                                        {{ $key->nama_pptk }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Status
                                                                User</label>
                                                            <select id="form-control choices-select" name="status_user"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Status
                                                                </option>
                                                                <option value="aktif">Aktif</option>
                                                                <option value="tidak aktif">Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Status
                                                                Edit</label>
                                                            <select id="form-control choices-select" name="status_edit"
                                                                id="satuan_1" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option selected="" disabled="">Pilih Status
                                                                </option>
                                                                <option value="aktif">Aktif</option>
                                                                <option value="tidak aktif">Tidak Aktif</option>
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
