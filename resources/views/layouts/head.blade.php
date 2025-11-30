<head>

    <meta charset="utf-8" />
    <title>Rencana Kebutuhan Barang Unit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">


    {{-- head.blade.php --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\assets\pages\data-table\css\buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('libraries\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/themes/bars-movie.min.css">

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
    <style>
        /* Untuk warna merah */
        .br-theme-bars-movie .br-widget a.br-active,
        .br-theme-bars-movie .br-widget a.br-selected {
            background-color: rgb(2, 110, 29) !important;
        }
    </style>

    <style>
        /* ====== TABLE MODERN STYLE ====== */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            font-size: 14px;
        }

        /* HEADER UTAMA */
        .table-modern thead.thead-primary th {
            background: #1e88e5 !important;
            color: white;
            font-weight: 600;
            border: none !important;
            font-size: 14px;
            padding: 12px;
        }

        /* LEVEL 1 – JENIS BELANJA */
        .table-modern thead.level-1 th {
            background: #e3f2fd !important;
            color: #0d47a1;
            font-size: 15px;
            font-weight: 700;
            border-top: 2px solid #bbdefb;
            padding: 10px 15px;
        }

        /* LEVEL 2 – JENIS KATEGORI */
        .table-modern thead.level-2 th {
            background: #f1f8ff !important;
            color: #1565c0;
            font-weight: 600;
            font-size: 14px;
        }

        /* LEVEL 3 – KATEGORI RKBU */
        .table-modern thead.level-3 th {
            background: #fafafa !important;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        /* LEVEL 4 – SUB KATEGORI */
        .table-modern thead.level-4 th {
            background: #ffffff !important;
            color: #555;
            font-weight: 500;
        }

        .table-modern td,
        .table-modern th {
            padding: 10px 12px !important;
            border-bottom: 1px solid #e0e0e0 !important;
        }

        .table-modern tbody tr:hover td {
            background: #f6faff !important;
        }

        .table-modern tfoot th {
            background: #0d47a1 !important;
            color: white !important;
            font-weight: 700 !important;
            border-top: 2px solid #002171 !important;
            padding: 12px;
        }

        .table-modern small {
            font-size: 12px;
            opacity: .85;
        }

        .text-right {
            text-align: right !important;
        }

        .modern-table {
            border-collapse: separate !important;
            border-spacing: 0 6px !important;
        }

        .modern-table thead th {
            background: #0d6efd;
            color: white !important;
            border: none;
            padding: 14px;
            font-weight: 600;
        }

        .modern-table tbody tr {
            background: #ffffff;
            transition: 0.2s;
        }

        .modern-table tbody tr:hover {
            background: #f3f7ff;
            transform: scale(1.01);
        }

        .modern-table td {
            padding: 12px 14px;
            vertical-align: middle;
            border-top: 1px solid #dee2e6 !important;
        }

        .modern-table .table-info {
            background: #e8f4ff !important;
            font-weight: 600;
        }

        .modern-table .table-light {
            background: #f9fafb !important;
        }

        .kegiatan-row td {
            padding-left: 26px !important;
            font-weight: 500;
        }

        .sub-row td {
            padding-left: 42px !important;
            font-size: 14px;
        }

        .white-nowrap {
            white-space: nowrap !important;
        }
    </style>




</head>
