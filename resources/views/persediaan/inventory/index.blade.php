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
        <div class="card-header border-0 pt-6 d-flex flex-wrap justify-content-between align-items-center gap-3"><div><h2 class="card-title fs-3 fw-bold">Registrasi Barang Masuk</h2><div class="text-muted">Daftar item persediaan yang status pembayarannya sudah Sudah di Bayar. Volume masuk dibatasi sesuai volume pada SPJ.</div></div><button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#externalReceiveModal"><i class="fas fa-plus me-2"></i>Barang Masuk Non-Usulan</button></div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-3">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Kode Barang / RKBU</th><th>Unit</th><th>No. SPJ / BAST</th><th class="text-end">Volume SPJ</th><th class="text-end">Sudah Masuk</th><th class="text-end">Sisa Penerimaan</th><th>Tanggal Bayar</th><th></th></tr></thead>
                    <tbody>
                        @if ($sources->isEmpty())
                            <tr><td colspan="8" class="text-center text-muted py-8">Belum ada item persediaan berstatus pembayaran Sudah di Bayar.</td></tr>
                        @else
                        @foreach ($sources as $source)
                            <tr>
                                <td><div class="fw-bold">{{ $source->inventory->kode_barang }}<br>{{ $source->nama_barang }}</div><div class="text-muted fs-7">{{ $source->nomor_rkbu }} &middot; {{ $source->kode_sub_kategori_rkbu }} {{ $source->nama_sub_kategori_rkbu }} &middot; {{ $source->spesifikasi ?: '-' }}</div></td>
                                <td>{{ $source->nama_unit }}</td>
                                <td>{{ $source->no_surat_pesanan ?: '-' }}<div class="text-muted fs-7">BAST: {{ $source->no_bast_serah_terima_unit ?: '-' }}</div></td>
                                <td class="text-end">{{ $qty($source->volume) }} {{ $source->satuan }}</td>
                                <td class="text-end">{{ $qty($source->received_quantity) }}</td>
                                <td class="text-end fw-bold">{{ $qty($source->remaining_quantity) }} {{ $source->satuan }}</td>
                                <td>{{ $source->tanggal_pembayaran ? \Illuminate\Support\Carbon::parse($source->tanggal_pembayaran)->format('d-m-Y') : '-' }}</td>
                                <td class="text-end">@if ($canManage && $source->remaining_quantity > 0)<button class="btn btn-sm btn-primary js-receive-stock" data-bs-toggle="modal" data-bs-target="#receiveStockModal" data-source-id="{{ $source->spj_detail_id }}" data-item-name="{{ $source->nama_barang }}" data-remaining="{{ $source->remaining_quantity }}" data-unit="{{ $source->satuan }}">Catat Barang Masuk</button>@else<span class="badge badge-light-success">{{ $source->remaining_quantity <= 0 ? 'Penerimaan Lengkap' : 'Menunggu Pengurus Barang' }}</span>@endif</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    @if (in_array($page, ['stok', 'opname'], true))
    <section class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Stok Persediaan per Unit</h2><div class="text-muted">Subkategori mengikuti RKBU persediaan. Nilai dihitung dari stok &times; harga satuan termasuk PPN.</div></div></div>
        <div class="card-body pt-2">
            @if ($page === 'stok')
                <form method="GET" action="{{ route('persediaan.inventory.stok') }}" class="row g-3 align-items-end mb-5">
                    <div class="col-md-4"><label class="form-label">Cari barang</label><input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Kode, nama, atau spesifikasi"></div>
                    <div class="col-md-5"><label class="form-label">Subkategori RKBU Persediaan</label><select name="sub_kategori_rkbu_id" class="form-select"><option value="">Semua subkategori</option>@foreach ($subcategories as $subcategory)<option value="{{ $subcategory->id_sub_kategori_rkbu }}" @selected(request('sub_kategori_rkbu_id') === $subcategory->id_sub_kategori_rkbu)>{{ $subcategory->kode_sub_kategori_rkbu }} &mdash; {{ $subcategory->nama_sub_kategori_rkbu }}</option>@endforeach</select></div>
                    <div class="col-md-3 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Filter</button><a href="{{ route('persediaan.inventory.stok') }}" class="btn btn-light">Reset</a></div>
                    <div class="col-12 d-flex justify-content-end"><button type="submit" formaction="{{ route('persediaan.inventory.stok-pdf') }}" formtarget="_blank" class="btn btn-light-danger"><i class="fas fa-file-pdf me-2"></i>Unduh PDF Stok</button></div>
                </form>
                <div class="alert alert-light-info d-flex justify-content-between align-items-center flex-wrap gap-2"><span>Nilai persediaan untuk filter ini</span><strong>{{ $rupiah($inventoryValue) }}</strong></div>
            @endif
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-3">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Subkategori RKBU</th><th>Nama Barang</th><th>Unit Stok</th><th>Spesifikasi</th><th class="text-end">Stok</th><th class="text-end">Harga Satuan + PPN</th><th class="text-end">Nilai Persediaan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr><td colspan="8" class="text-center text-muted py-8">Belum ada kartu stok. Item muncul setelah pembayaran usulan persediaan selesai.</td></tr>
                        @else
                        @foreach ($items as $item)
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
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $items->links() }}
        </div>
    </section>
    @endif

    <?php if ($page === 'permintaan'): ?>
    <section class="card border-0 shadow-sm mb-6">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Permintaan Pengeluaran dari Unit</h2></div></div>
        <div class="card-body pt-2">
            <?php if (auth()->user()->unit_id): ?>
                <form method="POST" action="<?= e(route('persediaan.inventory.request')) ?>" class="mb-6 p-4 bg-light rounded" id="multiRequestForm">
                    <?= csrf_field() ?>
                    <div class="fw-bold mb-1">Katalog barang</div><div class="text-muted mb-3">Cari kode, nama, atau spesifikasi barang. Hasil pencarian dibatasi 20 produk.</div>
                    <div class="mb-3"><input type="search" id="requestItemSearch" class="form-control form-control-lg" placeholder="Cari nama atau kode barang..."></div>
                    <div id="requestItemResults" class="row g-3 mb-5">
                        <?php if ($requestableItems->isEmpty()): ?><div class="col-12"><div class="alert alert-light mb-0">Belum ada stok barang tersedia di katalog.</div></div><?php endif; ?>
                        <?php foreach ($requestableItems as $catalogItem): ?>
                        <div class="col-sm-6 col-xl-4"><div class="card h-100 border shadow-sm"><div class="card-body d-flex flex-column"><div class="d-flex justify-content-between align-items-start gap-2 mb-3"><span class="badge badge-light-primary"><?= e($catalogItem->kode_barang ?: 'Barang') ?></span><i class="fas fa-box-open text-primary fs-2"></i></div><h3 class="fs-6 fw-bold mb-1"><?= e($catalogItem->nama_barang) ?></h3><div class="text-muted fs-7 mb-3"><?= e($catalogItem->spesifikasi ?: $catalogItem->subKategoriRkbu?->kode_sub_kategori_rkbu ?: 'Persediaan') ?></div><div class="mt-auto d-flex justify-content-between align-items-center"><div class="small"><span class="text-muted">Stok tersedia</span><strong class="d-block"><?= e($qty($catalogItem->stok)) ?> <?= e($catalogItem->satuan) ?></strong></div><button type="button" class="btn btn-sm btn-primary js-add-catalog-item" data-id="<?= e($catalogItem->id) ?>" data-code="<?= e($catalogItem->kode_barang) ?>" data-name="<?= e($catalogItem->nama_barang) ?>" data-specification="<?= e($catalogItem->spesifikasi) ?>" data-unit="<?= e($catalogItem->unit?->nama_unit) ?>" data-satuan="<?= e($catalogItem->satuan) ?>" data-stock="<?= e($catalogItem->stok) ?>">Tambah</button></div></div></div></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="fw-semibold mb-2">Keranjang permintaan <span class="text-muted" id="selectedRequestCount">(0)</span></div>
                    <div class="table-responsive mb-4"><table class="table table-row-bordered align-middle gy-2 mb-0"><thead><tr><th>Barang</th><th>Unit stok</th><th>Stok</th><th style="width:170px">Jumlah diminta</th><th style="width:60px"></th></tr></thead><tbody id="selectedRequestItems"><tr><td colspan="5" class="text-center text-muted py-4">Belum ada barang dipilih.</td></tr></tbody></table></div>
                    <div class="row g-3 align-items-end"><div class="col-lg-9"><label class="form-label required">Keperluan unit</label><input name="keperluan" class="form-control" maxlength="2000" required></div><div class="col-lg-3"><button class="btn btn-primary w-100" type="submit"><i class="fas fa-paper-plane me-2"></i>Kirim Permintaan</button></div></div>
                </form>
            <?php endif; ?>
            <div class="table-responsive"><table class="table table-row-bordered align-middle gy-3">
                <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>No. Permintaan</th><th>Tanggal</th><th>Unit Peminta</th><th>Barang</th><th class="text-end">Volume Diminta / Diberikan</th><th>Keperluan</th><th>Status Permintaan</th><?php if ($canManage || auth()->user()->unit_id): ?><th>Aksi</th><?php endif; ?></tr></thead>
                <tbody>
                <?php if ($requests->isEmpty()): ?>
                    <tr><td colspan="8" class="text-center text-muted py-8">Belum ada permintaan barang dari unit.</td></tr>
                <?php else: foreach ($requests as $requestItem): ?>
                    <?php
                        $requestStatusStyle = match ($requestItem->status) { 'Siap Diambil' => 'primary', 'Sudah Diambil' => 'success', 'Ditolak' => 'danger', default => 'warning' };
                        $requestStatusLabel = match ($requestItem->status) { 'Siap Diambil' => 'Silakan Ambil di Gudang', 'Sudah Diambil' => 'Barang Sudah Diambil', default => $requestItem->status };
                        $requestStatusMessage = match ($requestItem->status) { 'Siap Diambil' => 'Permintaan telah divalidasi Pengurus Barang. Barang silakan diambil di gudang.', 'Sudah Diambil' => 'Penerimaan barang telah dikonfirmasi.', 'Ditolak' => 'Permintaan ditolak.', default => 'Permintaan terkirim dan menunggu validasi Pengurus Barang.' };
                    ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= e($requestItem->nomor_permintaan ?: '-') ?></td><td><?= e($requestItem->created_at?->format('d-m-Y')) ?></td><td><?= e($requestItem->unit?->nama_unit) ?></td><td><?= e($requestItem->item?->nama_barang) ?></td>
                        <td class="text-end"><div>Diminta: <?= e($qty($requestItem->jumlah)) ?> <?= e($requestItem->item?->satuan) ?></div><?php if ($requestItem->jumlah_disetujui !== null): ?><div class="fw-bold text-primary">Diberikan: <?= e($qty($requestItem->jumlah_disetujui)) ?> <?= e($requestItem->item?->satuan) ?></div><?php endif; ?></td><td><?= e($requestItem->keperluan) ?></td>
                        <td><div class="alert alert-<?= e($requestStatusStyle) ?> py-2 px-3 mb-0"><div class="fw-bold"><i class="fas <?= $requestItem->status === 'Sudah Diambil' ? 'fa-check-circle' : ($requestItem->status === 'Ditolak' ? 'fa-times-circle' : ($requestItem->status === 'Siap Diambil' ? 'fa-box' : 'fa-clock')) ?> me-1"></i><?= e($requestStatusLabel) ?></div><div class="fs-7"><?= e($requestStatusMessage) ?></div>
                            <?php if ($requestItem->reviewed_at): ?><div class="fs-8 mt-1">Diperbarui <?= e($requestItem->reviewed_at->format('d-m-Y H:i')) ?><?php if ($requestItem->reviewer): ?> oleh <?= e($requestItem->reviewer->nama) ?><?php endif; ?></div><?php endif; ?>
                            <?php if ($requestItem->catatan_verifikasi): ?><div class="fs-7 mt-1"><strong>Catatan:</strong> <?= e($requestItem->catatan_verifikasi) ?></div><?php endif; ?>
                            <?php if ($requestItem->picked_up_at): ?><div class="fs-8 mt-1">Diterima <?= e($requestItem->picked_up_at->format('d-m-Y H:i')) ?><?php if ($requestItem->pickupConfirmer): ?> oleh <?= e($requestItem->pickupConfirmer->nama) ?><?php endif; ?></div><?php endif; ?>
                        </div></td>
                        <?php if ($canManage || auth()->user()->unit_id): ?><td class="text-nowrap">
                            <?php if ($canManage && $requestItem->status === 'Menunggu'): ?><form method="POST" action="<?= e(route('persediaan.inventory.approve', $requestItem->id)) ?>" class="d-flex flex-column gap-2"><?= csrf_field() ?><label class="form-label mb-0 fs-8">Jumlah diberikan</label><input type="number" name="jumlah_disetujui" class="form-control form-control-sm" min="0.001" max="<?= e($requestItem->jumlah) ?>" step="0.001" value="<?= e($requestItem->jumlah) ?>" required><button class="btn btn-sm btn-success" type="submit">Validasi &amp; Siapkan</button></form><button class="btn btn-sm btn-light-danger mt-2 js-reject-request" data-bs-toggle="modal" data-bs-target="#rejectRequestModal" data-request-id="<?= e($requestItem->id) ?>">Tolak</button>
                            <?php elseif ($requestItem->status === 'Siap Diambil' && ($canManage || $requestItem->unit_peminta_id === auth()->user()->unit_id)): ?><form method="POST" action="<?= e(route('persediaan.inventory.pickup', $requestItem->id)) ?>"><?= csrf_field() ?><button class="btn btn-sm btn-primary" type="submit">Konfirmasi Sudah Diambil</button></form>
                            <?php else: ?><?= e($requestItem->reviewer?->nama ?: '-') ?><?php endif; ?>
                        </td><?php endif; ?>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table></div>
            <?php if ($page === 'permintaan'): ?><?= $requests->links() ?><?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    @if (in_array($page, ['overview', 'laporan'], true))
    @if ($page === 'laporan')
        <form method="GET" action="{{ route('persediaan.inventory.laporan') }}" class="row g-3 align-items-end mb-5">
            <div class="col-md-4"><label class="form-label">Jenis transaksi</label><select name="jenis" class="form-select"><option value="">Semua jenis</option>@foreach (['masuk' => 'Barang masuk', 'keluar' => 'Barang keluar', 'opname' => 'Stok opname'] as $value => $label)<option value="{{ $value }}" @selected(request('jenis') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">Cari barang</label><input type="search" name="barang" class="form-control" value="{{ request('barang') }}" placeholder="Nama atau kode barang"></div>
            <div class="col-md-2"><label class="form-label">Dari tanggal</label><input type="date" name="dari" class="form-control" value="{{ request('dari') }}"></div>
            <div class="col-md-2"><label class="form-label">Sampai tanggal</label><input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}"></div>
            @if ($canManage)
                <div class="col-md-4"><label class="form-label">Unit gudang</label><select name="unit_id" class="form-select"><option value="">Semua unit gudang</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(request('unit_id') === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Unit peminta</label><select name="unit_peminta_id" class="form-select"><option value="">Semua unit peminta</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(request('unit_peminta_id') === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select></div>
            @endif
            <div class="col-md-2 d-flex gap-2"><button class="btn btn-primary flex-grow-1">Terapkan</button><a href="{{ route('persediaan.inventory.laporan') }}" class="btn btn-light">Reset</a></div>
        </form>
    @endif
    <section class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Kartu Stok / Riwayat Mutasi</h2><div class="text-muted">Mencakup barang masuk, barang keluar, dan selisih stok opname.</div></div></div>
        <div class="card-body pt-2"><div class="table-responsive">
            <table class="table table-row-bordered align-middle gy-3">
                <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Tanggal</th><th>Barang</th><th>Gudang / Unit Stok</th><th>Unit Peminta</th><th>Jenis</th><th class="text-end">Mutasi</th><th class="text-end">Saldo Sebelum</th><th class="text-end">Saldo Sesudah</th><th>Keterangan</th><th>Petugas</th></tr></thead>
                <tbody>
                    @if ($transactions->isEmpty())
                        <tr><td colspan="10" class="text-center text-muted py-8">Belum ada transaksi persediaan.</td></tr>
                    @else
                    @foreach ($transactions as $transaction)
                        <tr><td>{{ $transaction->tanggal?->format('d-m-Y') }}</td><td>{{ $transaction->item?->nama_barang }}</td><td>{{ $transaction->unit?->nama_unit }}</td><td>{{ $transaction->requestingUnit?->nama_unit ?: '-' }}</td><td>{{ ucfirst($transaction->jenis) }}</td><td class="text-end">{{ $qty($transaction->jumlah) }} {{ $transaction->item?->satuan }}</td><td class="text-end">{{ $qty($transaction->stok_sebelum) }}</td><td class="text-end fw-bold">{{ $qty($transaction->stok_sesudah) }}</td><td>{{ $transaction->keterangan ?: '-' }}</td><td>{{ $transaction->actor?->nama ?: 'Akun dihapus' }}</td></tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div></div>
    </section>
    @if ($page === 'laporan'){{ $transactions->links() }}@endif
    @endif

    @if ($canManage && $page === 'barang_masuk')
        <div class="modal fade" id="externalReceiveModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-centered"><form method="POST" action="{{ route('persediaan.inventory.receive-external') }}" class="modal-content">@csrf
            <div class="modal-header"><h3 class="modal-title fs-4 fw-bold">Penerimaan Barang Non-Usulan</h3><button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"></i></button></div>
            <div class="modal-body"><div class="alert alert-light-info">Gunakan untuk hibah, transfer, retur, atau sumber barang lain di luar Usulan Belanja. Barang dan transaksi masuk akan tersimpan di database persediaan.</div>
                <label class="form-label required">Jenis pencatatan</label><select name="mode" id="externalReceiveMode" class="form-select mb-4"><option value="existing">Tambah stok barang terdaftar</option><option value="new">Daftarkan barang baru dan tambah stok</option></select>
                <div id="existingReceiveFields" class="mb-4"><label class="form-label required">Pilih barang terdaftar</label><input type="search" id="manualReceiveItemSearch" class="form-control" placeholder="Cari kode atau nama barang"><input type="hidden" name="persediaan_item_id" id="manualReceiveItemId"><div id="manualReceiveItemResults" class="list-group mt-2"></div><div id="manualReceiveItemSelected" class="text-primary fw-semibold mt-2"></div></div>
                <div id="newReceiveFields" class="d-none"><div class="row g-3"><div class="col-md-6"><label class="form-label required">Nama barang</label><input name="nama_barang" class="form-control" maxlength="255"></div><div class="col-md-6"><label class="form-label">Kode barang (opsional)</label><input name="kode_barang" class="form-control" maxlength="20" placeholder="Kosongkan untuk kode otomatis"></div><div class="col-md-6"><label class="form-label required">Satuan</label><input name="satuan" class="form-control" maxlength="50" placeholder="unit, box, pcs, dll."></div><div class="col-md-6"><label class="form-label">Subkategori RKBU</label><select name="sub_kategori_rkbu_id" class="form-select"><option value="">Tidak dikategorikan</option><?php foreach ($subcategories as $subcategory): ?><option value="<?= e($subcategory->id_sub_kategori_rkbu) ?>"><?= e($subcategory->kode_sub_kategori_rkbu . ' - ' . $subcategory->nama_sub_kategori_rkbu) ?></option><?php endforeach; ?></select></div><div class="col-12"><label class="form-label">Spesifikasi</label><textarea name="spesifikasi" class="form-control" rows="2"></textarea></div><div class="col-md-6"><label class="form-label">Harga satuan</label><input type="number" name="harga_satuan" class="form-control" min="0" step="0.01"></div><div class="col-md-6"><label class="form-label">PPN (%)</label><input type="number" name="ppn" class="form-control" min="0" max="100" step="0.01" value="0"></div></div></div>
                <?php if ($isAdministrator): ?><label class="form-label required mt-4">Unit gudang</label><select name="unit_id" class="form-select mb-4" required><option value="">Pilih unit gudang</option><?php foreach ($units as $unit): ?><option value="<?= e($unit->id) ?>"><?= e($unit->nama_unit) ?></option><?php endforeach; ?></select><?php endif; ?>
                <div class="row g-3"><div class="col-md-4"><label class="form-label required">Jumlah diterima</label><input type="number" name="jumlah" class="form-control" min="0.001" step="0.001" required></div><div class="col-md-4"><label class="form-label required">Tanggal diterima</label><input type="date" name="tanggal" class="form-control" value="{{ now()->toDateString() }}" required></div><div class="col-md-12"><label class="form-label required">Sumber / keterangan penerimaan</label><textarea name="sumber_penerimaan" class="form-control" rows="2" placeholder="Contoh: Hibah dari ..., transfer dari ..., retur ..." required></textarea></div></div>
            </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Barang Masuk</button></div>
        </form></div></div>
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
        const externalReceiveMode = document.getElementById('externalReceiveMode');
        externalReceiveMode?.addEventListener('change', () => {
            const isNew = externalReceiveMode.value === 'new';
            document.getElementById('existingReceiveFields').classList.toggle('d-none', isNew);
            document.getElementById('newReceiveFields').classList.toggle('d-none', !isNew);
            document.getElementById('manualReceiveItemId').required = !isNew;
            document.querySelectorAll('#newReceiveFields [name="nama_barang"], #newReceiveFields [name="satuan"]').forEach((field) => field.required = isNew);
        });
        externalReceiveMode?.dispatchEvent(new Event('change'));
        let manualReceiveSearchTimer;
        document.getElementById('manualReceiveItemSearch')?.addEventListener('input', (event) => {
            clearTimeout(manualReceiveSearchTimer);
            const term = event.target.value.trim();
            const results = document.getElementById('manualReceiveItemResults');
            document.getElementById('manualReceiveItemId').value = '';
            document.getElementById('manualReceiveItemSelected').textContent = '';
            if (term.length < 2) { results.innerHTML = ''; return; }
            results.innerHTML = '<div class="list-group-item text-muted">Mencari stok barang...</div>';
            manualReceiveSearchTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`${@json(route('persediaan.inventory.request-items'))}?q=${encodeURIComponent(term)}`, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('search failed');
                    const items = await response.json();
                    results.innerHTML = '';
                    if (!items.length) { results.innerHTML = '<div class="list-group-item text-muted">Barang tidak ditemukan dalam cakupan stok Anda.</div>'; return; }
                    items.forEach((item) => {
                        const button = document.createElement('button'); button.type = 'button'; button.className = 'list-group-item list-group-item-action';
                        button.textContent = `${item.kode || ''} · ${item.nama} (stok ${item.stok} ${item.satuan || ''}, gudang ${item.unit || '-'})`;
                        button.addEventListener('click', () => {
                            document.getElementById('manualReceiveItemId').value = item.id;
                            document.getElementById('manualReceiveItemSelected').textContent = `Dipilih: ${item.nama} — stok ${item.stok} ${item.satuan || ''}`;
                            results.innerHTML = '';
                        });
                        results.appendChild(button);
                    });
                } catch (_) { results.innerHTML = '<div class="list-group-item text-danger">Pencarian gagal. Coba lagi.</div>'; }
            }, 250);
        });
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
        const requestSearch = document.getElementById('requestItemSearch');
        const requestResults = document.getElementById('requestItemResults');
        const selectedRequestItems = document.getElementById('selectedRequestItems');
        const selectedRequestMap = new Map();
        let requestSearchTimer;
        const initialCatalogMarkup = requestResults?.innerHTML || '';
        function addCatalogItem(item) {
            selectedRequestMap.set(item.id, item);
            renderSelectedRequestItems();
            updateCatalogButtons();
        }
        function updateCatalogButtons() {
            document.querySelectorAll('.js-add-catalog-item').forEach((button) => {
                const selected = selectedRequestMap.has(button.dataset.id);
                button.disabled = selected;
                button.textContent = selected ? 'Dipilih' : 'Tambah';
            });
        }
        function bindInitialCatalogButtons() {
            document.querySelectorAll('.js-add-catalog-item').forEach((button) => button.addEventListener('click', () => addCatalogItem({
                id: button.dataset.id, kode: button.dataset.code, nama: button.dataset.name,
                spesifikasi: button.dataset.specification, unit: button.dataset.unit,
                stok: Number(button.dataset.stock), satuan: button.dataset.satuan,
            })));
            updateCatalogButtons();
        }
        bindInitialCatalogButtons();
        requestSearch?.addEventListener('input', () => {
            clearTimeout(requestSearchTimer);
            const term = requestSearch.value.trim();
            if (term.length < 2) { requestResults.innerHTML = initialCatalogMarkup; bindInitialCatalogButtons(); return; }
            requestResults.innerHTML = '<div class="col-12"><div class="alert alert-light-info mb-0">Mencari katalog barang...</div></div>';
            requestSearchTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`${@json(route('persediaan.inventory.request-items'))}?q=${encodeURIComponent(term)}`, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('search failed');
                    const items = await response.json();
                    requestResults.innerHTML = '';
                    if (!items.length) { requestResults.innerHTML = '<div class="col-12"><div class="alert alert-light mb-0">Barang tidak ditemukan. Coba kata kunci lain.</div></div>'; return; }
                    items.forEach((item) => {
                        const card = document.createElement('div'); card.className = 'col-sm-6 col-xl-4';
                        card.innerHTML = `<div class="card h-100 border shadow-sm"><div class="card-body d-flex flex-column"><div class="d-flex justify-content-between align-items-start gap-2 mb-3"><span class="badge badge-light-primary"></span><i class="fas fa-box-open text-primary fs-2"></i></div><h3 class="fs-6 fw-bold mb-1"></h3><div class="text-muted fs-7 mb-3"></div><div class="mt-auto d-flex justify-content-between align-items-center"><div class="small"><span class="text-muted">Stok</span><strong class="d-block"></strong></div><button type="button" class="btn btn-sm btn-primary">Tambah</button></div></div></div>`;
                        card.querySelector('.badge').textContent = item.kode || 'Barang';
                        card.querySelector('h3').textContent = item.nama;
                        card.querySelector('.text-muted.fs-7').textContent = item.spesifikasi || item.subkategori || 'Persediaan';
                        card.querySelector('strong').textContent = `${item.stok} ${item.satuan || ''}`;
                        const addButton = card.querySelector('button');
                        addButton.classList.add('js-add-catalog-item'); addButton.dataset.id = item.id;
                        addButton.disabled = selectedRequestMap.has(item.id);
                        addButton.textContent = addButton.disabled ? 'Dipilih' : 'Tambah';
                        addButton.addEventListener('click', () => {
                            addCatalogItem(item);
                        });
                        requestResults.appendChild(card);
                    });
                } catch (_) { requestResults.innerHTML = '<div class="col-12"><div class="alert alert-danger mb-0">Pencarian gagal. Coba lagi.</div></div>'; }
            }, 250);
        });
        function renderSelectedRequestItems() {
            selectedRequestItems.innerHTML = '';
            document.getElementById('selectedRequestCount').textContent = `(${selectedRequestMap.size})`;
            if (!selectedRequestMap.size) { selectedRequestItems.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada barang dipilih.</td></tr>'; return; }
            [...selectedRequestMap.values()].forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `<td><div class="fw-semibold"></div><div class="text-muted fs-7"></div></td><td></td><td></td><td><input type="number" class="form-control form-control-sm" name="items[${index}][jumlah]" min="0.001" step="0.001" required></td><td><button type="button" class="btn btn-sm btn-light-danger" aria-label="Hapus barang">\u00D7</button></td>`;
                row.querySelector('td .fw-semibold').textContent = `${item.kode || ''} \u00B7 ${item.nama}`;
                row.querySelector('td .text-muted').textContent = item.spesifikasi || '-';
                row.children[1].textContent = item.unit || '-';
                row.children[2].textContent = `${item.stok} ${item.satuan || ''}`;
                const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = `items[${index}][persediaan_item_id]`; hidden.value = item.id; row.children[0].appendChild(hidden);
                row.querySelector('button').addEventListener('click', () => { selectedRequestMap.delete(item.id); renderSelectedRequestItems(); updateCatalogButtons(); if (requestSearch.value.trim().length >= 2) requestSearch.dispatchEvent(new Event('input')); });
                selectedRequestItems.appendChild(row);
            });
        }
        document.getElementById('multiRequestForm')?.addEventListener('submit', (event) => {
            if (!selectedRequestMap.size) { event.preventDefault(); alert('Pilih minimal satu barang.'); }
        });
    </script>
@endsection
