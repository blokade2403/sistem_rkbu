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
            border-collapse: collapse;
            /* Menghilangkan jarak antar border */
        }

        .info th,
        .info td {
            padding: 5px 10px;
            /* Menambahkan padding untuk memberi ruang */
            vertical-align: top;
            /* Agar teks di-align di atas */
        }

        .info th {
            text-align: left;
            width: 20%;
            /* Membuat kolom label lebih lebar */
        }

        .info td {
            text-align: left;
            width: 80%;
            /* Membuat kolom isi lebih lebar */
            line-height: 1.6;
            /* Memberi spasi lebih antar baris */
        }

        .info td:before {
            content: ": ";
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
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">{{ $kodeProgram }}. {{ $namaProgram }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Kegiatan</th>
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">{{ $kodeKegiatan }}. {{ $namaKegiatan }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Sub Kegiatan</th>
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">{{ $kodeSubKegiatan }}. {{ $namaSubKegiatan }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Aktivitas Sub Kegiatan</th>
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">{{ $namaAktivitas }}</td>
                </tr>
                <tr>
                    <th width="15%" align="left">Sumber Pendanaan</th>
                    <td width="1%" align="left">: </td>
                    <td width="79%" align="left">{{ $sumberDana }}</td>
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
                    <th width="4%" rowspan="2">No</th>
                    <th width="19%" rowspan="2">Nama / Jenis Barang</th>
                    <th width="19%" rowspan="2">Spesifikasi Merk/Ukuran</th>
                    <th width="6%" rowspan="2">Jumlah</th>
                    <th colspan="4" width="18%">Uraian Koefisien</th>
                    <!-- Menggabungkan 4 kolom untuk uraian koefisien -->
                    <th width="11%" rowspan="2">Harga Satuan</th>
                    <th width="10%" rowspan="2">PPN</th>
                    <th width="13%" rowspan="2">Jumlah Biaya</th>
                </tr>
                <tr>
                    <th width="4%">Vol.1</th>
                    <th width="5%">Uraian</th>
                    <th width="4%">Vol.2</th>
                    <th width="5%">Uraian</th>
                    <th colspan="2"></th> <!-- Kosongkan kolom setelah uraian koefisien -->
                </tr>
                <tr class="text-center">
                    <th width="4%">1</th>
                    <th width="19%">2</th>
                    <th width="19%">3</th>
                    <th width="6%">4=5x7</th>
                    <th width="4%">5</th>
                    <th width="5%">6</th>
                    <th width="4%">7</th>
                    <th width="5%">8</th>
                    <th width="11%">9</th>
                    <th width="10%">9</th>
                    <th width="13%">10=9x4</th>
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

                @foreach ($categories->unique('kode_jenis_belanja') as $category)
                    @php
                        $jenisKategoris = $categories
                            ->where('kode_jenis_belanja', $category->kode_jenis_belanja)
                            ->unique('kode_jenis_kategori_rkbu');
                    @endphp
                    @foreach ($jenisKategoris as $jenisKategori)
                        @php
                            $obyekBelanjas = $categories
                                ->where('kode_jenis_kategori_rkbu', $jenisKategori->kode_jenis_kategori_rkbu)
                                ->unique('kode_obyek_belanja');
                        @endphp
                        @foreach ($obyekBelanjas as $obyekBelanja)
                            @php
                                $kategoriRkbus = $categories
                                    ->where('kode_obyek_belanja', $obyekBelanja->kode_obyek_belanja)
                                    ->unique('kode_kategori_rkbu');
                            @endphp
                            @foreach ($kategoriRkbus as $kategoriRkbu)
                                @php
                                    $subKategoriRkbus = $categories
                                        ->where('kode_kategori_rkbu', $kategoriRkbu->kode_kategori_rkbu)
                                        ->unique('kode_sub_kategori_rkbu');
                                @endphp
                                @foreach ($subKategoriRkbus as $subKategori)
                                    <tr class="sub-header">
                                        <td colspan="11">{{ $subKategori->kode_sub_kategori_rkbu }}.
                                            {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                                    </tr>
                                    @php
                                        $subKategoriNumber = 1;
                                    @endphp

                                    @foreach ($categories->where('kode_sub_kategori_rkbu', $subKategori->kode_sub_kategori_rkbu) as $rkbu)
                                        <tr>
                                            <td width="4%" class="text-center">{{ $subKategoriNumber++ }}</td>
                                            <td width="19%"><b>{{ $rkbu->nama_barang }}</b>
                                                <br /><span class="text-left">Penempatan:
                                                    {{ $rkbu->penempatan ?? '-' }}</span>
                                                {{-- <br/><span class="text-left">Unit: {{ $rkbu->nama_unit ?? '-' }}</span> --}}
                                            </td>
                                            <td width="19%" class="spek">Spesifikasi:<br />
                                                {{ $rkbu->spek ?? '-' }}</td>
                                            <td width="6%" class="text-center">{{ $rkbu->jumlah_vol }}</td>
                                            <td width="4%" class="text-center">{{ $rkbu->vol_1 }}</td>
                                            <td width="5%" class="text-center">{{ $rkbu->satuan_1 }}</td>
                                            <td width="4%" class="text-center">{{ $rkbu->vol_2 ?? '1' }}</td>
                                            <td width="5%" class="text-center">{{ $rkbu->satuan_2 ?? 'Tahun' }}
                                            </td>
                                            <td width="11%" class="text-center">Rp.
                                                {{ number_format($rkbu->harga_satuan, 0, ',', '.') }}</td>
                                            <td width="10%" class="text-center">Rp.
                                                {{ number_format($rkbu->harga_satuan * $rkbu->jumlah_vol * ($rkbu->ppn / 100), 0, ',', '.') }}
                                            </td>
                                            <td width="13%" class="text-center">Rp.
                                                {{ number_format($rkbu->total_anggaran, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach


            </tbody>
        </table>
        <table border="0.1">
            <tr bgcolor="#ffffff">
                <th colspan="10" width="87%" align="center">Jumlah Anggaran Aktivitas Sub Kegiatan </th>
                <th width="13%" align="center"> Rp. {{ number_format($total_anggaran) }}</th>
            </tr>
        </table>
        <table rules="none">
            <tr class="table-rows-he">
                <th scope="row" width="20%" align="center"></th>
                <th width="55%" align="center"></th>
                <th width="20%" align="center"></th>
            </tr>
            <tr class="table-rows" bgcolor="#ffffff">
                <th scope="row" width="20%" align="center"></th>
                <th width="45%" align="center"></th>
                <th width="30%" align="center">Jakarta, </th>
            </tr>
            <tr class="table-rows" bgcolor="#ffffff">
                <th scope="row" width="20%" align="center">Kepala Program</th>
                <th width="45%" align="center"></th>
                <th width="30%" align="center">Direktur {{ ucwords(strtolower($nama_rumah_sakit)) }}</th>
            </tr>
            <p></p>
            <tr class="table-rows" bgcolor="#ffffff">
                <th scope="row" width="20%" align="center">{{ $nama_pejabat }}</th>
                <th width="45%" align="center"></th>
                <th width="30%" align="center">{{ $nama_direktur }}</th>
            </tr>
            <tr class="table-rows" bgcolor="#ffffff">
                <th scope="row" width="20%" align="center">NIP {{ $nip_pejabat_kabag }}</th>
                <th width="45%" align="center"></th>
                <th width="30%" align="center">NIP {{ $nip_direktur }}</th>
            </tr>
        </table>
    </div>
</body>

</html>
