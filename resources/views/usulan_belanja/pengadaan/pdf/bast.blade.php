<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Berita Acara</title>
    <style>
        @page { margin: 18mm; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 10pt; line-height: 1.4; }
        .letterhead { text-align: center; border-bottom: 3px double #111; padding: 0 0 9px; margin-bottom: 16px; }
        .letterhead div { font-size: 9pt; }
        .letterhead .name { font-weight: bold; font-size: 14pt; margin: 2px 0; }
        h1 { font-size: 12pt; text-align: center; text-decoration: underline; margin: 12px 0 3px; }
        .doc-number { text-align: center; margin-bottom: 2px; }
        .doc-meta { text-align: center; font-size: 9pt; margin-bottom: 16px; }
        .info { width: 100%; border-collapse: collapse; margin: 8px 0 13px; }
        .info td { padding: 3px 4px; vertical-align: top; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; margin: 10px 0 14px; }
        .items th, .items td { border: 1px solid #222; padding: 6px 5px; vertical-align: top; }
        .items th { background: #f1f1f1; text-align: center; }
        .center { text-align: center; }
        .right { text-align: right; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 24px; page-break-inside: avoid; }
        .signatures td { width: 50%; text-align: center; vertical-align: top; padding: 4px 10px; }
        .sign-space { height: 65px; }
        .name-line { font-weight: bold; text-decoration: underline; }
        .keep { page-break-inside: avoid; }
    </style>
</head>
<body>
    @if($documentType === 'pemeriksaan-pekerjaan')
        <div class="letterhead">
            <div>{{ $judulHeaders->header1 ?? '' }}</div>
            <div><strong>{{ $judulHeaders->header4 ?? '' }}</strong></div>
            <div class="name">{{ $judulHeaders->nama_rs ?? '' }}</div>
            <div>{{ $judulHeaders->alamat_rs ?? '' }}</div>
            <div>Telp. {{ $judulHeaders->tlp_rs ?? '' }}</div>
            <div>Website: {{ $judulHeaders->email_rs ?? '' }}</div>
            <div style="text-align: right; padding-right: 8%">Kode Pos : {{ $judulHeaders->kode_pos ?? '' }}</div>
        </div>
        <h1>{{ $documentTitle }}</h1>
        <div class="doc-number">Nomor: {{ $documentNumber ?? '-' }}</div>
        <div class="doc-meta">Nomor Usulan: {{ $spj->no_usulan_belanja ?? '-' }}</div>
        <p>Pada hari ini, {{ $tanggalPemeriksaanLong }} bertempat di {{ $judulHeaders->nama_rs ?? '-' }} telah diadakan pemeriksaan barang/jasa sebagai berikut:</p>
        <table class="info">
            <tr><td width="5%">1.</td><td width="29%">Nama Pekerjaan</td><td width="2%">:</td><td>{{ $mainItem?->kode_rekening_belanja ?? '-' }}. {{ $mainItem?->nama_rekening_belanja ?? '' }}</td></tr>
            <tr><td>2.</td><td>Nomor Surat Pesanan</td><td>:</td><td>{{ $detail_master_spj->no_surat_pesanan ?? '-' }}</td></tr>
            <tr><td>3.</td><td>Tanggal Surat Pesanan</td><td>:</td><td>{{ $tgl_pesanan }}</td></tr>
        </table>
        <p>Dengan Rincian sebagai berikut:</p>
        <table class="items">
            <thead><tr><th width="7%">No</th><th width="45%">Nama Barang/Jasa</th><th width="13%">Volume</th><th width="15%">Satuan</th><th width="20%">Hasil Pemeriksaan</th></tr></thead>
            <tbody>
                @forelse($keranjang as $index => $item)
                    <tr><td class="center">{{ $index + 1 }}</td><td>{{ $item->nama_barang ?? '-' }}</td><td class="center">{{ number_format((float) $item->jumlah_usulan_barang, 2, '.', ',') }}</td><td class="center">{{ $item->satuan_1_detail ?? '-' }}</td><td class="center">Sesuai</td></tr>
                @empty
                    <tr><td colspan="5" class="center">Tidak ada rincian barang/jasa.</td></tr>
                @endforelse
            </tbody>
        </table>
        <p>Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenarnya dalam rangkap 2 (dua) untuk dipergunakan sebagaimana mestinya.</p>
        <table class="signatures">
            <tr>
                <td width="33%">Hormat kami,<br>Pelaksana Pekerjaan<br>{{ $detail_master_spj->nama_perusahaan ?? '-' }}</td>
                <td width="33%">Pejabat Pembuat Komitmen<br>{{ $judulHeaders->nama_rs ?? '' }}</td>
                <td width="34%">Tim Pendukung Pengadaan Barang dan Jasa<br>{{ $judulHeaders->nama_rs ?? '' }}</td>
            </tr>
            <tr>
                <td class="sign-space"></td><td class="sign-space"></td>
                <td><table class="info" style="margin: 0"><tr><td>Nama</td><td>Tanda Tangan</td></tr></table></td>
            </tr>
            <tr>
                <td><span class="name-line">{{ $detail_master_spj->nama_direktur_perusahaan ?? '................................' }}</span><br>{{ $detail_master_spj->jabatan_perusahaan ?? 'Direktur' }}</td>
                <td><span class="name-line">{{ $detail_master_spj->nama_ppk }}</span><br>NIP {{ $detail_master_spj->nip_ppk }}</td>
                <td><table class="info" style="margin: 0"><tr><td>{{ $teamPendukung?->nama ?? '................................' }}</td><td>(____________)</td></tr></table></td>
            </tr>
        </table>
    @else
    <div class="letterhead">
        <div>{{ $judulHeaders->header1 ?? '' }}</div>
        <div>{{ $judulHeaders->header4 ?? '' }}</div>
        <div class="name">{{ $judulHeaders->nama_rs ?? '' }}</div>
        <div>{{ $judulHeaders->alamat_rs ?? '' }}</div>
        <div>Telp. {{ $judulHeaders->tlp_rs ?? '' }} | {{ $judulHeaders->email_rs ?? '' }}</div>
    </div>

    @php
    @endphp

    <h1>{{ $documentTitle }}</h1>
    <div class="doc-number">Nomor: {{ $documentNumber ?? '-' }}</div>
    <div class="doc-meta">Nomor Usulan: {{ $spj->no_usulan_belanja ?? '-' }}</div>

    <p>Pada hari ini, {{ $converttotekstglbast }}, bertempat di {{ $judulHeaders->nama_rs ?? '-' }}, telah dilaksanakan
        @if($documentType === 'pemeriksaan-pekerjaan') pemeriksaan barang/jasa @else serah terima barang/pekerjaan @endif
        dengan keterangan sebagai berikut:</p>

    <table class="info">
        <tr><td width="27%">Nama pekerjaan/kegiatan</td><td width="2%">:</td><td>{{ $mainItem?->kode_sub_kategori_rkbu }} {{ $mainItem?->nama_sub_kategori_rkbu ?? '-' }}</td></tr>
        <tr><td>Kode rekening</td><td>:</td><td>{{ $mainItem?->kode_rekening_belanja ?? '-' }} {{ $mainItem?->nama_rekening_belanja ?? '' }}</td></tr>
        <tr><td>Nomor Surat Pesanan</td><td>:</td><td>{{ $detail_master_spj->no_surat_pesanan ?? '-' }}</td></tr>
        <tr><td>Tanggal Surat Pesanan</td><td>:</td><td>{{ $tgl_pesanan }}</td></tr>
        @if($documentType !== 'pemeriksaan-pekerjaan')
            <tr><td>Nomor DPA</td><td>:</td><td>{{ $detail_master_spj->no_dpa ?? '-' }}</td></tr>
        @endif
    </table>

    @if($documentType === 'serah-terima-hasil')
        <p><strong>PIHAK PERTAMA</strong> adalah Pejabat Pembuat Komitmen {{ $judulHeaders->nama_rs ?? '' }}:</p>
        <table class="info">
            <tr><td width="27%">Nama</td><td width="2%">:</td><td>{{ $detail_master_spj->nama_ppk }}</td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $detail_master_spj->nip_ppk }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>Pejabat Pembuat Komitmen</td></tr>
            <tr><td>Alamat</td><td>:</td><td>{{ $alamat }}</td></tr>
        </table>
        <p><strong>PIHAK KEDUA</strong> adalah penyedia/pelaksana pekerjaan:</p>
        <table class="info">
            <tr><td width="27%">Nama penyedia</td><td width="2%">:</td><td>{{ $detail_master_spj->nama_perusahaan ?? '-' }}</td></tr>
            <tr><td>Nama direktur</td><td>:</td><td>{{ $detail_master_spj->nama_direktur_perusahaan ?? '-' }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>{{ $detail_master_spj->jabatan_perusahaan ?? '-' }}</td></tr>
            <tr><td>Alamat</td><td>:</td><td>{{ $detail_master_spj->alamat_perusahaan ?? '-' }}</td></tr>
        </table>
        <p>Dengan ini <strong>PIHAK KEDUA</strong> menyerahkan hasil pekerjaan sesuai Surat Pesanan Nomor
            {{ $detail_master_spj->no_surat_pesanan ?? '-' }} tanggal {{ $tgl_pesanan }} kepada <strong>PIHAK PERTAMA</strong>.
            Berdasarkan hasil pemeriksaan, pekerjaan telah mencapai prestasi 100% dan dapat dibayarkan sebesar
            <strong>Rp {{ number_format((float) $get_total, 0, ',', '.') }}</strong>
            ({{ ucfirst($terbilang_total) }} rupiah).</p>
    @elseif($documentType === 'serah-terima-barang')
        <p>Berdasarkan SK Gubernur Provinsi DKI Jakarta Nomor 29 Tahun 2023 tentang Penunjukan Pengurus Barang {{ $namaRsDokumen }}, Pengurus Barang telah menerima
            barang/jasa kebutuhan {{ $namaRsDokumen }} yang diserahkan oleh Pejabat Pembuat Komitmen:</p>
        <table class="info">
            <tr><td width="27%">Nama Pengurus Barang</td><td width="2%">:</td><td>{{ $pengurus_barang->nama_pengurus_barang }}</td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $pengurus_barang->nip_pengurus_barang }}</td></tr>
            <tr><td>Nama PPK</td><td>:</td><td>{{ $detail_master_spj->nama_ppk }}</td></tr>
            <tr><td>NIP PPK</td><td>:</td><td>{{ $detail_master_spj->nip_ppk }}</td></tr>
        </table>
        <p>Berdasarkan Surat Pesanan tersebut, barang/jasa yang diserahkan dinyatakan baik dan sesuai. Kesimpulan:</p>
        <p>a. Barang/jasa diterima dalam keadaan baik dan sesuai Surat Pesanan.<br>b. Barang/jasa telah diperiksa dan dicatat untuk kebutuhan {{ $namaRsDokumen }}.</p>
    @else
        <p>Pejabat yang bertanda tangan telah melakukan pemeriksaan atas barang/jasa yang diserahkan oleh penyedia dan menyatakan hasil pemeriksaan sebagai berikut:</p>
    @endif

    <p>Dengan rincian sebagai berikut:</p>
    <table class="items">
        <thead><tr><th width="6%">No.</th><th width="42%">Nama barang/jasa</th><th width="12%">Volume</th><th width="14%">Satuan</th><th width="26%">{{ $documentType === 'pemeriksaan-pekerjaan' ? 'Hasil pemeriksaan' : 'Jumlah' }}</th></tr></thead>
        <tbody>
            @forelse($keranjang as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}@if($item->spek_detail && $item->spek_detail !== '-')<br><small>{{ $item->spek_detail }}</small>@endif</td>
                    <td class="center">{{ number_format((float) $item->jumlah_usulan_barang, 0, ',', '.') }}</td>
                    <td class="center">{{ $item->satuan_1_detail }}</td>
                    <td class="{{ $documentType === 'pemeriksaan-pekerjaan' ? 'center' : 'right' }}">{{ $documentType === 'pemeriksaan-pekerjaan' ? 'Sesuai' : 'Rp ' . number_format((float) $item->total_anggaran_usulan_barang, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="center">Tidak ada rincian barang/jasa.</td></tr>
            @endforelse
            @if($documentType !== 'pemeriksaan-pekerjaan')
                <tr><td colspan="4" class="right"><strong>Total</strong></td><td class="right"><strong>Rp {{ number_format((float) $get_total, 0, ',', '.') }}</strong></td></tr>
            @endif
        </tbody>
    </table>

    <p>Demikian Berita Acara ini dibuat dengan sebenarnya dalam rangkap 2 (dua) untuk dipergunakan sebagaimana mestinya.</p>
    <div class="keep">
        <table class="signatures">
            @if($documentType === 'pemeriksaan-pekerjaan')
                <tr><td colspan="2">{{ $judulHeaders->wilayah ?? 'Jakarta' }}, {{ $tgl_ba }}</td></tr>
                <tr><td colspan="2">Pejabat Pemeriksa Pekerjaan</td></tr>
                <tr><td colspan="2" class="sign-space"></td></tr>
                <tr><td colspan="2"><span class="name-line">{{ $detail_master_spj->nama_ppk }}</span><br>NIP. {{ $detail_master_spj->nip_ppk }}</td></tr>
            @elseif($documentType === 'serah-terima-barang')
                <tr><td>Yang Menyerahkan<br>Pejabat Pembuat Komitmen</td><td>Yang Menerima<br>Pengurus Barang</td></tr>
                <tr><td class="sign-space"></td><td class="sign-space"></td></tr>
                <tr><td><span class="name-line">{{ $detail_master_spj->nama_ppk }}</span><br>NIP. {{ $detail_master_spj->nip_ppk }}</td><td><span class="name-line">{{ $pengurus_barang->nama_pengurus_barang }}</span><br>NIP. {{ $pengurus_barang->nip_pengurus_barang }}</td></tr>
            @else
                <tr><td>{{ $judulHeaders->wilayah ?? 'Jakarta' }}, {{ $tgl_ba }}</td><td>{{ $judulHeaders->wilayah ?? 'Jakarta' }}, {{ $tgl_ba }}</td></tr>
                <tr><td><strong>PIHAK PERTAMA</strong><br>Pejabat Pembuat Komitmen</td><td><strong>PIHAK KEDUA</strong><br>{{ $detail_master_spj->nama_perusahaan ?? '-' }}</td></tr>
                <tr><td class="sign-space"></td><td class="sign-space"></td></tr>
                <tr><td><span class="name-line">{{ $detail_master_spj->nama_ppk }}</span><br>NIP. {{ $detail_master_spj->nip_ppk }}</td><td><span class="name-line">{{ $detail_master_spj->nama_direktur_perusahaan ?? '................................' }}</span><br>{{ $detail_master_spj->jabatan_perusahaan ?? '' }}</td></tr>
            @endif
        </table>
    </div>
    @endif
</body>
</html>
