@php
    $fotoList = collect([
        $spj->foto_barang_datang,
        $spj->foto_barang_datang_2,
        $spj->foto_barang_datang_3,
    ])->filter()->values();
    $rkbuInfo = $spj->details->first()?->rkbu;
    $fotoLog = $logs->where('aksi', 'Unggah foto barang datang')->last();
@endphp

<div class="card card-bordered mb-6">
    <div class="card-header">
        <h4 class="card-title">Proses Verifikasi Barang Datang</h4>
    </div>
    <div class="card-body">
        @if ($canFoto && $step === 'upload_foto' && $spj->status_verifikasi_pengurus_barang === 'Revisi')
            <div class="alert alert-warning"><strong>Catatan Pengurus Barang:</strong>
                {{ $spj->keterangan_verif_pengurus_barang }}</div>
        @endif

        <div class="row g-6 align-items-start">
            <div class="col-lg-4">
                <div class="arrival-gallery">
                    @if ($fotoList->isNotEmpty())
                        <div class="arrival-gallery-main">
                            <button type="button" class="arrival-gallery-nav is-previous" data-gallery-step="-1" aria-label="Foto sebelumnya">‹</button>
                            <img id="arrival-gallery-image" src="{{ asset('storage/' . $fotoList->first()) }}"
                                alt="Foto barang datang" data-gallery-index="0" data-bs-toggle="modal"
                                data-bs-target="#arrival-photo-modal">
                            <button type="button" class="arrival-gallery-nav is-next" data-gallery-step="1" aria-label="Foto berikutnya">›</button>
                        </div>
                        <div class="arrival-gallery-thumbnails">
                            @foreach ($fotoList as $index => $foto)
                                <button type="button" class="arrival-gallery-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                                    data-gallery-index="{{ $index }}" aria-label="Tampilkan foto {{ $index + 1 }}">
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Foto barang datang {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="arrival-gallery-empty">
                            <i class="ki-duotone ki-picture fs-5x text-muted"><span class="path1"></span><span class="path2"></span></i>
                            <span>Foto barang datang belum diunggah</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-5">
                    <div>
                        <h3 class="mb-2">{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}</h3>
                        <div class="text-muted">
                            <span class="text-primary fw-semibold">{{ $masterInfo?->nama_perusahaan ?? 'Penyedia belum ditentukan' }}</span>
                            <span class="mx-2">|</span>ID Paket: <strong>{{ $spj->idpaket ?: ($masterInfo?->idpaket ?? '-') }}</strong>
                            <span class="mx-2">|</span>Update Foto:
                            <strong>{{ $fotoLog?->created_at ? \Illuminate\Support\Carbon::parse($fotoLog->created_at)->translatedFormat('d F Y') : '-' }}</strong>
                        </div>
                    </div>
                    <span class="badge badge-light-primary fs-7">{{ $spj->status_verifikasi_pengurus_barang ?: 'Menunggu unggah foto' }}</span>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="arrival-info-box"><span>Total Anggaran</span><strong>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</strong></div>
                    </div>
                    <div class="col-md-4">
                        <div class="arrival-info-box"><span>Tgl. Surat Pesanan</span><strong>{{ $spj->tgl_surat_pesanan ? \Illuminate\Support\Carbon::parse($spj->tgl_surat_pesanan)->translatedFormat('d F Y') : '-' }}</strong></div>
                    </div>
                    <div class="col-md-4">
                        <div class="arrival-info-box"><span>No. Surat Pesanan</span><strong>{{ $spj->no_surat_pesanan ?: '-' }}</strong></div>
                    </div>
                </div>

                <div class="row g-5 mb-5">
                    <div class="col-md-6">
                        <h5 class="mb-2">Catatan Barang Datang</h5>
                        <p class="text-muted mb-0">{{ $spj->keterangan_barang_datang ?: 'Tidak ada catatan.' }}</p>
                        <div class="text-muted mt-2">Tanggal datang: {{ $spj->tgl_barang_datang ? \Illuminate\Support\Carbon::parse($spj->tgl_barang_datang)->translatedFormat('d F Y') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2">Validasi Pengurus Barang</h5>
                        <ul class="text-muted ps-5 mb-0">
                            <li>Tanggal verifikasi: {{ $spj->tgl_verif_pengurus_barang ? \Illuminate\Support\Carbon::parse($spj->tgl_verif_pengurus_barang)->translatedFormat('d F Y') : '-' }}</li>
                            <li>Status: <span class="badge badge-light-info">{{ $spj->status_verifikasi_pengurus_barang ?: 'Menunggu' }}</span></li>
                            @if ($spj->keterangan_verif_pengurus_barang)
                                <li>Catatan: {{ $spj->keterangan_verif_pengurus_barang }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if ($spj->details->isNotEmpty())
                    <div class="mb-5">
                        <h5 class="mb-2">Rincian Belanja</h5>
                        <ul class="text-gray-700 ps-5 mb-0">
                            @foreach ($spj->details as $detail)
                                <li>{{ $detail->item?->spesifikasi ?: $detail->rkbu?->nomor_rkbu ?: 'Item belanja' }} — volume {{ rtrim(rtrim(number_format((float) $detail->volume, 3, ',', '.'), '0'), ',') }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($canFoto && $step === 'upload_foto')
                    <div class="card card-bordered bg-light mb-5">
                        <div class="card-header min-h-50px"><h5 class="card-title">{{ $fotoList->isNotEmpty() ? 'Perbarui Foto Barang Datang' : 'Unggah Foto Barang Datang' }}</h5></div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" action="{{ route('usulan-belanja.pengadaan.foto', $spj->id) }}">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-4"><label class="form-label">Foto utama (wajib)</label><input type="file" name="foto_barang_datang" class="form-control" accept="image/jpeg,image/png" required></div>
                                    <div class="col-md-4"><label class="form-label">Foto tambahan 2</label><input type="file" name="foto_barang_datang_2" class="form-control" accept="image/jpeg,image/png"></div>
                                    <div class="col-md-4"><label class="form-label">Foto tambahan 3</label><input type="file" name="foto_barang_datang_3" class="form-control" accept="image/jpeg,image/png"></div>
                                    <div class="col-md-4"><label class="form-label">Tanggal barang datang</label><input type="date" name="tgl_barang_datang" class="form-control" value="{{ old('tgl_barang_datang', $spj->tgl_barang_datang) }}" required></div>
                                    <div class="col-md-8"><label class="form-label">Keterangan</label><textarea name="keterangan_barang_datang" class="form-control" rows="2">{{ old('keterangan_barang_datang', $spj->keterangan_barang_datang) }}</textarea></div>
                                </div>
                                <button class="btn btn-primary mt-4">Kirim untuk validasi</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="mt-6">
                    <h5 class="mb-3">Informasi Belanja</h5>
                    <ul class="nav nav-tabs nav-line-tabs mb-0" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#arrival-account-tab" type="button">Detail Kode Rekening</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#arrival-vendor-tab" type="button">Data Penyedia</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#arrival-bast-tab" type="button">Data BAST</button></li>
                    </ul>
                    <div class="tab-content border border-top-0 rounded-bottom p-4">
                        <div class="tab-pane fade show active" id="arrival-account-tab">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 py-2 border-bottom">Rekening Belanja</dt><dd class="col-sm-8 py-2 border-bottom">{{ $rkbuInfo?->rekeningBelanja?->kode_rekening_belanja ?? $rkbuInfo?->subKategoriRkbu?->sub_kategori_rekening?->kode_sub_kategori_rekening }} {{ $rkbuInfo?->rekeningBelanja?->nama_rekening_belanja ?? ($rkbuInfo?->subKategoriRkbu?->sub_kategori_rekening?->nama_sub_kategori_rekening ?? '-') }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Jenis Kategori</dt><dd class="col-sm-8 py-2 border-bottom">{{ $rkbuInfo?->jenisKategoriRkbu?->nama_jenis_kategori_rkbu ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Kategori</dt><dd class="col-sm-8 py-2 border-bottom">{{ $rkbuInfo?->subKategoriRkbu?->kategori?->nama_kategori_rkbu ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Subkategori</dt><dd class="col-sm-8 py-2 border-bottom">{{ $rkbuInfo?->subKategoriRkbu?->kode_sub_kategori_rkbu }} {{ $rkbuInfo?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Jenis Belanja</dt><dd class="col-sm-8 py-2 border-bottom">{{ $rkbuInfo?->jenisBelanja?->nama_jenis_belanja ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2">Sumber Dana</dt><dd class="col-sm-8 py-2">{{ $rkbuInfo?->sumberDana?->nama_sumber_dana ?? '-' }}</dd>
                            </dl>
                        </div>
                        <div class="tab-pane fade" id="arrival-vendor-tab">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 py-2 border-bottom">Nama Penyedia</dt><dd class="col-sm-8 py-2 border-bottom">{{ $masterInfo?->nama_perusahaan ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">ID Paket</dt><dd class="col-sm-8 py-2 border-bottom">{{ $spj->idpaket ?: ($masterInfo?->idpaket ?? '-') }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Nomor SPK</dt><dd class="col-sm-8 py-2 border-bottom">{{ $masterInfo?->nomor_spk ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2">Nomor Surat Pesanan</dt><dd class="col-sm-8 py-2">{{ $spj->no_surat_pesanan ?? '-' }}</dd>
                            </dl>
                        </div>
                        <div class="tab-pane fade" id="arrival-bast-tab">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 py-2 border-bottom">Tanggal BAST</dt><dd class="col-sm-8 py-2 border-bottom">{{ $spj->tgl_bast ? \Illuminate\Support\Carbon::parse($spj->tgl_bast)->translatedFormat('d F Y') : '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Nomor BA</dt><dd class="col-sm-8 py-2 border-bottom">{{ $spj->no_ba ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Nomor BA HP</dt><dd class="col-sm-8 py-2 border-bottom">{{ $spj->no_ba_hp ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2 border-bottom">Nomor BA BP</dt><dd class="col-sm-8 py-2 border-bottom">{{ $spj->no_ba_bp ?? '-' }}</dd>
                                <dt class="col-sm-4 py-2">Nomor DPA</dt><dd class="col-sm-8 py-2">{{ $spj->no_dpa ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($fotoList->isNotEmpty())
    <div class="modal fade" id="arrival-photo-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0"><button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                <div class="modal-body d-flex align-items-center justify-content-center position-relative">
                    <button type="button" class="arrival-modal-nav is-previous" data-gallery-step="-1" aria-label="Foto sebelumnya">‹</button>
                    <img id="arrival-gallery-modal-image" src="{{ asset('storage/' . $fotoList->first()) }}" alt="Foto barang datang ukuran penuh">
                    <button type="button" class="arrival-modal-nav is-next" data-gallery-step="1" aria-label="Foto berikutnya">›</button>
                </div>
                <div class="text-center text-white pb-4"><span id="arrival-gallery-count">1 / {{ $fotoList->count() }}</span></div>
            </div>
        </div>
    </div>
@endif

<style>
    .arrival-gallery-main { position: relative; padding: 8px; border: 1px solid #e8edf5; border-radius: 10px; background: #f1f3f8; box-shadow: 0 4px 14px #181c320d; }
    .arrival-gallery-main img { display: block; width: 100%; height: min(72vh, 760px); min-height: 360px; object-fit: contain; border-radius: 5px; background: #fff; cursor: zoom-in; }
    .arrival-gallery-empty { min-height: 420px; display: flex; flex-direction: column; gap: 12px; align-items: center; justify-content: center; border-radius: 8px; color: #7e8299; background: #f1f3f8; }
    .arrival-gallery-thumbnails { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
    .arrival-gallery-thumbnail { padding: 5px; border: 1px solid #e4e6ef; border-radius: 6px; background: #fff; cursor: pointer; }
    .arrival-gallery-thumbnail.is-active { border: 2px solid #50cd89; }
    .arrival-gallery-thumbnail img { display: block; width: 82px; height: 96px; object-fit: cover; border-radius: 4px; }
    .arrival-gallery-nav, .arrival-modal-nav { position: absolute; z-index: 2; top: 50%; width: 42px; height: 48px; border: 0; border-radius: 8px; color: #5030a5; background: #fff; font-size: 36px; line-height: 1; transform: translateY(-50%); box-shadow: 0 2px 12px #0002; }
    .arrival-gallery-nav.is-previous { left: 16px; } .arrival-gallery-nav.is-next { right: 16px; }
    .arrival-info-box { height: 100%; min-height: 76px; padding: 14px 16px; border: 1px dashed #dce5f0; border-radius: 8px; display: flex; flex-direction: column; justify-content: center; gap: 4px; background: linear-gradient(135deg, #fbfdff, #fff); }
    .arrival-info-box span { color: #a1a5b7; font-size: 13px; } .arrival-info-box strong { color: #5e6278; overflow-wrap: anywhere; }
    #arrival-gallery-modal-image { max-width: 92vw; max-height: 82vh; object-fit: contain; }
    .arrival-modal-nav { position: fixed; top: 50%; width: 54px; height: 60px; font-size: 44px; }
    .arrival-modal-nav.is-previous { left: 24px; } .arrival-modal-nav.is-next { right: 24px; }
    @media (max-width: 991.98px) { .arrival-gallery-main img { height: 60vh; min-height: 280px; } }
</style>

@if ($fotoList->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const photos = @json($fotoList->map(fn ($path) => asset('storage/' . $path))->all());
            const mainImage = document.getElementById('arrival-gallery-image');
            const modalImage = document.getElementById('arrival-gallery-modal-image');
            const count = document.getElementById('arrival-gallery-count');
            const thumbnails = Array.from(document.querySelectorAll('.arrival-gallery-thumbnail'));
            let activeIndex = 0;

            function showPhoto(index) {
                activeIndex = (index + photos.length) % photos.length;
                if (mainImage) {
                    mainImage.src = photos[activeIndex];
                    mainImage.dataset.galleryIndex = activeIndex;
                }
                if (modalImage) modalImage.src = photos[activeIndex];
                if (count) count.textContent = `${activeIndex + 1} / ${photos.length}`;
                thumbnails.forEach((thumbnail, thumbnailIndex) => thumbnail.classList.toggle('is-active', thumbnailIndex === activeIndex));
            }

            thumbnails.forEach((thumbnail) => thumbnail.addEventListener('click', () => showPhoto(Number(thumbnail.dataset.galleryIndex))));
            document.querySelectorAll('[data-gallery-step]').forEach((button) => button.addEventListener('click', () => showPhoto(activeIndex + Number(button.dataset.galleryStep))));
        });
    </script>
@endif
