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
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><div><h2 class="fw-bold text-gray-900 mb-1">Serapan Anggaran</h2><span class="text-muted fs-7">Realisasi berdasarkan tanggal pembayaran SPJ</span></div></div>
                <div class="card-toolbar fw-bold text-gray-700">DPA Perubahan: Rp {{ $rupiah($totalDpa) }}</div>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive monthly-report-wrap">
                    <table class="table table-bordered align-middle monthly-report-table">
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
        .monthly-report-wrap { overflow: auto; max-height: calc(100vh - 240px); }
        .monthly-report-table { min-width: 1900px; border-color: #555; font-size: 13px; }
        .monthly-report-table th, .monthly-report-table td { padding: 7px 9px; border-color: #666; }
        .monthly-report-table thead th { position: sticky; top: 0; z-index: 5; background: #fff; white-space: nowrap; }
        .monthly-report-table thead tr:nth-child(2) th { top: 37px; z-index: 6; }
        .monthly-report-table .monthly-sticky-program { min-width: 175px; }
        .monthly-report-table .monthly-sticky-activity { min-width: 340px; }
        .monthly-report-table td:nth-child(n+3), .monthly-report-table th:nth-child(n+3) { min-width: 112px; }
        .monthly-report-table .monthly-program-row { background: #f5cbaa; font-weight: 700; }
        .monthly-report-table .monthly-activity-row { background: #e7f2fa; font-weight: 700; }
        .monthly-report-table .monthly-total-row { background: #f5cbaa; font-weight: 700; }
        .monthly-report-table .monthly-sticky-program, .monthly-report-table .monthly-sticky-activity { background-clip: padding-box; }
    </style>
@endsection
