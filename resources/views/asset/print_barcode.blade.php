<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Sistem Perencanaan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/s.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/s.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />


    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <style>
        .qr-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
        }

        .qr-item {
            width: calc(20% - 10px);
            /* 5 kolom, perbaiki gap */
            text-align: center;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* bayangan lebih smooth */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 198px;
            /* disesuaikan agar tidak terlalu sempit */
            overflow: hidden;
            /* penting untuk memastikan bayangan tidak terpotong */
        }

        .qr-item img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            padding: 2px;
            background: #fff;
            border-radius: 8px;
        }

        .qr-item small {
            margin-top: 4px;
            font-size: 9px;
            max-height: 32px;
            /* Batasi tinggi teks */
            overflow: hidden;
            text-overflow: ellipsis;

        }
    </style>
</head>

<body>
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="col-12 col-lg-12 px-1">
                <div class="card p-1">
                    <div class="app-brand justify-content-center mt-1">
                        @if ($header && $header->gambar1)
                            <a href="">
                                <img src="{{ asset('storage/uploads/' . basename($header->gambar1)) }}" alt="Gambar 1"
                                    width="145" height="45" class="img-thumbnail">
                            </a>
                        @endif
                    </div>
                    <div class="card-body mt-0">
                        <h4 class="mb-2 fw-semibold text-center">Data Barcode Asset Barang</h4>
                        <p class="mb-4 text-center">Form Detail Asset Barang</p>

                        <form method="GET" action="{{ route('barang_assets.cetakQRCode') }}"
                            class="mb-3 text-center">
                            <div class="row mb-3 ">
                                <div class="col-md-5 mb-3">
                                    <label for="tahun" class="fw-semibold me-2">Filter Tahun Perolehan:</label>
                                    <select name="tahun_perolehan" id="tahun"
                                        class="select2 form-select form-select-lg">
                                        <option value="">-- Semua Tahun --</option>
                                        @foreach ($daftarTahun as $thn)
                                            <option value="{{ $thn }}"
                                                {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label for="id_penempatan" class="fw-semibold me-2">Penempatan:</label>
                                    <select name="id_penempatan" id="id_penempatan"
                                        class="select form-select form-select-lg">
                                        <option value="">-- Semua Penempatan --</option>
                                        @foreach ($daftarPenempatan as $penempatan)
                                            <option value="{{ $penempatan->id_penempatan }}"
                                                {{ request('id_penempatan') == $penempatan->id_penempatan ? 'selected' : '' }}>
                                                {{ $penempatan->lokasi_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end ">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary ms-2">Reset</a>
                                </div>
                            </div>
                        </form>
                        <div class="bs-stepper-content">
                            <div id="checkout-cart" class="content">
                                <div class="list-group bg-lighter rounded p-3 mb-3">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="qr-container">
                                                @foreach ($asset as $item)
                                                    <div class="qr-item">
                                                        <img src="{{ asset('storage/qrcode_assets/' . basename($item->qrcode_path)) }}"
                                                            alt="QR Code">
                                                        <div class="mt-2">
                                                            <small
                                                                class="fw-bold">{{ $item->nama_asset ?? 'Nama Barang' }}</small></br>
                                                            <small
                                                                class="fw-bold">{{ $item->tahun_perolehan ?? ' ' }}</small></br>
                                                            <small>{{ $item->kode_asset ?? 'Kode Barang' }}
                                                                Reg.{{ $item->no_register ?? 'Kode Barang' }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider my-4">
                            <div class="divider-text">Asset @2025</div>
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
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    </html>
