@extends('layouts.main')

@section('content')
    @php($rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.'))

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Modul Aset Barang</h1>
            <div class="text-muted">Registrasikan barang modal yang sudah diterima dan dibayar, dengan jejak ke RKBU dan SPJ.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('asset-module.manual-create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Input Aset Hibah / Manual</a>
            <a href="{{ route('module-portal') }}" class="btn btn-light-primary"><i class="fas fa-layer-group me-2"></i>Beranda Modul</a>
        </div>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

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
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7">
                        <th>No. Register</th><th>Nama Aset</th><th>Unit</th><th>Tahun</th><th>Kondisi</th><th>Pengguna / Lokasi</th><th>Sumber RKBU</th><th class="text-end">Nilai Perolehan</th><th></th>
                    </tr></thead>
                    <tbody>
                        @forelse ($assets as $asset)
                            <tr>
                                <td class="fw-bold">{{ $asset->no_register ?: '-' }}</td>
                                <td><div class="fw-bold text-gray-900">{{ $asset->nama_asset }}</div><div class="text-muted fs-7">{{ $asset->merk }} {{ $asset->type }}</div></td>
                                <td>{{ $asset->unit?->nama_unit ?: '-' }}</td>
                                <td>{{ $asset->tahun_perolehan ?: '-' }}</td>
                                <td><span class="badge badge-light-info">{{ $asset->kondisi_asset }}</span></td>
                                <td>{{ $asset->pengguna_asset ?: $asset->lokasi_asset ?: '-' }}</td>
                                <td>{{ $asset->rkbu?->nomor_rkbu ?: ($asset->hibah ? 'Hibah' : 'Aset awal') }}</td>
                                <td class="text-end">{{ $rupiah($asset->total_anggaran_asset ?: $asset->harga_asset) }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('asset-module.show', $asset) }}" class="btn btn-sm btn-light-info">Detail</a>
                                    <a href="{{ route('asset-module.edit', $asset) }}" class="btn btn-sm btn-light-primary">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-8">Belum ada aset yang diregistrasikan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $assets->links() }}
        </div>
    </section>
@endsection
