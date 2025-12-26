@extends('layouts.main')
@section('content')
    <form id="confirmSubmitForm" method="POST"
        action="{{ route($routePrefix . '.update', $usulan_barang_details->id_usulan_barang_detail) }}" id="confirmSubmitForm"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row row-cols-md-3 row-cols-1">
                        <div class="col col-lg-8 border-end">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Komponen Usulan Belanja<i
                                        class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <input type="text" name="nama_pegawai"
                                        value="{{ $usulan_barang_details->rkbu->nama_pegawai }}" class="form-control"
                                        aria-label="Amount (to the nearest dollar)" />
                                    <input type="hidden" class="form-control" name="id_rkbu"
                                        value="{{ $usulan_barang_details->id_rkbu }}">
                                    <input type="hidden" name="id_usulan_barang_detail" class="form-control"
                                        value="{{ $usulan_barang_details->id_usulan_barang_detail }}">
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col col-lg-4">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Sisa Anggaran <i
                                        class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-service-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 text-center"><span>Rp.
                                                {{ number_format($rkbu->sisa_anggaran_rkbu, 0, ',', '.') }}</span></h2>
                                        <p class="text-center"> Sisa Volume : {{ $rkbu->sisa_vol_rkbu }}</p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        @include('usulan_spj.partials.detailinputkomponenpegawai', [
            'routePrefix' => $routePrefix,
        ])
    </form>
@endsection
