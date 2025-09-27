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
                                        action="{{ route('aktivitas.update', $aktivitas->id_aktivitas) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Aktivitas</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_aktivitas"
                                                    value="{{ $aktivitas->nama_aktivitas }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Kode Aktivitas</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="kode_aktivitas"
                                                    value="{{ $aktivitas->kode_aktivitas }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Program</label>
                                            <div class="col-sm-10">
                                                <select name="id_program" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $aktivitas->id_program }}">
                                                        {{ $aktivitas->program->nama_program }}
                                                    </option>
                                                    @foreach ($programs as $key)
                                                        <option value="{{ $key->id_program }}">
                                                            {{ $key->nama_program }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Sub Kegiatan</label>
                                            <div class="col-sm-10">
                                                <select name="id_sub_kegiatan"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $aktivitas->id_sub_kegiatan }}">
                                                        {{ $aktivitas->sub_kegiatan->nama_sub_kegiatan }}
                                                    </option>
                                                    @foreach ($sub_kegiatans as $key)
                                                        <option value="{{ $key->id_sub_kegiatan }}">
                                                            {{ $key->nama_sub_kegiatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('aktivitas.index') }}"
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
