@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-body form-steps">
                    <form class="vertical-navs-step">
                        <div class="row gy-5">
                            <div class="col-lg-3">
                                <div class="nav flex-column custom-nav nav-pills" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link done" id="v-pills-bill-info-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-bill-info" type="button" role="tab"
                                        aria-controls="v-pills-bill-info" aria-selected="true">
                                        <span class="step-title me-2">
                                            <i class="ri-close-circle-fill step-icon me-2"></i>
                                            Step create
                                        </span>
                                        {{ $title }} Info
                                    </button>
                                </div>
                                <!-- end nav -->
                            </div> <!-- end col-->
                            <div class="col-lg-6">
                                <div class="px-lg-4">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="v-pills-bill-address" role="tabpanel"
                                            aria-labelledby="v-pills-bill-address-tab">
                                            <div>
                                                <h5>Form {{ $title2 }}</h5>
                                                <p class="text-muted">Fill all information below</p>
                                            </div>
                                            @include('master_backend.setting_users.jabatan.create')
                                        </div>
                                    </div>
                                    <!-- end tab content -->
                                </div>
                            </div>
                            <!-- end col -->

                            <div class="col-lg-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fs-14 text-primary mb-0"><i
                                            class="ri-shopping-cart-fill align-middle me-2"></i> Your Form</h5>
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
@endsection
