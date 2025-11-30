<div class="card-body border-bottom-dashed border-bottom">
    <div class="row g-3">
        <form action="{{ route($routePrefix . '.index') }}" method="GET">
            <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-5">
                        <div class="col-sm-12">
                            <label for="userName-2" class="block">Filter by Sub Kategori Rkbu</label>
                        </div>
                        <div class="col-sm-12">
                            <select name="sub_kategori_rkbu" id="sub_kategori_rkbu" class="form-select" data-choices
                                data-choices-sorting="true">
                                <option value="" disable>-- Pilih Sub Kategori --</option>
                                @foreach ($sub_kategori_rkbus as $subKategori)
                                    <option value="{{ $subKategori->id_sub_kategori_rkbu }}"
                                        {{ request('sub_kategori_rkbu') == $subKategori->id_sub_kategori_rkbu ? 'selected' : '' }}>
                                        {{ $subKategori->kode_sub_kategori_rkbu }}.
                                        {{ $subKategori->nama_sub_kategori_rkbu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <label for="userName-2" class="form-label">Filter by Status Validasi</label>
                        </div>
                        <div class="col-sm-12">
                            <select name="id_status_validasi_rka" id="id_status_validasi_rka" class="form-select"
                                data-choices data-choices-sorting="true">
                                <option value="">-- Pilih Status Validasi --</option>
                                @foreach ($status_validasi_rka as $status)
                                    <option value="{{ $status->id_status_validasi_rka }}"
                                        {{ request('id_status_validasi_rka') == $status->id_status_validasi_rka ? 'selected' : '' }}>
                                        {{ $status->nama_validasi_rka }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                            <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                            Filter
                        </button>
                        <a href="{{ url()->current() }}"
                            class="btn btn-secondary btn-label waves-effect waves-light ms-2">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-repeat-fill label-icon align-middle fs-16 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Reset
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
        </form>
    </div>
</div>
