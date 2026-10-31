<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Pesanan</title>
    <style>
        @page { margin: 18mm 18mm 18mm 18mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 10pt; line-height: 1.35; }
        .letterhead { text-align: center; border-bottom: 3px double #111; padding: 0 0 9px; margin-bottom: 16px; }
        .letterhead .line { font-size: 9pt; }
        .letterhead .name { font-weight: bold; font-size: 14pt; margin: 2px 0; }
        .letterhead .address { font-size: 8.5pt; }
        h1 { text-align: center; font-size: 13pt; text-decoration: underline; margin: 12px 0 2px; }
        .number { text-align: center; margin-bottom: 16px; }
        .intro { margin: 0 0 10px; }
        .info { width: 100%; border-collapse: collapse; margin: 0 0 12px; }
        .info td { padding: 2px 4px; vertical-align: top; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; margin: 10px 0; }
        .items th, .items td { border: 1px solid #222; padding: 6px 5px; vertical-align: top; }
        .items th { text-align: center; font-weight: bold; background: #f1f1f1; }
        .center { text-align: center; }
        .right { text-align: right; }
        .nowrap { white-space: nowrap; }
        .total td { font-weight: bold; }
        .note { margin-top: 8px; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 24px; page-break-inside: avoid; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; padding: 4px 12px; }
        .sign-space { height: 62px; }
        .name-line { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="letterhead">
        <div class="line">{{ $judulHeaders->header1 ?? '' }}</div>
        <div class="line">{{ $judulHeaders->header4 ?? '' }}</div>
        <div class="name">{{ $judulHeaders->nama_rs ?? '' }}</div>
        <div class="address">{{ $judulHeaders->alamat_rs ?? '' }}</div>
        <div class="address">Telp. {{ $judulHeaders->tlp_rs ?? '' }} &nbsp; | &nbsp; {{ $judulHeaders->email_rs ?? '' }}</div>
    </div>

    <h1>SURAT PESANAN</h1>
    <div class="number">Nomor: {{ $detail_master_spj->no_surat_pesanan ?? '-' }}</div>

    <p class="intro">Yang bertanda tangan di bawah ini memesan barang/jasa kepada:</p>
    <table class="info">
        <tr><td width="24%">Nama penyedia</td><td width="2%">:</td><td>{{ $detail_master_spj->nama_perusahaan ?? '-' }}</td></tr>
        <tr><td>Alamat</td><td>:</td><td>{{ $detail_master_spj->alamat_perusahaan ?? $detail_master_spj->alamat ?? '-' }}</td></tr>
        <tr><td>NPWP</td><td>:</td><td>{{ $detail_master_spj->no_npwp ?? '-' }}</td></tr>
        <tr><td>Nomor paket</td><td>:</td><td>{{ $detail_master_spj->idpaket ?? '-' }}</td></tr>
        <tr><td>Nomor usulan</td><td>:</td><td>{{ $spj->no_usulan_belanja ?? '-' }}</td></tr>
        <tr><td>Tanggal pesanan</td><td>:</td><td>{{ $tgl_pesanan }}</td></tr>
        <tr><td>Jangka waktu</td><td>:</td><td>{{ $selisih_hari + 1 }} ({{ $jumlah_hari_terbilang }}) hari kalender, sampai dengan {{ $tgl_pekerjaan }}</td></tr>
    </table>

    <p>Dengan rincian barang/jasa sebagai berikut:</p>
    <table class="items">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="31%">Nama barang/jasa dan spesifikasi</th>
                <th width="10%">Volume</th>
                <th width="10%">Satuan</th>
                <th width="17%">Harga satuan</th>
                <th width="17%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($keranjang as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong>@if($item->spek_detail && $item->spek_detail !== '-')<br>{{ $item->spek_detail }}@endif</td>
                    <td class="center">{{ number_format((float) $item->jumlah_usulan_barang, 0, ',', '.') }}</td>
                    <td class="center">{{ $item->satuan_1_detail }}</td>
                    <td class="right nowrap">Rp {{ number_format((float) $item->harga_barang, 0, ',', '.') }}</td>
                    <td class="right nowrap">Rp {{ number_format((float) $item->total_anggaran_usulan_barang, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="center">Tidak ada rincian barang/jasa.</td></tr>
            @endforelse
            <tr class="total"><td colspan="5" class="right">Total termasuk pajak</td><td class="right nowrap">Rp {{ number_format((float) $get_total, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>
    <p class="note">Terbilang: <em>{{ ucfirst($terbilang_total) }} rupiah.</em></p>
    <p class="note">Untuk mengirimkan barang dengan memperhatikan ketentuan-ketentuan sebagai berikut:</p>
    <table class="info">
        <tr><td width="3%">1.</td><td width="25%">Tanggal diterima</td><td width="2%">:</td><td>{{ $tgl_pesanan }} s/d {{ $tgl_pekerjaan }}</td></tr>
        <tr><td>2.</td><td>Syarat pekerjaan</td><td>:</td><td>Sesuai dengan persyaratan dan ketentuan di dalam kontrak.</td></tr>
        <tr><td>3.</td><td>Waktu penyelesaian</td><td>:</td><td>Selama {{ $selisih_hari + 1 }} ({{ $jumlah_hari_terbilang }}) hari kalender dan pekerjaan harus sudah selesai pada tanggal {{ $tgl_pekerjaan }}.</td></tr>
        <tr><td>4.</td><td>Alamat pengiriman barang</td><td>:</td><td>{{ $judulHeaders->alamat_rs ?? '-' }}</td></tr>
        <tr><td>5.</td><td>Denda</td><td>:</td><td>Setiap hari keterlambatan penyelesaian pekerjaan, penyedia barang/jasa akan dikenakan denda keterlambatan sebesar 1/1000 (satu per seribu) dari bagian kontrak yang belum dikerjakan.</td></tr>
    </table>

    <table class="signatures">
        <tr>
            <td></td>
            <td>{{ $judulHeaders->wilayah ?? 'Jakarta' }}, {{ $tgl_pesanan }}</td>
        </tr>
        <tr>
            <td>Pejabat Pembuat Komitmen<br>{{ $judulHeaders->nama_rs ?? '' }}</td>
            <td>Pejabat Pengadaan Barang/Jasa<br>{{ $judulHeaders->nama_rs ?? '' }}</td>
        </tr>
        <tr>
            <td class="sign-space"></td><td class="sign-space"></td>
        </tr>
        <tr>
            <td><span class="name-line">{{ $detail_master_spj->nama_ppk }}</span><br>NIP. {{ $detail_master_spj->nip_ppk }}</td>
            <td><span class="name-line">{{ $detail_master_spj->nama_ppbj }}</span><br>NIP. {{ $detail_master_spj->nip_ppbj }}</td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 14px">Untuk dan atas nama<br>{{ $detail_master_spj->nama_perusahaan ?? '-' }}</td>
        </tr>
        <tr><td colspan="2" class="sign-space"></td></tr>
        <tr>
            <td colspan="2"><span class="name-line">{{ $detail_master_spj->nama_direktur_perusahaan ?? '................................' }}</span><br>{{ $detail_master_spj->jabatan_perusahaan ?? 'Direktur' }}</td>
        </tr>
    </table>
</body>
</html>
