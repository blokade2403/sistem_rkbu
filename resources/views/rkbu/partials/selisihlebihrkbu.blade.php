<div class="card">
    <div class="card-body">
        <h5 class="fs-15 fw-semibold">Total Selisih / Lebih Anggaran</h5>
        <div class="row row-cols-md-3 row-cols-1">
            <div class="col col-lg-8 border-end">
                <div class="py-4 px-3">
                    <h5 class="text-muted text-uppercase fs-13">Total Anggaran <i
                            class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                    </h5>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-space-ship-line display-6 text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-0">Rp.
                                {{ number_format($total_anggaran, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

            <div class="col col-lg border-end">
                <div class="mt-3 mt-md-0 py-4 px-3">
                    @if ($selisih > 0)
                        <h5 class="text-muted text-uppercase fs-13">Lebih <i
                                class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                        </h5>
                    @elseif($selisih < 0)
                        <h5 class="text-muted text-uppercase fs-13">Kurang <i
                                class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                        </h5>
                    @endif
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                        </div>
                        @if ($selisih > 0)
                            <div class="flex-grow-1 ms-3">
                                <h2 class="mb-0">{{ number_format($selisih, 0, ',', '.') }}</h2>
                            </div>
                        @elseif($selisih < 0)
                            <div class="flex-grow-1 ms-3">
                                <h2 class="mb-0 text-danger">{{ number_format($selisih, 0, ',', '.') }}</h2>
                            </div>
                        @endif
                    </div>
                </div>
            </div><!-- end col -->
        </div>
    </div>
    <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0" style="height: 6px;">
        <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%" aria-valuenow="30"
            aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%" aria-valuenow="50"
            aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0"
            aria-valuemax="100"></div>
    </div>
</div>
