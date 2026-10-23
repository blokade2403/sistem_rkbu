<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Usulan Belanja {{ $usulan->no_usulan_belanja }}</title>
    <style>
        @page { margin: 25px 32px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #111; }
        .letterhead { width: 100%; border-bottom: 1px solid #111; margin-bottom: 12px; padding-bottom: 7px; }
        .letterhead td { border: 0; text-align: center; vertical-align: middle; }
        .logo { max-width: 58px; max-height: 58px; }
        .heading { line-height: 1.4; font-size: 9px; }
        .heading .agency { font-size: 10px; }
        .heading .hospital { font-size: 12px; font-weight: bold; }
        .heading .address { font-size: 8px; }
        h1 { text-align: center; font-size: 13px; margin: 14px 0 20px; }
        .metadata { width: 100%; border-collapse: collapse; margin-bottom: 13px; }
        .metadata td { border: 0; padding: 2px 3px; vertical-align: top; }
        .metadata .label { width: 86px; }
        .metadata .colon { width: 10px; }
        .metadata .right-label { width: 68px; }
        .metadata .right-colon { width: 9px; }
        .amount { font-weight: bold; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items th, .items td { border: 1px solid #777; padding: 4px 3px; vertical-align: top; }
        .items th { text-align: center; font-weight: normal; }
        .items td.center, .items th.center { text-align: center; }
        .items td.right { text-align: right; white-space: nowrap; }
        .items .subtotal td, .items .grand-total td { border: 0; font-weight: bold; }
        .items .subtotal td { padding-top: 12px; }
        .items .grand-total td { border-top: 1px solid #777; }
        .summary { width: 100%; margin-top: 7px; }
        .summary td { border: 0; padding: 2px 4px; }
        .summary .value { width: 120px; text-align: right; font-weight: bold; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 22px; page-break-inside: avoid; }
        .signatures td { border: 0; width: 33.33%; text-align: center; vertical-align: top; padding: 4px 7px; }
        .sign-title { min-height: 25px; }
        .sign-space { height: 52px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
        .note { margin-top: 24px; page-break-inside: avoid; }
        .small { font-size: 7px; }
    </style>
</head>
<body>
    @php
        $subkategori = $rkbu?->subKategoriRkbu;
        $kategori = $subkategori?->kategori;
        $jenisKategori = $kategori?->jenis_kategori_rkbu;
        $rekening = $subkategori?->sub_kategori_rekening;
        $rekeningKategori = $rekening?->kategori_rekening;
        $subKegiatan = $rkbu?->subKegiatan;
        $kegiatan = $subKegiatan?->kegiatan;
        $program = $subKegiatan?->aktivitas?->program ?? $kegiatan?->program;
    @endphp

    <table class="letterhead">
        <tr>
            <td width="12%">@if($logoKiri)<img class="logo" src="{{ $logoKiri }}">@endif</td>
            <td width="76%" class="heading">
                @if($judulHeader?->header1)<div>{{ $judulHeader->header1 }}</div>@endif
                @if($judulHeader?->header2)<div class="agency">{{ $judulHeader->header2 }}</div>@endif
                <div class="hospital">{{ $judulHeader?->nama_rs ?? 'Instansi' }}</div>
                @if($judulHeader?->alamat_rs)<div class="address">{{ $judulHeader->alamat_rs }}</div>@endif
                @if($judulHeader?->tlp_rs)<div class="address">Telp. {{ $judulHeader->tlp_rs }}</div>@endif
                @if($judulHeader?->header3)<div class="address">{{ $judulHeader->header3 }}</div>@endif
            </td>
            <td width="12%">@if($logoKanan)<img class="logo" src="{{ $logoKanan }}">@endif</td>
        </tr>
    </table>

    <h1>USULAN BARANG DAN JASA</h1>

    <table class="metadata">
        <tr>
            <td class="label">Nomor</td><td class="colon">:</td><td width="43%"><strong>{{ $usulan->no_usulan_belanja }}</strong></td>
            <td class="right-label">Pengusul</td><td class="right-colon">:</td><td>{{ $usulan->nama_pengusul ?? $usulan->pengusul?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }} {{ $program?->nama_program ?? '' }}</td>
            <td class="right-label">Unit</td><td class="right-colon">:</td><td>{{ $usulan->unit?->nama_unit ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }} {{ $kegiatan?->nama_kegiatan ?? '' }}</td>
            <td class="right-label">Sumber Dana</td><td class="right-colon">:</td><td>{{ $rkbu?->sumberDana?->nama_sumber_dana ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Rekening Belanja</td><td class="colon">:</td><td colspan="4">{{ $rekeningKategori?->kode_kategori_rekening }} {{ $rekeningKategori?->nama_kategori_rekening }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td><td class="colon">:</td><td colspan="4">{{ $jenisKategori?->kode_jenis_kategori_rkbu }} {{ $jenisKategori?->nama_jenis_kategori_rkbu }} / {{ $kategori?->kode_kategori_rkbu }} {{ $kategori?->nama_kategori_rkbu }}</td>
        </tr>
        <tr>
            <td class="label">Sub Kategori</td><td class="colon">:</td><td colspan="4">{{ $subkategori?->kode_sub_kategori_rkbu }} {{ $subkategori?->nama_sub_kategori_rkbu }}</td>
        </tr>
        <tr>
            <td class="label amount">Anggaran</td><td class="colon">:</td><td class="amount">Rp {{ number_format($anggaranRkbu, 0, ',', '.') }}</td>
            <td class="right-label amount">Sisa Anggaran</td><td class="right-colon">:</td><td class="amount">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th rowspan="2" width="4%">No</th>
                <th rowspan="2" width="22%">Nama / Jenis Barang</th>
                <th rowspan="2" width="23%">Spesifikasi Merk/Ukuran</th>
                <th rowspan="2" width="12%">Harga Satuan</th>
                <th colspan="4" width="25%">Uraian Koefisien</th>
                <th rowspan="2" width="14%">Jumlah Biaya</th>
            </tr>
            <tr><th width="6%">Koef.1</th><th width="7%">Uraian</th><th width="6%">Koef.2</th><th width="6%">Uraian</th></tr>
            <tr class="small"><th>1</th><th>2</th><th>3</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>11=(8x6x5)</th></tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                @php
                    $volume1 = (float) ($item->volume_1 ?? 0);
                    $volume2 = (float) ($item->volume_2 ?? 0);
                    $satuan1 = $item->satuan_1 ?? '-';
                    if ($volume1 <= 0 && $item->detail_type === 'persediaan') {
                        $volume1 = (float) $item->volume;
                    }
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang ?? '-' }}<div class="small">Nomor RKBU: {{ $item->nomor_rkbu ?? '-' }}</div></td>
                    <td>{{ $item->spesifikasi ?: '-' }}</td>
                    <td class="right">{{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="center">{{ rtrim(rtrim(number_format($volume1, 2, ',', '.'), '0'), ',') }}</td>
                    <td class="center">{{ $satuan1 }}</td>
                    <td class="center">{{ $volume2 > 0 ? rtrim(rtrim(number_format($volume2, 2, ',', '.'), '0'), ',') : '-' }}</td>
                    <td class="center">{{ $volume2 > 0 ? ($item->satuan_2 ?? '-') : '-' }}</td>
                    <td class="right">{{ number_format((float) $item->total_anggaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="9" class="center">Tidak ada item usulan.</td></tr>
            @endforelse
            <tr class="subtotal"><td colspan="8" class="right">Sub Total</td><td class="right">{{ number_format($subtotal, 0, ',', '.') }}</td></tr>
            <tr class="subtotal"><td colspan="8" class="right">PPN</td><td class="right">{{ number_format($totalPpn, 0, ',', '.') }}</td></tr>
            <tr class="grand-total"><td colspan="8" class="right">Total Anggaran</td><td class="right">{{ number_format($totalAnggaran, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>

    <div class="small" style="text-align:right; margin-top:12px;">{{ $judulHeader?->wilayah ?? 'Jakarta' }}, {{ $printedAt->locale('id')->translatedFormat('d F Y') }}</div>

    @if($signatures->isNotEmpty())
        @foreach($signatures->chunk(3) as $signatureRow)
            <table class="signatures">
                <tr>
                    @foreach($signatureRow as $signature)
                        <td class="sign-title">
                            <strong>{{ $signature['jabatan'] }}</strong><br>
                            <span class="small">{{ $signature['status'] === 'approved' ? 'Disetujui' : ($signature['status'] === 'pending' ? 'Menunggu Validasi' : ucfirst($signature['status'])) }}</span>
                            @if($signature['tanggal'])<br><span class="small">{{ $signature['tanggal']->format('d-m-Y H:i') }}</span>@endif
                        </td>
                    @endforeach
                    @for($empty = $signatureRow->count(); $empty < 3; $empty++)<td></td>@endfor
                </tr>
                <tr>
                    @foreach($signatureRow as $signature)<td class="sign-space"></td>@endforeach
                    @for($empty = $signatureRow->count(); $empty < 3; $empty++)<td class="sign-space"></td>@endfor
                </tr>
                <tr>
                    @foreach($signatureRow as $signature)
                        <td class="sign-name">{{ $signature['nama'] }}</td>
                    @endforeach
                    @for($empty = $signatureRow->count(); $empty < 3; $empty++)<td></td>@endfor
                </tr>
                <tr>
                    @foreach($signatureRow as $signature)<td class="small">{{ $signature['nip'] ? 'NIP ' . $signature['nip'] : '' }}</td>@endforeach
                    @for($empty = $signatureRow->count(); $empty < 3; $empty++)<td></td>@endfor
                </tr>
            </table>
        @endforeach
    @endif

    <div class="note"><strong>Catatan:</strong> {{ $usulan->catatan ?: '-' }}</div>
</body>
</html>
