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
                    <label for="nameInput" class="form-label">Rata-rata Pemakaian</label>
                </div>
                <div class="col-lg-3">
                    <input type="number" onkeyup="sum_farmasi();" name="rata2_pemakaian" id="rata2_pemakaian"
                        value="{{ $usulan_barang_details->rata2_pemakaian }}" class="form-control"
                        placeholder="Rata-rata Pemakaian" />
                </div>
                <div class="col-lg-5">
                    @php
                        $selected = old('satuan_1_detail', optional($usulan_barang_details)->satuan_1_detail ?? '');
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
                    <label for="nameInput" class="form-label">Sisa Stok</label>
                </div>
                <div class="col-lg-3">
                    <input type="number" onkeyup="sum_farmasi();" name="sisa_stok" id="sisa_stok"
                        value="{{ $usulan_barang_details->sisa_stok }}" class="form-control" placeholder="Sisa Stok" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="nameInput" class="form-label">Buffer Stok</label>
                </div>
                <div class="col-lg-3">
                    <input type="number" onkeyup="sum_farmasi();" name="buffer_stok" id="buffer_stok"
                        value="{{ $usulan_barang_details->buffer_stok }}" class="form-control"
                        placeholder="Buffer Stok" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="nameInput" class="form-label">Vol Pengkali</label>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" onkeyup="sum_farmasi();" name="pengkali" id="pengkali"
                            value="{{ old('pengkali', $usulan_barang_details->pengkali ?? 1) }}" class="form-control"
                            placeholder="John Doe" />
                        <span class="input-group-text" id="basic-addon2" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-placement="top" title=""
                            data-bs-original-title="volume pengkali">#</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="leaveemails" class="form-label">Stok Minimal</label>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input id="minimal_stok" name="stok_minimal" value="{{ $usulan_barang_details->stok_minimal }}"
                            class="form-control bg-lighter" id="basic-addon21" aria-label="Username"
                            aria-describedby="basic-addon21" readonly="" />
                        <span class="input-group-text" id="basic-addon2" data-bs-toggle="tooltip"
                            data-bs-trigger="hover" data-bs-placement="top" title=""
                            data-bs-original-title="rata-rata pemakaian x vol pengkali">#</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="websiteUrl" class="form-label">Spesifikasi</label>
                </div>
                <div class="col-lg-10">
                    <textarea name="spek_detail" id="spek" rows="5" cols="5" class="form-control"
                        placeholder="Spesifikasi Komponen">{{ old('spek_detail', $usulan_barang_details->spek_detail ?? '') }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="dateInput" class="form-label">Harga Satuan</label>
                </div>
                <div class="col-lg-10">
                    <input id="harga_barang" type="text" class="form-control" name="harga_barang_display"
                        onkeyup="sum_farmasi();"
                        value="{{ number_format($usulan_barang_details->harga_barang ?? 0, 0, ',', '.') }}"
                        placeholder="Masukkan harga satuan">

                    <!-- Hidden input untuk kirim angka murni ke controller -->
                    <input type="hidden" id="harga_barang_hidden" name="harga_barang"
                        value="{{ $usulan_barang_details->harga_barang ?? 0 }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="timeInput" class="form-label">PPN</label>
                </div>
                <div class="col-lg-10">
                    <input type="number" step="0.01" min="0" max="100" class="form-control"
                        onkeyup="sum_farmasi();" name="ppn" id="ppn"
                        value="{{ $usulan_barang_details->ppn ?? 0 }}" placeholder="%">
                    <input type="hidden" name="total_ppn" id="hasil_ppn"
                        value="{{ $usulan_barang_details->total_ppn ?? 0 }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="leaveemails" class="form-label">Jumlah Usulan Barang</label>
                </div>
                <div class="col-lg-10">
                    <input id="usulan_barang" name="jumlah_usulan_barang"
                        value="{{ $usulan_barang_details->jumlah_usulan_barang ?? 0 }}"
                        class="form-control bg-lighter" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for="leaveemails" class="form-label">Total Anggaran Belanja</label>
                </div>
                <div class="col-lg-10">
                    <input type="text" id="hasil" class="form-control" placeholder="1.000.000" readonly>
                    <input type="hidden" name="total_anggaran_usulan_barang" id="total_anggaran_number"
                        value="{{ $usulan_barang_details->total_anggaran_usulan_barang ?? 0 }}">
                </div>
            </div>
        </div>
        @include('partials.submit_buttons')
        <div class="d-none code-view">
        </div>
    </div>
</div>
