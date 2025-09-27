<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rencana Bisnis Anggaran (RBA)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    {{-- <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet"> --}}


    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-analytics.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/datatable/jQuery-3.7.0/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('assets/datatable/DataTables-1.13.6/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

</head>

<body>
    @php
        // Fungsi CheckPageBreak() eksternal untuk memeriksa margin halaman
        function checkPageBreak($pdf, $height)
        {
            // Mendapatkan posisi saat ini di halaman
            $currentY = $pdf->GetY();
            // Mendapatkan tinggi halaman
            $pageHeight = $pdf->getPageHeight();
            // Mendapatkan margin bawah
            $breakMargin = $pdf->getBreakMargin();

            // Jika posisi saat ini + tinggi konten lebih besar dari batas bawah, tambahkan halaman baru
            if ($currentY + $height > $pageHeight - $breakMargin) {
                $pdf->AddPage();
            }
        }
    @endphp
    <div class="container">
        <!-- Header Section -->

        <table cellspacing="0" cellpadding="1">
            <tr bgcolor="#ffffff">
                <th rowspan="2" width="100%" align="center" style="white-space: normal; line-height: 0;">
                    <h2>USULAN BARANG DAN JASA</h2>
                </th>
            </tr>
        </table>

        <table cellspacing="0" bgcolor="#666666" cellpadding="1">
            <tr bgcolor="#ffffff">
                <th rowspan="1" width="100%" align="center"></th>
            </tr>
        </table>


        <!-- Informasi Usulan -->
        <table lines="none" bgcolor="#666666">
            <tr bgcolor="#ffffff">
                <th width="13%" align="left" style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    Nomor</th>
                <th width="2%" align="center" style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    :</th>
                <th width="20%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    <b>{{ $no_inv->no_usulan_barang }}</b>
                </th>
                <th width="30%" align="center"></th>
            </tr>
        </table>
        </br>


        <table lines="none">
            <tr bgcolor="#ffffff" style="line-height: 15px;">
                <th width="13%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Program</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">{{ $no_inv->kode_program }}.
                    {{ $no_inv->nama_program }}</th>
                <th width="10%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;"></th>
            </tr>
            <tr bgcolor="#ffffff">
                <th width="13%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Kegiatan</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->kode_kegiatan }}. {{ $no_inv->nama_kegiatan }}</th>
                <th width="5%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;"></th>
                <th width="15%" align="right"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Pengusul</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="20%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->nama_pengusul_barang }}</th>
            </tr>
            <tr bgcolor="#ffffff" style="line-height: 15px;">
                <th width="13%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Rekening Belanja</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->kode_rekening_belanja }}. {{ $no_inv->nama_rekening_belanja }}</th>
                <th width="5%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;"></th>
                <th width="15%" align="right"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Unit</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="20%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">{{ $no_inv->unit }}</th>
            </tr>
            <tr bgcolor="#ffffff">
                <th width="13%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Kategori</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->kode_kategori_rkbu }}. {{ $no_inv->nama_kategori_rkbu }}</th>
                <th width="5%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;"></th>

            </tr>
            <tr bgcolor="#ffffff" style="line-height: 15px;">
                <th width="13%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Sub Kategori</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->kode_sub_kategori_rkbu }}. {{ $no_inv->nama_sub_kategori_rkbu }}</th>
                <th width="5%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;"></th>
                <th width="15%" align="right"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">Sumber Dana</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">:</th>
                <th width="20%" align="left"
                    style="word-wrap: break-word; white-space: normal; line-height: 1.5;">
                    {{ $no_inv->nama_sumber_dana }}</th>
            </tr>
            <tr bgcolor="#ffffff" style="line-height: 15px;">
                <th width="13%" align="left"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">Anggaran
                </th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">:</th>
                <th width="40%" align="left"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">
                    {{ number_format($jumlahAnggaran->total_anggaran, 0, ',', '.') }}</th>
                <th width="5%" align="center"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;"></th>
                <th width="15%" align="right"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">Sisa
                    Anggaran</th>
                <th width="2%" align="center"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">:</th>
                <th width="20%" align="left"
                    style="word-wrap: break-word; font-weight: bold; white-space: normal; line-height: 1.5;">
                    {{ number_format($jumlahAnggaran->sisa_anggaran, 0, ',', '.') }}</th>
            </tr>

            <tr bgcolor="#ffffff" style="line-height: 15px;">
                <th></th>
            </tr>

            <table cellspacing="0.5" bgcolor="#666666" cellpadding="2" style="font-size: 6px;">
                <thead>
                    <tr bgcolor="#ffffff">
                        <th rowspan="2" width="4%" align="center">No</th>
                        <th rowspan="2" width="16%" align="center">Nama / Jenis Barang</th>
                        <th rowspan="2" width="10%" align="center">Spesifikasi Merk/Ukuran </th>
                        <th rowspan="2" width="9%" align="center">Harga Satuan</th>
                        <th colspan="5" width="26%" align="center">Uraian Koefisien</th>
                        <th rowspan="2" width="11%" align="center">Jumlah Biaya</th>
                        <th rowspan="2" width="5%" align="center">Sisa Vol</th>
                        <th rowspan="2" width="12%" align="center">Sisa Anggaran</th>
                        <th rowspan="2" width="6%" align="center">Riwayat Usulan</th>
                    </tr>
                    <tr bgcolor="#ffffff" style="font-size: 5px;">
                        <th width="6%" align="center">Rata2 Pemakaian</th>
                        <th width="5%" align="center">Stok Minimal</th>
                        <th width="5%" align="center">Buffer</th>
                        <th width="5%" align="center">Sisa Stok</th>
                        <th width="5%" align="center">Total Usulan</th>
                    </tr>
                </thead>

                <tr bgcolor="#ffffff">
                    <th width="4%" align="center">1</th>
                    <th width="16%" align="center">2</th>
                    <th width="10%" align="center">3</th>
                    <th width="9%" align="center">4</th>
                    <th width="6%" align="center">5</th>
                    <th width="5%" align="center">6</th>
                    <th width="5%" align="center">7</th>
                    <th width="5%" align="center">8</th>
                    <th width="5%" align="center">9</th>
                    <th width="11%" align="center">10=(4x9)</th>
                    <th width="5%" align="center">11</th>
                    <th width="12%" align="center">12</th>
                    <th width="6%" align="center">13</th>
                </tr>
                @php
                    $no = 1;
                @endphp

                @foreach ($keranjang->sortBy('created_at') as $item)
                    @php
                        $idRkbu = $item->id_rkbu;
                        $jumlah = $item->jumlah_usulan_barang ?? 0;

                        // Hitung riwayat: semua usulan_barang_details dengan id_rkbu sama dan created_at < item saat ini
                        $riwayat = $riwayatData
                            ->where('id_rkbu', $idRkbu)
                            ->where('created_at', '<', $item->created_at)
                            ->sum('jumlah_usulan_barang');
                        $total_anggaran_usulan_barang_non_ppn = $item->total_anggaran_usulan_barang - $item->total_ppn;
                        $total_anggaran_usulan_barang = $total_anggaran_usulan_barang_non_ppn + $item->total_ppn;
                    @endphp
                    <tr bgcolor="#ffffff">
                        <th width="4%" align="center">{{ $no++ }}</th>
                        <th width="16%" align="center">{{ $item->nama_barang }}</th>
                        <th width="10%" align="center">{{ $item->spek_detail }}</th>
                        <th width="9%" align="center">{{ number_format($item->harga_barang, 0, ',', '.') }}</th>
                        <th width="6%" align="center">{{ $item->rata2_pemakaian }}</th>
                        <th width="5%" align="center">{{ $item->stok_minimal }}</th>
                        <th width="5%" align="center">{{ $item->buffer_stok }}</th>
                        <th width="5%" align="center">{{ $item->sisa_stok }}</th>
                        <th width="5%" align="center">{{ $item->jumlah_usulan_barang }}
                            {{ $item->satuan_1_detail }}</th>
                        <th width="11%" align="center">
                            {{ number_format($total_anggaran_usulan_barang_non_ppn, 0, ',', '.') }}</th>
                        <th width="5%" align="center">{{ $item->sisa_vol_rkbu }}</th>
                        <th width="12%" align="center">{{ number_format($item->sisa_anggaran_rkbu, 0, ',', '.') }}
                        </th>
                        <th width="6%" align="center">{{ $riwayat }} {{ $item->satuan_1_detail }}</th>
                    </tr>
                @endforeach
            </table>
    </div>

</body>

</html>

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->
{{-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> --}}
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.j') }}s"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
