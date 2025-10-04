<div class="card border card-border-danger card ribbon-box border mb-lg-0 right">
    <div class="card-header">
        <div class="d-flex">
            <h5 class="card-title flex-grow-1 mb-0">Status Validasi Usulan</h5>

        </div>
        <div class="ribbon ribbon-danger ribbon-shape">Validasi</div>
        <hr>
        @php
            $pptk = [
                '9ce173c5-b49f-41e1-b17c-f55e25544a58',
                '9d5b9ca9-8699-4456-8267-153db7acb631',
                '9d5b9cbe-e3f4-4cfc-bb3a-3e5b4fca35db',
            ];
            $isApproved = $no_inv->status_validasi_pptk === 'Disetujui PPTK';
        @endphp

        @if (in_array(session('id_pptk'), $pptk))
            <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}" method="POST"
                id="confirmUpdateForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Status Validasi --}}
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Pilih Status Validasi</label>
                    <select name="status_validasi_pptk" class="form-select" data-choices data-choices-sorting="true"
                        {{ $isApproved ? 'disabled' : '' }}>
                        <option value="{{ $no_inv->status_permintaan_barang }}">
                            {{ $no_inv->status_permintaan_barang }}
                        </option>
                        @unless ($isApproved)
                            <option value="Disetujui PPTK">Validasi PPTK</option>
                            <option value="Di Tolak">Di Tolak</option>
                        @endunless
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Keterangan Validasi</label>
                    <textarea name="keterangan_pptk" class="form-control" rows="3" {{ $isApproved ? 'disabled' : '' }}>
                {{ old('keterangan_pptk', $no_inv->keterangan_pptk ?? '') }}
            </textarea>
                    <input type="hidden" name="no_usulan_barang" value="{{ $no_inv->no_usulan_barang }}">
                    <input type="hidden" name="tanggal_konfirmasi" value="{{ $no_inv->tanggal_konfirmasi }}">
                </div>

                {{-- Tombol --}}
                <div class="col-lg-12">
                    @if ($isApproved)
                        <button type="button" class="btn btn-outline-warning btn-load" disabled>
                            <span class="d-flex align-items-center">
                                <span class="flex-grow-1 me-2">Loading...</span>
                                <span class="spinner-grow flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                            </span>
                        </button>
                    @else
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    @endif
                </div>
            </form>
        @endif

        @php
            $forms = [
                'Validasi RKA' => [
                    'status_field' => 'status_validasi_kabag',
                    'keterangan_field' => 'keterangan_kabag',
                    'options' => [
                        'Disetujui Kabag' => 'Validasi Kabag/Kabid',
                        'Di Tolak' => 'Di Tolak',
                    ],
                    'approved_value' => 'Disetujui Kabag',
                    'disable_statuses' => ['Perlu Validasi PPTK', 'Disetujui Kabag', 'Disetujui Direktur'],
                ],
                'Direktur' => [
                    'status_field' => 'status_validasi_direktur',
                    'keterangan_field' => 'keterangan_direktur',
                    'options' => [
                        'Disetujui Direktur' => 'Validasi Direktur',
                        'Di Tolak' => 'Di Tolak',
                        'Revisi' => 'Revisi',
                    ],
                    'approved_value' => 'Disetujui Direktur',
                    'disable_statuses' => ['Disetujui PPTK', 'Perlu Validasi PPTK', 'Disetujui Direktur'],
                ],
            ];

            $role = session('nama_level_user');
            $isApproved = false;

            if (isset($forms[$role])) {
                $statusField = $forms[$role]['status_field'];
                $approvedValue = $forms[$role]['approved_value'];

                // default: disable kalau status role ini sudah "approved"
                $isApproved = ($no_inv->$statusField ?? $no_inv->status_permintaan_barang) === $approvedValue;

                // tambahan: cek status_permintaan_barang apakah termasuk list disable
                if (in_array($no_inv->status_permintaan_barang, $forms[$role]['disable_statuses'])) {
                    $isApproved = true;
                }
            }
        @endphp

        @if (isset($forms[$role]))
            <form action="{{ route($routePrefix . '.updateValidasi', $no_inv->no_usulan_barang) }}" method="POST"
                id="confirmUpdateForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Status Validasi --}}
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Pilih Status Validasi</label>
                    <select name="{{ $forms[$role]['status_field'] }}" class="form-select" data-choices
                        data-choices-sorting="true" {{ $isApproved ? 'disabled' : '' }}>
                        <option value="{{ $no_inv->status_permintaan_barang }}">
                            {{ $no_inv->status_permintaan_barang }}
                        </option>
                        @unless ($isApproved)
                            @foreach ($forms[$role]['options'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>

                {{-- Keterangan --}}
                <div class="col-lg-12 mb-3">
                    <label class="form-label">Keterangan Validasi</label>
                    <textarea name="{{ $forms[$role]['keterangan_field'] }}" class="form-control" rows="3"
                        {{ $isApproved ? 'disabled' : '' }}>{{ old($forms[$role]['keterangan_field'], $no_inv->{$forms[$role]['keterangan_field']} ?? '') }}</textarea>
                    <input type="hidden" name="no_usulan_barang" value="{{ $no_inv->no_usulan_barang }}">
                    <input type="hidden" name="tanggal_konfirmasi" value="{{ $no_inv->tanggal_konfirmasi }}">
                </div>

                {{-- Tombol --}}
                <div class="col-lg-12">
                    @if ($isApproved)
                        <button type="button" class="btn btn-outline-warning btn-load" disabled>
                            <span class="d-flex align-items-center">
                                <span class="flex-grow-1 me-2">Loading...</span>
                                <span class="spinner-grow flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                            </span>
                        </button>
                    @else
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    @endif
                </div>
            </form>
        @endif



    </div>
</div><!--end card-->

<div class="card">

</div>
