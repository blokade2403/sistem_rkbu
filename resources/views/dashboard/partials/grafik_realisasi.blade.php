<div class="col-xl-9 col-md-12">
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
                                    ({{ $bulanList[$filterBulan] }}–Des):<i
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
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                class="text-muted">Current
                                Year<i class="mdi mdi-chevron-down ms-1"></i></span>
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
