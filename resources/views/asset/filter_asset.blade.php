<div class="card-body border-bottom-dashed border-bottom">
    <form action="{{ route($routePrefix . '.index') }}" id="filter-form" method="GET">

        <div class="d-flex flex-wrap gap-3">

            <!-- Filter Tahun Perolehan -->
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="form-label">Filter Tahun Perolehan</label>
                <select name="tahun_perolehan" id="tahun_perolehan" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Tahun Perolehan --</option>
                    @foreach ($tahunOptions as $tahun)
                        <option value="{{ $tahun }}"
                            {{ request('tahun_perolehan') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kondisi Aset -->
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="form-label">Filter Kondisi Asset</label>
                <select name="kondisi_asset" id="kondisi_asset" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Kondisi --</option>
                    @foreach ($kondisi as $k)
                        <option value="{{ $k }}" {{ request('kondisi_asset') == $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi Penempatan -->
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="form-label">Filter Lokasi Penempatan</label>
                <select name="id_penempatan" id="id_penempatan" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Lokasi --</option>

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

            <!-- Tombol -->
            <div class="d-flex flex-wrap gap-2 align-items-end">

                <button type="submit" class="btn btn-primary btn-label">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                    Filter
                </button>

                <a href="{{ url()->current() }}" class="btn btn-secondary btn-label">
                    <i class="ri-repeat-fill label-icon align-middle fs-16 me-2"></i>
                    Reset
                </a>

            </div>

        </div>

    </form>
</div>
