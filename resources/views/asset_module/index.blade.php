@extends('layouts.main')

@section('content')
    @php($rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.'))

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Modul Aset Barang</h1>
            <div class="text-muted">Registrasikan barang modal yang sudah diterima dan dibayar, dengan jejak ke RKBU dan SPJ. Kondisi Rusak Berat tidak otomatis menjadi reklas; pengajuan dilakukan lewat tombol Usulan Reklas.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('asset-module.history') }}" class="btn btn-light-info"><i class="fas fa-clock-rotate-left me-2"></i>Riwayat Aset</a>
            <a href="{{ route('asset-module.manual-create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Input Aset Hibah / Manual</a>
            <a href="{{ route('module-portal') }}" class="btn btn-light-primary"><i class="fas fa-layer-group me-2"></i>Beranda Modul</a>
        </div>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if ($errors->has('file')) <div class="alert alert-danger">{{ $errors->first('file') }}</div> @endif

    <section class="card border-0 shadow-sm mb-7">
        <div class="card-header border-0 pt-6">
            <div>
                <h2 class="card-title fs-3 fw-bold text-gray-900">Siap Diregistrasikan</h2>
                <div class="text-muted mt-1">Item modal yang sudah dikonfirmasi Pengurus Barang dan pembayaran SPJ selesai.</div>
            </div>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7">
                        <th>Item / RKBU</th><th>Unit</th><th class="text-end">Jumlah diterima</th><th class="text-end">Nilai</th><th>Tanggal terima</th><th>No. BAST</th><th></th>
                    </tr></thead>
                    <tbody>
                        @forelse ($pendingItems as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $item->nama_barang ?: 'Item modal' }}</div>
                                    <div class="text-muted fs-7">{{ $item->nomor_rkbu }} · {{ $item->spesifikasi ?: '-' }}</div>
                                </td>
                                <td>{{ $item->nama_unit ?: '-' }}</td>
                                <td class="text-end">{{ rtrim(rtrim(number_format((float) $item->volume_diterima, 3, ',', '.'), '0'), ',') }} {{ $item->satuan }}</td>
                                <td class="text-end">{{ $rupiah((float) $item->harga_satuan * (float) $item->volume_diterima * (1 + (float) $item->ppn / 100)) }}</td>
                                <td>{{ $item->tgl_barang_datang ?: '-' }}</td>
                                <td>{{ $item->no_bast_serah_terima_unit ?: '-' }}</td>
                                <td class="text-end"><a href="{{ route('asset-module.create', $item->spj_detail_id) }}" class="btn btn-sm btn-primary">Registrasi Aset</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-8">Belum ada item modal yang memenuhi syarat registrasi aset.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $pendingItems->links() }}
        </div>
    </section>

    <section class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6">
            <div>
                <h2 class="card-title fs-3 fw-bold text-gray-900">Daftar Aset</h2>
                <div class="text-muted mt-1">Data aset unit beserta sumber perolehan yang dapat ditelusuri.</div>
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#uploadAssetsModal"><i class="fas fa-file-import me-2"></i>Upload Data Aset</button>
            </div>
        </div>
        <div class="card-body pt-2">
            <form method="GET" action="{{ route('asset-module.index') }}" class="row g-3 align-items-end mb-5">
                <div class="col-12 col-lg-3"><label class="form-label">Cari aset</label><input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama, register, kode, merk, tipe"></div>
                <div class="col-6 col-lg-2"><label class="form-label">Tahun input</label><select name="tahun" class="form-select"><option value="">Semua tahun</option>@foreach ($years as $year)<option value="{{ $year }}" @selected((string) request('tahun') === (string) $year)>{{ $year }}</option>@endforeach</select></div>
                <div class="col-6 col-lg-2"><label class="form-label">Kondisi aset</label><select name="kondisi" class="form-select"><option value="">Semua kondisi</option>@foreach (['Baik', 'Rusak Ringan', 'Rusak Berat', 'Belum Dinilai'] as $condition)<option value="{{ $condition }}" @selected(request('kondisi') === $condition)>{{ $condition }}</option>@endforeach</select></div>
                <div class="col-6 col-lg-2"><label class="form-label">Tahun perolehan</label><select name="tahun_perolehan" class="form-select"><option value="">Semua tahun</option>@foreach ($acquisitionYears as $year)<option value="{{ $year }}" @selected((string) request('tahun_perolehan') === (string) $year)>{{ $year }}</option>@endforeach</select></div>
                <div class="col-6 col-lg-1"><label class="form-label">Tampil</label><select name="per_page" class="form-select">@foreach ([10, 25, 50, 100] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>@endforeach</select></div>
                <div class="col-12 col-lg-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1" type="submit"><i class="fas fa-search me-1"></i>Filter</button><a href="{{ route('asset-module.index') }}" class="btn btn-light">Reset</a></div>
            </form>
            <form method="POST" action="{{ route('asset-module.print-qr') }}" target="_blank" id="bulkAssetQrForm">
                @csrf
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div class="text-muted">Menampilkan {{ $assets->firstItem() ?? 0 }}–{{ $assets->lastItem() ?? 0 }} dari {{ $assets->total() }} aset</div>
                    <button type="submit" class="btn btn-light-primary"><i class="fas fa-print me-2"></i>Cetak QR Terpilih (<span id="selectedAssetQrCount">0</span>)</button>
                </div>
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7">
                        <th><input type="checkbox" class="form-check-input" id="selectAllAssetQr" aria-label="Pilih semua aset di halaman ini"></th><th>No. Register</th><th>Nama Aset</th><th>Unit</th><th>Tahun Perolehan</th><th>Kondisi</th><th>Pengguna / Lokasi</th><th>Sumber RKBU</th><th class="text-end">Nilai Perolehan</th><th>QR Aset</th><th></th>
                    </tr></thead>
                    <tbody>
                        @forelse ($assets as $asset)
                            <tr>
                                <td><input type="checkbox" class="form-check-input asset-qr-checkbox" name="asset_ids[]" value="{{ $asset->id_asset }}" aria-label="Pilih QR {{ $asset->nama_asset }}"></td>
                                <td class="fw-bold">{{ $asset->no_register ?: '-' }}</td>
                                <td><div class="fw-bold text-gray-900">{{ $asset->nama_asset }}</div><div class="text-muted fs-7">{{ $asset->merk }} {{ $asset->type }}</div></td>
                                <td>{{ $asset->unit?->nama_unit ?: '-' }}</td>
                                <td>{{ $asset->tahun_perolehan ?: '-' }}</td>
                                <td><span class="badge badge-light-info">{{ $asset->kondisi_asset }}</span></td>
                                <td>{{ $asset->pengguna_asset ?: $asset->lokasi_asset ?: '-' }}</td>
                                <td>{{ $asset->rkbu?->nomor_rkbu ?: ($asset->hibah ? 'Hibah' : 'Aset awal') }}</td>
                                <td class="text-end">{{ $rupiah($asset->total_anggaran_asset ?: $asset->harga_asset) }}</td>
                                <td><a href="{{ route('asset.scan', $asset->id_asset) }}" target="_blank" title="Pindai untuk membuka detail"><img src="{{ route('asset.qr', $asset->id_asset) }}" alt="QR {{ $asset->nama_asset }}" width="72" height="72" loading="lazy"></a></td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('asset-module.show', $asset) }}" class="btn btn-sm btn-light-info">Detail</a>
                                    <a href="{{ route('asset-module.edit', $asset) }}" class="btn btn-sm btn-light-primary">Ubah</a>
                                    @if ($asset->kondisi_asset === 'Rusak Berat' && $asset->status_reklas_arb !== 'Reklas')
                                        <button type="button" class="btn btn-sm btn-light-danger js-propose-asset-deletion" data-bs-toggle="modal" data-bs-target="#proposeAssetDeletionModal" data-asset-id="{{ $asset->id_asset }}" data-asset-name="{{ $asset->nama_asset }}">Usulan Reklas</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-8">Belum ada aset yang cocok dengan filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </form>
            {{ $assets->links() }}
            <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                <label for="assetPageSizeBottom" class="text-muted">Baris per halaman</label>
                <select id="assetPageSizeBottom" class="form-select form-select-sm w-auto" onchange="const u = new URL(window.location); u.searchParams.set('per_page', this.value); u.searchParams.delete('assets_page'); window.location = u.toString();">
                    @foreach ([10, 25, 50, 100] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>@endforeach
                </select>
            </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm mt-6">
        <div class="card-header border-0 pt-6">
            <div>
                <h2 class="card-title fs-3 fw-bold text-gray-900">Usulan Penghapusan / Reklas</h2>
                <div class="text-muted mt-1">Aset rusak berat yang diusulkan untuk dikeluarkan dari daftar aktif. Data aset dan riwayatnya tetap tersimpan.</div>
            </div>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>No. Register</th><th>Nama Aset</th><th>Unit</th><th>Kondisi</th><th>Alasan Usulan</th><th>QR</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($reclassificationItems as $asset)
                            @php($proposalHistory = $asset->histories->first())
                            <tr>
                                <td class="fw-bold">{{ $asset->no_register ?: '-' }}</td>
                                <td>{{ $asset->nama_asset }}</td>
                                <td>{{ $asset->unit?->nama_unit ?: '-' }}</td>
                                <td><span class="badge badge-light-danger">{{ $asset->kondisi_asset }}</span></td>
                                <td>{{ $proposalHistory?->description ?: 'Usulan penghapusan diajukan.' }}</td>
                                <td><a href="{{ route('asset.scan', $asset->id_asset) }}" target="_blank"><img src="{{ route('asset.qr', $asset->id_asset) }}" alt="QR {{ $asset->nama_asset }}" width="64" height="64" loading="lazy"></a></td>
                                <td><a href="{{ route('asset-module.show', $asset) }}" class="btn btn-sm btn-light-info">Detail & Riwayat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-8">Belum ada usulan penghapusan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reclassificationItems->links() }}
        </div>
    </section>

    <div class="modal fade" id="uploadAssetsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('asset-module.import') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Upload Data Aset</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Tutup"><i class="ki-duotone ki-cross fs-2"></i></button></div>
                <div class="modal-body">
                    <p class="text-muted">Gunakan template CSV yang disediakan, lalu isi satu baris untuk setiap aset. Nama aset, kondisi, dan jumlah wajib diisi. Administrator wajib mengisi kolom unit; Pengurus Barang otomatis memakai unit akunnya.</p>
                    <a href="{{ route('asset-module.import-template') }}" class="btn btn-sm btn-light-info mb-4"><i class="fas fa-download me-2"></i>Unduh Template</a>
                    <label for="assetImportFile" class="form-label required">File data aset</label>
                    <input id="assetImportFile" type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                    <div class="form-text">Format CSV, XLS, atau XLSX; maksimal 10 MB dan 5.000 baris.</div>
                    @error('file')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="proposeAssetDeletionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="proposeAssetDeletionForm" class="modal-content">
                @csrf
                <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Usulan Reklas / Penghapusan Aset</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Tutup"><i class="ki-duotone ki-cross fs-2"></i></button></div>
                <div class="modal-body">
                    <p>Usulan untuk <strong id="proposeAssetName"></strong>. Aset rusak berat akan dipindahkan dari daftar aktif, tetapi record dan riwayatnya tidak dihapus.</p>
                    <label for="proposeAssetReason" class="form-label required">Alasan / kondisi kerusakan</label>
                    <textarea id="proposeAssetReason" name="alasan" class="form-control" rows="4" maxlength="2000" required></textarea>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Kirim Usulan</button></div>
            </form>
        </div>
    </div>
    <script>
        const assetQrCheckboxes = [...document.querySelectorAll('.asset-qr-checkbox')];
        const selectAllAssetQr = document.getElementById('selectAllAssetQr');
        const selectedAssetQrCount = document.getElementById('selectedAssetQrCount');
        const syncAssetQrSelection = () => {
            const selected = assetQrCheckboxes.filter((checkbox) => checkbox.checked).length;
            selectedAssetQrCount.textContent = selected;
            selectAllAssetQr.checked = assetQrCheckboxes.length > 0 && selected === assetQrCheckboxes.length;
            selectAllAssetQr.indeterminate = selected > 0 && selected < assetQrCheckboxes.length;
        };
        selectAllAssetQr?.addEventListener('change', () => {
            assetQrCheckboxes.forEach((checkbox) => { checkbox.checked = selectAllAssetQr.checked; });
            syncAssetQrSelection();
        });
        assetQrCheckboxes.forEach((checkbox) => checkbox.addEventListener('change', syncAssetQrSelection));
        document.getElementById('bulkAssetQrForm')?.addEventListener('submit', (event) => {
            if (!assetQrCheckboxes.some((checkbox) => checkbox.checked)) {
                event.preventDefault();
                alert('Pilih minimal satu aset untuk dicetak QR-nya.');
            }
        });

        document.querySelectorAll('.js-propose-asset-deletion').forEach((button) => {
            button.addEventListener('click', () => {
                const form = document.getElementById('proposeAssetDeletionForm');
                form.action = @json(url('/asset')) + '/' + encodeURIComponent(button.dataset.assetId) + '/reclass-proposal';
                document.getElementById('proposeAssetName').textContent = button.dataset.assetName;
                document.getElementById('proposeAssetReason').value = '';
            });
        });

        @if ($errors->has('file'))
            bootstrap.Modal.getOrCreateInstance(document.getElementById('uploadAssetsModal')).show();
        @endif
    </script>
@endsection
