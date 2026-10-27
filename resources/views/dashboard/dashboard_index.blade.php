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
                                <div class="text-muted fw-semibold mb-2">Total Anggaran Rekening Belanja</div>
                                <div class="fs-2 fw-bold text-gray-900">{{ $rupiah($totalAnggaranRekening) }}</div>
                            </div>
                            <span class="dashboard-icon bg-light-primary text-primary"><i class="fas fa-wallet"></i></span>
                        </div>
                        <div class="text-muted fs-7 mt-3">Total pagu seluruh rekening belanja</div>
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

        <div class="mb-3">
            <h2 class="fs-4 fw-bold text-gray-900 mb-1">Perbandingan Pagu dan Belanja BLUD</h2>
            <div class="text-muted">Pagu rekening dibandingkan dengan total RKBU BLUD berstatus disetujui</div>
        </div>
        <div class="row g-5 mb-6">
            @php
                $bludCards = array_merge([['label' => 'Total Belanja BLUD', 'pagu' => $bludComparisonTotal['pagu'], 'belanja' => $bludComparisonTotal['belanja'], 'selisih' => $bludComparisonTotal['selisih']]], $bludComparisonCategories->all());
            @endphp
            @foreach ($bludCards as $bludCard)
                <div class="col-sm-6 col-xxl-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-6">
                            <div class="text-muted fw-semibold mb-2">{{ $bludCard['label'] }}</div>
                            <div class="fs-2 fw-bold text-gray-900">Belanja: {{ $rupiah($bludCard['belanja']) }}</div>
                            <div class="text-muted fs-7 mt-3">Pagu: {{ $rupiah($bludCard['pagu']) }}</div>
                            <div class="fw-semibold fs-7 mt-1 {{ $bludCard['selisih'] < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $bludCard['selisih'] < 0 ? 'Melebihi pagu: ' . $rupiah(abs($bludCard['selisih'])) : 'Sisa pagu: ' . $rupiah($bludCard['selisih']) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm mb-6">
            <div class="card-header border-0 pt-6">
                <div class="card-title d-flex flex-column"><h3 class="fw-bold text-gray-900 mb-1">Selisih Pagu per Rekening BLUD</h3><span class="text-muted fs-7">Belanja dihitung dari RKBU BLUD yang sudah disetujui</span></div>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-300 align-middle gy-3">
                        <thead><tr class="text-muted fw-bold fs-7 text-uppercase"><th>No.</th><th>Rekening Belanja</th><th class="text-end">Pagu BLUD</th><th class="text-end">Belanja Disetujui</th><th class="text-end">Selisih</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse ($bludAccountComparison as $index => $account)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td><div class="fw-semibold text-gray-900">{{ $account['name'] }}</div><div class="text-muted fs-7">Kode {{ $account['code'] }}</div></td>
                                    <td class="text-end">{{ $rupiah($account['pagu']) }}</td>
                                    <td class="text-end">{{ $rupiah($account['belanja']) }}</td>
                                    <td class="text-end fw-semibold {{ $account['selisih'] < 0 ? 'text-danger' : 'text-success' }}">{{ $rupiah(abs($account['selisih'])) }}</td>
                                    <td><span class="badge badge-light-{{ $account['selisih'] < 0 ? 'danger' : 'success' }}">{{ $account['selisih'] < 0 ? 'Melebihi pagu' : 'Sisa pagu' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-8">Belum ada pagu BLUD pada tahun anggaran ini.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot><tr class="fw-bold bg-light-primary"><th colspan="2">TOTAL BLUD</th><th class="text-end">{{ $rupiah($bludComparisonTotal['pagu']) }}</th><th class="text-end">{{ $rupiah($bludComparisonTotal['belanja']) }}</th><th class="text-end">{{ $rupiah(abs($bludComparisonTotal['selisih'])) }}</th><th>{{ $bludComparisonTotal['selisih'] < 0 ? 'Melebihi pagu' : 'Sisa pagu' }}</th></tr></tfoot>
                    </table>
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
        <div class="row g-5 g-xl-8 mt-1">
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title d-flex flex-column">
                            <h3 class="fw-bold text-gray-900 mb-1">Daftar Inputan Unit</h3>
                            <span class="text-muted fs-7">Perbandingan total usulan dengan hasil validasi terakhir</span>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead><tr class="fw-bold text-muted"><th>No.</th><th>Nama Unit</th><th class="text-end">Total Usulan</th><th class="text-end">Perlu Validasi</th><th class="text-end">Validasi Terakhir</th><th class="text-end">Ditolak</th></tr></thead>
                                <tbody>
                                    @forelse ($unitInputan as $index => $unit)
                                        <tr><td>{{ $index + 1 }}</td><td class="fw-semibold text-gray-800">{{ $unit->nama_unit }}</td><td class="text-end">{{ $rupiah($unit->total_usulan) }}</td><td class="text-end">{{ $rupiah($unit->perlu_validasi) }}</td><td class="text-end text-success fw-semibold">{{ $rupiah($unit->tervalidasi_akhir) }}</td><td class="text-end text-danger">{{ $rupiah($unit->ditolak) }}</td></tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-8">Belum ada inputan unit pada tahun anggaran ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header border-0 pt-6"><div class="card-title d-flex flex-column"><h3 class="fw-bold text-gray-900 mb-1">Grafik Anggaran BLUD</h3><span class="text-muted fs-7">Berdasarkan jenis belanja tervalidasi</span></div></div>
                    <div class="card-body pt-2">
                        @foreach ($bludCategoryChart as $category)
                            <div class="mb-6"><div class="d-flex justify-content-between align-items-center gap-3 mb-2"><span class="fw-semibold text-gray-800">{{ $category['label'] }}</span><span class="text-muted text-nowrap">{{ $rupiah($category['total']) }}</span></div><div class="progress h-8px bg-light-primary"><div class="progress-bar bg-primary rounded" role="progressbar" style="width: {{ min(100, $category['persentase']) }}%" aria-valuenow="{{ $category['persentase'] }}" aria-valuemin="0" aria-valuemax="100"></div></div><div class="text-muted fs-8 mt-1">{{ $category['persentase'] }}% dari total BLUD</div></div>
                        @endforeach
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
