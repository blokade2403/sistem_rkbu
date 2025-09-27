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
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Basic Form Inputs card start -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-right">
                                        <i class="icofont icofont-spinner-alt-5"></i>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <h4 class="sub-title">Form Inputs</h4>
                                    <form id="confirmSubmitForm" method="POST"
                                        action="{{ route('admin_pendukungs.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Admin PPK</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_pendukung_ppk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NRK </label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nrk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pejabat Pengadaan</label>
                                            <div class="col-sm-10">
                                                <select name="id_pejabat_pengadaan"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Pejabat PPK</option>
                                                    @foreach ($pejabat_pengadaans as $key)
                                                        <option value="{{ $key->id_pejabat_pengadaan }}">
                                                            {{ $key->nama_ppk }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pejabat PPTK</label>
                                            <div class="col-sm-10">
                                                <select name="id_pptk" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Pejabat PPK</option>
                                                    @foreach ($pptks as $key)
                                                        <option value="{{ $key->id_pptk }}">
                                                            {{ $key->nama_pptk }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('admin_pendukungs.index') }}"
                                                class="btn btn-sm btn-secondary btn-outline-secondary me-4">
                                                <i class="icofont icofont-circled-left"></i> Kembali
                                            </a>
                                            <button class="btn btn-sm btn-primary btn-outline-primary me-4">
                                                <i class="icofont icofont-check-circled"></i> Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Basic Form Inputs card end -->
                        </div>
                    </div>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
