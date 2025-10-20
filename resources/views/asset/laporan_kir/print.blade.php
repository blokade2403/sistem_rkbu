<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rencana Bisnis Anggaran (RBA)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{asset("assets/vendor/libs/sweetalert2/sweetalert2.css")}}" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset("assets/vendor/fonts/materialdesignicons.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/vendor/fonts/fontawesome.css")}}" />
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
            padding : 3px;
        }

        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 6px;
            border: 0.2 px solid black;
        }

        .table-data td center {
            font-size: 20px;
        }

        .table-data th {
            text-align: left; /* Posisi default tetap di kiri */
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

        .spek{
            font-size: 6px; /* Kamu bisa menyesuaikan ukuran font */
            background-color: #ffffff;
            font-style: italic; /* Menjadikan teks miring */
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
                <th width="15%" align="left">Pengguna Barang</th>
                <td width="2%" align="right">: </td>
                <td width="80%" align="left">DINAS KESEHATAN PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA </td>
            </tr>
            <tr>
                <th width="15%" align="left">Kuasa Pengguna Barang</th>
                <td width="2%" align="right">: </td>
                <td width="80%" align="left">RUMAH SAKIT UMUM DAERAH CILINCING </td>
            </tr>
            <tr>
                <th width="15%" align="left">Nama Bangunan dan Gedung</th>
                <td width="2%" align="right">: </td>
                <td width="80%" align="left">{{$penempatan->gedung}}</td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td> <!-- Baris ini memberikan jarak kosong -->
            </tr>
            <tr>
                <th width="15%" align="left" style="font-weight: bold;">Nama Ruangan</th>
                <td width="2%" align="Right" style="font-weight: bold;">: </td>
                <td width="80%" align="left"><strong> {{$penempatan->lokasi_barang}} </strong></td>
            </tr>
        </table>
    </div>
<table class="table-data">
    <thead>
        <tr class="text-center" style="background-color: rgb(180, 186, 186)">
            <th width="3%" >No</th>
            <th width="11%">Kode Barang</th>
            <th width="11%">Nama Barang</th>
            <th width="10%">Merek</th>
            <th width="10%">Type</th>
            <th width="11%">No Chasis / Rangka</th> <!-- Menggabungkan 4 kolom untuk uraian koefisien -->
            <th width="11%">No Mesin Pabrik</th>
            <th width="11%">No Polisi</th>
            <th width="11%">No BPKB</th>
            <th width="11%">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @php
        // Fungsi CheckPageBreak() eksternal untuk memeriksa margin halaman
        function checkPageBreak($pdf, $height) {
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
    
    @foreach ($assets as $index => $asset)
        <tr>
            <td width="3%">{{ $index + 1 }}</td>
            <td width="11%">{{ $asset->kode_asset }}</td>
            <td width="11%">{{ $asset->nama_asset }}</td>
            <td width="10%">{{ $asset->merk }}</td>
            <td width="10%">{{ $asset->type }}</td>
            <td width="11%">{{ $asset->no_rangka }}</td>
            <td width="11%">{{ $asset->no_mesin }}</td>
            <td width="11%">{{ $asset->no_polisi }}</td>
            <td width="11%">{{ $asset->tgl_bpkb }}</td>
            <td width="11%" class="text-center">Rp. {{ number_format($asset->harga_asset, 0,'.',',') }}</td>
        </tr>
    @endforeach
    
    </tbody>
</table>
</div>
</body>
</html>
