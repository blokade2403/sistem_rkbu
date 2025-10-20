<!DOCTYPE html>
<html>
<head>
    <title>Surat Pesanan</title>
    <style>
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
            font-size: 11px;
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
    @php
    // Fungsi CheckPageBreak() eksternal untuk memeriksa margin halaman
    if (!function_exists('checkPageBreak')) {
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
    }

    @endphp
    <!-- Header -->
        <table class="header-info">
        <tr>
            <td style="font-weight: bold; margin-left: 10px; font-size: 13px; line-height: 1.2; text-align: center; text-decoration: underline;" class="no-border" width="100%">BERITA ACARA PEMERIKSAAN PEKERJAAN</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 0; text-align: center;" class="no-border" width="100%">Nomor : {{$detail_master_spj->no_ba ?? 'Nomor BA Tidak Ada'}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1; text-align: center;" class="no-border" width="100%">Nomor Usulan: 
                @if($keranjang->isNotEmpty())
                {{ $keranjang->first()->no_usulan_barang }}
            @else
                >No data available
            @endif
            </td>
        </tr>
        </table>
        <table>
            <tr><td class="no-border"><br></td></tr>
        </table>
    <!-- Informasi Penerima -->
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Pada hari ini, {{$converttotekstglbast}} bertempat di {{ucwords(strtolower($judulHeaders->nama_rs))}}
                telah diadakan pemeriksaan barang/jasa sebagai berikut</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">1.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Nama Pekerjaan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">
                @if($keranjang->isNotEmpty())
                {{$keranjang->first()->kode_sub_kategori_rkbu}}. {{$keranjang->first()->nama_sub_kategori_rkbu}}
                @else
                    No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">2.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Nomor Surat Pesanan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{$detail_master_spj->no_surat_pesanan}}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="4%">3.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="30%">Tanggal Surat Pesanan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;" class="no-border" width="60%">{{$tgl_pesanan}}</td>
        </tr>
    </table>
    <table>
        <tr><td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0;" class="no-border"></td></tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2; text-align: left;" class="no-border" width="100%">Dengan Rincian sebagai berikut : </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="7%">No</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="45%">Nama Barang/Jasa</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="13%">Volume</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="15%">Satuan</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="20%">Hasil Pemeriksaan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($keranjang as $item)
            <tr>
                <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="7%">{{ $no++ }}</td>
                <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="45%">{{ $item->nama_barang ?? '-' }}</td>
                <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="13%">{{ $item->jumlah_usulan_barang ?? '0' }}</td>
                <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="15%">{{ $item->satuan_1_detail ?? '-' }}</td>
                <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;" width="20%">Sesuai</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;" class="no-border" width="100%">Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenarnya dalam rangkap 2 (Dua) untuk dipergunakan
                sebagaimana mestinya.</td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="no-border"><p></p></td>
        </tr>
    </table>
    

    <!-- Footer -->
    {{-- <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis dan sah tanpa tanda tangan basah.</p>
    </div> --}}
</body>
</html>
