@extends('layouts.main')
@section('content')
    <form id="confirmUpdateForm" method="POST" action="{{ route($routePrefix . '.store') }}" enctype="multipart/form-data">
        @csrf
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
                                    <div class="mt-3">
                                        <label class="fs-10 form-label">Klik Input Jika tidak ada pilihan komponen
                                            --></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">
                                    <div class="form-icon right">
                                        <div class="input-group">
                                            <input type="text" name="barang" value="{{ old('barang') }}"
                                                id="nama_komponen" class="form-control" aria-label="Recipient's username"
                                                aria-describedby="button-addon2" readonly>
                                            <button type="button" class="btn btn-info " data-bs-toggle="modal"
                                                data-bs-target=".bs-example-modal-xl">Pilih Komponen disini</button>
                                            @include('rkbu.partials.modalkomponenrkbu')
                                        </div>
                                    </div>
                                    </p>
                                    <div class="mt-3">
                                        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-success"
                                            id="accordionBordered">
                                            <div class="accordion-item shadow">
                                                <h2 class="accordion-header" id="accordionborderedExample3">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#accor_borderedExamplecollapse3"
                                                        aria-expanded="false"
                                                        aria-controls="accor_borderedExamplecollapse3">
                                                        input Manual disini !!!
                                                    </button>
                                                </h2>
                                                <div id="accor_borderedExamplecollapse3" class="accordion-collapse collapse"
                                                    aria-labelledby="accordionborderedExample3"
                                                    data-bs-parent="#accordionBordered">
                                                    <div class="accordion-body">
                                                        <input type="text" name="nama_barang" class="form-control"
                                                            placeholder="">
                                                        <input type="hidden" name="id_status_validasi" class="form-control"
                                                            value="">
                                                        <input type="hidden" name="jumlah_vol" class="form-control">
                                                        <input type="hidden" name="status_komponen" class="form-control">
                                                        <input type="hidden" name="sisa_vol_rkbu" class="form-control">
                                                        <input type="hidden" name="nama_lengkap" class="form-control"
                                                            value="">
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
                                    <img src="{{ asset('assets/images/user-illustarator-1.png') }}" alt=""
                                        style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
            </div>
        </div>
        @include('rkbu.partials.detailinputkomponen', [
            'routePrefix' => $routePrefix,
        ])
    </form>
@endsection
