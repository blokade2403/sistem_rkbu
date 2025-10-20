

<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Sistem Perencanaan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="{{asset("assets/img/favicon/s.ico")}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset("assets/vendor/fonts/materialdesignicons.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/vendor/fonts/fontawesome.css")}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset("assets/vendor/css/rtl/core.css")}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset("assets/vendor/css/rtl/theme-default.css")}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset("assets/css/demo.css")}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset("assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/vendor/libs/node-waves/node-waves.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/vendor/libs/typeahead-js/typeahead.css")}}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset("assets/vendor/libs/formvalidation/dist/css/formValidation.min.css")}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{asset("assets/vendor/css/pages/page-auth.css")}}" />
    <!-- Helpers -->
    <script src="{{asset("assets/vendor/js/helpers.js")}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{asset("assets/vendor/js/template-customizer.js")}}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset("assets/js/config.js")}}"></script>
    
       <!-- Favicon -->
       <link rel="icon" type="image/x-icon" href="{{asset("assets/img/favicon/s.ico")}}" />

       <!-- Fonts -->
       <link rel="preconnect" href="https://fonts.googleapis.com" />
       <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
       <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
       
       <link rel="stylesheet" href="{{asset("assets/vendor/libs/sweetalert2/sweetalert2.css")}}" />
   
       <!-- Icons -->
       <link rel="stylesheet" href="{{asset("assets/vendor/fonts/materialdesignicons.css")}}" />
       <link rel="stylesheet" href="{{asset("assets/vendor/fonts/fontawesome.css")}}" />
   
       <!-- Core CSS -->
       <link rel="stylesheet" href="{{asset("assets/vendor/css/rtl/core.css")}}" class="template-customizer-core-css" />
       <link rel="stylesheet" href="{{asset("assets/vendor/css/rtl/theme-default.css")}}" class="template-customizer-theme-css" />
       <link rel="stylesheet" href="{{asset("assets/css/demo.css")}}" />
   
</head>

