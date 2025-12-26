@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="h-100">
                    <div class="row mb-3 pb-1">
                        <div class="col-12">
                            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                <div class="flex-grow-1">
                                    <h4 class="fs-16 mb-1">Hello, {{ session('nama_lengkap') }}!</h4>
                                    <p class="text-muted mb-0">Anda masuk ke Anggaran Tahun <strong
                                            class="bold">{{ session('tahun_anggaran') }}</strong> dalam
                                        fase {{ session('nama_fase') }}
                                    </p>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <form action="javascript:void(0);">
                                        <div class="row g-3 mb-0 align-items-center">
                                            <div class="col-sm-auto">
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control border-0 dash-filter-picker shadow"
                                                        data-provider="flatpickr" data-range-date="true"
                                                        data-date-format="d M, Y"
                                                        data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                                    <div class="input-group-text bg-primary border-primary text-white">
                                                        <i class="ri-calendar-2-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i
                                                        class="ri-pulse-line"></i></button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                            </div><!-- end card header -->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                </div> <!-- end col -->


            </div>
            <h4 class="mb-4">📊 Grafik Target, Realisasi, & Kumulatif SPJ</h4>
            {{-- Filter Bulan --}}
            <form method="GET" action="{{ route('grafik.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Filter Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" onchange="this.form.submit()">
                            @foreach ($bulanList as $num => $nama)
                                <option value="{{ $num }}" {{ $filterBulan == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card crm-widget">
                        <div class="card-body p-0">
                            <div class="row row-cols-xxl-4 row-cols-md-3 row-cols-1 g-0">
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Ringkasan Bulan<i
                                                class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-space-ship-line fs-18 display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span>{{ $bulanList[$filterBulan] }}</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Total Realisasi
                                            (Jan–{{ $bulanList[$filterBulan] }}):<i
                                                class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-exchange-dollar-line fs-18 display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0">
                                                    Rp. <span>{{ number_format($totalRealisasi, 0, ',', '.') }}</span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Total Target
                                            (Jan–{{ $bulanList[$filterBulan] }}):<i
                                                class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-pulse-line fs-18 display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0">Rp.
                                                    <span>{{ number_format($totalTarget, 0, ',', '.') }}</span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13"> Persentase Akumulatif:<i
                                                class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-trophy-line fs-18 display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-0"> <strong
                                                        class="{{ $persentase >= 90 ? 'text-success' : 'text-danger' }}">
                                                        {{ $persentase }}%
                                                    </strong>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->

            {{-- Grafik --}}
            <div class="col-xxl-12">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Balance Overview</h4>
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                        class="text-muted">Current Year<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">Current Year</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body px-0">
                        <canvas id="grafikSpj" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="mt-4 text-center">
            <h5>Ringkasan Bulan <span class="text-primary">{{ $bulanList[$filterBulan] }}</span></h5>
            <p>
                Total Realisasi (Jan–{{ $bulanList[$filterBulan] }}):
                <strong>{{ number_format($totalRealisasi, 0, ',', '.') }}</strong><br>
                Total Target ({{ $bulanList[$filterBulan] }}–Des):
                <strong>{{ number_format($totalTarget, 0, ',', '.') }}</strong><br>
                Persentase Akumulatif:
                <strong class="{{ $persentase >= 100 ? 'text-success' : 'text-danger' }}">
                    {{ $persentase }}%
                </strong>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikSpj');
        const data = @json($dataGabungan);

        new Chart(ctx, {
            data: {
                labels: data.map(d => d.bulan),
                datasets: [{
                        type: 'bar',
                        label: 'Target',
                        data: data.map(d => d.target),
                        backgroundColor: 'rgba(255, 99, 132, 0.4)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        yAxisID: 'y',
                    },
                    {
                        type: 'line',
                        label: 'Realisasi',
                        data: data.map(d => d.realisasi),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.3)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y',
                    },
                    {
                        type: 'line',
                        label: 'Realisasi Kumulatif',
                        data: data.map(d => d.realisasi_kumulatif),
                        borderColor: 'rgba(75, 192, 75, 1)',
                        backgroundColor: 'rgba(75, 192, 75, 0.3)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Target, Realisasi, & Kumulatif SPJ per Bulan'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return context.dataset.label + ': ' + new Intl.NumberFormat('id-ID').format(
                                    value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
