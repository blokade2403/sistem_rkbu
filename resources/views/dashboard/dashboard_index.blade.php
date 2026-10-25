@extends('layouts.main')

@section('content')
    @php
        $rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
        $realisasiProgress = $approvedUsulanBudget > 0
            ? min(100, round($realisasi / $approvedUsulanBudget * 100, 1))
            : 0;
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Dashboard Anggaran</h1>
            <div class="text-muted">Ringkasan RKBU, usulan belanja, dan realisasi pembayaran</div>
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center gap-3">
            <label for="tahun_anggaran_id" class="text-muted fw-semibold text-nowrap">Tahun anggaran</label>
            <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                @foreach ($tahunAnggarans as $tahun)
                    <option value="{{ $tahun->id }}" @selected($tahun->id === $tahunAnggaranId)>
                        {{ $tahun->nama_tahun_anggaran }}{{ $tahun->status === 'aktif' ? ' · Aktif' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if (! $tahunAnggaran)
        <div class="alert alert-info">Data tahun anggaran belum tersedia.</div>
    @else
        <div class="row g-5 mb-6">
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-6">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-2">Total Anggaran RKBU</div>
                                <div class="fs-2 fw-bold text-gray-900">{{ $rupiah($rkbuBudget) }}</div>
                            </div>
                            <span class="dashboard-icon bg-light-primary text-primary"><i class="fas fa-wallet"></i></span>
                        </div>
                        <div class="text-muted fs-7 mt-3">{{ number_format($totalRkbu) }} RKBU · selain yang ditolak</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-6">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-2">RKBU Disetujui</div>
                                <div class="fs-2 fw-bold text-gray-900">{{ number_format($approvedRkbu) }}</div>
                            </div>
                            <span class="dashboard-icon bg-light-success text-success"><i class="fas fa-check"></i></span>
                        </div>
                        <div class="text-muted fs-7 mt-3">Nilai disetujui: {{ $rupiah($approvedRkbuBudget) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-6">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-2">Usulan Belanja Aktif</div>
                                <div class="fs-2 fw-bold text-gray-900">{{ $rupiah($usulanBudget) }}</div>
                            </div>
                            <span class="dashboard-icon bg-light-info text-info"><i class="fas fa-file-invoice-dollar"></i></span>
                        </div>
                        <div class="text-muted fs-7 mt-3">{{ number_format($usulanCount) }} usulan · proses, revisi, atau disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-6">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-2">Realisasi Pembayaran</div>
                                <div class="fs-2 fw-bold text-gray-900">{{ $rupiah($realisasi) }}</div>
                            </div>
                            <span class="dashboard-icon bg-light-warning text-warning"><i class="fas fa-money-bill-wave"></i></span>
                        </div>
                        <div class="text-muted fs-7 mt-3">{{ $realisasiProgress }}% dari nilai usulan yang disetujui</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-8">
            <div class="col-xl-7">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 mb-1">Anggaran RKBU per Jenis Kategori</h3>
                            <span class="text-muted fs-7">Total nilai RKBU selain status ditolak</span>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        @forelse ($categoryChart as $category)
                            <div class="mb-6">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <span class="fw-semibold text-gray-800">{{ $category['kategori'] }}</span>
                                    <span class="text-muted text-nowrap">{{ $rupiah($category['total']) }}</span>
                                </div>
                                <div class="progress h-8px bg-light-primary">
                                    <div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ min(100, $category['persentase']) }}%" aria-valuenow="{{ $category['persentase'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted fs-8 mt-1">{{ $category['persentase'] }}% dari total anggaran RKBU</div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-10">Belum ada data anggaran RKBU untuk tahun ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 mb-1">Perjalanan Anggaran</h3>
                            <span class="text-muted fs-7">Dari perencanaan sampai pembayaran</span>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="budget-flow-item">
                            <span class="budget-flow-dot bg-primary"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-semibold text-gray-800">Anggaran RKBU</span>
                                    <span class="fw-bold text-gray-900 text-nowrap">{{ $rupiah($rkbuBudget) }}</span>
                                </div>
                                <div class="text-muted fs-8">{{ number_format($totalRkbu) }} RKBU</div>
                            </div>
                        </div>
                        <div class="budget-flow-item">
                            <span class="budget-flow-dot bg-success"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-semibold text-gray-800">RKBU disetujui</span>
                                    <span class="fw-bold text-gray-900 text-nowrap">{{ $rupiah($approvedRkbuBudget) }}</span>
                                </div>
                                <div class="text-muted fs-8">{{ number_format($approvedRkbu) }} RKBU</div>
                            </div>
                        </div>
                        <div class="budget-flow-item">
                            <span class="budget-flow-dot bg-info"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-semibold text-gray-800">Usulan belanja disetujui</span>
                                    <span class="fw-bold text-gray-900 text-nowrap">{{ $rupiah($approvedUsulanBudget) }}</span>
                                </div>
                                <div class="text-muted fs-8">{{ number_format($approvedUsulanCount) }} usulan</div>
                            </div>
                        </div>
                        <div class="budget-flow-item budget-flow-last">
                            <span class="budget-flow-dot bg-warning"></span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between gap-3">
                                    <span class="fw-semibold text-gray-800">Realisasi pembayaran</span>
                                    <span class="fw-bold text-gray-900 text-nowrap">{{ $rupiah($realisasi) }}</span>
                                </div>
                                <div class="text-muted fs-8">Berdasarkan pembayaran yang memiliki tanggal bayar</div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-semibold">Realisasi terhadap usulan disetujui</span>
                                <span class="fw-bold text-gray-900">{{ $realisasiProgress }}%</span>
                            </div>
                            <div class="progress h-8px bg-light-warning">
                                <div class="progress-bar bg-warning rounded" role="progressbar" style="width: {{ $realisasiProgress }}%" aria-valuenow="{{ $realisasiProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .dashboard-icon { width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 17px; }
        .budget-flow-item { display: flex; gap: 14px; position: relative; padding-bottom: 25px; }
        .budget-flow-item::after { content: ''; position: absolute; left: 7px; top: 17px; bottom: 0; width: 2px; background: #e4e6ef; }
        .budget-flow-last::after { display: none; }
        .budget-flow-dot { position: relative; z-index: 1; margin-top: 4px; width: 16px; height: 16px; border: 4px solid #fff; border-radius: 50%; box-shadow: 0 0 0 1px #e4e6ef; flex: 0 0 16px; }
    </style>
@endsection
