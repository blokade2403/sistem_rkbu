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
                                    <form id="confirmSubmitForm" method="POST" action="{{ route('ksps.store') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama KSP</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_ksp" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP KSP</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_ksp" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Jabatan KSP</label>
                                            <div class="col-sm-10">
                                                <select name="id_fungsional" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Atasan</option>
                                                    @foreach ($fungsionals as $item)
                                                        <option value="{{ $item->id_fungsional }}">
                                                            {{ $item->jabatan_fungsional }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Atasan KSP</label>
                                            <div class="col-sm-10">
                                                <select name="id_pejabat" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Atasan</option>
                                                    @foreach ($pejabats as $item)
                                                        <option value="{{ $item->id_pejabat }}">
                                                            {{ $item->nama_pejabat }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status KSP</label>
                                            <div class="col-sm-10">
                                                <select name="status" class="js-example-basic-single col-sm-12">
                                                    <option selected="" disabled="">Pilih Status</option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak aktif">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('ksps.index') }}"
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
