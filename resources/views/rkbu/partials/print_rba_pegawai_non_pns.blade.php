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
                    <td width="80%" align="left"><strong>{{ $kodeJenisBelanja }}. {{ $namaJenisBelanja }}</strong>
                    </td>
                </tr>
                <tr>
                    <th width="80%" align="left" style="font-weight: bold;">Belanja Barang Jasa Pegawai Non PNS
                    </th>
                </tr>
            </table>
        </div>
        <table class="table-data">
            <thead>
                <tr bgcolor="#ffffff">
                    <th rowspan="2" width="3%" align="center">No</th>
                    <th rowspan="2" width="12%" align="center">Nama Pegawai</th>
                    <th colspan="2" width="12%" align="center">Tempat Lahir</th>
                    <th rowspan="2" width="6%" align="center">Jabatan</th>
                    <th rowspan="2" width="5%" align="center">Pendidikan</th>
                    <th colspan="3" width="10%" align="center">Kontrak</th>
                    <th colspan="3" width="10%" align="center">Masa Kerja</th>
                    <th rowspan="2" width="4%" align="center">Status(TK/K0/K1/K2)</th>
                    <th colspan="4" width="11%" align="center">Gaji Pokok</th>
                    <th colspan="4" width="15%" align="center">Remunerasi</th>
                    <th rowspan="2" width="8%" align="center">Total Penghasilan</th>
                    <th rowspan="2" width="4%" align="center">Ket</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="6%" align="center">Tempat</th>
                    <th width="6%" align="center">Tgl Lahir</th>
                    <th width="5%" align="center">Nomor</th>
                    <th width="5%" align="center">TMT</th>
                    <th width="5%" align="center">Tahun</th>
                    <th width="5%" align="center">Bulan</th>
                    <th width="6%" align="center">Jumlah</th>
                    <th width="5%" align="center">Bulan</th>
                    <th width="4%" align="center">Koef</th>
                    <th width="6%" align="center">Jumlah</th>
                    <th width="5%" align="center">Bulan</th>
                </tr>
                <tr bgcolor="#ffffff">
                    <th width="3%" align="center">1</th>
                    <th width="12%" align="center">2</th>
                    <th width="6%" align="center">3</th>
                    <th width="6%" align="center">4</th>
                    <th width="6%" align="center">5</th>
                    <th width="5%" align="center">6</th>
                    <th width="5%" align="center">9</th>
                    <th width="5%" align="center">10</th>
                    <th width="5%" align="center">11</th>
                    <th width="5%" align="center">12</th>
                    <th width="4%" align="center">13</th>
                    <th width="6%" align="center">14</th>
                    <th width="5%" align="center">15</th>
                    <th width="4%" align="center">16</th>
                    <th width="6%" align="center">17</th>
                    <th width="5%" align="center">18</th>
                    <th width="8%" align="center">19</th>
                    <th width="4%" align="center">20</th>
                </tr>
            </thead>
            <tbody>
                <!-- Looping Hierarki -->
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
                                <tr class="sub-header-parent">
                                    <td width="100%">{{ $kategoriRkbu->kode_kategori_rkbu }}.
                                        {{ $kategoriRkbu->nama_kategori_rkbu }}</td>
                                </tr>

                                @php
                                    $subKategoriRkbus = $categories
                                        ->where('kode_kategori_rkbu', $kategoriRkbu->kode_kategori_rkbu)
                                        ->unique('kode_sub_kategori_rkbu');
                                @endphp

                                @foreach ($subKategoriRkbus as $subKategori)
                                    <tr class="sub-header-parent">
                                        <td width="100%">{{ $subKategori->kode_sub_kategori_rkbu }}.
                                            {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                                    </tr>

                                    @php
                                        $subKategoriNumber = 1;
                                    @endphp

                                    @foreach ($categories->where('kode_sub_kategori_rkbu', $subKategori->kode_sub_kategori_rkbu) as $rkbu)
                                        <tr bgcolor="#ffffff">
                                            <td width="3%" align="center">{{ $subKategoriNumber++ }}</td>
                                            <td width="12%" align="center">{{ $rkbu->nama_pegawai }}</td>
                                            <td width="6%" align="center">{{ $rkbu->tempat_lahir }}</td>
                                            <td width="6%" align="center">{{ $rkbu->tanggal_lahir }}</td>
                                            <td width="6%" align="center">{{ $rkbu->jabatan }}</td>
                                            <td width="5%" align="center">{{ $rkbu->pendidikan }}</td>
                                            <td width="5%" align="center">{{ $rkbu->nomor_kontrak }}</td>
                                            <td width="5%" align="center">{{ $rkbu->tmt_pegawai }}</td>
                                            <td width="5%" align="center">{{ $rkbu->tahun_tmt }}</td>
                                            <td width="5%" align="center">{{ $rkbu->bulan_tmt }}</td>
                                            <td width="4%" align="center">{{ $rkbu->status_kawin }}</td>
                                            <td width="6%" align="center">
                                                {{ number_format($rkbu->gaji_pokok, 0, ',', ',') }}</td>
                                            <td width="5%" align="center">
                                                {{ $rkbu->koefisien_gaji }}</td>
                                            <td width="4%" align="center">
                                                {{ $rkbu->koefisien_remunerasi }}</td>
                                            <td width="6%" align="center">
                                                {{ number_format($rkbu->remunerasi) }}
                                            </td>
                                            <td width="5%" align="center">{{ $rkbu->koefisien_remunerasi }}</td>
                                            <td width="8%" align="center">
                                                {{ number_format($rkbu->total_anggaran, 0, ',', ',') }}</td>
                                            <td width="4%" align="center"></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <table class="table-data">
            <tr bgcolor="#ffffff" style="line-height: 0.5;">
                <th colspan="4" width="88%" align="center">
                    <h4>Total Anggaran</h4>
                </th>
                <th width="12%" align="center">
                    <h4>Rp. {{ number_format($total_anggaran) }}</h4>
                </th>
            </tr>
            <tr bgcolor="#ffffff" style="line-height: 0.5;">
                <th rowspan="2" width="63%" align="center">
                    <h4>Sumber Dana</h4>
                </th>
                <th width="25%" align="left">
                    <h4>Pendapatan BLUD</h4>
                </th>
                <th width="12%" align="left">
                    <h4></h4>
                </th>
            </tr>
            <tr bgcolor="#ffffff" style="line-height: 0.5;">
                <th width="25%" align="left">
                    <h4>Silpa</h4>
                </th>
                <th width="12%" align="left">
                    <h4></h4>
                </th>
            </tr>


        </table>

    </div>
</body>

</html>
