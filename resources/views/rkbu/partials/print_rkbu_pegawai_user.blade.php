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

        table {
            width: 100%;
            table-layout: auto;
            /* Tabel dapat menyesuaikan lebar kolom secara dinamis */
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
            padding: 3px;
            table-layout: auto;
            /* Tabel dapat menyesuaikan lebar kolom secara dinamis */
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

        .sub-header {
            font-weight: bold;
            background-color: #f2f2f2;
            text-align: left;
            width: 100%;


        }

        .sub-header-child {
            font-weight: bold;
            background-color: #ffffff;
            text-align: left;
            width: 100%;
        }

        .sub-header-parent {
            background-color: #ffffff;
            text-align: left;
            width: 100%;
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
                    <th width="10%" align="left">Program</th>
                    <td width="80%" align="left">: {{ $kodeProgram }}. {{ $namaProgram }}</td>
                </tr>
                <tr>
                    <th width="10%" align="left">Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeKegiatan }}. {{ $namaKegiatan }}</td>
                </tr>
                <tr>
                    <th width="10%" align="left">Sub Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeSubKegiatan }}. {{ $namaSubKegiatan }}</td>
                </tr>
                <tr>
                    <td colspan="2" height="10"></td> <!-- Baris ini memberikan jarak kosong -->
                </tr>
                <tr>
                    <th width="10%" align="left" style="font-weight: bold;">Total Anggaran</th>
                    <td width="65%" align="left" style="font-weight: bold;">: {{ $kodeProgram }}</td>
                    <th width="5%" align="left" style="font-weight: bold;">Unit</th>
                    <td width="20%" align="left" style="font-weight: bold;">: {{ $kodeProgram }}</td>
                </tr>
            </table>

        </div>
        <table class="table-data">
            <thead>
                <tr bgcolor="#ffffff">
                    <th rowspan="2" width="4%" align="center">No</th>
                    <th rowspan="2" width="15%" align="center">Nama Pegawai</th>
                    <th colspan="2" width="12%" align="center">Tempat Lahir</th>
                    <th rowspan="2" width="8%" align="center">Jabatan</th>
                    <th rowspan="2" width="8%" align="center">Pend.</th>
                    <th rowspan="2" width="6%" align="center">NIP</th>
                    <th colspan="3" width="14%" align="center">Masa Kerja</th>
                    <th colspan="4" width="22%" align="center">Penghasilan</th>
                    <th rowspan="2" width="10%" align="center">Total Penghasilan</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="6%" align="center">Tempat</th>
                    <th width="6%" align="center">Tgl Lahir</th>
                    <th width="6%" align="center">TMT</th>
                    <th width="4%" align="center">TMT Tahun</th>
                    <th width="4%" align="center">TMT Bulan</th>
                    <th width="4%" align="center">Total Gaji Pokok</th>
                    <th width="10%" align="center">Total Remunerasi</th>
                    <th width="8%" align="center">Koefisien</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="4%" align="center">1</th>
                    <th width="15%" align="center">2</th>
                    <th width="6%" align="center">3</th>
                    <th width="6%" align="center">4</th>
                    <th width="8%" align="center">5</th>
                    <th width="8%" align="center">6</th>
                    <th width="6%" align="center">7</th>
                    <th width="6%" align="center">8</th>
                    <th width="4%" align="center">9</th>
                    <th width="4%" align="center">10</th>
                    <th width="4%" align="center">11</th>
                    <th width="10%" align="center">12</th>
                    <th width="8%" align="center">13</th>
                    <th width="10%" align="center">14=(8+9+10)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Looping Hierarki -->
                @foreach ($categories as $category)
                    <tr class="sub-header">
                        <th width="99%">{{ $category->kode_jenis_belanja }}. {{ $category->nama_jenis_belanja }}
                        </th>
                    </tr>
                    @foreach ($category->jenis_kategori_rkbus as $jenisKategori)
                        @foreach ($jenisKategori->obyek_belanjas as $obyekBelanja)
                            @foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu)
                                <tr class="sub-header-parent">
                                    <td width="99%">{{ $kategoriRkbu->kode_kategori_rkbu }}.
                                        {{ $kategoriRkbu->nama_kategori_rkbu }}</td>
                                </tr>
                                @foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategori)
                                    <tr class="sub-header-parent">
                                        <td width="99%">{{ $subKategori->kode_sub_kategori_rkbu }}.
                                            {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                                    </tr>
                                    @php

                                        $subKategoriNumber = 1; // Nomor urut untuk setiap sub_kategori_rkbu
                                    @endphp
                                    @foreach ($subKategori->rkbus as $rkbu)
                                        <tr bgcolor="#ffffff">
                                            <td width="4%" align="center">{{ $subKategoriNumber++ }}</td>
                                            <td width="15%" align="center">{{ $rkbu->nama_pegawai }}</td>
                                            <td width="6%" align="center">{{ $rkbu->tempat_lahir }}</td>
                                            <td width="6%" align="center">{{ $rkbu->tempat_tgl_lahir }}</td>
                                            <td width="8%" align="center">{{ $rkbu->jabatan }}</td>
                                            <td width="8%" align="center">{{ $rkbu->pendidikan }}</td>
                                            <td width="6%" align="center">{{ $rkbu->nomor_kontrak }}</td>
                                            <td width="6%" align="center">{{ $rkbu->tmt_pegawai }}</td>
                                            <td width="4%" align="center">{{ $rkbu->tahun_tmt }}</td>
                                            <td width="4%" align="center">{{ $rkbu->bulan_tmt }}</td>
                                            <td width="4%" align="center">
                                                {{ number_format($rkbu->total_gaji_pokok) }}</td>
                                            <td width="10%" align="center">Rp.
                                                {{ number_format($rkbu->remunerasi) }}</td>
                                            <td width="8%" align="center">
                                                {{ number_format($rkbu->koefisien_remunerasi) }}</td>
                                            <td width="10%" align="center">
                                                Rp.{{ number_format($rkbu->total_anggaran) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <table cellspacing="1" cellpadding="1">
            <tr bgcolor="#ffffff">
                <th colspan="10" rowspan="2" width="63%" style="text-align:center;line-height:7px;">
                    <h1></h1>
                </th>
                <th colspan="2" width="26%" align="center">
                    <h4>Total</h4>
                </th>
                <th width="10%" align="center">
                    <h4>Rp. </h4>
                </th>
            </tr>
            <tr bgcolor="#ffffff">
                <th colspan="2" align="center">
                    <h4>Grand Total</h4>
                </th>
                <th width="10%" align="center">
                    <h4>Rp. {{ number_format($total_anggaran) }}</h4>
                </th>

            </tr>
        </table>
    </div>

</body>

</html>
