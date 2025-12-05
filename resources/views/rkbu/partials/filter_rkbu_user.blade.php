<div class="card-body border-bottom-dashed border-bottom">
    <form action="{{ route($routePrefix . '.index') }}" method="GET">

        <div class="d-flex flex-wrap gap-3">

            <!-- Filter Sub Kategori RKBU -->
            <div class="flex-grow-1" style="min-width:260px;">
                <label class="form-label">Filter by Sub Kategori RKBU</label>
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

            <!-- Filter Status Validasi -->
            <div class="flex-grow-1" style="min-width:240px;">
                <label class="form-label">Filter by Status Validasi</label>
                <select name="id_status_validasi_rka" id="id_status_validasi_rka" class="form-select" data-choices
                    data-choices-sorting="true">
                    <option value="">-- Pilih Status Validasi --</option>
                    @foreach ($status_validasi_rka as $status)
                        <option value="{{ $status->id_status_validasi_rka }}"
                            {{ request('id_status_validasi_rka') == $status->id_status_validasi_rka ? 'selected' : '' }}>
                            {{ $status->nama_validasi_rka }}
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
