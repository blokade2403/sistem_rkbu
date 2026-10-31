@extends('layouts.main')

@section('content')
    @php($qty = fn ($value) => rtrim(rtrim(number_format((float) $value, 3, ',', '.'), '0'), ','))
    @php($rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.'))

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $title }}</h1>
            <div class="text-muted">Barang masuk berasal dari usulan belanja berstatus Sudah di Bayar. Pengeluaran dan opname tercatat dalam kartu stok.</div>
        </div>
        <a href="{{ route('module-portal') }}" class="btn btn-light-primary"><i class="fas fa-arrow-left me-2"></i>Beranda Modul</a>
    </div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @include('persediaan.inventory._navigation')

    @if ($page === 'overview')
        <div class="row g-4 mb-6">
            @if ($canManage)<div class="col-sm-6 col-xl-3"><a href="{{ route('persediaan.inventory.barang-masuk') }}" class="card card-bordered h-100 text-decoration-none"><div class="card-body"><div class="text-muted">Penerimaan menunggu</div><div class="fs-2 fw-bold text-gray-900 mt-2">{{ $incomingPending }}</div><div class="text-primary mt-2">Lihat barang masuk <i class="fas fa-arrow-right ms-1"></i></div></div></a></div>@endif
            <div class="col-sm-6 col-xl-3"><a href="{{ route('persediaan.inventory.stok') }}" class="card card-bordered h-100 text-decoration-none"><div class="card-body"><div class="text-muted">Jenis barang terdaftar</div><div class="fs-2 fw-bold text-gray-900 mt-2">{{ $totalItems }}</div><div class="text-primary mt-2">Lihat stok barang <i class="fas fa-arrow-right ms-1"></i></div></div></a></div>
            <div class="col-sm-6 col-xl-3"><a href="{{ route('persediaan.inventory.permintaan') }}" class="card card-bordered h-100 text-decoration-none"><div class="card-body"><div class="text-muted">Permintaan menunggu</div><div class="fs-2 fw-bold text-gray-900 mt-2">{{ $pendingRequests }}</div><div class="text-primary mt-2">Proses permintaan <i class="fas fa-arrow-right ms-1"></i></div></div></a></div>
            <div class="col-sm-6 col-xl-3"><a href="{{ route('persediaan.inventory.stok') }}" class="card card-bordered h-100 text-decoration-none"><div class="card-body"><div class="text-muted">Nilai persediaan</div><div class="fs-4 fw-bold text-gray-900 mt-2">{{ $rupiah($inventoryValue) }}</div><div class="text-primary mt-2">Lihat nilai per barang <i class="fas fa-arrow-right ms-1"></i></div></div></a></div>
            <div class="col-sm-6 col-xl-3"><a href="{{ route('persediaan.inventory.laporan') }}" class="card card-bordered h-100 text-decoration-none"><div class="card-body"><div class="text-muted">Catatan mutasi</div><div class="fs-2 fw-bold text-gray-900 mt-2">{{ $transactionCount }}</div><div class="text-primary mt-2">Lihat laporan mutasi <i class="fas fa-arrow-right ms-1"></i></div></div></a></div>
        </div>
    @endif

    @if ($page === 'barang_masuk')
    <section class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Registrasi Barang Masuk</h2><div class="text-muted">Daftar item persediaan yang status pembayarannya sudah Sudah di Bayar. Volume masuk dibatasi sesuai volume pada SPJ.</div></div></div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-3">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Kode Barang / RKBU</th><th>Unit</th><th>No. SPJ / BAST</th><th class="text-end">Volume SPJ</th><th class="text-end">Sudah Masuk</th><th class="text-end">Sisa Penerimaan</th><th>Tanggal Bayar</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($sources as $source)
                            <tr>
                                <td><div class="fw-bold">{{ $source->inventory->kode_barang }}<br>{{ $source->nama_barang }}</div><div class="text-muted fs-7">{{ $source->nomor_rkbu }} · {{ $source->kode_sub_kategori_rkbu }} {{ $source->nama_sub_kategori_rkbu }} · {{ $source->spesifikasi ?: '-' }}</div></td>
                                <td>{{ $source->nama_unit }}</td>
                                <td>{{ $source->no_surat_pesanan ?: '-' }}<div class="text-muted fs-7">BAST: {{ $source->no_bast_serah_terima_unit ?: '-' }}</div></td>
                                <td class="text-end">{{ $qty($source->volume) }} {{ $source->satuan }}</td>
                                <td class="text-end">{{ $qty($source->received_quantity) }}</td>
                                <td class="text-end fw-bold">{{ $qty($source->remaining_quantity) }} {{ $source->satuan }}</td>
                                <td>{{ $source->tanggal_pembayaran ? \Illuminate\Support\Carbon::parse($source->tanggal_pembayaran)->format('d-m-Y') : '-' }}</td>
                                <td class="text-end">@if ($canManage && $source->remaining_quantity > 0)<button class="btn btn-sm btn-primary js-receive-stock" data-bs-toggle="modal" data-bs-target="#receiveStockModal" data-source-id="{{ $source->spj_detail_id }}" data-item-name="{{ $source->nama_barang }}" data-remaining="{{ $source->remaining_quantity }}" data-unit="{{ $source->satuan }}">Catat Barang Masuk</button>@else<span class="badge badge-light-success">{{ $source->remaining_quantity <= 0 ? 'Penerimaan Lengkap' : 'Menunggu Pengurus Barang' }}</span>@endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-8">Belum ada item persediaan berstatus pembayaran Sudah di Bayar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    @if (in_array($page, ['stok', 'opname'], true))
    <section class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Stok Persediaan per Unit</h2><div class="text-muted">Subkategori mengikuti RKBU persediaan. Nilai dihitung dari stok × harga satuan termasuk PPN.</div></div></div>
        <div class="card-body pt-2">
            @if ($page === 'stok')
                <form method="GET" action="{{ route('persediaan.inventory.stok') }}" class="row g-3 align-items-end mb-5">
                    <div class="col-md-4"><label class="form-label">Cari barang</label><input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Kode, nama, atau spesifikasi"></div>
                    <div class="col-md-5"><label class="form-label">Subkategori RKBU Persediaan</label><select name="sub_kategori_rkbu_id" class="form-select"><option value="">Semua subkategori</option>@foreach ($subcategories as $subcategory)<option value="{{ $subcategory->id_sub_kategori_rkbu }}" @selected(request('sub_kategori_rkbu_id') === $subcategory->id_sub_kategori_rkbu)>{{ $subcategory->kode_sub_kategori_rkbu }} — {{ $subcategory->nama_sub_kategori_rkbu }}</option>@endforeach</select></div>
                    <div class="col-md-3 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Filter</button><a href="{{ route('persediaan.inventory.stok') }}" class="btn btn-light">Reset</a></div>
                </form>
                <div class="alert alert-light-info d-flex justify-content-between align-items-center flex-wrap gap-2"><span>Nilai persediaan untuk filter ini</span><strong>{{ $rupiah($inventoryValue) }}</strong></div>
            @endif
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-3">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Subkategori RKBU</th><th>Nama Barang</th><th>Unit Stok</th><th>Spesifikasi</th><th class="text-end">Stok</th><th class="text-end">Harga Satuan + PPN</th><th class="text-end">Nilai Persediaan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td><div class="fw-bold">{{ $item->subKategoriRkbu?->kode_sub_kategori_rkbu ?: '-' }}</div><div class="text-muted fs-7">{{ $item->subKategoriRkbu?->nama_sub_kategori_rkbu ?: 'Subkategori belum tersedia' }}</div></td>
                                <td class="fw-bold"><span class="text-muted fs-7">{{ $item->kode_barang }}</span><br>{{ $item->nama_barang }}</td><td>{{ $item->unit?->nama_unit ?: '-' }}</td><td>{{ $item->spesifikasi ?: '-' }}</td>
                                <td class="text-end fw-bold">{{ $qty($item->stok) }} {{ $item->satuan }}</td>
                                <td class="text-end">{{ $item->harga_satuan !== null ? $rupiah((float) $item->harga_satuan * (1 + (float) $item->ppn / 100)) : '-' }}</td>
                                <td class="text-end fw-bold">{{ $item->harga_satuan !== null ? $rupiah($item->nilai_persediaan) : '-' }}</td>
                                <td class="text-nowrap">
                                    @if ($page !== 'opname' && auth()->user()->unit_id)
                                        <button class="btn btn-sm btn-light-primary js-request-stock" data-bs-toggle="modal" data-bs-target="#requestStockModal" data-item-id="{{ $item->id }}" data-item-name="{{ $item->nama_barang }}" data-stock="{{ $item->stok }}" data-unit="{{ $item->satuan }}">Minta Barang</button>
                                    @endif
                                    @if ($canManage && $page !== 'stok')
                                        <button class="btn btn-sm btn-light-warning js-stocktake" data-bs-toggle="modal" data-bs-target="#stocktakeModal" data-item-id="{{ $item->id }}" data-item-name="{{ $item->nama_barang }}" data-stock="{{ $item->stok }}" data-unit="{{ $item->satuan }}">Stok Opname</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-8">Belum ada kartu stok. Item muncul setelah pembayaran usulan persediaan selesai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $items->links() }}
        </div>
    </section>
    @endif

    @if ($page === 'permintaan')
    <section class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Permintaan Pengeluaran dari Unit</h2></div></div>
        <div class="card-body pt-2">
            @if (auth()->user()->unit_id)
                <form method="POST" action="{{ route('persediaan.inventory.request') }}" class="row g-3 align-items-end mb-6 p-4 bg-light rounded">
                    @csrf
                    <div class="col-lg-4"><label class="form-label required">Barang yang diminta</label><select name="persediaan_item_id" class="form-select" required><option value="">Pilih stok barang</option>@foreach ($requestableItems as $requestableItem)<option value="{{ $requestableItem->id }}">{{ $requestableItem->subKategoriRkbu?->kode_sub_kategori_rkbu }} — {{ $requestableItem->nama_barang }} — {{ $requestableItem->unit?->nama_unit }} (stok {{ $qty($requestableItem->stok) }} {{ $requestableItem->satuan }})</option>@endforeach</select></div>
                    <div class="col-lg-2"><label class="form-label required">Jumlah</label><input type="number" name="jumlah" class="form-control" min="0.001" step="0.001" required></div>
                    <div class="col-lg-4"><label class="form-label required">Keperluan</label><input name="keperluan" class="form-control" maxlength="2000" required></div>
                    <div class="col-lg-2"><button class="btn btn-primary w-100" type="submit"><i class="fas fa-paper-plane me-2"></i>Kirim Permintaan</button></div>
                </form>
            @endif
            <div class="table-responsive">
            <table class="table table-row-bordered align-middle gy-3">
                <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Tanggal</th><th>Unit Peminta</th><th>Barang</th><th class="text-end">Jumlah</th><th>Keperluan</th><th>Status / Catatan</th>@if ($canManage)<th>Aksi</th>@endif</tr></thead>
                <tbody>
                    @forelse ($requests as $requestItem)
                        <tr>
                            <td>{{ $requestItem->created_at?->format('d-m-Y') }}</td><td>{{ $requestItem->unit?->nama_unit }}</td><td>{{ $requestItem->item?->nama_barang }}</td>
                            <td class="text-end">{{ $qty($requestItem->jumlah) }} {{ $requestItem->item?->satuan }}</td><td>{{ $requestItem->keperluan }}</td>
                            <td><span class="badge {{ $requestItem->status === 'Disetujui' ? 'badge-light-success' : ($requestItem->status === 'Ditolak' ? 'badge-light-danger' : 'badge-light-warning') }}">{{ $requestItem->status }}</span>@if ($requestItem->catatan_verifikasi)<div class="text-muted fs-7 mt-1">{{ $requestItem->catatan_verifikasi }}</div>@endif</td>
                            @if ($canManage)<td class="text-nowrap">@if ($requestItem->status === 'Menunggu')<form method="POST" action="{{ route('persediaan.inventory.approve', $requestItem->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success" type="submit">Setujui & Keluarkan</button></form><button class="btn btn-sm btn-light-danger js-reject-request" data-bs-toggle="modal" data-bs-target="#rejectRequestModal" data-request-id="{{ $requestItem->id }}">Tolak</button>@else{{ $requestItem->reviewer?->nama ?: '-' }}@endif</td>@endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $canManage ? 7 : 6 }}" class="text-center text-muted py-8">Belum ada permintaan barang dari unit.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </section>
    @endif

    @if (in_array($page, ['overview', 'laporan'], true))
    @if ($page === 'laporan')
        <form method="GET" action="{{ route('persediaan.inventory.laporan') }}" class="row g-3 align-items-end mb-5">
            <div class="col-sm-4"><label class="form-label">Jenis transaksi</label><select name="jenis" class="form-select"><option value="">Semua jenis</option>@foreach (['masuk' => 'Barang masuk', 'keluar' => 'Barang keluar', 'opname' => 'Stok opname'] as $value => $label)<option value="{{ $value }}" @selected(request('jenis') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-sm-3"><label class="form-label">Dari tanggal</label><input type="date" name="dari" class="form-control" value="{{ request('dari') }}"></div>
            <div class="col-sm-3"><label class="form-label">Sampai tanggal</label><input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}"></div>
            @if ($isAdministrator)<div class="col-sm-4"><label class="form-label">Unit gudang</label><select name="unit_id" class="form-select"><option value="">Semua unit</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(request('unit_id') === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select></div>@endif
            <div class="col-sm-2"><button class="btn btn-primary w-100">Terapkan</button></div>
        </form>
    @endif
    <section class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Kartu Stok / Riwayat Mutasi</h2><div class="text-muted">Mencakup barang masuk, barang keluar, dan selisih stok opname.</div></div></div>
        <div class="card-body pt-2"><div class="table-responsive">
            <table class="table table-row-bordered align-middle gy-3">
                <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Tanggal</th><th>Barang</th><th>Gudang / Unit Stok</th><th>Unit Peminta</th><th>Jenis</th><th class="text-end">Mutasi</th><th class="text-end">Saldo Sebelum</th><th class="text-end">Saldo Sesudah</th><th>Keterangan</th><th>Petugas</th></tr></thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr><td>{{ $transaction->tanggal?->format('d-m-Y') }}</td><td>{{ $transaction->item?->nama_barang }}</td><td>{{ $transaction->unit?->nama_unit }}</td><td>{{ $transaction->requestingUnit?->nama_unit ?: '-' }}</td><td>{{ ucfirst($transaction->jenis) }}</td><td class="text-end">{{ $qty($transaction->jumlah) }} {{ $transaction->item?->satuan }}</td><td class="text-end">{{ $qty($transaction->stok_sebelum) }}</td><td class="text-end fw-bold">{{ $qty($transaction->stok_sesudah) }}</td><td>{{ $transaction->keterangan ?: '-' }}</td><td>{{ $transaction->actor?->nama ?: 'Akun dihapus' }}</td></tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted py-8">Belum ada transaksi persediaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
    </section>
    @if ($page === 'laporan'){{ $transactions->links() }}@endif
    @endif

    @if ($canManage && $page === 'barang_masuk')
        <div class="modal fade" id="receiveStockModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><form method="POST" action="{{ route('persediaan.inventory.receive') }}" class="modal-content">@csrf
            <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Catat Barang Masuk</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"></i></button></div>
            <div class="modal-body"><input type="hidden" name="spj_detail_id" id="receiveSourceId"><div class="mb-4"><strong id="receiveItemName"></strong><div class="text-muted">Sisa volume penerimaan: <span id="receiveRemaining"></span></div></div><label class="form-label required">Jumlah diterima</label><input type="number" name="jumlah" id="receiveQuantity" class="form-control mb-4" min="0.001" step="0.001" required><label class="form-label required">Tanggal diterima</label><input type="date" name="tanggal" class="form-control mb-4" value="{{ now()->toDateString() }}" required><label class="form-label">Catatan</label><textarea name="keterangan" class="form-control" rows="3"></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Barang Masuk</button></div>
        </form></div></div>
    @endif
    @if ($canManage && $page === 'opname')
        <div class="modal fade" id="stocktakeModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><form method="POST" id="stocktakeForm" class="modal-content">@csrf
            <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Stok Opname</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"></i></button></div>
            <div class="modal-body"><div class="fw-bold mb-1" id="stocktakeItemName"></div><div class="text-muted mb-4">Saldo sistem: <span id="stocktakeCurrent"></span></div><label class="form-label required">Jumlah fisik hasil hitung</label><input type="number" name="stok_fisik" class="form-control mb-4" min="0" step="0.001" required><label class="form-label required">Tanggal opname</label><input type="date" name="tanggal" class="form-control mb-4" value="{{ now()->toDateString() }}" required><label class="form-label required">Catatan</label><textarea name="keterangan" class="form-control" rows="3" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-warning">Simpan Opname</button></div>
        </form></div></div>
    @endif
    @if ($canManage && $page === 'permintaan')
        <div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><form method="POST" id="rejectRequestForm" class="modal-content">@csrf
            <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Tolak Permintaan</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"></i></button></div><div class="modal-body"><label class="form-label required">Alasan penolakan</label><textarea name="catatan_verifikasi" class="form-control" rows="4" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Tolak Permintaan</button></div>
        </form></div></div>
    @endif
    @if (auth()->user()->unit_id && $page === 'stok')
    <div class="modal fade" id="requestStockModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><form method="POST" action="{{ route('persediaan.inventory.request') }}" class="modal-content">@csrf
        <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Permintaan Barang Keluar</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"></i></button></div><div class="modal-body"><input type="hidden" name="persediaan_item_id" id="requestItemId"><div class="fw-bold mb-1" id="requestItemName"></div><div class="text-muted mb-4">Stok tersedia: <span id="requestStockAvailable"></span></div><label class="form-label required">Jumlah diminta</label><input type="number" name="jumlah" class="form-control mb-4" min="0.001" step="0.001" required><label class="form-label required">Keperluan unit</label><textarea name="keperluan" class="form-control" rows="4" required></textarea></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Kirim Permintaan</button></div>
    </form></div></div>
    @endif

    <script>
        document.querySelectorAll('.js-receive-stock').forEach((button) => button.addEventListener('click', () => {
            document.getElementById('receiveSourceId').value = button.dataset.sourceId;
            document.getElementById('receiveItemName').textContent = button.dataset.itemName;
            document.getElementById('receiveRemaining').textContent = `${button.dataset.remaining} ${button.dataset.unit || ''}`;
            const quantity = document.getElementById('receiveQuantity');
            quantity.max = button.dataset.remaining;
            quantity.value = button.dataset.remaining;
        }));
        document.querySelectorAll('.js-request-stock').forEach((button) => button.addEventListener('click', () => {
            document.getElementById('requestItemId').value = button.dataset.itemId;
            document.getElementById('requestItemName').textContent = button.dataset.itemName;
            document.getElementById('requestStockAvailable').textContent = `${button.dataset.stock} ${button.dataset.unit || ''}`;
        }));
        document.querySelectorAll('.js-stocktake').forEach((button) => button.addEventListener('click', () => {
            document.getElementById('stocktakeForm').action = @json(url('/persediaan-inventory/stok')) + '/' + encodeURIComponent(button.dataset.itemId) + '/opname';
            document.getElementById('stocktakeItemName').textContent = button.dataset.itemName;
            document.getElementById('stocktakeCurrent').textContent = `${button.dataset.stock} ${button.dataset.unit || ''}`;
            document.querySelector('#stocktakeForm [name="stok_fisik"]').value = button.dataset.stock;
        }));
        document.querySelectorAll('.js-reject-request').forEach((button) => button.addEventListener('click', () => {
            document.getElementById('rejectRequestForm').action = @json(url('/persediaan-inventory/permintaan')) + '/' + encodeURIComponent(button.dataset.requestId) + '/tolak';
        }));
    </script>
@endsection
