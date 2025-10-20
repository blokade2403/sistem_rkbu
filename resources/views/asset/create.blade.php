@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Vertical nav Steps</h4>
                </div><!-- end card header -->
                <div class="card-body form-steps">
                    <form class="vertical-navs-step" method="POST" action="{{ route('barang_assets.store') }}"
                        enctype="multipart/form-data">
                        @csrf
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
                                        Billing Info
                                    </button>
                                    <button class="nav-link" id="v-pills-bill-address-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-bill-address" type="button" role="tab"
                                        aria-controls="v-pills-bill-address" aria-selected="false">
                                        <span class="step-title me-2">
                                            <i class="ri-close-circle-fill step-icon me-2"></i>
                                            Step 2
                                        </span>
                                        Address
                                    </button>
                                    <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-payment" type="button" role="tab"
                                        aria-controls="v-pills-payment" aria-selected="false">
                                        <span class="step-title me-2">
                                            <i class="ri-close-circle-fill step-icon me-2"></i>
                                            Step 3
                                        </span>
                                        Payment
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
                                                <h5>Billing Info</h5>
                                                <p class="text-muted">Fill all information below
                                                </p>
                                            </div>
                                            <!-------------- Harus Ada ---------->
                                            {{-- <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                <input id="profile-img-file-input" type="file"
                                                    class="profile-img-file-input" accept="image/png, image/jpeg">
                                            </div> --}}
                                            <!-------------- Harus Ada ---------->
                                            <div>
                                                <div class="row g-3">
                                                    <div class="col-sm-12">
                                                        <label for="firstName" class="form-label">Nama Asset</label>
                                                        <input type="text" name="nama_asset" class="form-control"
                                                            id="firstName" placeholder="Isi Nama Asset"
                                                            value="{{ old('nama_asset') }}">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="firstName" class="form-label">Kode Barang</label>
                                                        <input type="text" name="kode_asset" class="form-control"
                                                            id="firstName" placeholder="Isi Kode Barang"
                                                            value="{{ old('kode_asset') }}">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="firstName" class="form-label">Type</label>
                                                        <input type="text" name="type" class="form-control"
                                                            id="firstName" placeholder="Type" value="{{ old('type') }}">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="lastName" class="form-label">Merk</label>
                                                        <input type="text" name="merk" class="form-control"
                                                            id="lastName" placeholder="Isi Merk Barang"
                                                            value="{{ old('merk') }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="address" class="form-label">Serial Number</label>
                                                        <input type="text" class="form-control" name="serial_number"
                                                            id="address" placeholder="SN.1234">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="lastName" class="form-label">No Register</label>
                                                        <input type="text" name="no_register" class="form-control"
                                                            id="lastName" placeholder="Isi Nomor Reg"
                                                            value="{{ old('no_register') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Jenis Asset</label>
                                                        <select class="form-select" name="id_jenis_kategori_rkbu"
                                                            id="country" data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            @foreach ($jenis as $item)
                                                                <option value="{{ $item->id_jenis_kategori_rkbu }}">
                                                                    {{ $item->nama_jenis_kategori_rkbu }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Kondisi Asset</label>
                                                        <select class="form-select" name="id_jenis_kategori_rkbu"
                                                            id="country" data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            <option value="Baik">Baik</option>
                                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                                            <option value="Rusak Berat">Rusak Berat</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Satuan Asset</label>
                                                        <select class="form-select" name="satuan" id="country"
                                                            data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            @foreach ($satuan as $item)
                                                                <option value="{{ $item->nama_uraian_1 }}">
                                                                    {{ $item->nama_uraian_1 }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="lastName" class="form-label">Tahun Perolehan</label>
                                                        <input type="text" name="tahun_perolehan" class="form-control"
                                                            id="lastName" placeholder="Tahun Perolehan"
                                                            value="{{ old('tahun_perolehan') }}">
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label for="lastName" class="form-label">Spesifikasi
                                                            Komponen</label>
                                                        <textarea class="form-control" name="spek" id="spek" rows="3" placeholder="Spesifikasi" required></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="cc-number" class="form-label">Harga Satuan
                                                            Komponen</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"
                                                                id="inputGroup-sizing-default">Rp.</span>
                                                            <input type="text" name="harga_asset"
                                                                value="{{ old('harga_asset') }}" class="form-control"
                                                                aria-label="Sizing example input"
                                                                aria-describedby="inputGroup-sizing-default">
                                                            <input type="hidden" class="form-control" id="cc-number"
                                                                placeholder="" name="jumlah_asset" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="v-pills-bill-address-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                    to Shipping</button>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->
                                        <div class="tab-pane fade" id="v-pills-bill-address" role="tabpanel"
                                            aria-labelledby="v-pills-bill-address-tab">
                                            <div>
                                                <h5>Shipping Address</h5>
                                                <p class="text-muted">Fill all information below
                                                </p>
                                            </div>

                                            <div>
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label for="address" class="form-label">Tanggal Asset</label>
                                                        <input type="date" class="form-control" name="tgl_asset"
                                                            id="address" placeholder="1234 Main St">
                                                    </div>

                                                    <div class="col-6">
                                                        <label for="address2" class="form-label">Nama Penyedia
                                                            <span class="text-muted">(Optional)</span></label>
                                                        <input type="text" name="penyedia"
                                                            value="{{ old('penyedia') }}" class="form-control"
                                                            id="address2" placeholder="PT.Penyedia">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Kategori Asset</label>
                                                        <select class="form-select" name="hibah" id="country"
                                                            data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            <option value="Asset Tetap">Asset Tetap</option>
                                                            <option value="Hibah">Hibah</option>
                                                            <option value="Transfer">Transfer</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Sumber Anggaran</label>
                                                        <select class="form-select" name="sumber_anggaran" id="country"
                                                            data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            <option value="APBD">APBD</option>
                                                            <option value="BLUD">BLUD</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="state" class="form-label">Pengguna Asset</label>
                                                        <input type="text" name="pengguna_asset"
                                                            value="{{ old('pengguna_asset') }}" class="form-control"
                                                            placeholder="1234 Main St">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="zip" class="form-label">NIP/NRK</label>
                                                        <input type="text" name="nip_pengguna"
                                                            value="{{ old('nip_pengguna') }}" class="form-control"
                                                            id="zip" placeholder="">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="zip" class="form-label">Jabatan</label>
                                                        <input type="text" name="jabatan_pengguna"
                                                            value="{{ old('jabatan_pengguna') }}" class="form-control"
                                                            id="zip" placeholder="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="state" class="form-label">Keterangan Asset</label>
                                                        <input type="text" name="status_asset"
                                                            value="{{ old('status_asset') }}" class="form-control"
                                                            placeholder="Akan di Hibahkan..">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Penempatan Asset</label>
                                                        <select class="form-select" name="id_penempatan" id="country"
                                                            data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            @foreach ($penempatan as $item)
                                                                <option value="{{ $item->id_penempatan }}">
                                                                    {{ $item->lokasi_barang }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="country" class="form-label">Kategori Asset
                                                            Bergerak</label>
                                                        <select class="form-select" name="kategori_asset_bergerak"
                                                            id="country" data-choices data-choices-sorting="true">
                                                            <option value="">Choose...
                                                            </option>
                                                            <option value="Handphone">Handphone</option>
                                                            <option value="Tablet">Tablet</option>
                                                            <option value="Laptop">Laptop</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr class="my-4 text-muted">

                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="same-address">
                                                    <label class="form-check-label" for="same-address">Shipping address is
                                                        the
                                                        same as my billing address</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="save-info">
                                                    <label class="form-check-label" for="save-info">Save this
                                                        information for
                                                        next time</label>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="v-pills-bill-info-tab"><i
                                                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Back to Billing Info</button>
                                                <button type="button"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="v-pills-payment-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                    to Payment</button>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->
                                        <div class="tab-pane fade" id="v-pills-payment" role="tabpanel"
                                            aria-labelledby="v-pills-payment-tab">
                                            <div>
                                                <h5>Payment</h5>
                                                <p class="text-muted">Fill all information below
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <div class="row g-3 mt-5">
                                                    <div class="col-xxl-12 col-md-4 col-6">
                                                        <div
                                                            class="profile-user position-relative d-inline-block mx-auto mb-4">
                                                            <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                                class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                                alt="user-profile-image">
                                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                                <input id="profile-img-file-input" name="foto"
                                                                    type="file" class="profile-img-file-input"
                                                                    accept="image/png, image/jpeg">
                                                                <label for="profile-img-file-input"
                                                                    class="profile-photo-edit avatar-xs">
                                                                    <span
                                                                        class="avatar-title rounded-circle bg-light text-body">
                                                                        <i class="ri-camera-fill"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row gy-3">
                                                    <div class="col-md-6">
                                                        <label for="cc-number" class="form-label">Tanggal BPKB</label>
                                                        <input type="text" class="form-control" id="cc-number"
                                                            placeholder="" name="tgl_bpkb" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="cc-name" class="form-label">Nomor Rangka</label>
                                                        <input type="text" class="form-control" name="no_rangka"
                                                            id="cc-name" placeholder="" required>
                                                        <small class="text-muted"> Untuk Kendaraan Bermotor (KDO)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="cc-number" class="form-label">Nomor Mesin</label>
                                                        <input type="text" class="form-control" id="cc-number"
                                                            placeholder="" name="no_mesin" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="cc-number" class="form-label">Nomor Polisi</label>
                                                        <input type="text" class="form-control" id="cc-number"
                                                            placeholder="" name="no_polisi" required>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="v-pills-bill-address-tab"><i
                                                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Back to Shipping Info</button>
                                                <button type="button"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="v-pills-finish-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                    Order Complete</button>
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
                                                <h5>Your Order is Completed !</h5>
                                                <p class="text-muted">You Will receive an order
                                                    confirmation email with details of your order.
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
                                        Your cart</h5>
                                    <span class="badge bg-danger rounded-pill">3</span>
                                </div>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0">Product name</h6>
                                            <small class="text-muted">Brief description</small>
                                        </div>
                                        <span class="text-muted">$12</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0">Second product</h6>
                                            <small class="text-muted">Brief description</small>
                                        </div>
                                        <span class="text-muted">$8</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0">Third item</h6>
                                            <small class="text-muted">Brief description</small>
                                        </div>
                                        <span class="text-muted">$5</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between bg-light">
                                        <div class="text-success">
                                            <h6 class="my-0">Discount code</h6>
                                            <small>−$5 Discount</small>
                                        </div>
                                        <span class="text-success">−$5</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total (USD)</span>
                                        <strong>$20</strong>
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
    </div>
    <!-- end row -->
@endsection
