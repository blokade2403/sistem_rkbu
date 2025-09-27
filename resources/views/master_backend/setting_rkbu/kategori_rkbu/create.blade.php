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
                                        action="{{ route('kategori_rkbus.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_kategori_rkbu" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="kode_kategori_rkbu" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Obyek Belanja</label>
                                            <div class="col-sm-10">
                                                <select name="id_obyek_belanja"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Obyek Belanja
                                                    </option>
                                                    @foreach ($obyek_belanjas as $key)
                                                        <option value="{{ $key->id_obyek_belanja }}">
                                                            {{ $key->nama_obyek_belanja }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jenis Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <select name="id_jenis_kategori_rkbu"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Jenis Kategori
                                                    </option>
                                                    @foreach ($jenis_kategori_rkbus as $key)
                                                        <option value="{{ $key->id_jenis_kategori_rkbu }}">
                                                            {{ $key->nama_jenis_kategori_rkbu }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('kategori_rkbus.index') }}"
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
