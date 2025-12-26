<div class="card-body border-bottom-dashed border-bottom">
    <form action="{{ route($routePrefix . '.index') }}" method="GET">
        <div class="d-flex flex-wrap align-items-end gap-3">

            <!-- Sub Kategori RKBU -->
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="form-label">Sub Kategori RKBU</label>
                <select name="sub_kategori_rkbu" id="sub_kategori_rkbu" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Sub Kategori --</option>
                    @foreach ($sub_kategori_rkbus as $subKategori)
                        <option value="{{ $subKategori->id_sub_kategori_rkbu }}"
                            {{ request('sub_kategori_rkbu') == $subKategori->id_sub_kategori_rkbu ? 'selected' : '' }}>
                            {{ $subKategori->kode_sub_kategori_rkbu }}.
                            {{ $subKategori->nama_sub_kategori_rkbu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Validasi -->
            <div class="flex-grow-1" style="min-width:220px;">
                <label class="form-label">Status</label>
                <select name="status_pembayaran" id="status_pembayaran" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Status --</option>
                    <option value="Sudah di Bayar"
                        {{ request('status_pembayaran') == 'Sudah di Bayar' ? 'selected' : '' }}>
                        Sudah di Bayar
                    </option>
                    <option value="Parsial" {{ request('status_pembayaran') == 'Parsial' ? 'selected' : '' }}>
                        Parsial
                    </option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="d-flex flex-wrap gap-2">

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
