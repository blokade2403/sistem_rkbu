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
                                        action="{{ route('judul_headers.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_rs" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="alamat_rs" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tlp</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="tlp_rs" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="email_rs" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Wilayah</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="wilayah" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Pos</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="kode_pos" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Gambar 1</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="gambar1" id="formFileMultiple" multiple
                                                    class="form-control">

                                                {{-- kalau ada file tersimpan (hanya di edit) --}}
                                                @if (isset($judul_headers) && !empty($judul_headers->gambar1))
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $judul_headers->gambar1) }}"
                                                            target="_blank" class="label label-inverse"> Lihat Dokumen
                                                            KAP
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Gambar 2</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="gambar2" id="formFileMultiple" multiple
                                                    class="form-control">

                                                {{-- kalau ada file tersimpan (hanya di edit) --}}
                                                @if (isset($judul_headers) && !empty($judul_headers->gambar2))
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $judul_headers->gambar2) }}"
                                                            target="_blank" class="label label-inverse"> Lihat Dokumen
                                                            KAP
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Gambar 2</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="header7" id="formFileMultiple" multiple
                                                    class="form-control">

                                                {{-- kalau ada file tersimpan (hanya di edit) --}}
                                                @if (isset($judul_headers) && !empty($judul_headers->header7))
                                                    <div class="mt-2">
                                                        <a href="{{ asset('storage/' . $judul_headers->header7) }}"
                                                            target="_blank" class="label label-inverse"> Lihat Dokumen
                                                            KAP
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 1</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 2</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 3</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header3" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 4</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header4" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 5</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header5" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Header 6</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="header6" class="form-control">
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('judul_headers.index') }}"
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
