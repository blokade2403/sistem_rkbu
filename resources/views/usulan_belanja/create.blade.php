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
                    <form id="confirmSubmitForm" method="POST" action="{{ route($routePrefix . '.store') }}"
                        id="confirmSubmitForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-header-right">
                                            <i class="icofont icofont-spinner-alt-5"></i>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <h4 class="sub-title">Form Inputs</h4>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pilih Komponen RKBU</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-button">
                                                    <input type="text" name="barang" value="{{ old('barang') }}"
                                                        id="nama_komponen" class="form-control" readonly placeholder="">
                                                    <button type="button" class="btn btn-primary waves-effect"
                                                        data-toggle="modal" data-target="#large-Modal"><i
                                                            class="icofont icofont-ui-search"></i></button>
                                                    @include('rkbu.partials.modalkomponenrkbu')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <div class="card-block accordion-block">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="accordion-panel">
                                                            <div class="accordion-heading" role="tab"
                                                                id="headingOne">
                                                                <h3 class="card-title accordion-title">
                                                                    <a class="accordion-msg scale_active collapsed"
                                                                        data-toggle="collapse" data-parent="#accordion"
                                                                        href="#collapseOne" aria-expanded="false"
                                                                        aria-controls="collapseOne">
                                                                        Klik Disini jika tidak ada pilihan komponen!!
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div id="collapseOne" class="panel-collapse collapse in"
                                                                role="tabpanel" aria-labelledby="headingOne">
                                                                <div class="accordion-content accordion-desc">
                                                                    <p>
                                                                        <input type="text" name="nama_barang"
                                                                            class="form-control" placeholder="">
                                                                        <input type="hidden" name="id_status_validasi"
                                                                            class="form-control" value="">
                                                                        <input type="hidden" name="jumlah_vol"
                                                                            class="form-control">
                                                                        <input type="hidden" name="status_komponen"
                                                                            class="form-control">
                                                                        <input type="hidden" name="sisa_vol_rkbu"
                                                                            class="form-control">
                                                                        <input type="hidden" name="nama_lengkap"
                                                                            class="form-control" value="">
                                                                        <input type="hidden" name="id_user"
                                                                            value="{{ session('id_user') }}">
                                                                        <input type="hidden" name="nama_tahun_anggaran"
                                                                            value="{{ session('tahun_anggaran') }}">
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
                                @include('rkbu.partials.detailinputkomponen', [
                                    'routePrefix' => $routePrefix,
                                ])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.main>
