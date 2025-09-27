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
                        action="{{ route($routePrefix . '.update', $rkbuBarangJasa->id_rkbu) }}" id="confirmSubmitForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                                            <label class="col-sm-2 col-form-label">Nama Pegawai</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-primary">
                                                    <span class="input-group-addon">
                                                        <i class="icofont icofont-user"></i>
                                                    </span>
                                                    <input type="text" name="nama_pegawai"
                                                        value="{{ $rkbuBarangJasa->nama_pegawai }}" class="form-control"
                                                        placeholder="input-group-primary">
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
                                                                        <input type="hidden"
                                                                            value="{{ $rkbuBarangJasa->id_status_validasi }}"
                                                                            name="id_status_validasi"
                                                                            class="form-control" value="">
                                                                        <input type="hidden"
                                                                            value="{{ $rkbuBarangJasa->jumlah_vol }}"
                                                                            name="jumlah_vol" class="form-control">
                                                                        <input type="hidden"
                                                                            value="{{ $rkbuBarangJasa->status_komponen }}"
                                                                            name="status_komponen" class="form-control">
                                                                        <input type="hidden"
                                                                            value="{{ $rkbuBarangJasa->sisa_vol_rkbu }}"
                                                                            name="sisa_vol_rkbu" class="form-control">
                                                                        <input type="hidden"
                                                                            value="{{ $rkbuBarangJasa->nama_lengkap }}"
                                                                            name="nama_lengkap" class="form-control"
                                                                            value="">
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
                                @include('rkbu.partials.detailkomponenpegawai', [
                                    'routePrefix' => $routePrefix,
                                ])
                                @include('rkbu.partials.modalketeranganperubahanrka')
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
