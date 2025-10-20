<div class="card-body border-bottom-dashed border-bottom">
    <div class="row g-3">
        <form action="{{ route($routePrefix . '.index') }}" id="filter-form" method="GET">
            <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <div class="col-sm-12">
                            <label for="userName-2" class="block">Filter Tahun Perolehan</label>
                        </div>
                        <div class="col-sm-12">
                            <select name="tahun_perolehan" id="tahun_perolehan" class="form-select" data-choices
                                data-choices-sorting="true">
                                <option value="" disable>-- Pilih Sub Kategori --</option>
                                @foreach ($tahunOptions as $tahun)
                                    <option value="{{ $tahun }}"
                                        {{ request('tahun_perolehan') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="col-sm-12">
                            <label for="userName-2" class="form-label">Filter Kondisi Asset</label>
                        </div>
                        <div class="col-sm-12">
                            <select name="kondisi_asset" id="kondisi_asset" class="form-select" data-choices
                                data-choices-sorting="true">
                                <option value="">-- Pilih Status Validasi --</option>
                                @foreach ($kondisi as $kondisi)
                                    <option value="{{ $kondisi }}"
                                        {{ request('kondisi_asset') == $kondisi ? 'selected' : '' }}>
                                        {{ $kondisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="col-sm-12">
                            <label for="userName-2" class="form-label">Filter Lokasi Penempatan</label>
                        </div>
                        <div class="col-sm-12">
                            <select name="id_penempatan" id="id_penempatan" class="form-select" data-choices
                                data-choices-sorting="true">
                                <option value="">-- Pilih Status Validasi --</option>
                                <option value="null" {{ request('id_penempatan') === 'null' ? 'selected' : '' }}>
                                    Belum Ditempatkan
                                </option>
                                @foreach ($penempatan as $p)
                                    <option value="{{ $p->id_penempatan }}"
                                        {{ request('id_penempatan') == $p->id_penempatan ? 'selected' : '' }}>
                                        {{ $p->lokasi_barang }}
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
