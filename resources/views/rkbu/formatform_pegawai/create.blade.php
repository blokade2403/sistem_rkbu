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
                                            <label class="col-sm-2 col-form-label">Nama Pegawai</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-button">
                                                    <input type="text" name="nama_pegawai"
                                                        value="{{ old('nama_pegawai') }}" id="nama_pegawai"
                                                        class="form-control" placeholder="">
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
                                @include('rkbu.partials.detailkomponenpegawai', [
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
