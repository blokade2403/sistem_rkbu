<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisa Kebutuhan RKBU</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #222; line-height: 1; mso-line-height-rule: exactly; }
        p, div { margin: 0; padding: 0; line-height: 100%; mso-line-height-rule: exactly; mso-margin-top-alt: 0; mso-margin-bottom-alt: 0; }
        h1 { text-align: center; font-size: 15pt; line-height: 1; margin: 3pt 0 2pt; text-transform: uppercase; }
        .kop { width: 100%; border-bottom: 3px double #222; margin-bottom: 14pt; }
        .kop td { border: none; text-align: center; padding: 0 8pt 6pt; line-height: 1; }
        .kop .header1 { font-weight: bold; font-size: 12pt; line-height: 14pt; mso-line-height-rule: exactly; }
        .kop .hospital { font-weight: bold; font-size: 15pt; line-height: 17pt; mso-line-height-rule: exactly; text-transform: uppercase; }
        .kop img { width: 56px; height: 56px; max-width: 56px; max-height: 56px; margin-bottom: 3pt; }
        .doc-title { text-align: center; margin: 8pt 0 10pt; font-size: 13pt; line-height: 1; mso-line-height-rule: exactly; }
        .doc-title div { margin: 0; padding: 0; line-height: 1; }
        .doc-title .main { font-size: 14pt; }
        .subtitle { text-align: center; font-size: 10pt; margin: 0 0 20pt; }
        h2 { font-size: 12pt; line-height: 1; margin: 12pt 0 5pt; color: #1f4e79; }
        table { width: 100%; border-collapse: collapse; margin: 5pt 0; }
        th, td { border: 1px solid #777; padding: 4pt 6pt; vertical-align: top; line-height: 1; mso-line-height-rule: exactly; }
        th { background: #eaf1f8; text-align: left; }
        .label { width: 27%; font-weight: bold; background: #f5f7fa; }
        .noborder td { border: none; padding: 4pt 0; }
        .memo td { padding: 0 2pt; vertical-align: middle; line-height: 12pt; mso-line-height-rule: exactly; mso-margin-top-alt: 0; mso-margin-bottom-alt: 0; }
        .memo tr.short-row { height: 22pt; mso-height-rule: exactly; }
        .memo tr.hal-row { height: 38pt; mso-height-rule: at-least; }
        .data-table { font-size: 8pt; }
        .data-table th { text-align: center; vertical-align: middle; }
        .signatures { margin-top: 24pt; }
        .signatures td { border: none; text-align: center; vertical-align: top; padding: 3pt 8pt; line-height: 12pt; }
        .signature-space { height: 48pt; }
        .preview-actions { display: flex; justify-content: flex-end; gap: 8px; margin: 0 0 16pt; padding: 10px 12px; background: #f1f5f9; border: 1px solid #dbe3ed; }
        .preview-actions a { padding: 8px 12px; color: #fff; background: #0d6efd; text-decoration: none; border-radius: 4px; font: 600 10pt Arial, sans-serif; }
        @media screen { body.preview-mode { box-sizing: border-box; max-width: 210mm; min-height: 297mm; margin: 24px auto; padding: 18mm; background: #fff; box-shadow: 0 2px 18px #0002; } }
        @media print { .preview-actions { display: none; } }
    </style>
</head>
<body class="{{ !empty($preview) ? 'preview-mode' : '' }}">
    @if (!empty($preview))
        <div class="preview-actions"><a href="{{ $downloadUrl }}"><i class="fas fa-file-word"></i> Unduh dokumen .doc</a></div>
    @endif
    @php
        $namaRs = $header?->nama_rs ?? 'Rumah Sakit';
        $namaJenis = $barjas->rkbu?->subKategoriRkbu?->kategori?->jenis_kategori_rkbu?->nama_jenis_kategori_rkbu ?? '';
        $jenisBarang = str_contains(strtolower($namaJenis), 'kesehatan') ? 'alat Kesehatan' : 'alat kantor';
    @endphp
    <table class="kop">
        <tr><td>
            @if ($logo)<img src="{{ $logo }}" alt="Logo instansi" width="56" height="56" style="width:56px;height:56px;max-width:56px;max-height:56px;display:block;margin:0 auto 2pt;"><br>@endif
            @if ($header?->header1)<div class="header1" style="margin:0;padding:0;line-height:14pt;mso-line-height-rule:exactly;">{{ $header->header1 }}</div>@endif
            <div class="hospital" style="margin:0;padding:0;line-height:17pt;mso-line-height-rule:exactly;">{{ $namaRs }}</div>
        </td></tr>
    </table>
    <div class="doc-title"><div class="main">KAP (KERANGKA ACUAN PERENCANAAN)</div><div>TELAAHAN DAN ANALISA STAF</div></div>

    <table class="noborder memo" style="margin: 0 0 10pt;">
        <tr class="short-row"><td style="width: 20%;">Kepada</td><td style="width: 3%;">:</td><td>Yth. Direktur {{ $namaRs }}</td></tr>
        <tr class="short-row"><td>Dari</td><td>:</td><td>{{ $kabidPosition }}</td></tr>
        <tr class="short-row"><td>Nomor</td><td>:</td><td>KAP {{ $barjas->rkbu->nomor_rkbu ?? '-' }}</td></tr>
        <tr class="hal-row"><td>Hal</td><td>:</td><td>Kajian Kebutuhan Barang/Jasa Dalam Rangka belanja modal {{ $jenisBarang }} ({{ $barjas->nama_barang }}) di {{ $namaRs }}.</td></tr>
    </table>

    <h1>Analisa Kebutuhan RKBU</h1>
    <table class="noborder">
        <tr><td><strong>Tahun Anggaran</strong></td><td>{{ $barjas->rkbu->tahunAnggaran->nama_tahun_anggaran ?? '-' }}</td><td><strong>Unit Pengusul</strong></td><td>{{ $barjas->rkbu->unit->nama_unit ?? '-' }}</td></tr>
        <tr><td><strong>Kategori</strong></td><td colspan="3">{{ $namaJenis ?: '-' }}</td></tr>
    </table>

    <h2>A. Identitas Barang</h2>
    <table>
        <tr><td class="label">Nama Barang / Komponen</td><td>{{ $barjas->nama_barang ?? '-' }}</td></tr>
        <tr><td class="label">Subkategori</td><td>{{ $barjas->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td></tr>
        <tr><td class="label">Spesifikasi</td><td>{!! nl2br(e($barjas->spesifikasi ?: '-')) !!}</td></tr>
        <tr><td class="label">Volume Usulan</td><td>{{ $barjas->jumlah_volume ?? '-' }} {{ $barjas->satuan_1 ?? '' }}</td></tr>
        <tr><td class="label">User / Pengguna</td><td>{{ $barjas->user_pengguna ?? '-' }}</td></tr>
    </table>

    <h2>B. Analisa Kebutuhan</h2>
    <table>
        <tr><td class="label">Latar Belakang</td><td>{!! nl2br(e($barjas->latar_belakang ?: '-')) !!}</td></tr>
        <tr><td class="label">Pembahasan / Analisis</td><td>{!! nl2br(e($barjas->pembahasan_analisis ?: '-')) !!}</td></tr>
        <tr><td class="label">Maksud dan Tujuan</td><td>{!! nl2br(e($barjas->maksud_tujuan ?: '-')) !!}</td></tr>
        <tr><td class="label">Target dan Sasaran</td><td>{!! nl2br(e($barjas->target_sasaran ?: '-')) !!}</td></tr>
    </table>

    <h2>C. Standar dan Kondisi Eksisting</h2>
    <table>
        <tr><th>Standar Kebutuhan</th><th>Eksisting</th><th>Kondisi Baik</th><th>Kondisi Rusak Berat</th></tr>
        <tr><td>{{ $barjas->standar_kebutuhan ?? '-' }} unit</td><td>{{ $barjas->eksisting ?? '-' }} unit</td><td>{{ $barjas->kondisi_baik ?? '-' }} unit</td><td>{{ $barjas->kondisi_rusak_berat ?? '-' }} unit</td></tr>
    </table>

    <div class="doc-title"><div class="subtitle">DATA DASAR KEBUTUHAN {{ strtoupper($jenisBarang) }}</div>
    {{-- <h3>DATA DASAR KEBUTUHAN {{ strtoupper($jenisBarang) }}</h3> --}}
    <p class="subtitle">{{ $namaRs }}<br>{{ $header?->alamat_rs ?? '' }}<br>TAHUN {{ $barjas->rkbu->tahunAnggaran->nama_tahun_anggaran ?? now()->year }}</p>
    </div>
    <table class="data-table">
        <tr>
            <th rowspan="2">NO</th><th rowspan="2">JENIS ALAT / BARANG</th>
            <th colspan="2">Standar Kebutuhan</th><th colspan="2">Eksisting</th>
            <th colspan="2">Usulan TA. {{ $barjas->rkbu->tahunAnggaran->nama_tahun_anggaran ?? now()->year }}</th><th rowspan="2">KETERANGAN</th>
        </tr>
        <tr><th>Volume</th><th>Satuan</th><th>Volume</th><th>Satuan</th><th>Volume</th><th>Satuan</th></tr>
        <tr><td style="text-align:center">(1)</td><td style="text-align:center">(2)</td><td style="text-align:center">(3)</td><td style="text-align:center">(4)</td><td style="text-align:center">(5)</td><td style="text-align:center">(6)</td><td style="text-align:center">(7)</td><td style="text-align:center">(8)</td><td style="text-align:center">(9)</td></tr>
        <tr>
            <td style="text-align:center">1</td>
            <td>{{ $barjas->nama_barang }}@if($barjas->spesifikasi)<br>{{ $barjas->spesifikasi }}@endif</td>
            <td style="text-align:center">{{ $barjas->standar_kebutuhan ?? '-' }}</td><td style="text-align:center">{{ $barjas->satuan_1 ?: 'Unit' }}</td>
            <td style="text-align:center">{{ $barjas->eksisting ?? '-' }}</td><td style="text-align:center">{{ $barjas->satuan_1 ?: 'Unit' }}</td>
            <td style="text-align:center">{{ $barjas->jumlah_volume ?? '-' }}</td><td style="text-align:center">{{ $barjas->satuan_1 ?: 'Unit' }}</td>
            <td>{!! nl2br(e($barjas->pembahasan_analisis ?: '-')) !!}</td>
        </tr>
    </table>

    <table class="noborder signatures">
        <tr>
            <td style="width:50%;">{{ $barjas->rkbu->unit->nama_unit ?? 'Unit Pengusul' }}, {{ now()->format('d/m/Y') }}<br><strong>Pengusul</strong><br>{{ $signatureHierarchy['pengusul']['position'] }}<div class="signature-space"></div><strong>{{ $signatureHierarchy['pengusul']['name'] ?: '........................................' }}</strong><br>NIP. {{ $signatureHierarchy['pengusul']['nip'] ?: '-' }}</td>
            <td style="width:50%;">{{ $header?->wilayah ?? 'Jakarta' }}, {{ now()->format('d/m/Y') }}<br><strong>KSP / Ka.Ins</strong><br>{{ $signatureHierarchy['ksp']['position'] }}<div class="signature-space"></div><strong>{{ $signatureHierarchy['ksp']['name'] ?: '........................................' }}</strong><br>NIP. {{ $signatureHierarchy['ksp']['nip'] ?: '-' }}</td>
        </tr>
        <tr><td colspan="2" style="padding-top:10pt;"><strong>Mengetahui,</strong><br><strong>Kabag / Kabid</strong><br>{{ $signatureHierarchy['kabid']['position'] }}<div class="signature-space"></div><strong>{{ $signatureHierarchy['kabid']['name'] ?: '........................................' }}</strong><br>NIP. {{ $signatureHierarchy['kabid']['nip'] ?: '-' }}</td></tr>
    </table>
</body>
</html>
