    <div class="col-xl-12">
        <div class="card-body p-0">
            <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-alert-triangle text-warning me-2 icon-sm">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                    </path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div class="flex-grow-1 text-truncate">
                    Your free trial expired in <b>17</b> days.
                </div>
                <div class="flex-shrink-0">
                    <a href="pages-pricing.html" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                </div>
            </div>
        </div> <!-- end card-body-->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Proses BAST</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST"
                    action="{{ route($routePrefix . '.updateBast', $master_spj->id_master_spj) }}"
                    id="confirmSubmitForm" enctype="multipart/form-data" validate>
                    @csrf
                    @method('PUT')
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 1
                                    </span>
                                    Detail Info
                                </button>
                                <button class="nav-link" id="v-pills-finish-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-finish" type="button" role="tab"
                                    aria-controls="v-pills-finish" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 4
                                    </span>
                                    Finish
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-6">
                            <div class="px-lg-4">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="v-pills-bill-info" role="tabpanel"
                                        aria-labelledby="v-pills-bill-info-tab">
                                        <div>
                                            <h5>Detail Info</h5>
                                            <p class="text-muted">Data Informasi Pesanan Usulan
                                            </p>
                                        </div>
                                        <div>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal BAST</label>
                                                    <input type="date" class="form-control" name="tgl_bast"
                                                        value="{{ $master_spj->tgl_bast }}"
                                                        placeholder="Type your title in Placeholder"
                                                        @if (!empty($master_spj->tgl_bast)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">No. BA Pemeriksaan
                                                        Pekerjaan</label>
                                                    <input type="text" name="no_ba"
                                                        value="{{ $master_spj->no_ba }}" class="form-control"
                                                        placeholder="Nomor BA"
                                                        @if (!empty($master_spj->no_ba)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">No. BA Serah Terima Hasil
                                                        Pekerjaan</label>
                                                    <input type="text" name="no_ba_hp"
                                                        value="{{ $master_spj->no_ba_hp }}" class="form-control"
                                                        placeholder="Nomor BA"
                                                        @if (!empty($master_spj->no_ba_hp)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">No BA Serah Terima
                                                        Barang/Pekerjaan</label>
                                                    <input type="text" name="no_ba_bp"
                                                        value="{{ $master_spj->no_ba_bp }}" class="form-control"
                                                        placeholder="Nomor BA"
                                                        @if (!empty($master_spj->no_ba_bp)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">No. DPA</label>
                                                    <input type="text" name="no_dpa"
                                                        value="{{ $master_spj->no_dpa }}" class="form-control"
                                                        placeholder="Nomor DPA"
                                                        @if (!empty($master_spj->no_dpa)) readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            @if ($master_spj->status_proses_bast == 'Selesai')
                                                <button type="button"
                                                    class="btn btn-outline-warning btn-load right ms-auto nexttab nexttab"
                                                    data-nexttab="pills-success-tab"><span
                                                        class="d-flex align-items-center">
                                                        <span class="flex-grow-1 me-2">
                                                            Loading...
                                                        </span>
                                                        <span class="spinner-grow flex-shrink-0" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </span>
                                                    </span></button>
                                            @elseif($master_spj->status_proses_pengiriman_barang == 'Selesai')
                                                <button type="submit"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="v-pills-bill-address-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Kirim</button>
                                            @else
                                                <div class="col-12">
                                                    <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label shadow fade show mb-xl-0"
                                                        role="alert">
                                                        <i class="ri-error-warning-line label-icon"></i><strong>Perhatian
                                                            !!!</strong>
                                                        Tunggu Proses Verifikasi Pengurus Barang
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-finish" role="tabpanel"
                                        aria-labelledby="v-pills-finish-tab">
                                        <div class="text-center pt-4 pb-2">

                                            <div class="mb-4">
                                                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                                    colors="primary:#0ab39c,secondary:#405189"
                                                    style="width:120px;height:120px"></lord-icon>
                                            </div>
                                            <h5>Perubahan Data Berhasil di Simpan !</h5>
                                            <p class="text-muted">Data sudah disimpan dalam database, untuk lihat
                                                detail klik step / tombol kembali.
                                            </p>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                        </div>
                        <!-- end col -->

                        <div class="col-lg-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fs-14 text-primary mb-0"><i
                                        class="ri-shopping-cart-fill align-middle me-2"></i>
                                    Usulan Barang</h5>
                                <span class="badge bg-danger rounded-pill">3</span>
                            </div>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Harga Dasar</h6>
                                        <span class="text-muted fs-10">Harga sebelum ppn</span>
                                    </div>
                                    <span
                                        class="text-muted">{{ number_format($master_spj->harga_dasar, 0, '.', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <div class="text-success">
                                        <h6 class="my-0">PPN</h6>
                                    </div>
                                    <span class="text-success">{{ $master_spj->ppn }}%</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (Rp)</span>
                                    <strong>{{ number_format($master_spj->bruto, 0, '.', '.') }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end row -->
                </form>
            </div>
        </div>
        <!-- end -->
    </div>
    <!-- end col -->
