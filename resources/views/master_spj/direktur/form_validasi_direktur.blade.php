    <div class="col-xl-12">
        @include('partials.head_form')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Proses Verifikasi Direktur</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST"
                    action="{{ route($routePrefix . '.updateVerifDirektur', $master_spj->id_master_spj) }}"
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
                                                <div class="col-sm-12">
                                                    <label for="status_verifikasi_direktur" class="form-label">Status
                                                        Verifikasi</label>
                                                    <select name="status_verifikasi_direktur" class="form-select"
                                                        @if ($master_spj->status_verifikasi_direktur == 'Selesai') disabled @endif>
                                                        <option value="{{ $master_spj->status_verifikasi_direktur }}">
                                                            {{ $master_spj->status_verifikasi_direktur ?? 'Pilih Status' }}
                                                        </option>
                                                        <option value="Selesai">Selesai</option>
                                                        <option value="Revisi">Revisi</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="ket_verif_direktur"
                                                        class="form-label">Keterangan</label>
                                                    <textarea class="form-control" name="ket_verif_direktur" placeholder="Enter Description"
                                                        id="exampleFormControlTextarea5" rows="2" @if (!empty($master_spj->ket_verif_direktur) && $master_spj->status_verifikasi_direktur != 'Revisi') readonly @endif>
                                     {{ old('ket_verif_direktur', is_array($master_spj->ket_verif_direktur) ? implode(', ', $master_spj->ket_verif_direktur) : $master_spj->ket_verif_direktur) }}
                                                    </textarea>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <div class="text-end">
                                                <a href="{{ route($routePrefix . '.index') }}"
                                                    class="btn btn-secondary btn-label waves-effect waves-light">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            Kembali
                                                        </div>
                                                    </div>
                                                </a>
                                                @if ($master_spj->status_verifikasi_direktur == 'Selesai')
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
                                                @else
                                                    <button type="submit"
                                                        class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                        data-nexttab="v-pills-bill-address-tab"><i
                                                            class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Kirim</button>
                                                @endif
                                            </div>
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