<body>
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="col-12 col-lg-6 px-4">
            <div class="card p-2">
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/foto_rs/rsud.png') }}" alt="Logo RSUD" width="245" height="75">
                    </a>
                </div>

                <div class="card-body mt-2">
                    <h4 class="mb-2 fw-semibold text-center">Data Asset Barang 👋</h4>
                    <p class="mb-4 text-center">Form Detail Asset Barang</p>

                    <div class="bs-stepper-content">
                        <div id="checkout-cart" class="content">
                            <div class="list-group bg-lighter rounded p-3 mb-3">
                                <div class="flex-grow-1">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="me-3">
                                                Nama Barang: 
                                                <span class="text-body fa-xl">{{ $asset->nama_asset }}</span>
                                            </h6>
                                            <div class="mb-1 d-flex flex-wrap">
                                                <span class="me-1">Kode Barang:</span>
                                                <span class="me-1">#{{ $asset->kode_asset }}</span>

                                                @if ($asset->kondisi_asset == 'Baik')
                                                    <span class="badge bg-label-success rounded-pill">Baik</span>
                                                @elseif ($asset->kondisi_asset == 'Rusak Ringan')
                                                    <span class="badge bg-label-warning rounded-pill">Rusak Ringan</span>
                                                @else
                                                    <span class="badge bg-label-dark rounded-pill">Rusak Berat</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center mt-1">
                                                <img src="{{ asset('storage/qrcode_assets/' . basename($asset->qrcode_path ?? 'no_image.png')) }}" 
                                                         alt="Asset Image" width="55" height="55">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 mb-3 mb-xl-0">
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item p-2">
                                            <div class="text-center mt-3">
                                                <img src="{{ asset('storage/foto_asset/' . basename($asset->foto ?? 'no_image.png')) }}" 
                                                alt="Asset Image" width="285" height="265">
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="list-group">
                                        <div class="border rounded p-3 mb-3">
                                            <div class="bg-lighter rounded p-3">
                                                <p class="fw-semibold">Spesifikasi Asset:</p>
                                                <p>{{ $asset->spek }}</p>
                                            </div>
                                            <hr class="mx-n3" />

                                            <h6>Price Details</h6>
                                            <dl class="row mb-0">
                                                <dt class="col-6 fw-normal">Harga Asset:</dt>
                                                <dd class="col-6 text-end">Rp. {{ number_format($asset->harga_asset) }}</dd>

                                                <dt class="col-6 fw-normal">Tahun Perolehan:</dt>
                                                <dd class="col-6 text-end">
                                                    <span class="badge bg-info">{{ $asset->tahun_perolehan }}</span>
                                                </dd>

                                                <dt class="col-6 fw-normal">Merk Asset:</dt>
                                                <dd class="col-6 text-end">{{ $asset->merk }}</dd>

                                                <dt class="col-6 fw-normal">Serial Number:</dt>
                                                <dd class="col-6 text-end">{{ $asset->serial_number }}</dd>

                                                <dt class="col-6 fw-normal">Tipe Asset:</dt>
                                                <dd class="col-6 text-success text-end">{{ $asset->type }}</dd>

                                                <dt class="col-6 fw-normal">Satuan Asset:</dt>
                                                <dd class="col-6 text-end">{{ $asset->satuan }}</dd>

                                                <dt class="col-6 fw-normal">Lokasi Penempatan Asset:</dt>
                                                <dd class="col-6 text-end">
                                                    <span class="badge bg-label-success rounded-pill">
                                                        {{ $asset->penempatan->lokasi_barang ?? '-' }}
                                                    </span>
                                                </dd>

                                                <dt class="col-6 fw-normal">Status Asset:</dt>
                                                <dd class="col-6 text-end">{{ $asset->status_asset }}</dd>

                                                <hr />

                                                <dt class="col-6">Tanggal Update Data Asset:</dt>
                                                <dd class="col-6 fw-semibold text-end mb-0">{{ $asset->updated_at }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 mb-3 mb-xl-0">
                                    <div class="list-group">
                                        <div class="border rounded p-3 mb-3">
                                            <p class="fw-semibold">Data KDO:</p>
                                            <hr class="mx-n3" />

                                            <dl class="row mb-0">
                                                <dt class="col-6 fw-normal">Tanggal BPKB:</dt>
                                                <dd class="col-6 text-end">{{ $asset->tgl_bpkb }}</dd>
    
                                                <dt class="col-6 fw-normal">No. Rangka:</dt>
                                                <dd class="col-6 text-success text-end">{{ $asset->no_rangka }}</dd>
    
                                                <dt class="col-6 fw-normal">No. Mesin:</dt>
                                                <dd class="col-6 text-end">{{ $asset->no_mesin }}</dd>
    
                                                <dt class="col-6 fw-normal">No. Polisi:</dt>
                                                <dd class="col-6 text-end">{{ $asset->no_polisi }}</dd>
                                                <hr />
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider my-4">
                        <div class="divider-text">RSUD Cilincing @2023</div>
                    </div>
                </div>
            </div>

            <img alt="mask" src="{{ asset('assets/img/illustrations/auth-basic-login-mask-light.png') }}" 
                 class="authentication-image d-none d-lg-block" />
        </div>
    </div>
</div>

 <!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>

<script src="{{asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

<script src="{{asset('assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
{{-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> --}}
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.j')}}s"></script>
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
<script src="{{asset("assets/vendor/libs/sweetalert2/sweetalert2.js")}}"></script>
<script src="{{asset("assets/vendor/libs/flatpickr/flatpickr.js")}}"></script>
<script src="{{asset("assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js")}}"></script>
 <script src="{{asset("assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js")}}"></script>

<!-- Main JS -->
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="{{asset('assets/js/ui-toasts.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset("assets/vendor/libs/jquery-timepicker/jquery-timepicker.js")}}"></script>
<script src="{{asset("assets/vendor/libs/pickr/pickr.js")}}"></script>
<script src="{{asset("assets/js/cards-analytics.js")}}"></script>
<script src="{{asset("assets/vendor/libs/toastr/toastr.js")}}"></script>


<!-- Page JS -->
<script src="{{asset('assets/js/forms-tagify.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-typeahead.js')}}"></script>
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
<script src="{{asset('assets/js/dashboards-crm.js')}}"></script>
<script src="{{asset("assets/js/extended-ui-sweetalert2.js")}}"></script>
<script src="{{asset("assets/js/forms-pickers.js")}}"></script>
</body>

</html>
