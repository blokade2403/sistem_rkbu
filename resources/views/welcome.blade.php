<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>Dashboard | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    {{-- head.blade.php --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\assets\pages\data-table\css\buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css') }}">


    <!-- jsvectormap css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />



</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt=""
                                        height="17">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/logo-light.png') }}" alt=""
                                        height="17">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <!-- App Search-->
                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                                <div data-simplebar style="max-height: 320px;">
                                    <!-- item-->
                                    <div class="dropdown-header">
                                        <h6 class="text-overflow text-muted mb-0 text-uppercase">Recent Searches</h6>
                                    </div>

                                    <div class="dropdown-item bg-transparent text-wrap">
                                        <a href="index.html" class="btn btn-soft-secondary btn-sm btn-rounded">how to
                                            setup <i class="mdi mdi-magnify ms-1"></i></a>
                                        <a href="index.html" class="btn btn-soft-secondary btn-sm btn-rounded">buttons
                                            <i class="mdi mdi-magnify ms-1"></i></a>
                                    </div>
                                    <!-- item-->
                                    <div class="dropdown-header mt-2">
                                        <h6 class="text-overflow text-muted mb-1 text-uppercase">Pages</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                        <span>Analytics Dashboard</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                        <span>Help Center</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                        <span>My account settings</span>
                                    </a>

                                    <!-- item-->
                                    <div class="dropdown-header mt-2">
                                        <h6 class="text-overflow text-muted mb-2 text-uppercase">Members</h6>
                                    </div>

                                    <div class="notification-list">
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="m-0">Angela Bernier</h6>
                                                    <span class="fs-11 mb-0 text-muted">Manager</span>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/images/users/avatar-3.jpg') }}"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="m-0">David Grasso</h6>
                                                    <span class="fs-11 mb-0 text-muted">Web Designer</span>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- item -->
                                        <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/images/users/avatar-5.jpg') }}"
                                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="m-0">Mike Bunch</h6>
                                                    <span class="fs-11 mb-0 text-muted">React Developer</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="text-center pt-3 pb-1">
                                    <a href="pages-search-results.html" class="btn btn-primary btn-sm">View All
                                        Results <i class="ri-arrow-right-line ms-1"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center">

                        <div class="dropdown d-md-none topbar-head-dropdown header-item">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..."
                                                aria-label="Recipient's username">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-shopping-bag fs-22'></i>
                                <span
                                    class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"
                                aria-labelledby="page-header-cart-dropdown">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold"> My Cart</h6>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-warning fs-13"><span
                                                    class="cartitem-badge">7</span>
                                                items</span>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 300px;">
                                    <div class="p-2">
                                        <div class="text-center empty-cart" id="empty-cart">
                                            <div class="avatar-md mx-auto my-3">
                                                <div class="avatar-title bg-soft-info text-info fs-36 rounded-circle">
                                                    <i class='bx bx-cart'></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-3">Your Cart is Empty!</h5>
                                            <a href="#" class="btn btn-success w-md mb-3">Shop Now</a>
                                        </div>
                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/products/img-1.png') }}"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Branded
                                                            T-Shirts</a>
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        Quantity: <span>10 x $32</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">320</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/products/img-2.png') }}"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Bentwood Chair</a>
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        Quantity: <span>5 x $18</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">89</span>
                                                    </h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/products/img-3.png') }}"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">
                                                            Borosil Paper Cup</a>
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        Quantity: <span>3 x $250</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">750</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/products/img-6.png') }}"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Gray
                                                            Styled T-Shirt</a>
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        Quantity: <span>1 x $1250</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$ <span
                                                            class="cart-item-price">1250</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/products/img-5.png') }}"
                                                    class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                <div class="flex-1">
                                                    <h6 class="mt-0 mb-1 fs-14">
                                                        <a href="apps-ecommerce-product-details.html"
                                                            class="text-reset">Stillbird Helmet</a>
                                                    </h6>
                                                    <p class="mb-0 fs-12 text-muted">
                                                        Quantity: <span>2 x $495</span>
                                                    </p>
                                                </div>
                                                <div class="px-2">
                                                    <h5 class="m-0 fw-normal">$<span
                                                            class="cart-item-price">990</span></h5>
                                                </div>
                                                <div class="ps-2">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i
                                                            class="ri-close-fill fs-16"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border"
                                    id="checkout-elem">
                                    <div class="d-flex justify-content-between align-items-center pb-3">
                                        <h5 class="m-0 text-muted">Total:</h5>
                                        <div class="px-2">
                                            <h5 class="m-0" id="cart-item-total">$1258.58</h5>
                                        </div>
                                    </div>

                                    <a href="apps-ecommerce-checkout.html" class="btn btn-success text-center w-100">
                                        Checkout
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-bell fs-22'></i>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">3<span
                                        class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">

                                <div class="dropdown-head bg-primary bg-pattern rounded-top">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge badge-soft-light fs-13"> 4 New</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-2 pt-2">
                                        <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom"
                                            data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab"
                                                    role="tab" aria-selected="true">
                                                    All (4)
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-bs-toggle="tab" href="#messages-tab"
                                                    role="tab" aria-selected="false">
                                                    Messages
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light">
                                                <a class="nav-link" data-bs-toggle="tab" href="#alerts-tab"
                                                    role="tab" aria-selected="false">
                                                    Alerts
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <div class="tab-content" id="notificationItemsTabContent">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab"
                                        role="tabpanel">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3">
                                                        <span
                                                            class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                            <i class="bx bx-badge-check"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">Your <b>Elite</b> author
                                                                Graphic
                                                                Optimization <span class="text-secondary">reward</span>
                                                                is
                                                                ready!
                                                            </h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> Just 30 sec
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="all-notification-check01">
                                                            <label class="form-check-label"
                                                                for="all-notification-check01"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative active">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">Answered to your comment on the cash flow
                                                                forecast's
                                                                graph 🔔.</p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 48 min
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="all-notification-check02" checked>
                                                            <label class="form-check-label"
                                                                for="all-notification-check02"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3">
                                                        <span
                                                            class="avatar-title bg-soft-danger text-danger rounded-circle fs-16">
                                                            <i class='bx bx-message-square-dots'></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 fs-13 lh-base">You have received <b
                                                                    class="text-success">20</b> new messages in the
                                                                conversation
                                                            </h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 2 hrs
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="all-notification-check03">
                                                            <label class="form-check-label"
                                                                for="all-notification-check03"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-8.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Maureen Gibson</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">We talked about a project on linkedin.
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 4 hrs
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="all-notification-check04">
                                                            <label class="form-check-label"
                                                                for="all-notification-check04"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="my-3 text-center">
                                                <button type="button"
                                                    class="btn btn-soft-success waves-effect waves-light">View
                                                    All Notifications <i
                                                        class="ri-arrow-right-line align-middle"></i></button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel"
                                        aria-labelledby="messages-tab">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-3.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">James Lemire</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">We talked about a project on linkedin.
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 30 min
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="messages-notification-check01">
                                                            <label class="form-check-label"
                                                                for="messages-notification-check01"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">Answered to your comment on the cash flow
                                                                forecast's
                                                                graph 🔔.</p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 2 hrs
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="messages-notification-check02">
                                                            <label class="form-check-label"
                                                                for="messages-notification-check02"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-6.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Kenneth Brown</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">Mentionned you in his comment on 📃
                                                                invoice #12501.
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 10 hrs
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="messages-notification-check03">
                                                            <label class="form-check-label"
                                                                for="messages-notification-check03"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{ asset('assets/images/users/avatar-8.jpg') }}"
                                                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                                    <div class="flex-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Maureen Gibson</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">We talked about a project on linkedin.
                                                            </p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 3 days
                                                                ago</span>
                                                        </p>
                                                    </div>
                                                    <div class="px-2 fs-15">
                                                        <div class="form-check notification-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="messages-notification-check04">
                                                            <label class="form-check-label"
                                                                for="messages-notification-check04"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="my-3 text-center">
                                                <button type="button"
                                                    class="btn btn-soft-success waves-effect waves-light">View
                                                    All Messages <i
                                                        class="ri-arrow-right-line align-middle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade p-4" id="alerts-tab" role="tabpanel"
                                        aria-labelledby="alerts-tab">
                                        <div class="w-25 w-sm-50 pt-3 mx-auto">
                                            <img src="{{ asset('assets/images/svg/bell.svg') }}" class="img-fluid"
                                                alt="user-pic">
                                        </div>
                                        <div class="text-center pb-5 mt-2">
                                            <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Anna
                                            Adame</span>
                                        <span
                                            class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Founder</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome Anna!</h6>
                                <a class="dropdown-item" href="pages-profile.html"><i
                                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Profile</span></a>
                                <a class="dropdown-item" href="pages-profile.html"><i
                                        class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Balance : <b>$5971.67</b></span></a>
                                <a class="dropdown-item" href="pages-profile-settings.html"><span
                                        class="badge bg-soft-success text-success mt-1 float-end">New</span><i
                                        class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Settings</span></a>
                                <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                                        class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle">Lock screen</span></a>
                                <a class="dropdown-item" href="auth-logout-basic.html"><i
                                        class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                        class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="dashboard-analytics.html" class="nav-link" data-key="t-analytics">
                                            Analytics </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="dashboard-crm.html" class="nav-link" data-key="t-crm"> CRM </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="index.html" class="nav-link" data-key="t-ecommerce"> Ecommerce </a>
                                    </li>
                                </ul>
                            </div>
                        </li> <!-- end Dashboard Menu -->
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarApps">
                                <i class="ri-apps-2-line"></i> <span data-key="t-apps">Apps</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarApps">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#sidebarEcommerce" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarEcommerce"
                                            data-key="t-ecommerce"> Ecommerce
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarEcommerce">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="apps-ecommerce-products.html" class="nav-link"
                                                        data-key="t-products"> Products </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-ecommerce-product-details.html" class="nav-link"
                                                        data-key="t-product-Details"> Product Details </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-ecommerce-add-product.html" class="nav-link"
                                                        data-key="t-create-product"> Create Product </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-ecommerce-orders.html" class="nav-link"
                                                        data-key="t-orders"> Orders </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-ecommerce-order-details.html" class="nav-link"
                                                        data-key="t-order-details"> Order Details </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sidebarProjects" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarProjects"
                                            data-key="t-projects"> Projects
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarProjects">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="apps-projects-list.html" class="nav-link"
                                                        data-key="t-list"> List </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-projects-overview.html" class="nav-link"
                                                        data-key="t-overview"> Overview </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-projects-create.html" class="nav-link"
                                                        data-key="t-create-project"> Create Project </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sidebarTasks" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarTasks"
                                            data-key="t-tasks"> Tasks
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarTasks">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="apps-tasks-kanban.html" class="nav-link"
                                                        data-key="t-kanbanboard"> Kanban Board </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-tasks-list-view.html" class="nav-link"
                                                        data-key="t-list-view"> List View </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-tasks-details.html" class="nav-link"
                                                        data-key="t-task-details"> Task Details </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sidebarCRM" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarCRM"
                                            data-key="t-crm"> CRM
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarCRM">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="apps-crm-contacts.html" class="nav-link"
                                                        data-key="t-contacts"> Contacts </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="apps-crm-companies.html" class="nav-link"
                                                        data-key="t-companies"> Companies </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Layouts</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarLayouts">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="layouts-horizontal.html" target="_blank" class="nav-link"
                                            data-key="t-horizontal">Horizontal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="layouts-detached.html" target="_blank" class="nav-link"
                                            data-key="t-detached">Detached</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="layouts-two-column.html" target="_blank" class="nav-link"
                                            data-key="t-two-column">Two Column</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="layouts-vertical-hovered.html" target="_blank" class="nav-link"
                                            data-key="t-hovered">Hovered</a>
                                    </li>
                                </ul>
                            </div>
                        </li> <!-- end Dashboard Menu -->

                        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarAuth">
                                <i class="ri-account-circle-line"></i> <span
                                    data-key="t-authentication">Authentication</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarAuth">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarSignIn"
                                            data-key="t-signin"> Sign In
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarSignIn">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="auth-signin-basic.html" class="nav-link"
                                                        data-key="t-basic"> Basic </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="auth-signin-cover.html" class="nav-link"
                                                        data-key="t-cover"> Cover </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarSignUp"
                                            data-key="t-signup"> Sign Up
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarSignUp">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="auth-signup-basic.html" class="nav-link"
                                                        data-key="t-basic"> Basic </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="auth-signup-cover.html" class="nav-link"
                                                        data-key="t-cover"> Cover </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarPages">
                                <i class="ri-pages-line"></i> <span data-key="t-pages">Pages</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarPages">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="pages-starter.html" class="nav-link" data-key="t-starter">
                                            Starter </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#sidebarProfile" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarProfile"
                                            data-key="t-profile"> Profile
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarProfile">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="pages-profile.html" class="nav-link"
                                                        data-key="t-simple-page"> Simple Page </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="pages-profile-settings.html" class="nav-link"
                                                        data-key="t-settings"> Settings </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages-team.html" class="nav-link" data-key="t-team"> Team </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages-search-results.html" class="nav-link"
                                            data-key="t-search-results"> Search Results </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" target="_blank" href="landing.html">
                                <i class="ri-rocket-line"></i> <span data-key="t-landing">Landing</span>
                                <span class="badge badge-pill bg-danger" data-key="t-new">New</span>
                            </a>
                        </li>

                        <li class="menu-title"><i class="ri-more-fill"></i> <span
                                data-key="t-components">Components</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarUI" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="sidebarUI">
                                <i class="ri-pencil-ruler-2-line"></i> <span data-key="t-base-ui">Base UI</span>
                            </a>
                            <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="ui-alerts.html" class="nav-link"
                                                    data-key="t-alerts">Alerts</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ui-badges.html" class="nav-link"
                                                    data-key="t-badges">Badges</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ui-buttons.html" class="nav-link"
                                                    data-key="t-buttons">Buttons</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="ui-images.html" class="nav-link"
                                                    data-key="t-images">Images</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ui-tabs.html" class="nav-link" data-key="t-tabs">Tabs</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ui-accordions.html" class="nav-link"
                                                    data-key="t-accordion-collapse">Accordion & Collapse</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="ui-modals.html" class="nav-link"
                                                    data-key="t-modals">Modals</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                                <i class="ri-stack-line"></i> <span data-key="t-advance-ui">Advance UI</span>
                                <span class="badge badge-pill bg-success" data-key="t-new">New</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="advance-ui-sweetalerts.html" class="nav-link"
                                            data-key="t-sweet-alerts">Sweet Alerts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="advance-ui-nestable.html" class="nav-link"
                                            data-key="t-nestable-list">Nestable List</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="widgets.html">
                                <i class="ri-honour-line"></i> <span data-key="t-widgets">Widgets</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarForms">
                                <i class="ri-file-list-3-line"></i> <span data-key="t-forms">Forms</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarForms">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="forms-elements.html" class="nav-link"
                                            data-key="t-basic-elements">Basic Elements</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-select.html" class="nav-link" data-key="t-form-select">
                                            Form Select </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-checkboxs-radios.html" class="nav-link"
                                            data-key="t-checkboxs-radios">Checkboxs & Radios</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-pickers.html" class="nav-link" data-key="t-pickers">
                                            Pickers </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-masks.html" class="nav-link" data-key="t-input-masks">Input
                                            Masks</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-advanced.html" class="nav-link"
                                            data-key="t-advanced">Advanced</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-range-sliders.html" class="nav-link"
                                            data-key="t-range-slider"> Range Slider </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-validation.html" class="nav-link"
                                            data-key="t-validation">Validation</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-wizard.html" class="nav-link" data-key="t-wizard">Wizard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-editors.html" class="nav-link"
                                            data-key="t-editors">Editors</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-file-uploads.html" class="nav-link"
                                            data-key="t-file-uploads">File Uploads</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="forms-layouts.html" class="nav-link"
                                            data-key="t-form-layouts">Form
                                            Layouts</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarCharts">
                                <i class="ri-pie-chart-line"></i> <span data-key="t-charts">Charts</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarCharts">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="#sidebarApexcharts" class="nav-link" data-bs-toggle="collapse"
                                            role="button" aria-expanded="false" aria-controls="sidebarApexcharts"
                                            data-key="t-apexcharts"> Apexcharts
                                        </a>
                                        <div class="collapse menu-dropdown" id="sidebarApexcharts">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item">
                                                    <a href="charts-apex-line.html" class="nav-link"
                                                        data-key="t-line"> Line </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-area.html" class="nav-link"
                                                        data-key="t-area"> Area </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-column.html" class="nav-link"
                                                        data-key="t-column"> Column </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-bar.html" class="nav-link"
                                                        data-key="t-bar">
                                                        Bar </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-mixed.html" class="nav-link"
                                                        data-key="t-mixed"> Mixed </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-timeline.html" class="nav-link"
                                                        data-key="t-timeline"> Timeline </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-candlestick.html" class="nav-link"
                                                        data-key="t-candlstick"> Candlstick </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-boxplot.html" class="nav-link"
                                                        data-key="t-boxplot"> Boxplot </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-bubble.html" class="nav-link"
                                                        data-key="t-bubble"> Bubble </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-scatter.html" class="nav-link"
                                                        data-key="t-scatter"> Scatter </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-heatmap.html" class="nav-link"
                                                        data-key="t-heatmap"> Heatmap </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-treemap.html" class="nav-link"
                                                        data-key="t-treemap"> Treemap </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-pie.html" class="nav-link"
                                                        data-key="t-pie">
                                                        Pie </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-radialbar.html" class="nav-link"
                                                        data-key="t-radialbar"> Radialbar </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-radar.html" class="nav-link"
                                                        data-key="t-radar"> Radar </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="charts-apex-polar.html" class="nav-link"
                                                        data-key="t-polar-area"> Polar Area </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="charts-chartjs.html" class="nav-link" data-key="t-chartjs">
                                            Chartjs </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="charts-echarts.html" class="nav-link" data-key="t-echarts">
                                            Echarts </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarIcons">
                                <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Icons</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarIcons">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="icons-remix.html" class="nav-link" data-key="t-remix">Remix</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="icons-boxicons.html" class="nav-link"
                                            data-key="t-boxicons">Boxicons</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="icons-materialdesign.html" class="nav-link"
                                            data-key="t-material-design">Material Design</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="icons-lineawesome.html" class="nav-link"
                                            data-key="t-line-awesome">Line Awesome</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="icons-feather.html" class="nav-link"
                                            data-key="t-feather">Feather</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a>
                                        </li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

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
                                                                <div
                                                                    class="input-group-text bg-primary border-primary text-white">
                                                                    <i class="ri-calendar-2-line"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-auto">
                                                            <button type="button"
                                                                class="btn btn-soft-success shadow-none"><i
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
                                                        <p
                                                            class="text-uppercase fw-medium text-muted text-truncate mb-0">
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
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span
                                                                class="counter-value" data-target="559.25">0</span>k
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
                                                        <p
                                                            class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                            Orders</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <h5 class="text-danger fs-14 mb-0">
                                                            <i
                                                                class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                                            -3.57 %
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div>
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                                class="counter-value" data-target="36894">0</span>
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
                                                        <p
                                                            class="text-uppercase fw-medium text-muted text-truncate mb-0">
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
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                                                class="counter-value" data-target="183.35">0</span>M
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
                                                        <p
                                                            class="text-uppercase fw-medium text-muted text-truncate mb-0">
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
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span
                                                                class="counter-value" data-target="165.89">0</span>k
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
                                                    <button type="button"
                                                        class="btn btn-soft-secondary btn-sm shadow-none">
                                                        ALL
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-soft-secondary btn-sm shadow-none">
                                                        1M
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-soft-secondary btn-sm shadow-none">
                                                        6M
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-soft-primary btn-sm shadow-none">
                                                        1Y
                                                    </button>
                                                </div>
                                            </div><!-- end card header -->

                                            <div class="card-header p-0 border-0 bg-soft-light">
                                                <div class="row g-0 text-center">
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="7585">0</span></h5>
                                                            <p class="text-muted mb-0">Orders</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1">$<span class="counter-value"
                                                                    data-target="22.89">0</span>k</h5>
                                                            <p class="text-muted mb-0">Earnings</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="367">0</span></h5>
                                                            <p class="text-muted mb-0">Refunds</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div
                                                            class="p-3 border border-dashed border-start-0 border-end-0">
                                                            <h5 class="mb-1 text-success"><span
                                                                    class="counter-value"
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
                                                        data-colors='["--vz-success", "--vz-primary", "--vz-danger"]'
                                                        class="apex-charts" dir="ltr"></div>
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
                                                    <button type="button"
                                                        class="btn btn-soft-primary btn-sm shadow-none">
                                                        Export Report
                                                    </button>
                                                </div>
                                            </div><!-- end card header -->

                                            <!-- card body -->
                                            <div class="card-body">

                                                <div id="sales-by-locations"
                                                    data-colors='["--vz-light", "--vz-success", "--vz-primary"]'
                                                    style="height: 269px" dir="ltr"></div>

                                                <div class="px-2 py-2 mt-1">
                                                    <p class="mb-1">Canada <span class="float-end">75%</span></p>
                                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                                        <div class="progress-bar progress-bar-striped bg-primary"
                                                            role="progressbar" style="width: 75%"
                                                            aria-valuenow="75" aria-valuemin="0"
                                                            aria-valuemax="75">
                                                        </div>
                                                    </div>

                                                    <p class="mt-3 mb-1">Greenland <span
                                                            class="float-end">47%</span>
                                                    </p>
                                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                                        <div class="progress-bar progress-bar-striped bg-primary"
                                                            role="progressbar" style="width: 47%"
                                                            aria-valuenow="47" aria-valuemin="0"
                                                            aria-valuemax="47">
                                                        </div>
                                                    </div>

                                                    <p class="mt-3 mb-1">Russia <span class="float-end">82%</span>
                                                    </p>
                                                    <div class="progress bg-soft-primary mt-2" style="height: 6px;">
                                                        <div class="progress-bar progress-bar-striped bg-primary"
                                                            role="progressbar" style="width: 82%"
                                                            aria-valuenow="82" aria-valuemin="0"
                                                            aria-valuemax="82">
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
                                                        <a class="text-reset dropdown-btn" href="#"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
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
                                                    <table
                                                        class="table table-hover table-centered align-middle table-nowrap mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div
                                                                            class="avatar-sm bg-light rounded p-1 me-2">
                                                                            <img src="{{ asset('assets/images/products/img-1.png') }}"
                                                                                alt=""
                                                                                class="img-fluid d-block" />
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

                                                <div
                                                    class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="text-muted">Showing <span
                                                                class="fw-semibold">5</span> of <span
                                                                class="fw-semibold">25</span> Results
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
                                                        <a class="text-reset dropdown-btn" href="#"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
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
                                                    <table
                                                        class="table table-centered table-hover align-middle table-nowrap mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <img src="{{ asset('assets/images/companies/img-1.png') }}"
                                                                                alt=""
                                                                                class="avatar-sm p-2" />
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

                                                <div
                                                    class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="text-muted">Showing <span
                                                                class="fw-semibold">5</span> of <span
                                                                class="fw-semibold">25</span> Results
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
                                                    <button type="button"
                                                        class="btn btn-soft-info btn-sm shadow-none">
                                                        <i class="ri-file-list-3-line align-middle"></i> Generate
                                                        Report
                                                    </button>
                                                </div>
                                            </div><!-- end card header -->
                                            <div class="card-block">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="responsive-reorder"
                                                            class="table table-striped table-bordered wrap">
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
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
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
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-soft-warning">Pending</span>
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
                                                                        default single select.</p>
                                                                    <select class="form-control" data-choices
                                                                        name="choices-single-default"
                                                                        id="choices-single-default">
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
                                                                    <select class="form-control"
                                                                        id="choices-single-groups" data-choices
                                                                        data-choices-groups
                                                                        data-placeholder="Select City"
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
                                                                    <select class="form-control"
                                                                        id="choices-single-no-search"
                                                                        name="choices-single-no-search" data-choices
                                                                        data-choices-search-false
                                                                        data-choices-removeItem>
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
                                                                    <select class="form-control"
                                                                        id="choices-single-no-sorting"
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
                                                                    <select class="form-control"
                                                                        id="choices-multiple-default" data-choices
                                                                        name="choices-multiple-default" multiple>
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
                                                                    <select class="form-control"
                                                                        id="choices-multiple-remove-button"
                                                                        data-choices data-choices-removeItem
                                                                        name="choices-multiple-remove-button"
                                                                        multiple>
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
                                                                    <select class="form-control"
                                                                        id="choices-multiple-groups"
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
                                                                    <input class="form-control"
                                                                        id="choices-text-remove-button" data-choices
                                                                        data-choices-limit="3" data-choices-removeItem
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
                                                                    <input class="form-control"
                                                                        id="choices-text-unique-values" data-choices
                                                                        data-choices-text-unique-true type="text"
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
                                                            <input class="form-control" id="choices-text-disabled"
                                                                data-choices data-choices-text-disabled-true
                                                                type="text"
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

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Velzon.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('libraries\assets\pages\data-table\extensions\row-reorder\js\dataTables.rowReorder.min.js') }}">
    </script>
    <script src="{{ asset('libraries\bower_components\datatables.net\js\jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libraries\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libraries\assets\pages\data-table\js\jszip.min.js') }}"></script>
    <script src="{{ asset('libraries\assets\pages\data-table\js\pdfmake.min.js') }}"></script>
    <script src="{{ asset('libraries\assets\pages\data-table\js\vfs_fonts.js') }}"></script>
    <script src="{{ asset('libraries\assets\pages\data-table\extensions\responsive\js\dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('libraries\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libraries\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('libraries\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('libraries\bower_components\jquery-i18next\js\jquery-i18next.min.js') }}">
    </script>
    <!-- Custom js -->
    <script src="{{ asset('libraries\assets\pages\data-table\extensions\row-reorder\js\row-reorder-custom.js') }}">
    </script>


    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector map-->
    <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
