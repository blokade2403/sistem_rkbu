@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Good Morning, Anna!</h4>
                                <p class="text-muted mb-0">Here's what's happening with your store
                                    today.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <form action="javascript:void(0);">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control border-0 dash-filter-picker shadow"
                                                    data-provider="flatpickr" data-range-date="true"
                                                    data-date-format="d M, Y"
                                                    data-deafult-date="01 Jan 2022 to 31 Jan 2022">
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-soft-success shadow-none"><i
                                                    class="ri-add-circle-line align-middle me-1"></i>
                                                Add Product</button>
                                        </div>
                                        <!--end col-->
                                        <div class="col-auto">
                                            <button type="button"
                                                class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none"><i
                                                    class="ri-pulse-line"></i></button>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Total Earnings</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            +16.24 %
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value"
                                                data-target="559.25">0</span>k
                                        </h4>
                                        <a href="" class="text-decoration-underline">View net
                                            earnings</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success rounded fs-3">
                                            <i class="bx bx-dollar-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Orders</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-danger fs-14 mb-0">
                                            <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                            -3.57 %
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="36894">0</span>
                                        </h4>
                                        <a href="" class="text-decoration-underline">View all
                                            orders</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info rounded fs-3">
                                            <i class="bx bx-shopping-bag"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Customers</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            +29.08 %
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                                data-target="183.35">0</span>M
                                        </h4>
                                        <a href="" class="text-decoration-underline">See
                                            details</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning rounded fs-3">
                                            <i class="bx bx-user-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            My Balance</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-muted fs-14 mb-0">
                                            +0.00 %
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value"
                                                data-target="165.89">0</span>k
                                        </h4>
                                        <a href="" class="text-decoration-underline">Withdraw
                                            money</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-danger rounded fs-3">
                                            <i class="bx bx-wallet"></i>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Revenue</h4>
                                <div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                        ALL
                                    </button>
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                        1M
                                    </button>
                                    <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                        6M
                                    </button>
                                    <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                                        1Y
                                    </button>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-header p-0 border-0 bg-soft-light">
                                <div class="row g-0 text-center">
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Orders</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k
                                            </h5>
                                            <p class="text-muted mb-0">Earnings</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0">
                                            <h5 class="mb-1"><span class="counter-value" data-target="367">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Refunds</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-6 col-sm-3">
                                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                                            <h5 class="mb-1 text-success"><span class="counter-value"
                                                    data-target="18.92">0</span>%</h5>
                                            <p class="text-muted mb-0">Conversation Ratio</p>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body p-0 pb-2">
                                <div class="w-100">
                                    <div id="customer_impression_charts"
                                        data-colors='["--vz-success", "--vz-primary", "--vz-danger"]' class="apex-charts"
                                        dir="ltr"></div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-4">
                        <!-- card -->
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Sales by Locations</h4>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                                        Export Report
                                    </button>
                                </div>
                            </div><!-- end card header -->

                            <!-- card body -->
                            <div class="card-body">

                                <div id="sales-by-locations" data-colors='["--vz-light", "--vz-success", "--vz-primary"]'
                                    style="height: 269px" dir="ltr"></div>

                                <div class="px-2 py-2 mt-1">
                                    <p class="mb-1">Canada <span class="float-end">75%</span></p>
                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                            style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                        </div>
                                    </div>

                                    <p class="mt-3 mb-1">Greenland <span class="float-end">47%</span>
                                    </p>
                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                            style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="47">
                                        </div>
                                    </div>

                                    <p class="mt-3 mb-1">Russia <span class="float-end">82%</span>
                                    </p>
                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                            style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="82">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Best Selling Products</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="fw-semibold text-uppercase fs-12">Sort by:
                                            </span><span class="text-muted">Today<i
                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Yesterday</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                                            <img src="{{ asset('assets/images/products/img-1.png') }}"
                                                                alt="" class="img-fluid d-block" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1"><a
                                                                    href="apps-ecommerce-product-details.html"
                                                                    class="text-reset">Branded
                                                                    T-Shirts</a></h5>
                                                            <span class="text-muted">24 Apr
                                                                2021</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$29.00</h5>
                                                    <span class="text-muted">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">62</h5>
                                                    <span class="text-muted">Orders</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">510</h5>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1 fw-normal">$1,798</h5>
                                                    <span class="text-muted">Amount</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="text-muted">Showing <span class="fw-semibold">5</span> of
                                            <span class="fw-semibold">25</span> Results
                                        </div>
                                    </div>
                                    <ul class="pagination pagination-separated pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a href="#" class="page-link">←</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">1</a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="#" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">→</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Top Sellers</h4>
                                <div class="flex-shrink-0">
                                    <div class="dropdown card-header-dropdown">
                                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted">Report<i
                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Download
                                                Report</a>
                                            <a class="dropdown-item" href="#">Export</a>
                                            <a class="dropdown-item" href="#">Import</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ asset('assets/images/companies/img-1.png') }}"
                                                                alt="" class="avatar-sm p-2" />
                                                        </div>
                                                        <div>
                                                            <h5 class="fs-14 my-1 fw-medium"><a
                                                                    href="apps-ecommerce-seller-details.html"
                                                                    class="text-reset">iTest
                                                                    Factory</a>
                                                            </h5>
                                                            <span class="text-muted">Oliver
                                                                Tyler</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">Bags and Wallets</span>
                                                </td>
                                                <td>
                                                    <p class="mb-0">8547</p>
                                                    <span class="text-muted">Stock</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">$541200</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 mb-0">32%<i
                                                            class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                    </h5>
                                                </td>
                                            </tr><!-- end -->
                                        </tbody>
                                    </table><!-- end table -->
                                </div>

                                <div class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="text-muted">Showing <span class="fw-semibold">5</span> of
                                            <span class="fw-semibold">25</span> Results
                                        </div>
                                    </div>
                                    <ul class="pagination pagination-separated pagination-sm mb-0">
                                        <li class="page-item disabled">
                                            <a href="#" class="page-link">←</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">1</a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="#" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">→</a>
                                        </li>
                                    </ul>
                                </div>

                            </div> <!-- .card-body-->
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div> <!-- end row-->


                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Recent Orders</h4>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                                        <i class="ri-file-list-3-line align-middle"></i> Generate
                                        Report
                                    </button>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-block">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="responsive-reorder" class="table table-striped table-bordered wrap">
                                            <thead class="text-muted table-light">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Order ID</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Vendor</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="apps-ecommerce-order-details.html"
                                                            class="fw-medium link-primary">1</a>
                                                    </td>
                                                    <td>
                                                        <a href="apps-ecommerce-order-details.html"
                                                            class="fw-medium link-primary">#VZ2111</a>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                                    alt=""
                                                                    class="avatar-xs rounded-circle shadow" />
                                                            </div>
                                                            <div class="flex-grow-1">Jansh Brown</div>
                                                        </div>
                                                    </td>
                                                    <td>Kitchen Storage</td>
                                                    <td>
                                                        <span class="text-success">$149.00</span>
                                                    </td>
                                                    <td>Micro Design</td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 fw-medium mb-0">4.5<span
                                                                class="text-muted fs-11 ms-1">(61
                                                                votes)</span></h5>
                                                    </td>
                                                </tr><!-- end tr -->
                                                <tr>
                                                    <td>
                                                        <a href="apps-ecommerce-order-details.html"
                                                            class="fw-medium link-primary">1</a>
                                                    </td>
                                                    <td>
                                                        <a href="apps-ecommerce-order-details.html"
                                                            class="fw-medium link-primary">#VZ2111</a>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                                    alt=""
                                                                    class="avatar-xs rounded-circle shadow" />
                                                            </div>
                                                            <div class="flex-grow-1">Jansh Brown</div>
                                                        </div>
                                                    </td>
                                                    <td>Kitchen Storage</br>Kitchen Storage</br>Kitchen
                                                        Storage</br>Kitchen Storage</br></td>
                                                    <td>
                                                        <span class="text-success">$149.00</span>
                                                    </td>
                                                    <td>Micro Design</td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="fs-14 fw-medium mb-0">4.5<span
                                                                class="text-muted fs-11 ms-1">(61
                                                                votes)</span></h5>
                                                    </td>
                                                </tr><!-- end tr -->
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                </div> <!-- end row-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Choices</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div>
                                    <div>
                                        <h5 class="fs-14 mb-3">Single select input Example</h5>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default"
                                                        class="form-label text-muted">Default</label>
                                                    <p class="text-muted">Set
                                                        <code>data-choices</code> attribute to set a
                                                        default single select.
                                                    </p>
                                                    <select class="form-control" data-choices
                                                        name="choices-single-default" id="choices-single-default">
                                                        <option value="">This is a placeholder
                                                        </option>
                                                        <option value="Choice 1">Choice 1</option>
                                                        <option value="Choice 2">Choice 2</option>
                                                        <option value="Choice 3">Choice 3</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-groups"
                                                        class="form-label text-muted">Option
                                                        Groups</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-groups</code> attribute to set
                                                        option group</p>
                                                    <select class="form-control" id="choices-single-groups" data-choices
                                                        data-choices-groups data-placeholder="Select City"
                                                        name="choices-single-groups">
                                                        <option value="">Choose a city</option>
                                                        <optgroup label="UK">
                                                            <option value="London">London</option>
                                                            <option value="Manchester">Manchester
                                                            </option>
                                                            <option value="Liverpool">Liverpool
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="FR">
                                                            <option value="Paris">Paris</option>
                                                            <option value="Lyon">Lyon</option>
                                                            <option value="Marseille">Marseille
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="DE" disabled>
                                                            <option value="Hamburg">Hamburg</option>
                                                            <option value="Munich">Munich</option>
                                                            <option value="Berlin">Berlin</option>
                                                        </optgroup>
                                                        <optgroup label="US">
                                                            <option value="New York">New York</option>
                                                            <option value="Washington" disabled>
                                                                Washington</option>
                                                            <option value="Michigan">Michigan</option>
                                                        </optgroup>
                                                        <optgroup label="SP">
                                                            <option value="Madrid">Madrid</option>
                                                            <option value="Barcelona">Barcelona
                                                            </option>
                                                            <option value="Malaga">Malaga</option>
                                                        </optgroup>
                                                        <optgroup label="CA">
                                                            <option value="Montreal">Montreal</option>
                                                            <option value="Toronto">Toronto</option>
                                                            <option value="Vancouver">Vancouver
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-no-search"
                                                        class="form-label text-muted">Options added
                                                        via config with no search</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-search-false
                                                            data-choices-removeItem</code></p>
                                                    <select class="form-control" id="choices-single-no-search"
                                                        name="choices-single-no-search" data-choices
                                                        data-choices-search-false data-choices-removeItem>
                                                        <option value="Zero">Zero</option>
                                                        <option value="One">One</option>
                                                        <option value="Two">Two</option>
                                                        <option value="Three">Three</option>
                                                        <option value="Four">Four</option>
                                                        <option value="Five">Five</option>
                                                        <option value="Six">Six</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-no-sorting"
                                                        class="form-label text-muted">Options added
                                                        via config with no sorting</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-sorting-false</code> attribute.
                                                    </p>
                                                    <select class="form-control" id="choices-single-no-sorting"
                                                        name="choices-single-no-sorting" data-choices
                                                        data-choices-sorting-false>
                                                        <option value="Madrid">Madrid</option>
                                                        <option value="Toronto">Toronto</option>
                                                        <option value="Vancouver">Vancouver</option>
                                                        <option value="London">London</option>
                                                        <option value="Manchester">Manchester</option>
                                                        <option value="Liverpool">Liverpool</option>
                                                        <option value="Paris">Paris</option>
                                                        <option value="Malaga">Malaga</option>
                                                        <option value="Washington" disabled>Washington
                                                        </option>
                                                        <option value="Lyon">Lyon</option>
                                                        <option value="Marseille">Marseille</option>
                                                        <option value="Hamburg">Hamburg</option>
                                                        <option value="Munich">Munich</option>
                                                        <option value="Barcelona">Barcelona</option>
                                                        <option value="Berlin">Berlin</option>
                                                        <option value="Montreal">Montreal</option>
                                                        <option value="New York">New York</option>
                                                        <option value="Michigan">Michigan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- Single select input Example -->


                                    <div class="mt-4">
                                        <h5 class="fs-14 mb-3">Multiple select input</h5>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-multiple-default"
                                                        class="form-label text-muted">Default</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            multiple</code> attribute.</p>
                                                    <select class="form-control" id="choices-multiple-default"
                                                        data-choices name="choices-multiple-default" multiple>
                                                        <option value="Choice 1" selected>Choice 1
                                                        </option>
                                                        <option value="Choice 2">Choice 2</option>
                                                        <option value="Choice 3">Choice 3</option>
                                                        <option value="Choice 4" disabled>Choice 4
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-multiple-remove-button"
                                                        class="form-label text-muted">With
                                                        remove button</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-removeItem multiple</code>
                                                        attribute.</p>
                                                    <select class="form-control" id="choices-multiple-remove-button"
                                                        data-choices data-choices-removeItem
                                                        name="choices-multiple-remove-button" multiple>
                                                        <option value="Choice 1" selected>Choice 1
                                                        </option>
                                                        <option value="Choice 2">Choice 2</option>
                                                        <option value="Choice 3">Choice 3</option>
                                                        <option value="Choice 4">Choice 4</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-multiple-groups"
                                                        class="form-label text-muted">Option
                                                        groups</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-multiple-groups="true"
                                                            multiple</code> attribute. </p>
                                                    <select class="form-control" id="choices-multiple-groups"
                                                        name="choices-multiple-groups" data-choices
                                                        data-choices-multiple-groups="true" multiple>
                                                        <option value="">Choose a city</option>
                                                        <optgroup label="UK">
                                                            <option value="London">London</option>
                                                            <option value="Manchester">Manchester
                                                            </option>
                                                            <option value="Liverpool">Liverpool
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="FR">
                                                            <option value="Paris">Paris</option>
                                                            <option value="Lyon">Lyon</option>
                                                            <option value="Marseille">Marseille
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="DE" disabled>
                                                            <option value="Hamburg">Hamburg</option>
                                                            <option value="Munich">Munich</option>
                                                            <option value="Berlin">Berlin</option>
                                                        </optgroup>
                                                        <optgroup label="US">
                                                            <option value="New York">New York</option>
                                                            <option value="Washington" disabled>
                                                                Washington</option>
                                                            <option value="Michigan">Michigan</option>
                                                        </optgroup>
                                                        <optgroup label="SP">
                                                            <option value="Madrid">Madrid</option>
                                                            <option value="Barcelona">Barcelona
                                                            </option>
                                                            <option value="Malaga">Malaga</option>
                                                        </optgroup>
                                                        <optgroup label="CA">
                                                            <option value="Montreal">Montreal</option>
                                                            <option value="Toronto">Toronto</option>
                                                            <option value="Vancouver">Vancouver
                                                            </option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- multi select input Example -->

                                    <div class="mt-4">
                                        <h5 class="fs-14 mb-3">Text inputs</h5>

                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-text-remove-button"
                                                        class="form-label text-muted">Set limit
                                                        values with remove button</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-limit="Required Limit"
                                                            data-choices-removeItem</code>
                                                        attribute.</p>
                                                    <input class="form-control" id="choices-text-remove-button"
                                                        data-choices data-choices-limit="3" data-choices-removeItem
                                                        type="text" value="Task-1" />
                                                </div>
                                            </div>
                                            <!-- end col -->

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-text-unique-values"
                                                        class="form-label text-muted">Unique values
                                                        only, no pasting</label>
                                                    <p class="text-muted">Set <code>data-choices
                                                            data-choices-text-unique-true</code>
                                                        attribute.</p>
                                                    <input class="form-control" id="choices-text-unique-values"
                                                        data-choices data-choices-text-unique-true type="text"
                                                        value="Project-A, Project-B" />
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->

                                        <div>
                                            <label for="choices-text-disabled"
                                                class="form-label text-muted">Disabled</label>
                                            <p class="text-muted">Set <code>data-choices
                                                    data-choices-text-disabled-true</code> attribute.
                                            </p>
                                            <input class="form-control" id="choices-text-disabled" data-choices
                                                data-choices-text-disabled-true type="text"
                                                value="josh@joshuajohnson.co.uk, joe@bloggs.co.uk" />
                                        </div>
                                    </div>
                                </div>
                                <!-- end preview -->
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>

                <div class="vertical-overlay"></div>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->


                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Wizard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                    <li class="breadcrumb-item active">Wizard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Progress Nav Steps</h4>
                            </div><!-- end card header -->
                            <div class="card-body form-steps">
                                <form action="#">
                                    <div class="text-center pt-3 pb-4 mb-1">
                                        <h5>Signup Your Account</h5>
                                    </div>
                                    <div id="custom-progress-bar" class="progress-nav mb-4">
                                        <div class="progress" style="height: 1px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
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
                                                <button class="nav-link rounded-pill"
                                                    data-progressbar="custom-progress-bar" id="pills-info-desc-tab"
                                                    data-bs-toggle="pill" data-bs-target="#pills-info-desc"
                                                    type="button" role="tab" aria-controls="pills-info-desc"
                                                    aria-selected="false">2</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link rounded-pill"
                                                    data-progressbar="custom-progress-bar" id="pills-success-tab"
                                                    data-bs-toggle="pill" data-bs-target="#pills-success" type="button"
                                                    role="tab" aria-controls="pills-success"
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
                                                            <label class="form-label"
                                                                for="gen-info-email-input">Email</label>
                                                            <input type="text" class="form-control"
                                                                id="gen-info-email-input" placeholder="Enter Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="gen-info-username-input">User
                                                                Name</label>
                                                            <input type="text" class="form-control"
                                                                id="gen-info-username-input"
                                                                placeholder="Enter User Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="gen-info-password-input">Password</label>
                                                    <input type="password" class="form-control"
                                                        id="gen-info-password-input" placeholder="Enter Password">
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
                                                    <div
                                                        class="profile-user position-relative d-inline-block mx-auto mb-2">
                                                        <img src="assets/images/users/user-dummy-img.jpg"
                                                            class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                                            alt="user-profile-image">
                                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                            <input id="profile-img-file-input" type="file"
                                                                class="profile-img-file-input"
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
                                                    <h5 class="fs-14">Add Image</h5>

                                                </div>
                                                <div>
                                                    <label class="form-label"
                                                        for="gen-info-description-input">Description</label>
                                                    <textarea class="form-control" placeholder="Enter Description" id="gen-info-description-input" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button"
                                                    class="btn btn-link text-decoration-none btn-label previestab"
                                                    data-previous="pills-gen-info-tab"><i
                                                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Back to General</button>
                                                <button type="button"
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
                                                        <lord-icon src="https://cdn.lordicon.com/lupuorrc.json"
                                                            trigger="loop" colors="primary:#0ab39c,secondary:#405189"
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
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Arrow Nav Steps</h4>
                            </div><!-- end card header -->
                            <div class="card-body form-steps">
                                <form action="#">
                                    <div class="text-center pt-3 pb-4 mb-1">
                                        <img src="assets/images/logo-dark.png" alt="" height="17">
                                    </div>
                                    <div class="step-arrow-nav mb-4">

                                        <ul class="nav nav-pills custom-nav nav-justified" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link done" id="steparrow-gen-info-tab"
                                                    data-bs-toggle="pill" data-bs-target="#steparrow-gen-info"
                                                    type="button" role="tab" aria-controls="steparrow-gen-info"
                                                    aria-selected="true">General</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="steparrow-description-info-tab"
                                                    data-bs-toggle="pill" data-bs-target="#steparrow-description-info"
                                                    type="button" role="tab"
                                                    aria-controls="steparrow-description-info"
                                                    aria-selected="false">Description</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-experience-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-experience" type="button" role="tab"
                                                    aria-controls="pills-experience" aria-selected="false">Finish</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane fade" id="steparrow-gen-info" role="tabpanel"
                                            aria-labelledby="steparrow-gen-info-tab">
                                            <div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="steparrow-gen-info-email-input">Email</label>
                                                            <input type="text" class="form-control"
                                                                id="steparrow-gen-info-email-input"
                                                                placeholder="Enter Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="steparrow-gen-info-username-input">User
                                                                Name</label>
                                                            <input type="text" class="form-control"
                                                                id="steparrow-gen-info-username-input"
                                                                placeholder="Enter User Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="steparrow-gen-info-password-input">Password</label>
                                                    <input type="password" class="form-control"
                                                        id="steparrow-gen-info-password-input"
                                                        placeholder="Enter Password">
                                                </div>
                                                <div>
                                                    <label class="form-label"
                                                        for="steparrow-gen-info-confirm-password-input">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                        id="steparrow-gen-info-confirm-password-input"
                                                        placeholder="Enter Confirm Password">
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="steparrow-description-info-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                                    to more info</button>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->

                                        <div class="tab-pane fade show active" id="steparrow-description-info"
                                            role="tabpanel" aria-labelledby="steparrow-description-info-tab">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Upload
                                                        Image</label>
                                                    <input class="form-control" type="file" id="formFile">
                                                </div>
                                                <div>
                                                    <label class="form-label"
                                                        for="des-info-description-input">Description</label>
                                                    <textarea class="form-control" placeholder="Enter Description" id="des-info-description-input" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start gap-3 mt-4">
                                                <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="steparrow-gen-info-tab"><i
                                                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                    Back to General</button>
                                                <button type="button"
                                                    class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                                    data-nexttab="pills-experience-tab"><i
                                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->

                                        <div class="tab-pane fade" id="pills-experience" role="tabpanel">
                                            <div class="text-center">

                                                <div class="avatar-md mt-5 mb-4 mx-auto">
                                                    <div
                                                        class="avatar-title bg-light text-success display-4 rounded-circle">
                                                        <i class="ri-checkbox-circle-fill"></i>
                                                    </div>
                                                </div>
                                                <h5>Well Done !</h5>
                                                <p class="text-muted">You have Successfully Signed Up</p>
                                            </div>
                                        </div>
                                        <!-- end tab pane -->
                                    </div>
                                    <!-- end tab content -->
                                </form>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Vertical nav Steps</h4>
                            </div><!-- end card header -->
                            <div class="card-body form-steps">
                                <form class="vertical-navs-step">
                                    <div class="row gy-5">
                                        <div class="col-lg-3">
                                            <div class="nav flex-column custom-nav nav-pills" role="tablist"
                                                aria-orientation="vertical">
                                                <button class="nav-link done" id="v-pills-bill-info-tab"
                                                    data-bs-toggle="pill" data-bs-target="#v-pills-bill-info"
                                                    type="button" role="tab" aria-controls="v-pills-bill-info"
                                                    aria-selected="true">
                                                    <span class="step-title me-2">
                                                        <i class="ri-close-circle-fill step-icon me-2"></i>
                                                        Step 1
                                                    </span>
                                                    Billing Info
                                                </button>
                                                <button class="nav-link active" id="v-pills-bill-address-tab"
                                                    data-bs-toggle="pill" data-bs-target="#v-pills-bill-address"
                                                    type="button" role="tab" aria-controls="v-pills-bill-address"
                                                    aria-selected="false">
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
                                                    <div class="tab-pane fade" id="v-pills-bill-info" role="tabpanel"
                                                        aria-labelledby="v-pills-bill-info-tab">
                                                        <div>
                                                            <h5>Billing Info</h5>
                                                            <p class="text-muted">Fill all information below
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <div class="row g-3">
                                                                <div class="col-sm-6">
                                                                    <label for="firstName" class="form-label">First
                                                                        name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="firstName" placeholder="Enter First Name"
                                                                        value="">
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label for="lastName" class="form-label">Last
                                                                        name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="lastName" placeholder="Enter Last Name"
                                                                        value="">
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="username"
                                                                        class="form-label">Username</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">@</span>
                                                                        <input type="text" class="form-control"
                                                                            id="username" placeholder="Username">
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="email" class="form-label">Email <span
                                                                            class="text-muted">(Optional)</span></label>
                                                                    <input type="email" class="form-control"
                                                                        id="email" placeholder="Enter Email">
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
                                                    <div class="tab-pane fade show active" id="v-pills-bill-address"
                                                        role="tabpanel" aria-labelledby="v-pills-bill-address-tab">
                                                        <div>
                                                            <h5>Shipping Address</h5>
                                                            <p class="text-muted">Fill all information below
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <label for="address"
                                                                        class="form-label">Address</label>
                                                                    <input type="text" class="form-control"
                                                                        id="address" placeholder="1234 Main St">
                                                                </div>

                                                                <div class="col-12">
                                                                    <label for="address2" class="form-label">Address 2
                                                                        <span class="text-muted">(Optional)</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="address2" placeholder="Apartment or suite">
                                                                </div>

                                                                <div class="col-md-5">
                                                                    <label for="country"
                                                                        class="form-label">Country</label>
                                                                    <select class="form-select" id="country">
                                                                        <option value="">Choose...
                                                                        </option>
                                                                        <option>United States</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label for="state" class="form-label">State</label>
                                                                    <select class="form-select" id="state">
                                                                        <option value="">Choose...
                                                                        </option>
                                                                        <option>California</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="zip" class="form-label">Zip</label>
                                                                    <input type="text" class="form-control"
                                                                        id="zip" placeholder="">
                                                                </div>
                                                            </div>

                                                            <hr class="my-4 text-muted">

                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="same-address">
                                                                <label class="form-check-label"
                                                                    for="same-address">Shipping address is the
                                                                    same as my billing address</label>
                                                            </div>

                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="save-info">
                                                                <label class="form-check-label" for="save-info">Save this
                                                                    information for
                                                                    next time</label>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-start gap-3 mt-4">
                                                            <button type="button"
                                                                class="btn btn-light btn-label previestab"
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

                                                        <div>
                                                            <div class="my-3">
                                                                <div class="form-check form-check-inline">
                                                                    <input id="credit" name="paymentMethod"
                                                                        type="radio" class="form-check-input" checked
                                                                        required>
                                                                    <label class="form-check-label" for="credit">Credit
                                                                        card</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input id="debit" name="paymentMethod"
                                                                        type="radio" class="form-check-input" required>
                                                                    <label class="form-check-label" for="debit">Debit
                                                                        card</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input id="paypal" name="paymentMethod"
                                                                        type="radio" class="form-check-input"
                                                                        required>
                                                                    <label class="form-check-label"
                                                                        for="paypal">PayPal</label>
                                                                </div>
                                                            </div>

                                                            <div class="row gy-3">
                                                                <div class="col-md-12">
                                                                    <label for="cc-name" class="form-label">Name on
                                                                        card</label>
                                                                    <input type="text" class="form-control"
                                                                        id="cc-name" placeholder="" required>
                                                                    <small class="text-muted">Full name as
                                                                        displayed on card</small>
                                                                    <div class="invalid-feedback">
                                                                        Name on card is required
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="cc-number" class="form-label">Credit
                                                                        card
                                                                        number</label>
                                                                    <input type="text" class="form-control"
                                                                        id="cc-number" placeholder="" required>
                                                                    <div class="invalid-feedback">
                                                                        Credit card number is required
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="cc-expiration"
                                                                        class="form-label">Expiration</label>
                                                                    <input type="text" class="form-control"
                                                                        id="cc-expiration" placeholder="" required>
                                                                    <div class="invalid-feedback">
                                                                        Expiration date required
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="cc-cvv" class="form-label">CVV</label>
                                                                    <input type="text" class="form-control"
                                                                        id="cc-cvv" placeholder="" required>
                                                                    <div class="invalid-feedback">
                                                                        Security code required
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-start gap-3 mt-4">
                                                            <button type="button"
                                                                class="btn btn-light btn-label previestab"
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
                                                                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json"
                                                                    trigger="loop"
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


            </div> <!-- end col -->

        </div>
    @endsection
