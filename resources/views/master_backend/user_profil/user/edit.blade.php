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
                                            action="{{ route($routePrefix . '.update', $users->id_user) }}">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <div class="row g-3">

                                                    {{-- Username --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Username</label>
                                                            <input type="text" name="username" class="form-control"
                                                                placeholder="Enter your username"
                                                                value="{{ old('username', $users->username) }}">
                                                        </div>
                                                    </div>

                                                    {{-- Nama Lengkap --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" name="nama_lengkap" class="form-control"
                                                                placeholder="Enter your name"
                                                                value="{{ old('nama_lengkap', $users->nama_lengkap) }}">
                                                        </div>
                                                    </div>

                                                    {{-- NIP User --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">NIP User</label>
                                                            <input type="text" name="nip_user" class="form-control"
                                                                placeholder="Enter your nip"
                                                                value="{{ old('nip_user', $users->nip_user) }}">
                                                        </div>
                                                    </div>

                                                    {{-- Email --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="text" name="email" class="form-control"
                                                                placeholder="Enter your email"
                                                                value="{{ old('email', $users->email) }}">
                                                        </div>
                                                    </div>

                                                    {{-- Password --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Password</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="Password">
                                                        </div>
                                                    </div>

                                                    {{-- Unit --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Unit</label>
                                                            <select name="id_unit" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_unit', $users->id_unit) ? '' : 'selected' }}>
                                                                    Pilih Unit</option>
                                                                @foreach ($units as $key)
                                                                    <option value="{{ $key->id_unit }}"
                                                                        {{ old('id_unit', $users->id_unit) == $key->id_unit ? 'selected' : '' }}>
                                                                        {{ $key->nama_unit }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Level User --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Level User</label>
                                                            <select name="id_level" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_level', $users->id_level) ? '' : 'selected' }}>
                                                                    Pilih Level</option>
                                                                @foreach ($level_users as $key)
                                                                    <option value="{{ $key->id_level }}"
                                                                        {{ old('id_level', $users->id_level) == $key->id_level ? 'selected' : '' }}>
                                                                        {{ $key->nama_level_user }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Fase User --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Fase User</label>
                                                            <select name="id_fase" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_fase', $users->id_fase) ? '' : 'selected' }}>
                                                                    Pilih Fase</option>
                                                                @foreach ($fases as $key)
                                                                    <option value="{{ $key->id_fase }}"
                                                                        {{ old('id_fase', $users->id_fase) == $key->id_fase ? 'selected' : '' }}>
                                                                        {{ $key->nama_fase }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- KSP --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">KSP</label>
                                                            <select name="id_ksp" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_ksp', $users->id_ksp) ? '' : 'selected' }}>
                                                                    Pilih KSP</option>
                                                                @foreach ($ksps as $key)
                                                                    <option value="{{ $key->id_ksp }}"
                                                                        {{ old('id_ksp', $users->id_ksp) == $key->id_ksp ? 'selected' : '' }}>
                                                                        {{ $key->nama_ksp }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Pejabat --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pejabat</label>
                                                            <select name="id_pejabat" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_pejabat', $users->id_pejabat) ? '' : 'selected' }}>
                                                                    Pilih Atasan</option>
                                                                @foreach ($pejabats as $key)
                                                                    <option value="{{ $key->id_pejabat }}"
                                                                        {{ old('id_pejabat', $users->id_pejabat) == $key->id_pejabat ? 'selected' : '' }}>
                                                                        {{ $key->nama_pejabat }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Admin Pendukung PPK --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pendukung PPK</label>
                                                            <select name="id_admin_pendukung_ppk" class="form-select"
                                                                data-choices data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_admin_pendukung_ppk', $users->id_admin_pendukung_ppk) ? '' : 'selected' }}>
                                                                    Pilih Pendukung</option>
                                                                @foreach ($admin_pendukungs as $key)
                                                                    <option value="{{ $key->id_admin_pendukung_ppk }}"
                                                                        {{ old('id_admin_pendukung_ppk', $users->id_admin_pendukung_ppk) == $key->id_admin_pendukung_ppk ? 'selected' : '' }}>
                                                                        {{ $key->nama_pendukung_ppk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- PPTK --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">PPTK</label>
                                                            <select name="id_pptk" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('id_pptk', $users->id_pptk) ? '' : 'selected' }}>
                                                                    Pilih PPTK</option>
                                                                @foreach ($pptks as $key)
                                                                    <option value="{{ $key->id_pptk }}"
                                                                        {{ old('id_pptk', $users->id_pptk) == $key->id_pptk ? 'selected' : '' }}>
                                                                        {{ $key->nama_pptk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Status User --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Status User</label>
                                                            <select name="status_user" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('status_user', $users->status_user) ? '' : 'selected' }}>
                                                                    Pilih Status</option>
                                                                <option value="aktif"
                                                                    {{ old('status_user', $users->status_user) == 'aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="tidak aktif"
                                                                    {{ old('status_user', $users->status_user) == 'tidak aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- Status Edit --}}
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Status Edit</label>
                                                            <select name="status_edit" class="form-select" data-choices
                                                                data-choices-sorting="true">
                                                                <option disabled
                                                                    {{ old('status_edit', $users->status_edit) ? '' : 'selected' }}>
                                                                    Pilih Status</option>
                                                                <option value="aktif"
                                                                    {{ old('status_edit', $users->status_edit) == 'aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="tidak aktif"
                                                                    {{ old('status_edit', $users->status_edit) == 'tidak aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <hr class="my-4 text-muted">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
                                                    <label class="form-check-label" for="same-address">Yakin isian anda
                                                        pada form ini?</label>
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
