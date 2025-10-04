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
                <h4 class="card-title mb-0">Surat Pesanan Barang</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST"
                    action="{{ route($routePrefix . '.updateSuratPesanan', $master_spj->id_master_spj) }}"
                    id="confirmSubmitForm" enctype="multipart/form-data" validate>
                    @csrf
                    @method('PUT')
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 1
                                    </span>
                                    Detail Info
                                </button>
                                <button class="nav-link active" id="v-pills-bill-address-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-address" type="button" role="tab"
                                    aria-controls="v-pills-bill-address" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 2
                                    </span>
                                    Detail Perusahaan
                                </button>
                                <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-payment" type="button" role="tab"
                                    aria-controls="v-pills-payment" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 3
                                    </span>
                                    Detail Anggaran
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
                                    <div class="tab-pane fade" id="v-pills-bill-info" role="tabpanel"
                                        aria-labelledby="v-pills-bill-info-tab">
                                        <div>
                                            <h5>Detail Info</h5>
                                            <p class="text-muted">Data Informasi Pesanan Usulan
                                            </p>
                                        </div>

                                        <div>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal Surat
                                                        Pesanan</label>
                                                    <input type="date" class="form-control" name="tgl_surat_pesanan"
                                                        value="{{ $master_spj->tgl_surat_pesanan }}"
                                                        placeholder="Type your title in Placeholder"
                                                        @if (!empty($master_spj->tgl_surat_pesanan)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Waktu Pekerjaan</label>
                                                    <input type="date" name="jangka_waktu_pekerjaan"
                                                        value="{{ $master_spj->jangka_waktu_pekerjaan }}"
                                                        class="form-control" placeholder="Password input"
                                                        @if (!empty($master_spj->jangka_waktu_pekerjaan)) readonly @endif>
                                                </div>
                                                <div class="col-12">
                                                    <label for="email" class="form-label">Uraian Belanja<span
                                                            class="text-muted">(Optional)</span></label>
                                                    <textarea rows="2" cols="2" name="rincian_belanja" class="form-control" placeholder="Default textarea"
                                                        @if (!empty($master_spj->rincian_belanja)) readonly @endif>
    {{ old('rincian_belanja', is_array($master_spj->rincian_belanja) ? implode(', ', $master_spj->rincian_belanja) : $master_spj->rincian_belanja) }}
</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Lanjut
                                                ke Hal detail perusahaan</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade show active" id="v-pills-bill-address" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab">
                                        <div>
                                            <h5>Detail Perusahaan</h5>
                                            <p class="text-muted">Detail Perusahaan pesanan barang
                                            </p>
                                        </div>

                                        <div>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="address" class="form-label">Perusahaan</label>
                                                    <select name="id_perusahaan" id="perusahaan" data-choices
                                                        data-choices-sorting="true"
                                                        @if (!empty($master_spj->id_perusahaan)) disabled @endif>
                                                        <option value="{{ $master_spj->id_perusahaan }}">
                                                            {{ optional($master_spj->perusahaan)->nama_perusahaan ?? 'Pilih Nama Perusahaan' }}
                                                        </option>
                                                        @foreach ($perusahaan as $key)
                                                            <option value="{{ $key->id_perusahaan }}"
                                                                {{ $key->id_perusahaan == $master_spj->id_perusahaan ? 'selected' : '' }}>
                                                                {{ $key->nama_perusahaan }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- hidden input supaya value tetap terkirim jika select di-disable --}}
                                                    @if (!empty($master_spj->id_perusahaan))
                                                        <input type="hidden" name="id_perusahaan"
                                                            value="{{ $master_spj->id_perusahaan }}">
                                                    @endif

                                                </div>

                                                <div class="col-12">
                                                    <label for="address2" class="form-label">Nomor Surat Pesanan
                                                        <span class="text-muted">(Optional)</span></label>
                                                    <input type="text" class="form-control"
                                                        name="no_surat_pesanan"
                                                        value="{{ $master_spj->no_surat_pesanan }}"
                                                        placeholder="No SP"
                                                        @if (!empty($master_spj->no_surat_pesanan)) readonly @endif>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="country" class="form-label">ID Paket</label>
                                                    <input type="text" name="idpaket"
                                                        value="{{ $master_spj->idpaket }}" class="form-control"
                                                        placeholder="No ID Paket"
                                                        @if (!empty($master_spj->idpaket)) readonly @endif>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="state" class="form-label">Admin Pendukung</label>
                                                    <select name="id_admin_pendukung_ppk" id="perusahaan" data-choices
                                                        data-choices-sorting="true"
                                                        @if (!empty($master_spj->id_admin_pendukung_ppk)) disabled @endif>
                                                        <option value="{{ $master_spj->id_admin_pendukung_ppk }}">
                                                            {{ optional($master_spj->pendukung_ppk)->nama_pendukung_ppk ?? 'Pilih Pendukung PPK' }}
                                                        </option>
                                                        @foreach ($perusahaan as $key)
                                                            <option value="{{ $item->id_admin_pendukung_ppk }}">
                                                                {{ $item->nama_pendukung_ppk }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- hidden input supaya value tetap terkirim jika select di-disable --}}
                                                    @if (!empty($master_spj->id_admin_pendukung_ppk))
                                                        <input type="hidden" name="id_admin_pendukung_ppk"
                                                            value="{{ $master_spj->id_admin_pendukung_ppk }}">
                                                    @endif

                                                </div>
                                            </div>


                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Kembali</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-payment-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Lanjut
                                                ke Hal Detail Anggaran</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-payment" role="tabpanel"
                                        aria-labelledby="v-pills-payment-tab">
                                        <div>
                                            <h5>Detail Anggaran</h5>
                                            <p class="text-muted">Detail anggaran pesanan barang
                                            </p>
                                        </div>

                                        <div>
                                            <div class="row gy-3">
                                                <div class="col-md-12">
                                                    <label for="cc-name" class="form-label">Harga Dasar</label>
                                                    <input type="text" name="harga_dasar"
                                                        id="harga_dasar_pengadaan"
                                                        value="{{ number_format($master_spj->harga_dasar, 0, '.', '.') }}"
                                                        readonly="" class="form-control currency"
                                                        placeholder="Harga Dasar" readonly="">
                                                    <small class="text-muted">Full name as
                                                        displayed on card</small>
                                                    <div class="invalid-feedback">
                                                        Name on card is required
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="cc-number" class="form-label">PPN</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100" class="form-control" id="ppn_pengadaan"
                                                        name="ppn" onkeyup="spj_pengadaan();"
                                                        value="{{ $master_spj->ppn }}" class="form-control"
                                                        placeholder="Isi PPN"
                                                        @if (!empty($master_spj->ppn)) readonly @endif>
                                                    <div class="invalid-feedback">
                                                        Credit card number is required
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <label for="cc-cvv" class="form-label">Total Harga Bruto</label>
                                                    <input type="text" onkeyup="spj_pengadaan();" id="harga_bruto"
                                                        name="bruto"
                                                        value="{{ number_format($master_spj->bruto, 0, '.', '.') }}"
                                                        class="form-control currency" placeholder="Isi Harga Bruto"
                                                        readonly>
                                                    <input type="hidden" class="form-control" name="nama_lengkap"
                                                        id="validationCustomUsername"
                                                        value="{{ session('nama_lengkap') }}">
                                                    <input type="hidden" class="form-control" name="id_user"
                                                        id="validationCustomUsername"
                                                        value="{{ session('id_user') }}">
                                                    <input type="hidden" class="form-control" name="tahun_anggaran"
                                                        id="validationCustomUsername"
                                                        value="{{ session('nama_tahun_anggaran') }}">
                                                    <input type="hidden" class="form-control" name="id_status_spj"
                                                        id="validationCustomUsername" value="6">
                                                    <input type="hidden" class="form-control"
                                                        name="ket_verif_bendahara" id="validationCustomUsername">
                                                    <input type="hidden" class="form-control"
                                                        name="status_proses_pesanan"
                                                        value="{{ session('status_proses_pesanan') }}">
                                                    <div class="invalid-feedback">
                                                        Security code required
                                                    </div>
                                                </div>
                                                <hr class="my-3 text-muted">

                                                <div class="form-check mb-2">
                                                    <input id="credit" name="status_hutang" type="radio"
                                                        class="form-check-input" value="Tahun Berjalan"
                                                        {{ $master_spj->status_hutang == 'Tahun Berjalan' ? 'checked' : '' }}
                                                        required>
                                                    <label class="form-check-label" for="same-address">Surat
                                                        Pertanggung Jawaban (SPJ) Tahun
                                                        Berjalan</label>
                                                </div>
                                                <div class="form-check">
                                                    <input id="debit" name="status_hutang" type="radio"
                                                        class="form-check-input" value="Hutang"
                                                        {{ $master_spj->status_hutang == 'Hutang' ? 'checked' : '' }}
                                                        required>
                                                    <label class="form-check-label text-danger" for="save-info">Surat
                                                        Pertanggung
                                                        Jawaban (SPJ) Hutang Tahun
                                                        Lalu</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-address-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Kembali</button>
                                            @if ($master_spj->status_proses_pesanan == 'Selesai')
                                                <button type="button"
                                                    class="btn btn-outline-warning btn-load btn right ms-auto nexttab nexttab">
                                                    <span class="d-flex align-items-center">
                                                        <span class="flex-grow-1 me-2">
                                                            No Action...
                                                        </span>
                                                        <span class="spinner-grow flex-shrink-0" role="status">
                                                            <span class="visually-hidden">No Action...</span>
                                                        </span>
                                                    </span>
                                                </button>
                                            @else
                                                <button type="submit"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="v-pills-finish-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                    Kirim Data</button>
                                            @endif

                                        </div>
                                    </div>
                                    <!-- end tab pane -->
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
