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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            margin: 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            vertical-align: top;
        }

        .total-anggaran {
            text-align: right;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
            padding: 3px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 6px;
            border: 0.2 px solid black;
        }

        .table-data td center {
            font-size: 20px;
        }

        .table-data th {
            text-align: left;
            /* Posisi default tetap di kiri */
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
            font-size: 4px;
        }

        .sub-header {
            font-weight: bold;
            background-color: #f2f2f2;
            text-align: left;
        }

        .sub-header-child {
            font-weight: bold;
            background-color: #ffffff;
            text-align: left;
        }

        .sub-header-parent {
            background-color: #ffffff;
            text-align: left;
        }

        .spek {
            font-size: 6px;
            /* Kamu bisa menyesuaikan ukuran font */
            background-color: #ffffff;
            font-style: italic;
            /* Menjadikan teks miring */
        }

        /* Footer Styling */
        footer {
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding: 10px 0;
            background-color: #f2f2f2;
        }

        .page-number:after {
            content: counter(page);
        }

        @media print {
            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                font-size: 6px;
                border-top: 1px solid #ccc;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header Section -->
        <div class="info">
            <table border="0" cellspacing="1" cellpadding="1" width="100%">
                <tr>
                    <th width="15%" align="left">Program</th>
                    <td width="80%" align="left">: {{ $kodeProgram }}. {{ $namaProgram }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeKegiatan }}. {{ $namaKegiatan }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Sub Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeSubKegiatan }}. {{ $namaSubKegiatan }}</td>
                </tr>
                <tr>
                    <td colspan="2" height="10"></td> <!-- Baris ini memberikan jarak kosong -->
                </tr>
                <tr>
                    <th width="15%" align="left">Alokasi Anggaran</th>
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">Rp. {{ number_format($total_anggaran) }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left"></th>
                    <td width="1%" align="left"> </td>
                    <td width="79%" align="left">( {{ $total_anggaran_terbilang }} )</td>
                </tr>
            </table>
        </div>
        <table class="table-data">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" width="4%" align="center">No</th>
                    <th rowspan="2" width="15%" align="center">Nama / Jenis Barang</th>
                    <th rowspan="2" width="5%" align="center">Satuan</th>
                    <th rowspan="2" width="6%" align="center">Sisa Stok Desember {{ $angka_tahun - 2 }}</th>
                    <th rowspan="2" width="6%" align="center">Rata2 Pemakaian per Bulan {{ $angka_tahun - 1 }}
                    </th>
                    <th rowspan="2" width="6%" align="center">Pengadaan Tahun {{ $angka_tahun - 1 }}</th>
                    <th colspan="3" width="18%" align="center">Uraian Koefisien</th>
                    <th rowspan="2" width="4%" align="center">Buffer</th>
                    <th rowspan="2" width="6%" align="center">Rencana Pengadaan {{ $angka_tahun }}</th>
                    <th rowspan="2" width="7%" align="center">Harga Satuan</th>
                    <th rowspan="2" width="6%" align="center">Pajak</th>
                    <th rowspan="2" width="15%" align="center">Jumlah</th>
                </tr>
                <tr>
                    <th width="6%" align="center">Prediksi Sisa Stok {{ $angka_tahun }} </th>
                    <th width="6%" align="center">Kebutuhan Tahun {{ $angka_tahun }}</th>
                    <th width="6%" align="center">Perkiraan Kebutuhan {{ $angka_tahun }}</th>
                </tr>
                <tr class="text-center">
                    <th width="4%" align="center">1</th>
                    <th width="15%" align="center">2</th>
                    <th width="5%" align="center">3</th>
                    <th width="6%" align="center">4</th>
                    <th width="6%" align="center">5</th>
                    <th width="6%" align="center">6</th>
                    <th width="6%" align="center">7=(4+6)-(5x12bln)</th>
                    <th width="6%" align="center">8=(5x12bln)</th>
                    <th width="6%" align="center">9=(8+(5x10))</th>
                    <th width="4%" align="center">10</th>
                    <th width="6%" align="center">11=(9-7)</th>
                    <th width="7%" align="center">12</th>
                    <th width="6%" align="center">13</th>
                    <th width="15%" align="center">14=(11x12)+(13)</th>
                </tr>
            </thead>
            <tbody>
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

                <!-- Looping Hierarki -->
                @foreach ($categories->unique('kode_jenis_belanja') as $category)
                    <tr class="sub-header">
                        <td width="98%">{{ $category->kode_jenis_belanja }}. {{ $category->nama_jenis_belanja }}
                        </td>
                    </tr>

                    @php
                        $jenisKategoris = $categories
                            ->where('kode_jenis_belanja', $category->kode_jenis_belanja)
                            ->unique('kode_jenis_kategori_rkbu');
                    @endphp

                    @foreach ($jenisKategoris as $jenisKategori)
                        <tr class="sub-header-parent">
                            <td width="98%">{{ $jenisKategori->kode_jenis_kategori_rkbu }}.
                                {{ $jenisKategori->nama_jenis_kategori_rkbu }}</td>
                        </tr>

                        @php
                            $obyekBelanjas = $categories
                                ->where('kode_jenis_kategori_rkbu', $jenisKategori->kode_jenis_kategori_rkbu)
                                ->unique('kode_obyek_belanja');
                        @endphp

                        @foreach ($obyekBelanjas as $obyekBelanja)
                            <tr class="sub-header-parent">
                                <td width="98%">{{ $obyekBelanja->kode_obyek_belanja }}.
                                    {{ $obyekBelanja->nama_obyek_belanja }}</td>
                            </tr>

                            @php
                                $kategoriRkbus = $categories
                                    ->where('kode_obyek_belanja', $obyekBelanja->kode_obyek_belanja)
                                    ->unique('kode_kategori_rkbu');
                            @endphp

                            @foreach ($kategoriRkbus as $kategoriRkbu)
                                <tr class="sub-header-parent">
                                    <td width="98%">{{ $kategoriRkbu->kode_kategori_rkbu }}.
                                        {{ $kategoriRkbu->nama_kategori_rkbu }}</td>
                                </tr>

                                @php
                                    $subKategoriRkbus = $categories
                                        ->where('kode_kategori_rkbu', $kategoriRkbu->kode_kategori_rkbu)
                                        ->unique('kode_sub_kategori_rkbu');
                                @endphp

                                @foreach ($subKategoriRkbus as $subKategori)
                                    <tr class="sub-header-parent">
                                        <td width="98%">{{ $subKategori->kode_sub_kategori_rkbu }}.
                                            {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                                    </tr>

                                    @php
                                        $subKategoriNumber = 1;
                                    @endphp

                                    @foreach ($categories->where('kode_sub_kategori_rkbu', $subKategori->kode_sub_kategori_rkbu) as $rkbu)
                                        <tr>
                                            <td width="4%" align="center">{{ $subKategoriNumber++ }}</td>
                                            <td width="15%">
                                                <b>{{ $rkbu->nama_barang }}</b><br />
                                                <small>Spesifikasi : {{ $rkbu->spek ? $rkbu->spek : '-' }}</small>
                                            </td>
                                            <td width="5%" align="center">{{ $rkbu->satuan_1 }}</td>
                                            <td width="6%" align="center">{{ $rkbu->stok }}</td>
                                            <td width="6%" align="center">{{ $rkbu->rata_rata_pemakaian }}</td>
                                            <td width="6%" align="center">{{ $rkbu->pengadaan_sebelumnya }}</td>
                                            <td width="6%" align="center">{{ $rkbu->proyeksi_sisa_stok }}</td>
                                            <td width="6%" align="center">{{ $rkbu->kebutuhan_tahun_x1 }}</td>
                                            <td width="6%" align="center">{{ $rkbu->kebutuhan_plus_buffer }}</td>
                                            <td width="4%" align="center">{{ $rkbu->buffer }}</td>
                                            <td width="6%" align="center">{{ $rkbu->rencana_pengadaan_tahun_x1 }}
                                            </td>
                                            <td width="7%" align="center">
                                                {{ number_format($rkbu->harga_satuan) }}</td>
                                            <td width="6%" align="center">{{ $rkbu->ppn }}</td>
                                            <td width="15%" align="center">Rp.
                                                {{ number_format($rkbu->total_anggaran) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <p>
        <table border="0">
            <tr bgcolor="#ffffff">
                <th colspan="6" width="60%" align="left">
                    <h4></h4>
                </th>
                <th colspan="4" width="28%" align="left">
                    <h4>Total Anggaran</h4>
                </th>
                <th width="20%" align="left">
                    <h4>Rp. {{ number_format($total_anggaran) }}</h4>
                </th>
            </tr>
        </table>
    </div>
</body>

</html>
