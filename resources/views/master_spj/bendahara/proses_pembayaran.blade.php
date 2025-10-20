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
                <h4 class="card-title mb-0">Proses Pembayaran SPJ</h4>
            </div><!-- end card header -->
            <div class="card-body form-steps">
                <form class="vertical-navs-step" method="POST"
                    action="{{ route($routePrefix . '.updatepembayaranBendahara', $master_spj->id_master_spj) }}"
                    id="confirmSubmitForm" enctype="multipart/form-data" validate>
                    @csrf
                    @method('PUT')
                    <div class="row gy-5">
                        <div class="col-lg-3">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab2" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info2" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 1
                                    </span>
                                    Detail Info
                                </button>
                                <button class="nav-link active" id="v-pills-bill-address-tab2" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-address2" type="button" role="tab"
                                    aria-controls="v-pills-bill-address" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 2
                                    </span>
                                    Detail Pajak
                                </button>
                                <button class="nav-link" id="v-pills-payment-tab2" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-payment2" type="button" role="tab"
                                    aria-controls="v-pills-payment" aria-selected="false">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 3
                                    </span>
                                    Detail Anggaran
                                </button>
                                <button class="nav-link" id="v-pills-finish-tab2" data-bs-toggle="pill"
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
                                    <div class="tab-pane fade" id="v-pills-bill-info2" role="tabpanel"
                                        aria-labelledby="v-pills-bill-info-tab2">
                                        <div>
                                            <h5>Detail Info</h5>
                                            <p class="text-muted">Data Informasi Pesanan Usulan
                                            </p>
                                        </div>

                                        <div>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal Serah Terima
                                                        SPJ</label>
                                                    <input type="date" class="form-control"
                                                        value="{{ $master_spj->tgl_verif_bendahara }}"
                                                        placeholder="Type your title in Placeholder"
                                                        @if (!empty($master_spj->tgl_verif_bendahara)) readonly @endif>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal Penyerahan
                                                        SPJ</label>
                                                    <input type="text" name="tanggal_penyerahan_spj"
                                                        class="form-control"
                                                        value="{{ $master_spj->tanggal_penyerahan_spj }}"
                                                        readonly="">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal Revisi
                                                        SPJ</label>
                                                    <input type="text" name="tanggal_revisi_spj"
                                                        class="form-control"
                                                        value="{{ $master_spj->tanggal_revisi_spj ?? '-' }}"
                                                        readonly="">
                                                    <input type="hidden" name="tanggal_pembayaran"
                                                        value="{{ date('Y-m-d H:i:s') }}">
                                                    <input type="hidden" name="nama_validasi_keuangan"
                                                        value="{{ session('nama_lengkap') }}">
                                                    <input type="hidden" name="tgl_verif_bendahara"
                                                        value="{{ now()->toDateString() }}">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Tanggal Faktur</label>
                                                    <input type="text" name="tanggal_faktur"
                                                        value="{{ $master_spj->tanggal_faktur }}"
                                                        class="form-control flatpickr-input" id="flatpickr-date"
                                                        placeholder="----/--/--">
                                                    <input type="hidden" name="bulan_penyerahan_spj"
                                                        value="{{ date('M') }}">
                                                    <input type="hidden" name="tanggal_revisi_spj">
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="firstName" class="form-label">Total Bruto</label>
                                                    <input type="text" id="bruto"
                                                        oninput="formatCurrency(this); spj();" name="bruto"
                                                        value="{{ number_format($master_spj->bruto, 0, ',', '.') }}"
                                                        class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-bill-address-tab2"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Lanjut
                                                ke Hal detail perusahaan</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade show active" id="v-pills-bill-address2" role="tabpanel"
                                        aria-labelledby="v-pills-bill-address-tab2">
                                        <div>
                                            <h5>Detail Perusahaan</h5>
                                            <p class="text-muted">Detail Perusahaan pesanan barang
                                            </p>
                                        </div>
                                        <div>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Harga Dasar</label>
                                                    <input type="number" name="harga_dasar" id="harga_dasar"
                                                        value="{{ $master_spj->harga_dasar }}" readonly=""
                                                        class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-6">
                                                    <label for="firstName" class="form-label">PPN</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100" class="form-control" id="ppn"
                                                        name="ppn" onkeyup="spj();" value=""
                                                        aria-label="Amount (to the nearest dollar)" />
                                                    <input type="hidden" class="form-control" id="pajakppn"
                                                        readonly="">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="firstName" class="form-label">PPH22</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100"
                                                        value="{{ old('pph22', $defaultValue ?? $master_spj->pph22) }}"
                                                        class="form-control" id="pph22" name="pph22"
                                                        onkeyup="spj();"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                    <input type="hidden" class="form-control" id="pajakpph22"
                                                        onkeyup="spj();">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="firstName" class="form-label">PPH23</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100"
                                                        value="{{ old('pph23', $defaultValue ?? $master_spj->pph23) }}"
                                                        class="form-control" id="pph23" name="pph23"
                                                        onkeyup="spj();" value="{{ $master_spj->pph23 }}"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                    <input type="hidden" class="form-control" id="pajakpph23"
                                                        onkeyup="spj();">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="firstName" class="form-label">PPH21</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100"
                                                        value="{{ old('pph21', $defaultValue ?? $master_spj->pph21) }}"
                                                        class="form-control" id="pph21" name="pph21"
                                                        onkeyup="spj();" value="{{ $master_spj->pph21 }}"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                    <input type="hidden" class="form-control" id="pajakpph21"
                                                        onkeyup="spj();">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="firstName" class="form-label">PP 0,5</label>
                                                    <input type="number" step="0.01" min="0"
                                                        max="100"
                                                        value="{{ old('pp05', $defaultValue ?? $master_spj->pph05) }}"
                                                        class="form-control" id="pp05" name="pp05"
                                                        onkeyup="spj();" value="{{ $master_spj->pp05 }}"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                    <input type="hidden" class="form-control" id="pajakpp05"
                                                        onkeyup="spj();">
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="firstName" class="form-label">Total Pajak</label>
                                                    <input type="text" class="form-control" id="jumlahpajak"
                                                        name="jumlah_pajak" value="{{ $master_spj->jumlah_pajak }}"
                                                        readonly="">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Total Pembayaran</label>
                                                    <input type="text" id="total_bruto" readonly=""
                                                        class="form-control bg-lighter" readonly aria-label="Username"
                                                        aria-describedby="basic-addon21" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Harga Bersih</label>
                                                    <input type="text" id="harga_bersih" name="harga_bersih"
                                                        value="{{ $master_spj->harga_bersih }}"
                                                        class="form-control bg-lighter" aria-label="Username"
                                                        aria-describedby="basic-addon21" readonly="" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">BPJS Kesehatan</label>
                                                    <input type="number" id="bpjs_kes" name="bpjs_kes"
                                                        value="{{ old('bpjs_kes', $defaultValue ?? $master_spj->bpjs_kes) }}"
                                                        onkeyup="spj()" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">BPJS Ketenaga
                                                        Kerjaan</label>
                                                    <input type="number" id="bpjs_tk" name="bpjs_tk"
                                                        value="{{ old('bpjs_tk', $defaultValue ?? $master_spj->bpjs_tk) }}"
                                                        onkeyup="spj()" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-6">
                                                    <label for="address" class="form-label">Validasi SPJ</label>
                                                    <select name="status_pembayaran" id="perusahaan" data-choices
                                                        data-choices-sorting="true">
                                                        <option value="{{ $master_spj->status_pembayaran }}">
                                                            {{ $master_spj->status_pembayaran }}</option>
                                                        <option value="Sudah di Bayar">Sudah Di Bayar</option>
                                                        <option value="Revisi">Di Tolak</option>
                                                        <option value="Bayar Parsial">Bayar Parsial</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="state" class="form-label">Bulan Pembayaran</label>
                                                    <select name="bulan_pembayaran" data-choices
                                                        data-choices-sorting="true">
                                                        <option value="{{ $master_spj->bulan_pembayaran }}">
                                                            {{ $master_spj->bulan_pembayaran }}</option>
                                                        <option value="Januari">Januari</option>
                                                        <option value="Februari">Februari</option>
                                                        <option value="Maret">Maret</option>
                                                        <option value="April">April</option>
                                                        <option value="Mei">Mei</option>
                                                        <option value="Juni">Juni</option>
                                                        <option value="Juli">Juli</option>
                                                        <option value="Agustus">Agustus</option>
                                                        <option value="September">September</option>
                                                        <option value="Oktober">Oktober</option>
                                                        <option value="November">November</option>
                                                        <option value="Desember">Desember</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-info-tab2"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Kembali</button>
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="v-pills-payment-tab2"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Lanjut
                                                ke Hal Detail Anggaran</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-payment2" role="tabpanel"
                                        aria-labelledby="v-pills-payment-tab2">
                                        <div>
                                            <h5>Detail Anggaran</h5>
                                            <p class="text-muted">Detail anggaran pesanan barang
                                            </p>
                                        </div>
                                        <div>
                                            <div class="row gy-3">
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Jumlah Yang
                                                        Dibayarkan</label>
                                                    <input type="number" name="pembayaran" id="pembayaran"
                                                        value="{{ old('pembayaran', $defaultValue ?? $master_spj->pembayaran) }}"
                                                        onkeyup="spj();" class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Sisa Pembayaran</label>
                                                    <input type="text" id="sisa_pembayaran" name="sisa_pembayaran"
                                                        value="{{ $master_spj->sisa_pembayaran }}"
                                                        class="form-control" oninput="formatCurrency(this);"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Kode Billing PPN</label>
                                                    <input type="text" name="kode_billingppn"
                                                        value="{{ old('kode_billingppn', $defaultValue ?? $master_spj->kode_billingppn) }}"
                                                        class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">Kode Billing
                                                        PPH22</label>
                                                    <input type="text" name="kode_billingpph22"
                                                        value="{{ old('kode_billingppn', $defaultValue ?? $master_spj->kode_billingpph22) }}"
                                                        class="form-control"
                                                        aria-label="Amount (to the nearest dollar)" />
                                                </div>
                                                <div class="col-12">
                                                    <label for="email" class="form-label">Keterangan Validasi
                                                        Bendahara<span class="text-muted">(Optional)</span></label>
                                                    <textarea rows="2" cols="2" name="ket_verif_bendahara" class="form-control"
                                                        placeholder="Default textarea" @if (!empty($master_spj->ket_verif_bendahara)) readonly @endif>
    {{ old('ket_verif_bendahara', is_array($master_spj->ket_verif_bendahara) ? implode(', ', $master_spj->ket_verif_bendahara) : $master_spj->ket_verif_bendahara) }}
</textarea>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="firstName" class="form-label">Upload Bukti
                                                        Bayar</label>
                                                    <div class="input-group">
                                                        <input type="file" name="bukti_bayar" class="form-control"
                                                            id="inputGroupFile02">
                                                        <label class="input-group-text"
                                                            for="inputGroupFile02">Upload</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                data-previous="v-pills-bill-address-tab2"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Kembali</button>
                                            @if ($master_spj->status_pembayaran == 'Sudah di Bayar')
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
                                                    data-nexttab="v-pills-finish-tab2"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                    Kirim Data</button>
                                            @endif

                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                    <div class="tab-pane fade" id="v-pills-finish" role="tabpanel"
                                        aria-labelledby="v-pills-finish-tab2">
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
