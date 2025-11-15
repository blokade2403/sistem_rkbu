@extends('layouts.main')
@section('content')
    <form id="confirmUpdateForm" method="POST" action="{{ route($routePrefix . '.update', $rkbuBarangJasa->id_rkbu) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center"
                            role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-alert-triangle text-warning me-2 icon-sm">
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
                                <a href="pages-pricing.html" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-sm-3">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">
                                        <label for="meassageInput" class="form-label">Nama Komponen</label>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="p-3">
                                    <div class="mt-3">
                                        <input type="text" name="nama_barang" value="{{ $rkbuBarangJasa->nama_barang }}"
                                            class="form-control form-control-lg text-primary"
                                            placeholder="input-group-primary">
                                        <input type="hidden" value="{{ $rkbuBarangJasa->id_status_validasi }}"
                                            name="id_status_validasi" class="form-control" value="">
                                        <input type="hidden" value="{{ $rkbuBarangJasa->jumlah_vol }}" name="jumlah_vol"
                                            class="form-control">
                                        <input type="hidden" value="{{ $rkbuBarangJasa->status_komponen }}"
                                            name="status_komponen" class="form-control">
                                        <input type="hidden" value="{{ $rkbuBarangJasa->sisa_vol_rkbu }}"
                                            name="sisa_vol_rkbu" class="form-control">
                                        <input type="hidden" value="{{ $rkbuBarangJasa->nama_lengkap }}"
                                            name="nama_lengkap" class="form-control" value="">
                                        <input type="hidden" name="id_user" value="{{ session('id_user') }}">
                                        <input type="hidden" name="nama_tahun_anggaran"
                                            value="{{ session('tahun_anggaran') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="px-3">
                                    <img src="{{ asset('assets/images/user-illustarator-1.png') }}" alt=""
                                        style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
            </div>
        </div>

        @include('rkbu.partials.error_form')

        @include('rkbu.partials.detailinputkomponen', [
            'routePrefix' => $routePrefix,
        ])
        @include('rkbu.partials.modalketeranganperubahanrka')
    </form>
@endsection
