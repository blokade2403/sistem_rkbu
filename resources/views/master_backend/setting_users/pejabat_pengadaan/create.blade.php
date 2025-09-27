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
                                        action="{{ route('pejabat_pengadaans.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama PPK</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_ppk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP PPTK</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_ppk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama PPBJ</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_ppbj" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP PPBJ</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_ppbj" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Pengurus Barang</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_pengurus_barang" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP Pengurus Barang</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_pengurus_barang" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Bendahara</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_bendahara" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP Bendahara</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_bendahara" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Direktur</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_direktur" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP Direktur</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_direktur" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PPK Keuangan</label>
                                            <div class="col-sm-10">
                                                <select name="id_ppk_keuangan"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Pejabat PPK Keuangan
                                                    </option>
                                                    @foreach ($ppk_keuangan as $key)
                                                        <option value="{{ $key->id_ppk_keuangan }}">
                                                            {{ $key->nama_ppk_keuangan }}</option>
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
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Tahun Anggaran</label>
                                            <div class="col-sm-10">
                                                <select name="id_tahun_anggaran"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Tahun Anggaran</option>
                                                    @foreach ($tahun_anggaran as $tahun_anggaran)
                                                        <option value="{{ $tahun_anggaran->id }}">
                                                            {{ $tahun_anggaran->nama_tahun_anggaran }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('pejabat_pengadaans.index') }}"
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
