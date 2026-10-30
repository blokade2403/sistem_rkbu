@extends('layouts.main')

@section('content')
    <div class="container-fluid py-4 revenue-page">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-6">
            <div>
                <div class="text-uppercase text-muted fs-8 fw-bold mb-2">Modul Pendapatan</div>
                <h1 class="fs-2 fw-bold text-gray-900 mb-1">Laporan Keuangan</h1>
                <div class="text-muted">Realisasi belanja dan capaian target pendapatan per rekening.</div>
            </div>
            <form method="GET" action="{{ route('pendapatan.index') }}" class="d-flex flex-wrap gap-3">
                <select name="tahun_anggaran_id" class="form-select" aria-label="Tahun anggaran" onchange="this.form.submit()">
                    @foreach ($years as $item)<option value="{{ $item->id }}" @selected($yearId === $item->id)>{{ $item->nama_tahun_anggaran }}</option>@endforeach
                </select>
                @if ($isAdmin)
                    <select name="unit_id" class="form-select" aria-label="Unit" onchange="this.form.submit()">
                        <option value="">Semua Unit</option>
                        @foreach ($units as $unit)<option value="{{ $unit->id }}" @selected($unitId === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach
                    </select>
                @endif
            </form>
        </div>

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger"><strong>Periksa isian:</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="row g-4 mb-6">
            @foreach ([['Realisasi Belanja', $summary['realisasi_belanja'], 'primary'], ['Target Pendapatan', $summary['target_pendapatan'], 'warning'], ['Realisasi Pendapatan', $summary['realisasi_pendapatan'], 'success']] as [$label, $amount, $color])
                <div class="col-md-4"><div class="card border-0 shadow-sm revenue-summary h-100"><div class="card-body p-5"><div class="text-muted fw-semibold mb-2">{{ $label }}</div><div class="fs-2 fw-bold text-{{ $color }}">Rp {{ number_format($amount, 0, ',', '.') }}</div><small class="text-muted">Tahun {{ $year?->nama_tahun_anggaran ?? '-' }}</small></div></div></div>
            @endforeach
        </div>

        <div class="row g-5 mb-6">
            <div class="col-lg-6">
                <form method="POST" action="{{ route('pendapatan.target.save') }}" class="card border-0 shadow-sm h-100">
                    @csrf
                    <input type="hidden" name="tahun_anggaran_id" value="{{ $yearId }}">
                    <div class="card-header border-0 pt-5"><div><h2 class="fs-4 fw-bold mb-1">Target Rekening Pendapatan</h2><div class="text-muted fs-7">Simpan atau perbarui target tahunan berdasarkan kode rekening.</div></div></div>
                    <div class="card-body pt-2">
                        @if ($isAdmin)
                            <label class="form-label fw-semibold">Unit</label>
                            <select name="unit_id" class="form-select mb-4" required><option value="">Pilih unit</option>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $unitId) === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select>
                        @else
                            <label class="form-label fw-semibold">Unit</label><div class="form-control bg-light mb-4">{{ auth()->user()->unit?->nama_unit ?? 'Unit belum diatur' }}</div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-5"><label class="form-label fw-semibold">Kode Rekening</label><input name="kode_rekening" value="{{ old('kode_rekening') }}" class="form-control" required maxlength="100" placeholder="Contoh: 4.1.01.01.001"></div>
                            <div class="col-md-7"><label class="form-label fw-semibold">Nama Rekening</label><input name="nama_rekening" value="{{ old('nama_rekening') }}" class="form-control" required maxlength="255" placeholder="Nama rekening pendapatan"></div>
                            <div class="col-md-7"><label class="form-label fw-semibold">Target Tahunan (Rp)</label><input type="number" name="target_tahunan" value="{{ old('target_tahunan', 0) }}" class="form-control" min="0" step="0.01" required></div>
                            <div class="col-md-5 d-flex align-items-end"><button class="btn btn-primary w-100" @disabled(! $yearId)><i class="fas fa-save me-2"></i>Simpan Target</button></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <form method="POST" action="{{ route('pendapatan.realisasi.save') }}" class="card border-0 shadow-sm h-100">
                    @csrf
                    <input type="hidden" name="tahun_anggaran_id" value="{{ $yearId }}">
                    @if ($isAdmin && $unitId)<input type="hidden" name="unit_id" value="{{ $unitId }}">@endif
                    <div class="card-header border-0 pt-5"><div><h2 class="fs-4 fw-bold mb-1">Input Realisasi Bulanan</h2><div class="text-muted fs-7">Input baru pada bulan yang sama akan memperbarui nilainya.</div></div></div>
                    <div class="card-body pt-2">
                        <label class="form-label fw-semibold">Rekening Pendapatan</label>
                        <select name="target_pendapatan_id" class="form-select mb-4" required><option value="">Pilih rekening</option>@foreach ($targetOptions as $target)<option value="{{ $target->id }}" @selected(old('target_pendapatan_id') === $target->id)>{{ $target->kode_rekening }} - {{ $target->nama_rekening }} ({{ $target->nama_unit }})</option>@endforeach</select>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label fw-semibold">Bulan</label><select name="bulan" class="form-select" required><option value="">Pilih bulan</option>@foreach ($months as $number => $month)<option value="{{ $number }}" @selected((string) old('bulan') === (string) $number)>{{ $month }}</option>@endforeach</select></div>
                            <div class="col-md-8"><label class="form-label fw-semibold">Realisasi (Rp)</label><input type="number" name="jumlah_realisasi" value="{{ old('jumlah_realisasi') }}" class="form-control" min="0" step="0.01" required></div>
                            <div class="col-12"><label class="form-label fw-semibold">Keterangan</label><input name="keterangan" value="{{ old('keterangan') }}" class="form-control" maxlength="2000" placeholder="Opsional"></div>
                            <div class="col-12 text-end"><button class="btn btn-success" @disabled($targetOptions->isEmpty())><i class="fas fa-plus me-2"></i>Simpan Realisasi</button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <section class="card border-0 shadow-sm mb-6">
            <div class="card-header border-0 pt-6"><div><h2 class="fs-4 fw-bold mb-1">Realisasi Belanja per Bulan</h2><div class="text-muted fs-7">Berdasarkan transaksi pembayaran usulan belanja yang disetujui.</div></div></div>
            <div class="table-responsive"><table class="table table-row-bordered align-middle gy-4 mb-0 revenue-table"><thead><tr><th>Bulan</th>@foreach ($months as $month)<th class="text-end">{{ $month }}</th>@endforeach<th class="text-end">Total</th></tr></thead><tbody><tr><td class="fw-bold">Realisasi Belanja</td>@foreach ($months as $number => $month)<td class="text-end">Rp {{ number_format($expenseMonthly[$number], 0, ',', '.') }}</td>@endforeach<td class="text-end fw-bold">Rp {{ number_format($summary['realisasi_belanja'], 0, ',', '.') }}</td></tr></tbody></table></div>
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-header border-0 pt-6"><div><h2 class="fs-4 fw-bold mb-1">Target dan Realisasi Pendapatan</h2><div class="text-muted fs-7">Realisasi dicatat per bulan untuk setiap kode rekening.</div></div></div>
            <div class="table-responsive"><table class="table table-row-bordered align-middle gy-3 mb-0 revenue-table"><thead><tr><th>Unit</th><th>Kode Rekening</th><th>Nama Rekening</th><th class="text-end">Target</th>@foreach ($months as $month)<th class="text-end">{{ $month }}</th>@endforeach<th class="text-end">Total Realisasi</th><th class="text-end">Sisa Target</th></tr></thead><tbody>
                @forelse ($targets as $target)
                    <tr><td>{{ $target->nama_unit }}</td><td class="fw-semibold text-nowrap">{{ $target->kode_rekening }}</td><td>{{ $target->nama_rekening }}</td><td class="text-end text-nowrap">Rp {{ number_format($target->target_tahunan, 0, ',', '.') }}</td>@foreach ($months as $number => $month)<td class="text-end text-nowrap">{{ $target->monthly[$number] > 0 ? 'Rp '.number_format($target->monthly[$number], 0, ',', '.') : '-' }}</td>@endforeach<td class="text-end text-nowrap fw-semibold">Rp {{ number_format($target->total_realisasi, 0, ',', '.') }}</td><td class="text-end text-nowrap {{ $target->sisa_target < 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($target->sisa_target, 0, ',', '.') }}</td></tr>
                @empty
                    <tr><td colspan="18" class="text-center text-muted py-8">Belum ada target rekening pendapatan pada tahun dan unit terpilih.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>
    </div>

    <style>
        .revenue-page { max-width: 1680px; margin: 0 auto; }
        .revenue-summary { border-radius: .85rem; background: linear-gradient(135deg, #fff, #f8fbff); }
        .revenue-table { min-width: 1500px; }
        .revenue-table thead th { color: #7e8798; font-size: .75rem; text-transform: uppercase; white-space: nowrap; }
    </style>
@endsection
