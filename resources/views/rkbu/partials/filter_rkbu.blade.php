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
                <label class="form-label">Status Validasi</label>
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

            <!-- Filter Sumber Dana (Admin Only) -->
            @if (session('nama_level_user') == 'Administrator')
                <div class="flex-grow-1" style="min-width:220px;">
                    <label class="form-label">Sumber Dana</label>
                    <select name="id_sumber_dana" class="form-select" data-choices data-choices-sorting="true">
                        <option value="">-- Pilih Sumber Dana --</option>
                        @foreach ($daftarSumberDana as $sd)
                            <option value="{{ $sd->id_sumber_dana }}"
                                {{ request('id_sumber_dana') == $sd->id_sumber_dana ? 'selected' : '' }}>
                                {{ $sd->nama_sumber_dana }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

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

                <a href="{{ route($routePrefixPrint . '.print', request()->query()) }}" target="_blank"
                    class="btn btn-danger btn-label">
                    <i class="ri-printer-line label-icon align-middle fs-16 me-2"></i>
                    Print PDF
                </a>

            </div>

        </div>
    </form>
</div>
