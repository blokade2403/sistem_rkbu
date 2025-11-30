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
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
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
            font-size: 10px;
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
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        // Fungsi CheckPageBreak() eksternal untuk memeriksa margin halaman
        if (!function_exists('checkPageBreak')) {
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
        }

    @endphp
    <!-- Header -->
    <table class="header-info">
        <tr>
            <td style="font-weight: bold; margin-left: 10px; font-size: 10px; line-height: 1.4; text-align: center; text-decoration: underline;"
                class="no-border" width="100%">BERITA ACARA SERAH TERIMA HASIL PEKERJAAN</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 0; text-align: center;"
                class="no-border" width="100%">Nomor Usulan:
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->no_usulan_barang }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.4; text-align: center;"
                class="no-border" width="100%">Nomor : {{ $detail_master_spj->no_ba_hp }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 0; text-align: center;"
                class="no-border" width="100%">Kode Rekening :
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->kode_rekening_belanja }}. {{ $keranjang->first()->nama_rekening_belanja }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: center; word-wrap: break-word; white-space: normal;"
                class="no-border" width="100%">
                Kegiatan :
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->kode_sub_kategori_rkbu }}. {{ $keranjang->first()->nama_sub_kategori_rkbu }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="no-border"><br></td>
        </tr>
    </table>
    <!-- Informasi Penerima -->
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Pada hari ini, {{ $converttotekstglbast }} bertempat di
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }}
                telah diadakan pemeriksaan barang/jasa sebagai berikut</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 2; text-align: left;"
                class="no-border" width="3%">I.</td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 2; text-align: left;"
                class="no-border" width="90%">Dari Pihak Pemilik Pekerjaan-<strong>PIHAK PERTAMA</strong></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nama_ppk }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">Pejabat Pembuat Komitmen
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Email</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $email }} / {{ $tlp }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Alamat</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $alamat }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 2; text-align: left;"
                class="no-border" width="3%">II.</td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 2; text-align: left;"
                class="no-border" width="90%">Dari Pihak Pelaksana/Penugasan-<strong>PIHAK KEDUA</strong></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0;" class="no-border"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nama_direktur_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->jabatan_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Alamat</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->alamat_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="2%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="60%"></td>
        </tr>
        <tr>
            <td style="font-weight: bold; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nomor DPA</td>
            <td style="font-weight: bold; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: bold; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">066/DPA/2025</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="2%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="60%"></td>
        </tr>

        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="100%">Dengan ini <strong>PIHAK KEDUA</strong> telah menyerahkan hasil
                pekerjaan sesuai : </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Surat Pesanan Nomor </td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.2; text-align: left;"
                class="no-border" width="68%">{{ $detail_master_spj->no_surat_pesanan }} tanggal
                {{ $tgl_ba }} kepada <strong>PIHAK PERTAMA</strong></td>
        </tr>
    </table>


    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="100%">
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Berdasarkan Hasil Pemeriksaan, maka Pelaksanaan Pekerjaan atau
                Penyerahan Pekerjaan oleh <strong>PIHAK KEDUA</strong>
                telah mencapai tahap prestasi 100% dengan baik, sehingga <strong>PIHAK KEDUA</strong> dapat menerima
                pembayaran dari
                <strong>PIHAK PERTAMA</strong> sebesar Rp. {{ number_format($get_total ?? 0, 0, ',', '.') }},-
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">terbilang <strong>({{ $terbilang_total }} Rupiah)</strong>
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenarnya dalam
                rangkap 2 (Dua) untuk dipergunakan
                sebagaimana mestinya.</td>
        </tr>

    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center;"
                class="no-border" width="40%"><strong></strong></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center;"
                class="no-border" width="40%"><strong></strong></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center;"
                class="no-border" width="40%"><strong>PIHAK PERTAMA</strong></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center;"
                class="no-border" width="40%"><strong>PIHAK KEDUA</strong></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: center;"
                class="no-border" width="40%">Pejabat Pembuat Komitmen</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: center; text:wrap"
                class="no-border" width="40%">{{ $detail_master_spj->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.5; text-align: center;"
                class="no-border" width="40%">{{ ucwords(strtolower($judulHeaders->nama_rs)) }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1.5; text-align: center; text:wrap"
                class="no-border" width="40%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 4; text-align: center;"
                class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 4; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 4; text-align: center; text:wrap"
                class="no-border" width="40%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center;"
                class="no-border" width="40%">{{ $detail_master_spj->nama_ppk }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 1; text-align: center; text:wrap"
                class="no-border" width="40%">{{ $detail_master_spj->nama_direktur_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: center;"
                class="no-border" width="40%">NIP {{ $detail_master_spj->nip_ppk }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 10px; line-height: 0; text-align: center; text:wrap"
                class="no-border" width="40%">{{ $detail_master_spj->jabatan_perusahaan }}</td>
        </tr>
    </table>

    <table class="page-break">
        <tr>
            <td style="font-weight: bold; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">
                Lampiran Berita Acara Serah Terima Pekerjaan
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="20%">
                Nomor
            </td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="30%">
                : {{ $detail_master_spj->no_ba_hp }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%">
                Tanggal
            </td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 10px; line-height: 1; text-align: left;"
                class="no-border" width="30%">
                : {{ $tgl_ba }}
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="7%">No</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="45%">Nama Barang/Jasa</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="13%">Volume</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="15%">Satuan</th>
                <th style="font-weight: bold; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="20%">Hasil Pemeriksaan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($keranjang as $item)
                <tr>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="7%">{{ $no++ }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="45%">{{ $item->nama_barang ?? '-' }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="13%">{{ $item->jumlah_usulan_barang ?? '0' }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="15%">{{ $item->satuan_1_detail ?? '-' }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="20%">Sesuai</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Footer -->
    {{-- <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis dan sah tanpa tanda tangan basah.</p>
    </div> --}}
</body>

</html>
