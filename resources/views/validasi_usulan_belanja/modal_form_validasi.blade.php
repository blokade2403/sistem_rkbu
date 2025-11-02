<div class="modal fade" id="modalValidasi{{ $item->id_usulan_barang }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="confirmUpdateForm" action="{{ route($routePrefix . '.updateValidasi', $item->no_usulan_barang) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Validasi Usulan Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">No Usulan</label>
                        <input type="text" class="form-control" value="{{ $item->no_usulan_barang }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control"
                            value="{{ $item->sub_kategori_rkbu->kode_sub_kategori_rkbu }}. {{ $item->sub_kategori_rkbu->nama_sub_kategori_rkbu }}"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pengusul</label>
                        <input type="text" class="form-control" value="{{ $item->nama_pengusul_barang }}" readonly>
                    </div>

                    @php
                        $pptk = ['PPTK', 'PPTK Umum', 'PPTK Alkes'];
                        $isApproved = $item->status_validasi_pptk == 'Disetujui PPTK';
                        $isApprovedkabag = $item->status_validasi_kabag == 'Disetujui Kabag';
                        $isApproveddirektur = $item->status_validasi_direktur == 'Disetujui Direktur';
                    @endphp
                    @if (in_array(session('nama_level_user'), $pptk))
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Validasi</label>
                            <select class="form-select" name="status_validasi_pptk" required
                                {{ $isApproved ? 'disabled' : '' }}>
                                <option value="">-- Pilih --</option>
                                <option value="Disetujui PPTK"
                                    {{ $item->status_validasi_pptk == 'Disetujui PPTK' ? 'selected' : '' }}>
                                    Disetujui PPTK
                                </option>
                                <option value="Di Tolak"
                                    {{ $item->status_validasi_pptk == 'Di Tolak' ? 'selected' : '' }}>
                                    Di Tolak
                                </option>
                            </select>

                            {{-- Jika field disabled, kirim hidden input supaya nilai tetap terkirim ke controller --}}
                            @if ($isApproved)
                                <input type="hidden" name="status_validasi_pptk"
                                    value="{{ $item->status_validasi_pptk }}">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan PPTK</label>
                            <textarea name="keterangan_pptk" class="form-control" rows="3" {{ $isApproved ? 'readonly' : '' }}>{{ $item->keterangan_pptk }}</textarea>
                        </div>
                    @endif
                    @if (session('nama_level_user') == 'Validasi RKA')
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Validasi</label>
                            <select class="form-select" name="status_validasi_kabag" required
                                {{ $isApprovedkabag ? 'disabled' : '' }}>
                                <option value="">-- Pilih --</option>
                                <option value="Disetujui Kabag"
                                    {{ $item->status_validasi_kabag == 'Disetujui Kabag' ? 'selected' : '' }}>
                                    Disetujui Kabag
                                </option>
                                <option value="Di Tolak"
                                    {{ $item->status_validasi_kabag == 'Di Tolak' ? 'selected' : '' }}>
                                    Di Tolak
                                </option>
                            </select>
                            @if ($isApprovedkabag)
                                <input type="hidden" name="status_validasi_kabag"
                                    value="{{ $item->status_validasi_kabag }}">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Kabag</label>
                            <textarea name="keterangan_kabag" class="form-control" rows="3" {{ $isApprovedkabag ? 'readonly' : '' }}>{{ $item->keterangan_kabag }}</textarea>
                        </div>
                    @endif
                    @if (session('nama_level_user') == 'Direktur')
                        <div class="mb-3">
                            <label class="form-label">Pilih Status Validasi</label>
                            <select class="form-select" name="status_validasi_direktur" required
                                {{ $isApproveddirektur ? 'disabled' : '' }}>
                                <option value="">-- Pilih --</option>
                                <option value="Disetujui Direktur"
                                    {{ $item->status_validasi_direktur == 'Disetujui Direktur' ? 'selected' : '' }}>
                                    Disetujui Direktur
                                </option>
                                <option value="Di Tolak"
                                    {{ $item->status_validasi_direktur == 'Di Tolak' ? 'selected' : '' }}>
                                    Di Tolak
                                </option>
                            </select>
                            @if ($isApproveddirektur)
                                <input type="hidden" name="status_validasi_direktur"
                                    value="{{ $item->status_validasi_direktur }}">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Direktur</label>
                            <textarea name="keterangan_direktur" class="form-control" rows="3" {{ $isApproveddirektur ? 'readonly' : '' }}>{{ $item->keterangan_direktur }}</textarea>
                        </div>
                    @endif
                </div>

                @php
                    $isApprovedpptk = $item->status_validasi_pptk == 'Disetujui PPTK';
                    $isApprovedkabag = $item->status_validasi_kabag == 'Disetujui Kabag';
                    $isApproveddirektur = $item->status_validasi_direktur == 'Disetujui Direktur';

                    // Tentukan kondisi disable berdasarkan level user yang login
                    $disableButton = false;

                    if (session('nama_level_user') == 'PPTK Umum' && $isApprovedpptk) {
                        $disableButton = true;
                    } elseif (session('nama_level_user') == 'Validasi RKA' && $isApprovedkabag) {
                        $disableButton = true;
                    } elseif (session('nama_level_user') == 'Direktur' && $isApproveddirektur) {
                        $disableButton = true;
                    }
                @endphp
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success"
                        {{ $disableButton ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
