@php
    $pptk = [
        '9ce173c5-b49f-41e1-b17c-f55e25544a58',
        '9d5b9ca9-8699-4456-8267-153db7acb631',
        '9d5b9cbe-e3f4-4cfc-bb3a-3e5b4fca35db',
    ];
@endphp

@if (in_array(session('id_pptk'), $pptk))
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product Categories</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-2"> <a href="#" class="float-end text-decoration-underline">Add New</a>Select
                product category</p>
            <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list"
                aria-haspopup="true" aria-expanded="false">
                <div class="choices__inner">
                    <select id="form-control choices-select" name="satuan_1_detail" id="satuan_1" class="form-select"
                        data-choices data-choices-sorting="true">
                        <option value="">
                            Pilih Satuan
                        </option>

                    </select>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex">
                    <h5 class="card-title flex-grow-1 mb-0">Pengusul Detail</h5>
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0);" class="link-secondary">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-3 col-sm-12">
                <div class="panel panel-danger">
                    <div class="panel-heading bg-danger">
                        Validasi
                    </div>
                    <div class="panel-footer text-primary">
                        <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}"
                            method="POST" id="confirmSubmitForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group row md-3">
                                <label class="col-sm-2 col-form-label">Pilih Status Validasi</label>
                                <div class="col-sm-10">
                                    <input type="hidden" value="{{ $no_inv->no_usulan_barang }}"
                                        name="no_usulan_barang">
                                    <input type="hidden" value="{{ $no_inv->tanggal_konfirmasi }}"
                                        name="tanggal_konfirmasi">
                                    <select name="status_validasi_pptk" class="form-control js-example-basic-single">
                                        <option value="{{ $no_inv->status_permintaan_barang }}">
                                            {{ $no_inv->status_permintaan_barang }}</option>
                                        <option value="Disetujui PPTK">Validasi PPTK</option>
                                        <option value="Di Tolak">Di Tolak</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row md-3">
                                <label class="col-sm-2 col-form-label">Keterangan Validasi</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan_pptk" id="keterangan_pptk" rows="5" cols="5" class="form-control"
                                        placeholder="Keterangan PPTK">{{ old('keterangan_pptk', $no_inv->keterangan_pptk ?? '') }}</textarea>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
@endif

@if (session('nama_level_user') == 'Validasi RKA')
    <div class="card-block">
        <h4 class="sub-title">Update Validasi</h4>
        <div class="col-xl-12 col-lg-12 col-md-3 col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading bg-danger">
                    Validasi
                </div>
                <div class="panel-footer text-primary">
                    <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}"
                        method="POST" id="confirmSubmitForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row md-3">
                            <label class="col-sm-2 col-form-label">Pilih Status Validasi</label>
                            <div class="col-sm-10">
                                <input type="hidden" value="{{ $no_inv->no_usulan_barang }}" name="no_usulan_barang">
                                <input type="hidden" value="{{ $no_inv->tanggal_konfirmasi }}"
                                    name="tanggal_konfirmasi">
                                <select name="status_validasi_kabag" class="form-control js-example-basic-single">
                                    <option value="{{ $no_inv->status_permintaan_barang }}">
                                        {{ $no_inv->status_permintaan_barang }}</option>
                                    <option value="Disetujui Kabag">Validasi Kabag/Kabid</option>
                                    <option value="Di Tolak">Di Tolak</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row md-3">
                            <label class="col-sm-2 col-form-label">Keterangan Validasi</label>
                            <div class="col-sm-10">
                                <textarea name="keterangan_kabag" id="keterangan_kabag" rows="5" cols="5" class="form-control"
                                    placeholder="Keterangan Kabag/Kabid">{{ old('keterangan_kabag', $no_inv->keterangan_kabag ?? '') }}</textarea>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('nama_level_user') == 'Direktur')
    <div class="card-block">
        <h4 class="sub-title">Update Validasi</h4>
        <div class="col-xl-12 col-lg-12 col-md-3 col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading bg-danger">
                    Validasi
                </div>
                <div class="panel-footer text-primary">
                    <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}"
                        method="POST" id="confirmSubmitForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row md-3">
                            <label class="col-sm-2 col-form-label">Pilih Status Validasi</label>
                            <div class="col-sm-10">
                                <input type="hidden" value="{{ $no_inv->no_usulan_barang }}"
                                    name="no_usulan_barang">
                                <input type="hidden" value="{{ $no_inv->tanggal_konfirmasi }}"
                                    name="tanggal_konfirmasi">
                                <select name="status_validasi_direktur" class="form-control js-example-basic-single">
                                    <option value="{{ $no_inv->status_permintaan_barang }}">
                                        {{ $no_inv->status_permintaan_barang }}</option>
                                    <option value="Disetujui Direktur">Validasi Direktur</option>
                                    <option value="Di Tolak">Di Tolak</option>
                                    <option value="Revisi">Revisi</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row md-3">
                            <label class="col-sm-2 col-form-label">Keterangan Validasi</label>
                            <div class="col-sm-10">
                                <textarea name="keterangan_direktur" id="keterangan_direktur" rows="5" cols="5" class="form-control"
                                    placeholder="Keterangan Direktur">{{ old('keterangan_direktur', $no_inv->keterangan_direktur ?? '') }}</textarea>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endif
