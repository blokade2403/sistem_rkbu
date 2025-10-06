@extends('layouts.main')
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="assets/images/profile-bg.jpg" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image  shadow"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body shadow">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ session('nama_lengkap') }}</h5>
                        <p class="text-muted mb-0">{{ session('nama_unit') }}</p>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-5">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Complete Your Profile</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="badge bg-light text-primary fs-12"><i
                                    class="ri-edit-box-line align-bottom me-1"></i> Edit</a>
                        </div>
                    </div>
                    <div class="progress animated-progress custom-progress progress-label">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="label">30%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="confirmSubmitForm" method="POST"
                                action="{{ route('users.update_profil', $user->id_user) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">Username</label>
                                            <input type="text" name="username" value="{{ $user->username }}"
                                                class="form-control" id="firstnameInput" placeholder="Enter your firstname">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="lastnameInput"
                                                placeholder="Enter your lastname" name="nama_lengkap"
                                                value="{{ $user->nama_lengkap }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">NIP/NRK</label>
                                            <input type="text" class="form-control" id="lastnameInput"
                                                placeholder="Enter your lastname" name="nip_user"
                                                value="{{ $user->nip_user }}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="emailInput"
                                                placeholder="Enter your email" name="email"
                                                value="{{ $user->email }}">
                                            <input type="hidden" class="form-control" name="id_admin_pendukung_ppk"
                                                value="{{ $user->id_admin_pendukung_ppk }}" id="basic-addon11"
                                                placeholder="John Doe" aria-label="Username"
                                                aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="id_level"
                                                value="{{ $user->id_level }}" id="basic-addon11" placeholder="John Doe"
                                                aria-label="Username" aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="id_fase"
                                                value="{{ $user->id_fase }}" id="basic-addon11" placeholder="John Doe"
                                                aria-label="Username" aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="id_unit"
                                                value="{{ $user->id_unit }}" id="basic-addon11" placeholder="John Doe"
                                                aria-label="Username" aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="status_user"
                                                value="{{ $user->status_user }}" id="basic-addon11"
                                                placeholder="John Doe" aria-label="Username"
                                                aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="id_pejabat"
                                                value="{{ $user->id_pejabat }}" id="basic-addon11"
                                                placeholder="John Doe" aria-label="Username"
                                                aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="id_ksp"
                                                value="{{ $user->id_ksp }}" id="basic-addon11" placeholder="John Doe"
                                                aria-label="Username" aria-describedby="basic-addon11" />
                                            <input type="hidden" class="form-control" name="status_edit"
                                                value="{{ $user->status_edit }}" id="basic-addon11"
                                                placeholder="John Doe" aria-label="Username"
                                                aria-describedby="basic-addon11" />
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">Password</label>
                                            <input type="password" class="form-control" data-provider="flatpickr"
                                                name="password" placeholder="Change Password" />
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea" class="form-label">Description</label>
                                            <textarea class="form-control" id="exampleFormControlTextarea" placeholder="Enter your description" rows="3">Hi I'm Anna Adame,It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is European languages are members of the same family.</textarea>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
