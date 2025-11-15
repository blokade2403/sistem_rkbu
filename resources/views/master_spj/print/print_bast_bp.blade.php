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
            <td style="font-weight: bold; margin-left: 9px; font-size: 13px; line-height: 1.2; text-align: center; text-decoration: underline;"
                class="no-border" width="100%">BERITA ACARA SERAH TERIMA BARANG / PEKERJAAN</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 9px; font-size: 11px; line-height: 0; text-align: center;"
                class="no-border" width="100%">Nomor : {{ $detail_master_spj->no_ba_bp ?? 'Nomor BA Tidak Ada' }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.2; text-align: center;"
                class="no-border" width="100%">Nomor Usulan:
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->no_usulan_barang }}
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
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Pada hari ini, {{ $converttotekstglbast }} bertempat di
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }}
                telah diadakan pemeriksaan barang/jasa sebagai berikut</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">1.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $pengurus_barang->nama_pengurus_barang }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">2.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">NIP</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $pengurus_barang->nip_pengurus_barang }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">3.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">Pengurus Barang</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0;" class="no-border"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Berdasarkan SK Gubernur Provinsi DKI Jakarta No. 29 Tahun 2023 Tentang
                Penunjukan Pengurus Barang {{ ucwords(strtolower($judulHeaders->nama_rs)) }}, telah menerima belanja
                barang/jasa dengan kode kategori {{ $detail_master_spj->kode_sub_kategori_rkbu }}
                {{ $detail_master_spj->nama_sub_kategori_rkbu }} kebutuhan
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }} yang diserahkan oleh Pejabat Pembuat Komitmen
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }}: </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">1.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nama_ppk }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">2.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">NIP</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nip_ppk }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">3.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">Pejabat Pembuat Komitmen</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Berdasarkan Surat Pesanan {{ $detail_master_spj->no_surat_pesanan }}
                tanggal {{ $tgl_pesanan }}, {{ $detail_master_spj->kode_sub_kategori_rkbu }}
                {{ $detail_master_spj->nama_sub_kategori_rkbu }} Kode rekening : 5.1.02.99.99.9999</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Dengan Kesimpulan sebagai berikut :</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">a.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">Terdapat baik, sesuai Surat Pesanan</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="4%">b.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%"> <s>Kurang / Tidak baik</s></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenarnya dalam
                rangkap 2 (Dua) untuk dipergunakan
                sebagaimana mestinya.</td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="no-border">
                <p></p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;"
                class="no-border" width="40%"><strong>Yang Menyerahkan</strong></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;"
                class="no-border" width="40%"><strong>Yang Menerima</strong> </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;"
                class="no-border" width="40%">Pejabat Pembuat Komitmen</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap"
                class="no-border" width="40%">Pengurus Barang </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;"
                class="no-border" width="40%">{{ ucwords(strtolower($judulHeaders->nama_rs)) }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap"
                class="no-border" width="40%">{{ ucwords(strtolower($judulHeaders->nama_rs)) }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 3; text-align: center;"
                class="no-border" width="40%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 3; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 3; text-align: center; text:wrap"
                class="no-border" width="40%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center;"
                class="no-border" width="40%">{{ $detail_master_spj->nama_ppk }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: center; text:wrap"
                class="no-border" width="40%">{{ $pengurus_barang->nama_pengurus_barang }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center;"
                class="no-border" width="40%">NIP {{ $detail_master_spj->nip_ppk }}</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="20%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: center; text:wrap"
                class="no-border" width="40%">NIP {{ $pengurus_barang->nip_pengurus_barang }}</td>
        </tr>

    </table>

    <table class="page-break">
        <tr>
            <td style="font-weight: bold; margin-left: 10px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="100%">
                Lampiran Berita Acara Serah Terima Pekerjaan
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1; text-align: left;"
                class="no-border" width="20%">
                Nomor
            </td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1; text-align: left;"
                class="no-border" width="30%">
                : {{ $detail_master_spj->no_ba_bp }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2.5; text-align: left;"
                class="no-border" width="20%">
                Tanggal
            </td>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1; text-align: left;"
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
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 3; text-align: left;"
                class="no-border" width="100%">Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenarnya dalam
                rangkap 2 (Dua) untuk dipergunakan
                sebagaimana mestinya.</td>
        </tr>
    </table>
</body>

</html>
