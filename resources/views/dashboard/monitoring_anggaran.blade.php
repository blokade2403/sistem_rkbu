@extends('layouts.main')

@section('content')
    @php
        $rupiah = fn ($amount) => 'Rp ' . number_format((float) $amount, 0, ',', '.');
        $pageTitle = $source === 'BLUD' ? 'Monitoring Realisasi Anggaran BLUD' : 'Monitoring Keseluruhan Anggaran';
        $displayRows = $source === 'BLUD' ? $ledgerRows : $programRows;
    @endphp

    <div class="monitoring-hero d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
        <div class="monitoring-hero-copy">
            <span class="monitoring-eyebrow">DASHBOARD ANGGARAN</span>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $pageTitle }}</h1>
            <div class="text-muted">Tahun anggaran <strong class="text-gray-700">{{ $year->nama_tahun_anggaran ?? 'belum dipilih' }}</strong></div>
        </div>
        <form method="GET" class="monitoring-year-form d-flex align-items-center gap-3">
            <label for="tahun_anggaran_id" class="text-muted fw-semibold text-nowrap mb-0">Tahun anggaran</label>
            <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select" onchange="this.form.submit()">
                @foreach ($years as $option)
                    <option value="{{ $option->id }}" @selected($option->id === $yearId)>{{ $option->nama_tahun_anggaran }}{{ $option->status === 'aktif' ? ' · Aktif' : '' }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if (! $year)
        <div class="alert alert-info">Data tahun anggaran belum tersedia.</div>
    @else
        @if ($source !== 'BLUD')
            <div class="row g-5 mb-6">
                @foreach ([['label' => 'Anggaran Pegawai', 'value' => $typeBudgets['pegawai'], 'color' => 'primary', 'icon' => 'fa-briefcase'], ['label' => 'Anggaran Barang dan Jasa', 'value' => $typeBudgets['barjas'], 'color' => 'warning', 'icon' => 'fa-boxes'], ['label' => 'Anggaran Modal', 'value' => $typeBudgets['modal'], 'color' => 'info', 'icon' => 'fa-clock']] as $card)
                    <div class="col-xl-4 col-md-6">
                        <div class="card monitoring-stat-card h-100 border-0 shadow-sm"><div class="card-body d-flex align-items-center gap-4">
                            <span class="dashboard-icon bg-light-{{ $card['color'] }} text-{{ $card['color'] }}"><i class="fas {{ $card['icon'] }}"></i></span>
                            <div class="min-w-0"><div class="text-muted fw-semibold mb-2">{{ $card['label'] }}</div><div class="monitoring-stat-value fs-2 fw-bold text-gray-800">{{ $rupiah($card['value']) }}</div></div>
                        </div></div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row g-5 mb-6">
                @foreach ([['label' => 'Anggaran BLUD', 'value' => $summary['anggaran'], 'color' => 'primary'], ['label' => 'Proses Pengadaan', 'value' => $summary['pengadaan'], 'color' => 'info'], ['label' => 'SPJ di Keuangan', 'value' => $summary['keuangan'], 'color' => 'warning'], ['label' => 'Realisasi', 'value' => $summary['realisasi'], 'color' => 'success'], ['label' => 'Sisa Anggaran', 'value' => $summary['sisa'], 'color' => 'danger']] as $card)
                    <div class="col-xl col-md-4 col-sm-6"><div class="card monitoring-stat-card h-100 border-0 shadow-sm"><div class="card-body p-5"><div class="text-muted fw-semibold mb-2">{{ $card['label'] }}</div><div class="monitoring-stat-value fs-3 fw-bold text-{{ $card['color'] }}">{{ $rupiah($card['value']) }}</div>@if ($card['label'] === 'Realisasi')<div class="text-muted fs-8 mt-2">{{ $summary['persentase'] }}% dari pagu BLUD</div>@endif</div></div></div>
                @endforeach
            </div>
        @endif

        <div class="card monitoring-report-card border-0 shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title d-flex flex-column">
                    <h2 class="fw-bold text-gray-900 mb-1">{{ $source === 'BLUD' ? 'Rincian Subkategori RKBU BLUD' : 'Program, Kegiatan, dan Rekening Belanja' }}</h2>
                    <span class="text-muted fs-7">{{ $source === 'BLUD' ? 'Pagu pada rekening utama; rincian anggaran mengikuti RKBU disetujui per jenis, kategori, dan subkategori. Proses dan realisasi mengikuti usulan disetujui.' : 'Anggaran dari pagu rekening; proses dan realisasi berdasarkan usulan yang disetujui' }}</span>
                </div>
                <div class="card-toolbar"><span class="monitoring-realization-pill"><span class="monitoring-live-dot"></span>Realisasi {{ number_format($summary['persentase'], 2, ',', '.') }}%</span></div>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle gy-2 monitoring-table">
                        <thead><tr>
                            <th class="text-white">#</th>
                            <th class="text-white">{{ $source === 'BLUD' ? 'Kode Rek. Belanja / Kegiatan' : 'Uraian / Kode Rekening Belanja' }}</th>
                            <th class="text-end text-white">Anggaran</th>
                            @if ($source === 'BLUD')
                                <th class="text-end text-white">Proses Pengadaan</th><th class="text-end text-white">SPJ di Keuangan</th>
                            @else
                                <th class="text-end text-white">Proses Bendahara</th>
                            @endif
                            <th class="text-end text-white">Realisasi</th>
                            @if ($source === 'BLUD')<th class="text-end text-white">Sisa</th>@endif
                            <th class="text-end text-white">%</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($displayRows as $index => $row)
                                <tr class="{{ $row['level'] < 2 ? 'monitoring-parent' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><div class="monitoring-label" style="--row-level: {{ min($row['level'], 8) }}">@if ($source === 'BLUD')<span class="monitoring-code">{{ $row['kode'] }}</span><span class="monitoring-separator">·</span>{{ $row['nama'] }}@else{{ $row['label'] }}@endif</div></td>
                                    <td class="text-end">{{ number_format($row['anggaran'], 0, ',', '.') }}</td>
                                    @if ($source === 'BLUD')
                                        <td class="text-end">{{ number_format($row['pengadaan'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($row['keuangan'], 0, ',', '.') }}</td>
                                    @else
                                        <td class="text-end">{{ number_format($row['pengadaan'] + $row['keuangan'], 0, ',', '.') }}</td>
                                    @endif
                                    <td class="text-end">{{ number_format($row['realisasi'], 0, ',', '.') }}</td>
                                    @if ($source === 'BLUD')<td class="text-end">{{ number_format($row['sisa'], 0, ',', '.') }}</td>@endif
                                    <td class="text-end">{{ number_format($row['persentase'], 2, ',', '.') }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-8">Belum ada pagu atau transaksi anggaran untuk filter ini.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot><tr class="monitoring-total"><th colspan="2">TOTAL</th><th class="text-end">{{ number_format($summary['anggaran'], 0, ',', '.') }}</th>
                            @if ($source === 'BLUD')<th class="text-end">{{ number_format($summary['pengadaan'], 0, ',', '.') }}</th><th class="text-end">{{ number_format($summary['keuangan'], 0, ',', '.') }}</th>@else<th class="text-end">{{ number_format($summary['pengadaan'] + $summary['keuangan'], 0, ',', '.') }}</th>@endif
                            <th class="text-end">{{ number_format($summary['realisasi'], 0, ',', '.') }}</th>
                            @if ($source === 'BLUD')<th class="text-end">{{ number_format($summary['sisa'], 0, ',', '.') }}</th>@endif
                            <th class="text-end">{{ number_format($summary['persentase'], 2, ',', '.') }}%</th>
                        </tr></tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <style>
        .monitoring-hero { padding: 1.5rem 1.75rem; border: 1px solid #e5edf7; border-radius: 16px; background: radial-gradient(ellipse at top right, rgba(59,130,246,.12), transparent 42%), linear-gradient(120deg,#fff 0%,#f7faff 100%); }
        .monitoring-eyebrow { display: inline-block; margin-bottom: .45rem; color: #4683c5; font-size: .68rem; font-weight: 800; letter-spacing: .12em; }
        .monitoring-year-form { padding: .55rem .65rem .55rem .9rem; border: 1px solid #e3eaf3; border-radius: 11px; background: rgba(255,255,255,.9); }
        .monitoring-year-form .form-select { width: auto; min-width: 150px; border-color: #e7edf5; font-weight: 600; }
        .dashboard-icon { width: 50px; height: 50px; display: inline-flex; align-items: center; justify-content: center; border-radius: 14px; font-size: 19px; flex: 0 0 auto; }
        .monitoring-stat-card { overflow: hidden; border-radius: 14px; transition: transform .18s ease, box-shadow .18s ease; }
        .monitoring-stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(28,55,90,.10)!important; }
        .monitoring-stat-value { overflow-wrap: anywhere; }
        .monitoring-report-card { overflow: hidden; border-radius: 16px; }
        .monitoring-report-card .card-header { gap: 1rem; }
        .monitoring-realization-pill { display: inline-flex; align-items: center; gap: .5rem; padding: .55rem .8rem; border: 1px solid #d8eee4; border-radius: 999px; background: #f1faf5; color: #24835a; font-size: .8rem; font-weight: 700; white-space: nowrap; }
        .monitoring-live-dot { width: 8px; height: 8px; border-radius: 50%; background: #38b77b; box-shadow: 0 0 0 3px rgba(56,183,123,.13); }
        .monitoring-table { min-width: 780px; }
        .monitoring-table thead tr { background: linear-gradient(100deg,#236bb3,#278bd4); }
        .monitoring-table thead th { padding-top: .9rem; padding-bottom: .9rem; white-space: nowrap; font-size: .75rem; letter-spacing: .025em; }
        .monitoring-table td, .monitoring-table th { border-color: #e9eef5; }
        .monitoring-table tr > :first-child { padding-left: 1rem; }
        .monitoring-table tr > :last-child { padding-right: 1rem; }
        .monitoring-table tbody tr:nth-child(even) { background: #fbfcfe; }
        .monitoring-row { transition: background-color .15s ease; }
        .monitoring-row:hover { background: #f1f7fd!important; }
        .monitoring-label { padding-left: calc(var(--row-level) * 18px); min-width: 240px; }
        .monitoring-code { color: #6b7d93; font-size: .82em; font-weight: 600; }
        .monitoring-separator { margin: 0 .45rem; color: #a8b5c5; }
        .monitoring-parent { background: #eef6ff!important; color: #15549a; font-weight: 700; }
        .monitoring-total { background: #e9f2fc; color: #15549a; font-weight: 800; }
        @media (max-width: 575.98px) {
            .monitoring-hero { padding: 1.1rem; }
            .monitoring-year-form { width: 100%; align-items: stretch!important; flex-direction: column; gap: .5rem!important; }
            .monitoring-year-form .form-select { width: 100%; }
            .monitoring-report-card .card-header { align-items: flex-start; flex-direction: column; }
            .monitoring-report-card .card-toolbar { width: 100%; }
        }
    </style>
@endsection
