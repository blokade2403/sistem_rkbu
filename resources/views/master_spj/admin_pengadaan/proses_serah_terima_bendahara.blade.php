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
                <h4 class="card-title mb-0">Proses Serah Terima SPJ ke Bendahara</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST"
                    action="{{ route($routePrefix . '.updateSerahTerimaBendahara', $master_spj->id_master_spj) }}"
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
                                                    <label for="firstName" class="form-label">Tanggal Penyerahan</label>
                                                    <input type="date" class="form-control"
                                                        name="tanggal_penyerahan_spj"
                                                        value="{{ $master_spj->tanggal_penyerahan_spj }}"
                                                        placeholder="Type your title in Placeholder"
                                                        @if (!empty($master_spj->tanggal_penyerahan_spj)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Status</label>
                                                    <select name="status_serah_terima_bendahara" data-choices
                                                        data-choices-sorting="true">
                                                        <option
                                                            value="{{ $master_spj->status_serah_terima_bendahara }}">
                                                            {{ $master_spj->status_serah_terima_bendahara ?? 'Pilih Status' }}
                                                        </option>
                                                        <option value="Selesai">Selesai</option>
                                                        <option value="Revisi">Revisi SPJ</option>
                                                    </select>
                                                    <input type="hidden" name="bulan_penyerahan_spj">
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="col-md-12">
                                                        <label for="cc-name" class="form-label">Harga
                                                            Dasar</label>
                                                        <input type="text" name="harga_dasar"
                                                            id="harga_dasar_pengadaan"
                                                            value="{{ number_format($master_spj->harga_dasar, 0, '.', '.') }}"
                                                            class="form-control currency" placeholder="Harga Dasar"
                                                            readonly>
                                                        <small class="text-muted">Full name as
                                                            displayed on card</small>
                                                        <div class="invalid-feedback">
                                                            Name on card is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="cc-number" class="form-label">PPN</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100" class="form-control" id="ppn_pengadaan"
                                                        name="ppn" onkeyup="spj_pengadaan();"
                                                        value="{{ $master_spj->ppn }}" class="form-control"
                                                        placeholder="Isi PPN" readonly>
                                                    <div class="invalid-feedback">
                                                        Credit card number is required
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="cc-cvv" class="form-label">Total Harga
                                                        Bruto</label>
                                                    <input type="text" onkeyup="spj_pengadaan();" id="harga_bruto"
                                                        name="bruto"
                                                        value="{{ number_format($master_spj->bruto, 0, '.', '.') }}"
                                                        class="form-control currency" placeholder="Isi Harga Bruto"
                                                        readonly>
                                                    <div class="invalid-feedback">
                                                        Security code required
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="cc-cvv" class="form-label">Upload Dokumen SPJ
                                                        1</label>
                                                    <input class="form-control form-control-sm" id="formSizeSmall"
                                                        type="file" name="upload_spj_1">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="cc-cvv" class="form-label">Upload Dokumen SPJ
                                                        2</label>
                                                    <input class="form-control form-control-sm" id="formSizeSmall"
                                                        type="file" name="upload_spj_2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            @if ($master_spj->status_serah_terima_bendahara == 'Selesai')
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
                                            @elseif($master_spj->status_verifikasi_direktur == 'Selesai')
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
                                                        Tunggu Proses Verifikasi BAST
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
