@if (session('nama_level_user') == 'Validasi')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Update Validasi</h4>
            <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                </div>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
                form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
                well so they’re vertically centered with their associated form controls.</p>
            <div class="live-preview">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="nameInput" class="form-label">Pilih Status Validasi</label>
                    </div>
                    <div class="col-lg-10">

                        <select id="form-control choices-select" name="id_status_validasi" class="form-select"
                            data-choices data-choices-sorting="true">
                            @php
                                $selectedId = old(
                                    'id_sub_kategori_rkbu',
                                    optional($rkbuBarangJasa)->id_status_validasi,
                                );
                            @endphp
                            <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                                Pilih Status Validasi
                            </option>

                            @foreach ($status_validasi as $sub)
                                <option value="{{ $sub->id_status_validasi }}"
                                    {{ (string) $selectedId === (string) $sub->id_status_validasi ? 'selected' : '' }}>
                                    {{ $sub->nama_validasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="websiteUrl" class="form-label">Keterangan Validasi</label>
                    </div>
                    <div class="col-lg-10">
                        <textarea name="keterangan_status" id="keterangan_status" rows="5" cols="5" class="form-control"
                            placeholder="Keterangan Validasi">{{ old('keterangan_status', $rkbuBarangJasa->keterangan_status ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if (session('nama_level_user') == 'Validasi RKA')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Update Validasi</h4>
            <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                </div>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
                form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
                well so they’re vertically centered with their associated form controls.</p>
            <div class="live-preview">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="nameInput" class="form-label">Pilih Status Validasi</label>
                    </div>
                    <div class="col-lg-10">

                        <select id="form-control choices-select" name="id_status_validasi_rka" class="form-select"
                            data-choices data-choices-sorting="true">
                            @php
                                $selectedId = old(
                                    'id_sub_kategori_rkbu',
                                    optional($rkbuBarangJasa)->id_status_validasi_rka,
                                );
                            @endphp

                            <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                                Pilih Status Validasi
                            </option>

                            @foreach ($status_validasi_rka as $sub)
                                <option value="{{ $sub->id_status_validasi_rka }}"
                                    {{ (string) $selectedId === (string) $sub->id_status_validasi_rka ? 'selected' : '' }}>
                                    {{ $sub->nama_validasi_rka }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="websiteUrl" class="form-label">Keterangan Validasi</label>
                    </div>
                    <div class="col-lg-10">
                        <textarea name="keterangan_status" id="keterangan_status" rows="5" cols="5" class="form-control"
                            placeholder="Keterangan Validasi">{{ old('keterangan_status', $rkbuBarangJasa->keterangan_status ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if (session('nama_level_user') == 'Administrator')
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Update Validasi</h4>
            <div class="flex-shrink-0">
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                    <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                </div>
            </div>
        </div><!-- end card header -->
        <div class="card-body">
            <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
                form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
                well so they’re vertically centered with their associated form controls.</p>
            <div class="live-preview">
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="nameInput" class="form-label">Pilih Status Validasi KSP</label>
                    </div>
                    <div class="col-lg-10">

                        <select id="form-control choices-select" name="id_status_validasi" class="form-select"
                            data-choices data-choices-sorting="true">
                            @php
                                $selectedId = old(
                                    'id_sub_kategori_rkbu',
                                    optional($rkbuBarangJasa)->id_status_validasi,
                                );
                            @endphp
                            <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                                Pilih Status Validasi
                            </option>

                            @foreach ($status_validasi as $sub)
                                <option value="{{ $sub->id_status_validasi }}"
                                    {{ (string) $selectedId === (string) $sub->id_status_validasi ? 'selected' : '' }}>
                                    {{ $sub->nama_validasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="websiteUrl" class="form-label">Keterangan Validasi</label>
                    </div>
                    <div class="col-lg-10">
                        <textarea name="keterangan_status" id="keterangan_status" rows="5" cols="5" class="form-control"
                            placeholder="Keterangan Validasi">{{ old('keterangan_status', $rkbuBarangJasa->keterangan_status ?? '') }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="nameInput" class="form-label">Pilih Status Validasi RKA</label>
                    </div>
                    <div class="col-lg-10">

                        <select id="form-control choices-select" name="id_status_validasi_rka" class="form-select"
                            data-choices data-choices-sorting="true">
                            @php
                                $selectedId = old(
                                    'id_sub_kategori_rkbu',
                                    optional($rkbuBarangJasa)->id_status_validasi_rka,
                                );
                            @endphp

                            <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                                Pilih Status Validasi
                            </option>

                            @foreach ($status_validasi_rka as $sub)
                                <option value="{{ $sub->id_status_validasi_rka }}"
                                    {{ (string) $selectedId === (string) $sub->id_status_validasi_rka ? 'selected' : '' }}>
                                    {{ $sub->nama_validasi_rka }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2">
                        <label for="websiteUrl" class="form-label">Keterangan Validasi</label>
                    </div>
                    <div class="col-lg-10">
                        <textarea name="keterangan_status" id="keterangan_status" rows="5" cols="5" class="form-control"
                            placeholder="Keterangan Validasi">{{ old('keterangan_status', $rkbuBarangJasa->keterangan_status ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
