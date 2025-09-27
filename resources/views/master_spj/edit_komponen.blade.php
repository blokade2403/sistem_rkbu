<x-layouts.main class="main">
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-header start -->
                <x-partials.breadcrumb>
                </x-partials.breadcrumb>
                <!-- Page-header end -->

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
                                                            <div id="accordion" role="tablist"
                                                                aria-multiselectable="true">
                                                                <div class="accordion-panel">
                                                                    <div id="collapseOne"
                                                                        class="panel-collapse collapse in"
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
                                @include('master_spj.detailinputkomponenusulanbelanja', [
                                    'routePrefix' => $routePrefix,
                                ])
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
