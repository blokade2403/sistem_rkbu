@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Progress Nav Steps</h4>
                </div><!-- end card header -->
                <div class="card-body form-steps">
                    <div class="row gy-3">
                        <div class="col-lg-2">
                            <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                    aria-controls="v-pills-bill-info" aria-selected="true">
                                    <span class="step-title me-2">
                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                        Step 1
                                    </span>
                                    Billing Info
                                </button>
                            </div>
                            <!-- end nav -->
                        </div> <!-- end col-->
                        <div class="col-lg-7">
                            <form id="confirmSubmitForm" method="POST" action="{{ route('judul_headers.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="text-center pt-3 pb-4 mb-1">
                                    <h5>Signup Your Account</h5>
                                </div>
                                <div id="custom-progress-bar" class="progress-nav mb-4">
                                    <div class="progress" style="height: 1px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link rounded-pill active"
                                                data-progressbar="custom-progress-bar" id="pills-gen-info-tab"
                                                data-bs-toggle="pill" data-bs-target="#pills-gen-info" type="button"
                                                role="tab" aria-controls="pills-gen-info"
                                                aria-selected="true">1</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                                id="pills-info-desc-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-info-desc" type="button" role="tab"
                                                aria-controls="pills-info-desc" aria-selected="false">2</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                                id="pills-success-tab" data-bs-toggle="pill" data-bs-target="#pills-success"
                                                type="button" role="tab" aria-controls="pills-success"
                                                aria-selected="false">3</button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel"
                                        aria-labelledby="pills-gen-info-tab">
                                        <div>
                                            <div class="mb-4">
                                                <div>
                                                    <h5 class="mb-1">General Information</h5>
                                                    <p class="text-muted">Fill all Information as below</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="gen-info-email-input">Nama
                                                            Instansi</label>
                                                        <input type="text" name="nama_rs" class="form-control"
                                                            id="gen-info-email-input" placeholder="Enter Name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="gen-info-username-input">Email</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="email_rs"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="gen-info-username-input">Wilayah</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="wilayah"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="gen-info-username-input">Tlp</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="tlp_rs"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="gen-info-username-input">Kode
                                                            Pos</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="kode_pos"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Alamat</label>
                                                        <textarea class="form-control" name="alamat_rs" id="VertimeassageInput" rows="3"
                                                            placeholder="Enter your message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="pills-info-desc-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                to more info</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->

                                    <div class="tab-pane fade" id="pills-info-desc" role="tabpanel"
                                        aria-labelledby="pills-info-desc-tab">
                                        <div>
                                            <div class="text-center">
                                                <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                    <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                        <input id="profile-img-file-input" name="gambar1" type="file"
                                                            class="profile-img-file-input" accept="image/png, image/jpeg">
                                                        <label for="profile-img-file-input"
                                                            class="profile-photo-edit avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                                <i class="ri-camera-fill"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- Gambar 2 --}}
                                                <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                    <img id="preview-img-2"
                                                        src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                        <input id="profile-img-file-input-2" name="gambar2"
                                                            type="file" class="profile-img-file-input"
                                                            accept="image/png, image/jpeg">
                                                        <label for="profile-img-file-input-2"
                                                            class="profile-photo-edit avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                                <i class="ri-camera-fill"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Gambar 3 --}}
                                                <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                    <img id="preview-img-3"
                                                        src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                        <input id="profile-img-file-input-3" name="header7"
                                                            type="file" class="profile-img-file-input"
                                                            accept="image/png, image/jpeg">
                                                        <label for="profile-img-file-input-3"
                                                            class="profile-photo-edit avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                                <i class="ri-camera-fill"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Gambar 3 --}}
                                                <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                    <img id="preview-img-4"
                                                        src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                        <input id="profile-img-file-input-4" name="gambar3"
                                                            type="file" class="profile-img-file-input"
                                                            accept="image/png, image/jpeg">
                                                        <label for="profile-img-file-input-2"
                                                            class="profile-photo-edit avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                                <i class="ri-camera-fill"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Gambar 4 --}}
                                                <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                    <img id="preview-img-5"
                                                        src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                        class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                        alt="user-profile-image">
                                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                        <input id="profile-img-file-input-5" name="gambar4"
                                                            type="file" class="profile-img-file-input"
                                                            accept="image/png, image/jpeg">
                                                        <label for="profile-img-file-input-2"
                                                            class="profile-photo-edit avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                                <i class="ri-camera-fill"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <h5 class="fs-14">Add Image</h5>
                                                <p></p>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            1</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header1"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            1</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header1"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            2</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header2"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            3</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header3"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            4</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header4"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            5</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header5"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="VertimeassageInput" class="form-label">Header
                                                            6</label>
                                                        <input type="text" class="form-control"
                                                            id="gen-info-username-input" name="header6"
                                                            placeholder="Enter Email">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-3 mt-4">
                                            <button type="button"
                                                class="btn btn-link text-decoration-none btn-label previestab"
                                                data-previous="pills-gen-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                Back to General</button>
                                            <button type="submit"
                                                class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                data-nexttab="pills-success-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->

                                    <div class="tab-pane fade" id="pills-success" role="tabpanel"
                                        aria-labelledby="pills-success-tab">
                                        <div>
                                            <div class="text-center">

                                                <div class="mb-4">
                                                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                                        colors="primary:#0ab39c,secondary:#405189"
                                                        style="width:120px;height:120px"></lord-icon>
                                                </div>
                                                <h5>Well Done !</h5>
                                                <p class="text-muted">You have Successfully Signed Up</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <!-- end tab content -->
                            </form>
                        </div>
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
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (USD)</span>
                                    <strong>$20</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->


        <!-- end col -->
    </div><!-- end row -->
@endsection
