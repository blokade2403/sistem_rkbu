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
            </div> <!-- end .h-100-->

        </div> <!-- end col -->

    </div>
@endsection
