<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Detail Komponen</h4>
        <div class="flex-shrink-0">
            <div class="form-check form-switch form-switch-right form-switch-md">
                <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
            </div>
        </div>
    </div><!-- end card header -->
    <div class="card-body">
        <p>
            @if (session('lebih'))
                <div class="alert alert-danger alert-border-left alert-dismissible fade shadow show mb-xl-0"
                    role="alert">
                    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Danger</strong>
                    {{ session('lebih') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </p>
        <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
            form groups and using the <code>col-*-*</code> class to specify the width of your labels and
            controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
            well so they’re vertically centered with their associated form controls.</p>

        <div class="live-preview">
            {{-- Volume --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Volume</label>
                </div>
                <div class="col-lg-5">
                    <input type="text" name="vol_1_detail" id="vol_1"
                        value="{{ $usulan_barang_details->vol_1_detail }}" class="form-control"
                        placeholder="Isi Vol Kebutuhan">
                </div>
                <div class="col-lg-5">
                    @php
                        $selected = old('satuan_1_detail', optional($usulan_barang_details)->satuan_1_detail ?? '');
                    @endphp
                    <select name="satuan_1_detail" id="satuan_1" class="form-select" data-choices
                        data-choices-sorting="true">
                        <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>Pilih Satuan</option>
                        @foreach ($uraian_satu as $item)
                            <option value="{{ $item->nama_uraian_1 }}"
                                {{ (string) $selected === (string) $item->nama_uraian_1 ? 'selected' : '' }}>
                                {{ $item->nama_uraian_1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Koefisien --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Koefisien</label>
                </div>
                <div class="col-lg-5">
                    <input type="text" name="vol_2_detail" id="vol_2"
                        value="{{ $usulan_barang_details->vol_2_detail }}" class="form-control"
                        placeholder="Isi Vol Koefisien">
                </div>
                <div class="col-lg-5">
                    @php
                        $selected = old('satuan_2_detail', optional($usulan_barang_details)->satuan_2_detail ?? '');
                    @endphp
                    <select name="satuan_2_detail" id="satuan_2" class="form-select" data-choices
                        data-choices-sorting="true">
                        <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>Pilih Satuan</option>
                        @foreach ($uraian_dua as $item)
                            <option value="{{ $item->nama_uraian_2 }}"
                                {{ (string) $selected === (string) $item->nama_uraian_2 ? 'selected' : '' }}>
                                {{ $item->nama_uraian_2 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Spesifikasi --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Spesifikasi</label>
                </div>
                <div class="col-lg-10">
                    <textarea name="spek_detail" id="spek" rows="5" class="form-control" placeholder="Spesifikasi Komponen">{{ old('spek_detail', $usulan_barang_details->spek_detail ?? '') }}</textarea>
                </div>
            </div>

            {{-- Harga Satuan --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Harga Satuan</label>
                </div>
                <div class="col-lg-10">
                    <input id="harga_barang" name="harga_barang"
                        value="{{ number_format($usulan_barang_details->harga_barang, 0, ',', '.') }}"
                        class="form-control" placeholder="Harga Satuan">
                    <input type="hidden" id="harga_barang_number" name="harga_barang_number"
                        value="{{ $usulan_barang_details->harga_barang }}">
                </div>
            </div>

            {{-- PPN --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">PPN</label>
                </div>
                <div class="col-lg-10">
                    <input type="number" step="0.01" min="0" max="100" class="form-control"
                        name="ppn" id="ppn" value="{{ $usulan_barang_details->ppn }}" placeholder="%">
                </div>
            </div>

            {{-- Persediaan --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Persediaan</label>
                </div>
                <div class="col-lg-10">
                    <div class="row gy-4">
                        <div class="col-xxl-3 col-md-6">
                            <label class="form-label">Rata-Rata Pemakaian</label>
                            <input type="number" class="form-control" id="rata2_pemakaian" name="rata2_pemakaian"
                                value="{{ $usulan_barang_details->rata2_pemakaian ?? 0 }}" placeholder="1" />
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <label class="form-label">Sisa Stok</label>
                            <input type="number" class="form-control" id="sisa_stok" name="sisa_stok"
                                value="{{ $usulan_barang_details->sisa_stok ?? 0 }}" />
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <label class="form-label">Buffer Stok</label>
                            <input type="number" class="form-control" id="buffer_stok" name="buffer_stok"
                                value="{{ $usulan_barang_details->buffer_stok ?? 0 }}" placeholder="1" />
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <label class="form-label">Vol Pengkali</label>
                            <input type="number" name="pengkali" class="form-control" id="pengkali"
                                value="{{ old('pengkali', $usulan_barang_details->pengkali ?? 1) }}" placeholder="1"
                                required />
                        </div>

                        <div class="col-xxl-3 col-md-6">
                            <label class="form-label">Stok Minimal</label>
                            <input id="minimal_stok" name="stok_minimal"
                                value="{{ $usulan_barang_details->stok_minimal }}" class="form-control bg-lighter"
                                readonly />
                        </div>

                        <div class="col-xxl-9 col-md-6">
                            <label class="form-label">Jumlah Usulan Barang Persediaan</label>
                            <input id="usulan_barang" value="{{ $usulan_barang_details->jumlah_usulan_barang }}"
                                class="form-control bg-lighter" readonly />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Usulan --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Total Usulan</label>
                </div>
                <div class="col-lg-10">
                    <input id="jumlah_usulan_barang" name="jumlah_usulan_barang"
                        value="{{ $usulan_barang_details->jumlah_usulan_barang }}" class="form-control bg-lighter"
                        readonly />
                </div>
            </div>

            {{-- Total Anggaran --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">Total Anggaran Belanja</label>
                </div>
                <div class="col-lg-10">
                    <input id="hasil" class="form-control bg-lighter" placeholder="1.000.000" readonly>
                    <input type="hidden" name="total_anggaran_usulan_barang" id="total_anggaran_number"
                        value="{{ $usulan_barang_details->total_anggaran_usulan_barang }}">
                </div>
            </div>

            <hr class="mb-3">

            {{-- No Usulan Barang --}}
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="form-label">No Usulan Barang</label>
                </div>
                <div class="col-lg-10">
                    @php
                        $selected = old('id_usulan_barang', optional($usulan_barang_details)->id_usulan_barang ?? '');
                    @endphp
                    <select name="id_usulan_barang" id="id_usulan_barang" class="form-select" data-choices
                        data-choices-sorting="true">
                        <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>Pilih Usulan</option>
                        @foreach ($usulan_barangs as $item)
                            <option value="{{ $item->id_usulan_barang }}"
                                {{ (string) $selected === (string) $item->id_usulan_barang ? 'selected' : '' }}>
                                {{ $item->no_usulan_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @include('partials.submit_buttons')
        <div class="d-none code-view">
        </div>
    </div>
</div>
