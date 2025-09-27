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
            <table border="0.5" cellspacing="1" cellpadding="2">
                <tr width="100%">
                    <td style="text-align: center;">Rincian Anggaran Belanja</td>
                </tr>
            </table>
            <table border="0" cellspacing="1" cellpadding="3" width="100%">
                <tr>
                    <th width="20%" align="left">Program</th>
                    <td width="80%" align="left">: {{ $kodeProgram }}. {{ $namaProgram }}</td>
                </tr>
                <tr>
                    <th width="20%" align="left">Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeKegiatan }}. {{ $namaKegiatan }}</td>
                </tr>
                <tr>
                    <th width="20%" align="left">Sub Kegiatan</th>
                    <td width="80%" align="left">: {{ $kodeSubKegiatan }}. {{ $namaSubKegiatan }}</td>
                </tr>
            </table>
        </div>
        <table class="table-data">
            <thead>
                <tr class="text-center">
                    <th width="4%" rowspan="2">No</th>
                    <th width="22%" rowspan="2">Nama / Jenis Barang</th>
                    <th width="25%" rowspan="2">Spesifikasi Merk/Ukuran</th>
                    <th width="6%" rowspan="2">Jumlah</th>
                    <th colspan="4" width="18%">Uraian Koefisien</th>
                    <!-- Menggabungkan 4 kolom untuk uraian koefisien -->
                    <th width="12%" rowspan="2">Harga Satuan</th>
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
                    <th width="22%">2</th>
                    <th width="25%">3</th>
                    <th width="6%">4</th>
                    <th width="4%">5</th>
                    <th width="5%">6</th>
                    <th width="4%">7</th>
                    <th width="5%">8</th>
                    <th width="12%">9</th>
                    <th width="13%">10</th>
                </tr>
            </thead>
            <tbody>
                <!-- Looping Hierarki -->
                @foreach ($categories as $category)
                    <tr class="sub-header">
                        <td colspan="10">{{ $category->kode_jenis_belanja }}. {{ $category->nama_jenis_belanja }}
                        </td>
                    </tr>
                    @foreach ($category->jenis_kategori_rkbus as $jenisKategori)
                        @foreach ($jenisKategori->obyek_belanjas as $obyekBelanja)
                            @foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu)
                                <tr class="sub-header-parent">
                                    <td colspan="10">{{ $kategoriRkbu->kode_kategori_rkbu }}.
                                        {{ $kategoriRkbu->nama_kategori_rkbu }}</td>
                                </tr>
                                @foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategori)
                                    <tr class="sub-header-parent">
                                        <td colspan="10">{{ $subKategori->kode_sub_kategori_rkbu }}.
                                            {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                                    </tr>
                                    @php
                                        $subKategoriNumber = 1; // Nomor urut untuk setiap sub_kategori_rkbu
                                    @endphp
                                    @foreach ($subKategori->rkbus as $rkbu)
                                        <tr>
                                            <td width="4%" class="text-center">{{ $subKategoriNumber++ }}</td>
                                            <td width="22%">{{ $rkbu->nama_barang }}<br /><span
                                                    class="text-center">id user:
                                                    {{ $rkbu->user->id_user ?? '-' }}</span></td>
                                            <td width="25%" class="spek">Spesifikasi:<br />
                                                {{ $rkbu->spek ?? '-' }}</td>
                                            <td width="6%" class="text-center">{{ $rkbu->jumlah_vol }}</td>
                                            <td width="4%" class="text-center">{{ $rkbu->vol_1 }}</td>
                                            <td width="5%" class="text-center">{{ $rkbu->satuan_1 }}</td>
                                            <td width="4%" class="text-center">{{ $rkbu->vol_2 ?? '1' }}</td>
                                            <td width="5%" class="text-center">{{ $rkbu->satuan_2 ?? 'Tahun' }}
                                            </td>
                                            <td width="12%" class="text-center">Rp.
                                                {{ number_format($rkbu->harga_satuan, 0, ',', '.') }}</td>
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
    </div>
    <div class="info">
        <table cellspacing="1" cellpadding="1">
            <tr width="100%">
                <td style="text-align: right;"><b>Total Anggaran : Rp. {{ number_format($total_anggaran) }}</b></td>
            </tr>
        </table>
    </div>
</body>

</html>
