@extends('layouts.main')

@section('content')
    @php
        $rupiah = fn ($amount) => number_format((float) $amount, 0, ',', '.');
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Laporan Realisasi Anggaran Bulanan</h1>
            <div class="text-muted">DPA Perubahan dan serapan Januari sampai Desember {{ $year->nama_tahun_anggaran ?? '' }}</div>
        </div>
        <form method="GET" class="d-flex align-items-center gap-3">
            <label for="tahun_anggaran_id" class="text-muted fw-semibold text-nowrap">Tahun anggaran</label>
            <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach ($years as $option)
                    <option value="{{ $option->id }}" @selected($option->id === $yearId)>{{ $option->nama_tahun_anggaran }}{{ $option->status === 'aktif' ? ' · Aktif' : '' }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if (! $year)
        <div class="alert alert-info">Data tahun anggaran belum tersedia.</div>
    @else
        <div class="card border-0 shadow-sm monthly-report-card">
            <div class="card-header border-0 pt-6 pb-4">
                <div class="card-title mb-0"><div><h2 class="fw-bold text-gray-900 mb-1">Serapan Anggaran</h2><span class="text-muted fs-7">Realisasi berdasarkan tanggal pembayaran SPJ</span></div></div>
                <div class="card-toolbar"><div class="monthly-dpa-summary"><span class="text-muted fs-8 fw-semibold text-uppercase">DPA Perubahan</span><strong>Rp {{ $rupiah($totalDpa) }}</strong></div></div>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive monthly-report-wrap">
                    <table class="table align-middle monthly-report-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="monthly-sticky-program">PROGRAM</th>
                                <th rowspan="2" class="monthly-sticky-activity">KEGIATAN</th>
                                <th rowspan="2" class="text-end">DPA PERUBAHAN</th>
                                <th colspan="12" class="text-center">SERAPAN</th>
                            </tr>
                            <tr>
                                @foreach ($months as $month)<th class="text-end">{{ $month }}</th>@endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="monthly-total-row">
                                <td colspan="2">TOTAL ANGGARAN</td>
                                <td class="text-end">{{ $rupiah($totalDpa) }}</td>
                                @foreach (range(1, 12) as $month)<td class="text-end">{{ $monthTotals[$month] > 0 ? $rupiah($monthTotals[$month]) : '–' }}</td>@endforeach
                            </tr>
                            @forelse ($reportRows as $row)
                                <tr class="{{ $row['level'] === 0 ? 'monthly-program-row' : ($row['level'] === 1 ? 'monthly-activity-row' : '') }}">
                                    <td class="monthly-sticky-program">@if ($row['level'] === 0){{ $row['label'] }}@endif</td>
                                    <td class="monthly-sticky-activity"><div style="padding-left: {{ max(0, $row['level'] - 1) * 18 }}px">@if ($row['level'] > 0){{ $row['label'] }}@endif</div></td>
                                    <td class="text-end">{{ $rupiah($row['dpa']) }}</td>
                                    @foreach (range(1, 12) as $month)<td class="text-end">{{ $row['serapan'][$month] > 0 ? $rupiah($row['serapan'][$month]) : '–' }}</td>@endforeach
                                </tr>
                            @empty
                                <tr><td colspan="15" class="text-center text-muted py-8">Belum ada data DPA atau realisasi untuk tahun anggaran ini.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="monthly-total-row"><th colspan="2">TOTAL SERAPAN</th><th class="text-end">{{ $rupiah($totalDpa) }}</th>@foreach (range(1, 12) as $month)<th class="text-end">{{ $monthTotals[$month] > 0 ? $rupiah($monthTotals[$month]) : '–' }}</th>@endforeach</tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <style>
        .monthly-report-card { border-radius: 12px; }
        .monthly-dpa-summary { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; padding: 10px 16px; border: 1px solid #dbe5f0; border-radius: 10px; background: #f5f8fc; }
        .monthly-dpa-summary strong { color: #1e3a5f; font-size: 1.05rem; }
        .monthly-report-wrap { overflow: auto; max-height: calc(100vh - 255px); border: 1px solid #dce3ec; border-radius: 9px; scrollbar-color: #aab8c8 #f1f4f8; scrollbar-width: thin; }
        .monthly-report-table { min-width: 1900px; margin-bottom: 0; border-collapse: separate; border-spacing: 0; font-size: 12px; color: #344054; }
        .monthly-report-table th, .monthly-report-table td { padding: 9px 11px; border-right: 1px solid #e5eaf0; border-bottom: 1px solid #e5eaf0; }
        .monthly-report-table thead th { position: sticky; top: 0; z-index: 5; background: #edf3f9; color: #34445a; font-size: 11px; font-weight: 750; letter-spacing: .035em; white-space: nowrap; }
        .monthly-report-table thead tr:first-child th { height: 39px; }
        .monthly-report-table thead tr:first-child th[colspan] { background: #dce9f5; color: #24476c; text-align: center; }
        .monthly-report-table thead tr:nth-child(2) th { top: 39px; z-index: 6; background: #edf3f9; }
        .monthly-report-table .monthly-sticky-program { position: sticky; left: 0; z-index: 3; width: 220px; min-width: 220px; max-width: 220px; }
        .monthly-report-table .monthly-sticky-activity { position: sticky; left: 220px; z-index: 3; width: 360px; min-width: 360px; max-width: 360px; box-shadow: 5px 0 8px -8px #344054; }
        .monthly-report-table thead .monthly-sticky-program, .monthly-report-table thead .monthly-sticky-activity { z-index: 8; background: #edf3f9; }
        .monthly-report-table td:nth-child(n+3), .monthly-report-table th:nth-child(n+3) { min-width: 112px; white-space: nowrap; }
        .monthly-report-table tbody tr:nth-child(even):not(.monthly-program-row):not(.monthly-activity-row):not(.monthly-total-row) { background: #f9fbfd; }
        .monthly-report-table tbody tr:not(.monthly-program-row):not(.monthly-activity-row):not(.monthly-total-row) .monthly-sticky-program,
        .monthly-report-table tbody tr:not(.monthly-program-row):not(.monthly-activity-row):not(.monthly-total-row) .monthly-sticky-activity { background: #fff; }
        .monthly-report-table tbody tr:nth-child(even):not(.monthly-program-row):not(.monthly-activity-row):not(.monthly-total-row) .monthly-sticky-program,
        .monthly-report-table tbody tr:nth-child(even):not(.monthly-program-row):not(.monthly-activity-row):not(.monthly-total-row) .monthly-sticky-activity { background: #f9fbfd; }
        .monthly-report-table tbody tr:hover:not(.monthly-total-row) { background: #f0f6fc; }
        .monthly-report-table tbody tr:hover:not(.monthly-total-row) .monthly-sticky-program,
        .monthly-report-table tbody tr:hover:not(.monthly-total-row) .monthly-sticky-activity { background: #f0f6fc; }
        .monthly-report-table .monthly-program-row { background: #e8eff8; color: #1f3b5b; font-weight: 750; }
        .monthly-report-table .monthly-activity-row { background: #f1f6fb; color: #344b64; font-weight: 650; }
        .monthly-report-table .monthly-total-row { position: sticky; z-index: 4; background: #e3edf7; color: #1f3b5b; font-weight: 750; }
        .monthly-report-table .monthly-total-row td:first-child, .monthly-report-table .monthly-total-row th:first-child { position: sticky; left: 0; z-index: 5; background: #e3edf7; }
        .monthly-report-table .monthly-total-row td:nth-child(2), .monthly-report-table .monthly-total-row th:nth-child(2) { position: sticky; left: 220px; z-index: 5; background: #e3edf7; }
        .monthly-report-table tfoot .monthly-total-row { bottom: 0; }
        .monthly-report-table tfoot .monthly-total-row th:first-child { z-index: 7; }
        .monthly-report-table .text-end { font-variant-numeric: tabular-nums; }
        @media (max-width: 767.98px) {
            .monthly-report-wrap { max-height: calc(100vh - 290px); }
            .monthly-report-table .monthly-sticky-program { width: 170px; min-width: 170px; max-width: 170px; }
            .monthly-report-table .monthly-sticky-activity { left: 170px; width: 260px; min-width: 260px; max-width: 260px; }
            .monthly-report-table .monthly-total-row td:nth-child(2), .monthly-report-table .monthly-total-row th:nth-child(2) { left: 170px; }
            .monthly-dpa-summary { align-items: flex-start; }
        }
    </style>
@endsection
