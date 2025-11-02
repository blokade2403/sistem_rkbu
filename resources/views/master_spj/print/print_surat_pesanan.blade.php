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
            <td style="font-weight: bold; margin-left: 10px; font-size: 13px; line-height: 1.4; text-align: center; text-decoration: underline;"
                class="no-border" width="100%">SURAT PESANAN</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 0; text-align: center;"
                class="no-border" width="100%">Nomor Usulan:
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->no_usulan_barang }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.4; text-align: center;"
                class="no-border" width="100%">Nomor : {{ $detail_master_spj->no_surat_pesanan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 0; text-align: center; word-wrap: break-word; white-space: normal;"
                class="no-border" width="100%">
                Kegiatan :
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->kode_sub_kategori_rkbu }}. {{ $keranjang->first()->nama_sub_kategori_rkbu }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1.4; text-align: center;"
                class="no-border" width="100%">Kode Rekening :
                @if ($keranjang->isNotEmpty())
                    {{ $keranjang->first()->kode_rekening_belanja }}. {{ $keranjang->first()->nama_rekening_belanja }}
                @else
                    >No data available
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 0; text-align: center;"
                class="no-border" width="100%">ID Paket : {{ $detail_master_spj->idpaket }}</td>
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
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2; text-align: left;"
                class="no-border" width="100%">Yang bertanda tangan dibawah ini :</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nama_ppk }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Jabatan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">Pejabat Pembuat Komitmen
                {{ ucwords(strtolower($judulHeaders->nama_rs)) }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Email</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $email }} / {{ $tlp }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Alamat</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $alamat }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2; text-align: left;"
                class="no-border" width="100%">Selanjutnya disebut sebagai : <b>Pejabat Pembuat Komitmen</b></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 1; text-align: left;"
                class="no-border" width="100%">Bersama ini memerintahkan</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0;" class="no-border"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Nama Perusahaan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->nama_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Email/Telp</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->email_perusahaan }} /
                {{ $detail_master_spj->tlp_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="30%">Alamat</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.2; text-align: left;"
                class="no-border" width="60%">{{ $detail_master_spj->alamat_perusahaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2; text-align: left;"
                class="no-border" width="100%">Selanjutnya disebut sebagai : <b>Penyedia Barang</b> </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="7%">No</th>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="25%">Nama Barang/Jasa</th>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="13%">Jumlah</th>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="15%">Satuan</th>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="15%">Harga Satuan</th>
                <th style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                    width="25%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($keranjang as $item)
                <tr>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="7%">{{ $no++ }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="25%">{{ $item->nama_barang ?? '-' }} <br> <small>Spesifikasi:
                            {{ $item->spek_detail ?? '-' }} </small></td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="13%">{{ $item->jumlah_usulan_barang ?? '0' }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="15%">{{ $item->satuan_1_detail ?? '-' }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="15%">{{ number_format($item->harga_barang ?? 0, 0, ',', '.') }}</td>
                    <td style="font-weight: normal; font-size: 9px; line-height: 1.2; text-align: center; border-collapse: collapse; width: 100%; border: 0.5px solid black;"
                        width="25%">
                        {{ number_format($item->total_anggaran_usulan_barang - $item->total_ppn ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" rowspan="3" class="center"
                    style="font-weight: normal; font-size: 9px; text-align: center; vertical-align: middle; border-collapse: collapse; border: 0.5px solid black;">
                    <strong>{{ $terbilang_total }} Rupiah</strong>
                </td>
                <td
                    style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;">
                    <strong>Sub Total</strong>
                </td>
                <td style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;"
                    class="center"><strong>{{ number_format($get_total - $ppn ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td
                    style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;">
                    <strong>PPN</strong>
                </td>
                <td style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;"
                    class="center"><strong>{{ number_format($ppn ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td
                    style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;">
                    <strong>Total</strong>
                </td>
                <td style="font-weight: normal; font-size: 9px; text-align: center; border-collapse: collapse; border: 0.5px solid black;"
                    class="center"><strong>{{ number_format($get_total ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <td style="font-weight: normal; margin-left: 10px; font-size: 11px; line-height: 2; text-align: left;"
                class="no-border" width="100%">Untuk mengirimkan barang dengan memperhatikan ketentuan-ketentuan
                sebagai berikut :</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="3%">1.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="30%">Tanggal diterima</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="60%">{{ $tgl_pesanan }} s/d {{ $tgl_pekerjaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="3%">2.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="30%">Syarat Pekerjaan</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%">Sesuai dengan persyaratan dan ketentuan di dalam kontrak.
            </td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="3%">3.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="30%">Waktu Penyelesaian</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%">Selama {{ $selisih_hari + 1 }}
                ({{ $jumlah_hari_terbilang }}) hari kalender dan pekerjaan harus sudah selesai pada tanggal
                {{ $tgl_pekerjaan }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="3%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="2%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%"></td>
        </tr>

        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="3%">4.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border">Alamat Pengiriman Barang</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%">{{ $alamat }}</td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="3%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="30%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left;"
                class="no-border" width="2%"></td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 0; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%"></td>
        </tr>
        <tr>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="3%">5.</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="30%">Denda</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left;"
                class="no-border" width="2%">:</td>
            <td style="font-weight: normal; margin-left: 50px; font-size: 11px; line-height: 1.5; text-align: left; text-align:justify;"
                class="no-border text-wrap" width="60%">Setiap hari keterlambatan penyelesaian pekerjaan Penyedia
                barang akan dikenakan Denda Keterlambatan sebesar 1/1000 (satu per seribu) dari bagian kontrak yang
                belum dikerjakan.</td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="no-border">
                <p></p>
            </td>
        </tr>
    </table>



    <!-- Footer -->
    {{-- <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis dan sah tanpa tanda tangan basah.</p>
    </div> --}}
</body>

</html>
