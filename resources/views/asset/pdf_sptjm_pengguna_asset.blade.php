<!DOCTYPE html>
<html>
<head>
    <title>Surat Pesanan</title>
    <style>
         @media print {
        table.page-break {
            page-break-before: always;
        }
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .no-border {
            border: none;
        }
        .center {
            text-align: center;
            vertical-align: middle;
        }
        .right {
            text-align: right;
        }
        h4 {
            margin: 2px 0;
            font-size: 11px;
        }
        .header-info {
            font-size: 10px;
            text-align: center;
        }
        .content-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>

<body>
    
    <!-- Header -->
        <table class="header-info">
        <tr>
            <td style="font-weight: bold; margin-left: 9px; font-size: 13px; line-height: 1.5; text-align: center; text-decoration: underline;" class="no-border" width="100%">SURAT PENUNJUKAN PENGGUNAAN</td>
        </tr>
        <tr>
            <td style="font-weight: bold; margin-left: 9px; font-size: 13px; line-height: 0; text-align: center; text-decoration: underline;" class="no-border" width="100%">BARANG INVENTARIS (BARANG MILIK DAERAH)</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 9px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="100%">Nomor : ........  / ........   </td>
        </tr>
        </table>
      
    <!-- Informasi Penerima -->
    
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Dalam rangka optimalisasi penggunaan Barang Milik Daerah Provinsi DKI Jakarta sebagai sarana penunjang kegiatan operasional pada Rumah Sakit Umum Daerah Cilincing dipandang perlu menunjuk pemegang barang inventaris berupa {{$invo->kategori_asset_bergerak}} tersebut kepada :
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{$invo->pengguna_asset}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">NIP/NRK</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{$invo->nip_pengguna}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{$invo->jabatan_pengguna}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Unit Kerja</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">Rumah Sakit Umum Daerah Cilincing</td>
        </tr>
    </table>
    <table>
        <tr><td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0;" class="no-border"></td></tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Dengan spesifikasi teknis secara lengkap sebagai berikut : 
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Nama Barang</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{ $invo->nama_asset }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Merk / Type</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{ $invo->merk }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Tahun Perolehan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{ $invo->tahun_perolehan }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Untuk digunakan sebagai sarana operasional dalam rangka menunjang seluruh kegiatan Rumah Sakit Umum Daerah Cilincing Jakarta Utara dengan ketentuan sebagai berikut :</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">1.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="90%">Pemakai bertanggung jawab atas perawatan barang inventaris tersebut dalam keadaan baik dan siap dipergunakan untuk kepentingan dinas serta bertanggung jawab atas keamanannya.</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">2.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="90%">Apabila terjadi kehilangan maka yang bersangkutan akan diproses sesuai dengan
                ketentuan yang berlaku</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">3.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="90%">Penyerahan barang Inventaris berlaku terhitung mulai tanggal surat penunjukan
                pemakaian dimaksud</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Demikian Surat Penunjukan Penggunaan Barang Inventaris Milik Daerah ini untuk dipergunakan
                sebaik-baiknya.</td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td class="no-border"><p></p></td>
        </tr>
    </table>
    <table>
        <tr >
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
            @php
                use Carbon\Carbon;
            @endphp
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1; text-align: center;" class="no-border" width="40%">Jakarta, {{ Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr >
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">Yang Menerima</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">Yang Menyerahkan </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="40%">{{$invo->pengguna_asset}}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap" class="no-border" width="40%">{{$pengurus_barang->nama_pengurus_barang}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="40%">NIP {{$pengurus_barang->nip_pengurus_barang}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="40%">Mengetahui</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
         <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="60%">Direktur Rumah Sakit Umum Daerah Cilincing</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="10%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="20%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center;" class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 2; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
         <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
         <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="40%">{{$pengurus_barang->nama_direktur}}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
         <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;" class="no-border" width="40%">{{$pengurus_barang->nip_direktur}}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap" class="no-border" width="40%"></td>
        </tr>
    </table>
</body>
</html>
