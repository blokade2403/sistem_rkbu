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
                                    <input type="text" name="nama_barang"
                                        value="{{ $usulan_barang_details->rkbu->nama_barang }}" class="form-control"
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

        @include('usulan_spj.partials.detailinputkomponenupdate', [
            'routePrefix' => $routePrefix,
        ])
    </form>
@endsection

{{-- 
<div class="pcoded-inner-content">
    <!-- Main-body start -->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- Page body start -->
            <div class="page-body">
                <form id="confirmSubmitForm" method="POST"
                    action="{{ route($routePrefix . '.update', $usulan_barang_details->id_usulan_barang_detail) }}"
                    id="confirmSubmitForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="row d-flex align-items-center">
                                    <div class="col-sm-8">
                                        <div class="card-header">
                                            <div class="card-header-right">
                                                <i class="icofont icofont-spinner-alt-5"></i>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <h4 class="sub-title">Form Inputs</h4>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Komponen RKBU</label>
                                                <div class="col-sm-10">
                                                    <div class="input-group input-group-primary">
                                                        <span class="input-group-addon">
                                                            <i class="icofont icofont-presentation"></i>
                                                        </span>
                                                        <input type="text" name="nama_barang"
                                                            value="{{ $usulan_barang_details->rkbu->nama_barang }}"
                                                            class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" />
                                                        <input type="hidden" class="form-control" name="id_rkbu"
                                                            value="{{ $usulan_barang_details->id_rkbu }}">
                                                        <input type="hidden" name="id_usulan_barang_detail"
                                                            class="form-control"
                                                            value="{{ $usulan_barang_details->id_usulan_barang_detail }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                    <div class="card-block accordion-block">
                                                        <div id="accordion" role="tablist" aria-multiselectable="true">
                                                            <div class="accordion-panel">
                                                                <div id="collapseOne" class="panel-collapse collapse in"
                                                                    role="tabpanel" aria-labelledby="headingOne">
                                                                    <div class="accordion-content accordion-desc">
                                                                        <p>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-xl-4 pr-5">
                                        <div class="card widget-card-1 w-100">
                                            <div class="card-block-small text-center">
                                                <i class="feather icon-pie-chart bg-c-blue card1-icon"
                                                    style="font-size:40px; margin-bottom:10px;"></i>
                                                <span class="text-c-blue f-w-600" style="font-size:18px;">Sisa
                                                    Anggaran</span>
                                                <h4 style="font-size:24px; font-weight:bold;">Rp.
                                                    {{ number_format($rkbu->sisa_anggaran_rkbu, 0, ',', '.') }}</h4>
                                                <div>
                                                    <span class="m-t-10 text-muted d-block">
                                                        <i
                                                            class="text-c-blue f-16 feather icon-alert-triangle m-r-10"></i>
                                                        Sisa Volume : {{ $rkbu->sisa_vol_rkbu }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="m-3">
                                @if (session('lebih'))
                                    <div class="alert alert-danger">
                                        {{ session('lebih') }}
                                    </div>
                                @endif
                            </div>
                            @include('usulan_spj.partials.detailinputkomponenusulanbelanja', [
                                'routePrefix' => $routePrefix,
                            ])
                        </div>
                    </div>
                </form>
            </div>
            <!-- Page body end -->
        </div>
    </div>
</div> --}}
