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
                                            action="{{ route($routePrefix . '.update', $perusahaan->id_perusahaan) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-3">
                                                {{-- Nama Perusahaan --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Perusahaan</label>
                                                        <input type="text" name="nama_perusahaan"
                                                            value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- Email --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="text" name="email_perusahaan"
                                                            value="{{ old('email_perusahaan', $perusahaan->email_perusahaan) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- Alamat --}}
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat</label>
                                                        <textarea class="form-control" name="alamat_perusahaan" rows="3">{{ old('alamat_perusahaan', $perusahaan->alamat_perusahaan) }}</textarea>
                                                    </div>
                                                </div>

                                                {{-- Telp --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Telp</label>
                                                        <input type="text" name="tlp_perusahaan"
                                                            value="{{ old('tlp_perusahaan', $perusahaan->tlp_perusahaan) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- NPWP --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">NPWP Perusahaan</label>
                                                        <input type="text" name="no_npwp"
                                                            value="{{ old('no_npwp', $perusahaan->no_npwp) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- Pimpinan --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pimpinan Perusahaan</label>
                                                        <input type="text" name="nama_direktur_perusahaan"
                                                            value="{{ old('nama_direktur_perusahaan', $perusahaan->nama_direktur_perusahaan) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- Jabatan --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jabatan Perusahaan</label>
                                                        <input type="text" name="jabatan_perusahaan"
                                                            value="{{ old('jabatan_perusahaan', $perusahaan->jabatan_perusahaan) }}"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                {{-- Status --}}
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select" data-choices
                                                            data-choices-sorting="true" required>
                                                            <option value="aktif"
                                                                {{ old('status', $perusahaan->status) == 'aktif' ? 'selected' : '' }}>
                                                                Aktif
                                                            </option>
                                                            <option value="non aktif"
                                                                {{ old('status', $perusahaan->status) == 'non aktif' ? 'selected' : '' }}>
                                                                Non Aktif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr class="my-4 text-muted">

                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" id="same-address" required>
                                                <label class="form-check-label" for="same-address">Yakin isian anda pada
                                                    form ini</label>
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
