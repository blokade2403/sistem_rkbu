@extends('layouts.main')

@section('content')
    <div class="asset-detail-page">
        <div class="asset-detail-hero d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
            <div class="d-flex align-items-center gap-3">
                <span class="asset-detail-icon"><i class="fas fa-cube"></i></span>
                <div>
                    <div class="text-muted fs-7">Detail aset</div>
                    <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $asset->nama_asset }}</h1>
                    <div class="text-muted">Nomor register <strong>{{ $asset->no_register ?: '-' }}</strong></div>
                </div>
            </div>
            <div class="d-flex gap-2 no-print">
                <a href="{{ route('asset-module.index') }}" class="btn btn-light">Kembali</a>
                <a href="{{ route('asset-module.edit', $asset) }}" class="btn btn-primary"><i class="fas fa-pen me-2"></i>Ubah Data</a>
            </div>
        </div>

        @if (session('success')) <div class="alert alert-success no-print">{{ session('success') }}</div> @endif

        <div class="row g-5">
            <div class="col-xl-8">
                <section class="card asset-detail-card border-0 shadow-sm">
                    <div class="card-header border-0 pt-6">
                        <div><h2 class="card-title fs-3 fw-bold">Informasi Aset</h2><div class="text-muted fs-7">Identitas, nilai perolehan, dan penanggung jawab.</div></div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle mb-0">
                                @foreach ([
                                    ['Kode Aset', $asset->kode_asset],
                                    ['Merk / Tipe', trim(($asset->merk ?? '') . ' ' . ($asset->type ?? ''))],
                                    ['Spesifikasi', $asset->spek],
                                    ['Nomor Seri', $asset->serial_number],
                                    ['Kondisi', $asset->kondisi_asset],
                                    ['Tahun Perolehan', $asset->tahun_perolehan],
                                    ['Tanggal Perolehan', $asset->tgl_asset?->format('d-m-Y')],
                                    ['Nilai Perolehan', 'Rp ' . number_format((float) $asset->total_anggaran_asset, 0, ',', '.')],
                                    ['Jumlah', $asset->jumlah_asset . ' ' . $asset->satuan],
                                    ['Unit', $asset->unit?->nama_unit],
                                    ['Pengguna', $asset->pengguna_asset],
                                    ['NIP', $asset->nip_pengguna],
                                    ['Jabatan', $asset->jabatan_pengguna],
                                    ['Lokasi', $asset->lokasi_asset],
                                    ['Penanggung Jawab', $asset->penanggung_jawab],
                                ] as [$label, $value])
                                    <tr><th class="text-muted asset-detail-label">{{ $label }}</th><td>{{ $value ?: '-' }}</td></tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </section>

                <section class="card asset-detail-card border-0 shadow-sm mt-5">
                    <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Jejak Perolehan</h2><div class="text-muted fs-7">Sumber aset yang dapat ditelusuri kembali.</div></div></div>
                    <div class="card-body row g-4 pt-2">
                        <div class="col-md-6"><div class="asset-origin-field"><span>Nomor RKBU</span><strong>{{ $asset->rkbu?->nomor_rkbu ?: ($asset->hibah ? 'Hibah / manual' : 'Aset awal / manual') }}</strong></div></div>
                        <div class="col-md-6"><div class="asset-origin-field"><span>Nomor SPJ</span><strong>{{ $asset->spj?->no_usulan_belanja ?: '-' }}</strong></div></div>
                        <div class="col-md-6"><div class="asset-origin-field"><span>Sumber Anggaran</span><strong>{{ $asset->sumber_anggaran ?: '-' }}</strong></div></div>
                        @if ($asset->hibah)<div class="col-md-6"><div class="asset-origin-field"><span>Asal Hibah / Berita Acara</span><strong>{{ $asset->hibah }}</strong></div></div>@endif
                        <div class="col-md-6"><div class="asset-origin-field"><span>Status Registrasi</span><strong><span class="badge badge-light-success">{{ ucfirst($asset->status_registrasi) }}</span></strong></div></div>
                    </div>
                </section>
            </div>

            <div class="col-xl-4 no-print">
                <section class="card asset-detail-card asset-qr-card border-0 shadow-sm">
                    <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Label QR Aset</h2><div class="text-muted fs-7">Pindai untuk membuka halaman detail aset.</div></div></div>
                    <div class="card-body text-center">
                        <div id="asset-qr-print-area" class="asset-qr-label mx-auto">
                            <img src="{{ route('asset.qr', $asset->id_asset) }}" alt="QR {{ $asset->nama_asset }}" class="asset-qr-image">
                            <div class="asset-qr-register">{{ $asset->no_register ?: $asset->kode_asset ?: 'ASET' }}</div>
                            <div class="asset-qr-name">{{ $asset->nama_asset }}</div>
                            <div class="asset-qr-unit">{{ $asset->unit?->nama_unit ?: '-' }}</div>
                        </div>
                        <button type="button" class="btn btn-primary w-100 mt-5" onclick="window.print()"><i class="fas fa-print me-2"></i>Cetak Label QR</button>
                        <div class="text-muted fs-7 mt-3">Scan QR membuka ringkasan aset tanpa perlu login.</div>
                    </div>
                </section>
            </div>
        </div>

        <section class="card asset-detail-card border-0 shadow-sm mt-5">
            <div class="card-header border-0 pt-6"><div><h2 class="card-title fs-3 fw-bold">Riwayat Aset</h2><div class="text-muted fs-7">Pencatatan aset, perubahan data, dan usulan penghapusan. Riwayat disimpan terpisah agar tetap tersedia walau record aset dihapus.</div></div></div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle gy-3 mb-0">
                        <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Waktu</th><th>Aktivitas</th><th>Perubahan</th><th>Keterangan</th><th>Petugas</th></tr></thead>
                        <tbody>
                            @forelse ($histories as $history)
                                <tr>
                                    <td class="text-nowrap">{{ $history->created_at?->format('d-m-Y H:i') }}</td>
                                    <td>{{ match ($history->action) { 'created' => 'Pencatatan aset', 'updated' => 'Perubahan data', 'deletion_proposed' => 'Usulan penghapusan / reklas', 'deleted' => 'Aset dihapus', default => ucfirst(str_replace('_', ' ', $history->action)) } }}</td>
                                    <td>
                                        @forelse ($history->changes as [$field, $oldValue, $newValue])
                                            <div><strong>{{ $field }}:</strong> {{ $oldValue }} → {{ $newValue }}</div>
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                    <td>{{ $history->description ?: '-' }}</td>
                                    <td>{{ $history->user?->nama ?: 'Akun dihapus' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-6">Belum ada riwayat aset.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <style>
        .asset-detail-page { max-width: 1440px; margin: 0 auto; }
        .asset-detail-hero { padding: 1.4rem 1.6rem; border: 1px solid #e7edf5; border-radius: 16px; background: linear-gradient(115deg,#eef5ff,#fff 75%); }
        .asset-detail-icon { display: inline-grid; width: 50px; height: 50px; place-items: center; flex: 0 0 50px; border-radius: 15px; background: #fff; color: #3699ff; box-shadow: 0 5px 18px rgba(40,90,140,.08); font-size: 1.25rem; }
        .asset-detail-card { overflow: hidden; border: 1px solid #e9edf4 !important; border-radius: 14px; box-shadow: 0 4px 18px rgba(31,45,61,.04) !important; }
        .asset-detail-card > .card-header { border-bottom: 1px solid #edf0f5; background: #fbfcfe; }
        .asset-detail-label { width: 220px; }
        .asset-origin-field { display: flex; height: 100%; min-height: 72px; flex-direction: column; gap: .4rem; padding: 1rem; border: 1px solid #edf0f5; border-radius: 10px; background: #fff; }
        .asset-origin-field > span { color: #8a94a6; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
        .asset-origin-field > strong { color: #30394b; overflow-wrap: anywhere; }
        .asset-qr-label { display: flex; width: min(100%, 280px); flex-direction: column; align-items: center; padding: 1.1rem; border: 1px dashed #cbd5e1; border-radius: 12px; background: #fff; }
        .asset-qr-image { display: block; width: 210px; max-width: 100%; height: auto; image-rendering: pixelated; }
        .asset-qr-register { margin-top: .65rem; color: #152238; font-size: .9rem; font-weight: 800; letter-spacing: .04em; }
        .asset-qr-name { margin-top: .25rem; color: #344054; font-size: .82rem; font-weight: 650; }
        .asset-qr-unit { margin-top: .2rem; color: #8a94a6; font-size: .72rem; }
        @media(max-width:767.98px) { .asset-detail-hero { padding: 1rem; } .asset-detail-label { width: 145px; } }
        @media print {
            @page { size: 80mm 65mm; margin: 3mm; }
            body * { visibility: hidden !important; }
            #asset-qr-print-area, #asset-qr-print-area * { visibility: visible !important; }
            #asset-qr-print-area { position: fixed; inset: 0 auto auto 0; width: 74mm; min-height: 59mm; padding: 2mm; border: 0; border-radius: 0; }
            .asset-qr-image { width: 42mm; }
            .asset-qr-register { font-size: 10pt; }
            .asset-qr-name { font-size: 8pt; text-align: center; }
            .asset-qr-unit { font-size: 7pt; }
        }
    </style>
@endsection
