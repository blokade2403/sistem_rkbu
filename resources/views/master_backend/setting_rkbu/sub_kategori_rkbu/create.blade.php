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
                                        action="{{ route('sub_kategori_rkbus.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_sub_kategori_rkbu"
                                                    value="{{ old('nama_sub_kategori_rkbu') }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="kode_sub_kategori_rkbu"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kategori RKBU</label>
                                            <div class="col-sm-10">
                                                <select name="id_kategori_rkbu"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Kategori RKBU
                                                    </option>
                                                    @foreach ($kategori_rkbus as $key)
                                                        <option value="{{ $key->id_kategori_rkbu }}">
                                                            {{ $key->kode_kategori_rkbu }}.{{ $key->nama_kategori_rkbu }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Sub Kategori Rekening</label>
                                            <div class="col-sm-10">
                                                <select name="id_sub_kategori_rekening"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Sub Kategori Rekening
                                                    </option>
                                                    @foreach ($sub_kategori_rekenings as $key)
                                                        <option value="{{ $key->id_sub_kategori_rekening }}">
                                                            {{ $key->kode_sub_kategori_rekening }}.{{ $key->nama_sub_kategori_rekening }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Rekening Belanja</label>
                                            <div class="col-sm-10">
                                                <select name="id_kode_rekening_belanja"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Rekening Belanja
                                                    </option>
                                                    @foreach ($rekening_belanjas as $key)
                                                        <option value="{{ $key->id_kode_rekening_belanja }}">
                                                            {{ $key->kode_rekening_belanja }}.{{ $key->nama_rekening_belanja }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PPTK</label>
                                            <div class="col-sm-10">
                                                <select name="id_pptk" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih PPTK
                                                    </option>
                                                    @foreach ($pptk as $key)
                                                        <option value="{{ $key->id_pptk }}">
                                                            {{ $key->nama_pptk }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Admin Pendukung PPK</label>
                                            <div class="col-sm-10">
                                                <select name="id_admin_pendukung_ppk"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Admin Pendukung PPK
                                                    </option>
                                                    @foreach ($admin_pendukungs as $key)
                                                        <option value="{{ $key->id_admin_pendukung_ppk }}">
                                                            {{ $key->nama_pendukung_ppk }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="col-sm-10">
                                                <select name="status" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Status</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak aktif">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('sub_kategori_rkbus.index') }}"
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
