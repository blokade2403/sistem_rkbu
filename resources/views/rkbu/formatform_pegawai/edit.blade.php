@extends('layouts.main')
@section('content')
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <form id="confirmSubmitForm" method="POST"
                        action="{{ route($routePrefix . '.update', $rkbuBarangJasa->id_rkbu) }}" id="confirmSubmitForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center"
                                            role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-alert-triangle text-warning me-2 icon-sm">
                                                <path
                                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                                </path>
                                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                            </svg>
                                            <div class="flex-grow-1 text-truncate">
                                                Rencana Kebutuhan Barang Unit (RKBU) Tahun
                                                <b>{{ session('tahun_anggaran') }}</b>.
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="#"
                                                    class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                                            </div>
                                        </div>

                                        <div class="row align-items-end">
                                            <div class="col-sm-3">
                                                <div class="p-3">
                                                    <p class="fs-16 lh-base">
                                                        <label for="meassageInput" class="form-label">Nama Pegawai</label>
                                                    </p>
                                                    <div class="mt-3">
                                                        <label class="fs-10 form-label">Klik Input Jika tidak ada pilihan
                                                            komponen
                                                            --></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="p-3">
                                                    <div class="mt-3">
                                                        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-success"
                                                            id="accordionBordered">
                                                            <div class="accordion-item shadow">
                                                                <h2 class="accordion-header" id="accordionborderedExample3">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#accor_borderedExamplecollapse3"
                                                                        aria-expanded="false"
                                                                        aria-controls="accor_borderedExamplecollapse3">
                                                                        input nama Pegawai disini !!!
                                                                    </button>
                                                                </h2>
                                                                <div id="accor_borderedExamplecollapse3"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="accordionborderedExample3"
                                                                    data-bs-parent="#accordionBordered">
                                                                    <div class="accordion-body">
                                                                        <input type="text" name="nama_pegawai"
                                                                            class="form-control"
                                                                            value="{{ $rkbuBarangJasa->nama_pegawai }}"
                                                                            placeholder="">
                                                                        <input type="hidden" name="id_status_validasi"
                                                                            class="form-control"
                                                                            value="{{ $rkbuBarangJasa->id_status_validasi }}">
                                                                        <input type="hidden" name="jumlah_vol"
                                                                            class="form-control"
                                                                            value="{{ $rkbuBarangJasa->jumlah_vol }}">
                                                                        <input type="hidden" name="sisa_vol_rkbu"
                                                                            class="form-control"
                                                                            value="{{ $rkbuBarangJasa->sisa_vol_rkbu }}">
                                                                        <input type="hidden" name="nama_lengkap"
                                                                            class="form-control"
                                                                            value="{{ session('nama_lengkap') }}">
                                                                        <input type="hidden" name="id_user"
                                                                            value="{{ session('id_user') }}">
                                                                        <input type="hidden" name="nama_tahun_anggaran"
                                                                            value="{{ session('tahun_anggaran') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1">
                                                <div class="px-3">
                                                    <img src="{{ asset('assets/images/user-illustarator-1.png') }}"
                                                        alt=""
                                                        style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div>
                            </div>
                        </div>
                        @include('rkbu.partials.detailkomponenpegawai', [
                            'routePrefix' => $routePrefix,
                        ])
                    </form>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>
@endsection
