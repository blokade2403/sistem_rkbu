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
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="nameInput" class="form-label">Volume</label>
                </div>
                <div class="col-lg-5">
                    <input type="text" onkeyup="sum_modal();" name="vol_1_detail" id="vol_1"
                        value="{{ $rkbuBarangJasa->vol_1_detail }}"class="form-control" placeholder="Isi Vol Kebutuhan">
                </div>
                <div class="col-lg-5">
                    @php
                        $selected = old('satuan_1_detail', optional($rkbuBarangJasa)->satuan_1_detail ?? '');
                    @endphp
                    <select id="form-control choices-select" name="satuan_1_detail" id="satuan_1" class="form-select"
                        data-choices data-choices-sorting="true">
                        <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>
                            Pilih Satuan
                        </option>
                        @foreach ($uraian_satu as $item)
                            <option value="{{ $item->nama_uraian_1 }}"
                                {{ (string) $selected === (string) $item->nama_uraian_1 ? 'selected' : '' }}>
                                {{ $item->nama_uraian_1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="nameInput" class="form-label">Koefisien</label>
                </div>
                <div class="col-lg-5">
                    <input type="text" onkeyup="sum_modal();" name="vol_2_detail" id="vol_2"
                        value="{{ $rkbuBarangJasa->vol_2_detail }}" class="form-control"
                        placeholder="Isi Vol Koefisien">
                </div>
                <div class="col-lg-5">
                    @php
                        $selected = old('satuan_2_detail', optional($rkbuBarangJasa)->satuan_2_detail ?? '');
                    @endphp
                    <select id="form-control choices-select" name="satuan_2_detail" class="form-select" data-choices
                        data-choices-sorting="true">
                        <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>
                            Pilih Satuan
                        </option>
                        @foreach ($uraian_dua as $item)
                            <option value="{{ $item->nama_uraian_2 }}"
                                {{ (string) $selected === (string) $item->nama_uraian_2 ? 'selected' : '' }}>
                                {{ $item->nama_uraian_2 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="websiteUrl" class="form-label">Spesifikasi</label>
                </div>
                <div class="col-lg-10">
                    <textarea name="spek_detail" id="spek" rows="5" cols="5" class="form-control"
                        placeholder="Spesifikasi Komponen">{{ old('spek_detail', $rkbuBarangJasa->spek_detail ?? '') }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="dateInput" class="form-label">Harga Satuan</label>
                </div>
                <div class="col-lg-10">
                    <input id="harga_barang" name="harga_barang" onkeyup="sum_modal();"
                        value="{{ number_format($rkbuBarangJasa->harga_barang, 0, ',', '.') }}" class="form-control"
                        placeholder="Harga Satuan">
                    <input type="hidden" id="harga_barang_number" name="harga_barang_number"
                        value="{{ $rkbuBarangJasa->harga_barang }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="timeInput" class="form-label">PPN</label>
                </div>
                <div class="col-lg-10">
                    <input type="number" step="0.01" min="0" max="100" class="form-control"
                        onkeyup="sum_modal();" name="ppn" id="ppn" value="{{ $rkbuBarangJasa->ppn }}"
                        placeholder="%">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="leaveemails" class="form-label">Total Anggaran</label>
                </div>
                <div class="col-lg-10">
                    <input id="jumlah_usulan_barang" name="jumlah_usulan_barang"
                        value="{{ $rkbuBarangJasa->jumlah_usulan_barang }}" class="form-control bg-lighter"
                        id="basic-addon21" aria-label="Username" aria-describedby="basic-addon21" readonly="" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="leaveemails" class="form-label">Total Anggaran Belanja</label>
                </div>
                <div class="col-lg-10">
                    <input id="hasil" class="form-control" placeholder="1.000.000" readonly>
                    <input type="hidden" name="total_anggaran_usulan_barang" id="total_anggaran_number"
                        value="{{ $rkbuBarangJasa->total_anggaran_usulan_barang }}">
                </div>
            </div>
        </div>
        @include('partials.submit_buttons')
        <div class="d-none code-view">
        </div>
    </div>
</div>
