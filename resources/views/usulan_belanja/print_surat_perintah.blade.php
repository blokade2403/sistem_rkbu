<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Belanja {{ $usulan->no_usulan_belanja }}</title>
    <style>
        @page { margin: 28px 34px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .letterhead { width: 100%; border-bottom: 1px solid #111; margin-bottom: 18px; padding-bottom: 8px; }
        .letterhead td { border: 0; text-align: center; vertical-align: middle; }
        .logo { max-width: 62px; max-height: 62px; }
        .heading { line-height: 1.45; font-size: 10px; }
        .heading .agency { font-weight: bold; }
        .heading .hospital { font-size: 12px; }
        .address { font-size: 9px; }
        .postcode { text-align: right; font-size: 9px; padding-top: 2px; }
        .metadata { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .metadata td { border: 0; padding: 2px 3px; vertical-align: top; }
        .label { width: 72px; }
        .colon { width: 12px; }
        .right { text-align: right; }
        .recipient { text-align: center; line-height: 1.5; }
        .opening { text-align: center; line-height: 1.6; margin: 18px 6px; }
        .items { width: 88%; margin: 0 auto; border-collapse: collapse; table-layout: fixed; font-size: 8px; }
        .items th, .items td { border: 1px solid #777; padding: 5px 4px; vertical-align: top; }
        .items th { text-align: center; font-weight: normal; }
        .items td.center { text-align: center; }
        .items td.right { text-align: right; white-space: nowrap; }
        .items .summary-label { text-align: right; border-right: 0; border-left: 0; }
        .items .summary-value { text-align: right; border-left: 1px solid #777; }
        .closing { width: 88%; margin: 22px auto 0; line-height: 1.6; }
        .sign { width: 88%; margin: 46px auto 0; text-align: right; line-height: 1.5; page-break-inside: avoid; }
        .sign-name { margin-top: 55px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    @php
        $hospital = $judulHeader?->nama_rs ?? 'Rumah Sakit';
        $subkategori = $usulan->rkbu?->subKategoriRkbu;
        $subject = 'Belanja ' . ($subkategori?->nama_sub_kategori_rkbu ?? 'Pengadaan Barang/Jasa');
    @endphp

    <table class="letterhead">
        <tr>
            <td width="12%">@if($logoKiri)<img class="logo" src="{{ $logoKiri }}">@endif</td>
            <td width="76%" class="heading">
                @if($judulHeader?->header1)<div>{{ $judulHeader->header1 }}</div>@endif
                @if($judulHeader?->header2)<div class="agency">{{ $judulHeader->header2 }}</div>@endif
                <div class="hospital">{{ $hospital }}</div>
                @if($judulHeader?->alamat_rs)<div class="address">{{ $judulHeader->alamat_rs }}</div>@endif
                @if($judulHeader?->tlp_rs)<div class="address">Telp. {{ $judulHeader->tlp_rs }}</div>@endif
                @if($judulHeader?->header3)<div class="address">{{ $judulHeader->header3 }}</div>@endif
            </td>
            <td width="12%">@if($logoKanan)<img class="logo" src="{{ $logoKanan }}">@endif</td>
        </tr>
        @if($judulHeader?->kode_pos)
            <tr><td colspan="3" class="postcode">Kode Pos : {{ $judulHeader->kode_pos }}</td></tr>
        @endif
    </table>

    <table class="metadata">
        <tr>
            <td class="label">Nomor</td><td class="colon">:</td><td width="48%">{{ $usulan->no_usulan_belanja }}</td>
            <td colspan="3" class="right">{{ $printedAt->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr><td class="label">Sifat</td><td class="colon">:</td><td colspan="4">Penting</td></tr>
        <tr><td class="label">Lampiran</td><td class="colon">:</td><td colspan="4">-</td></tr>
        <tr>
            <td class="label">Hal</td><td class="colon">:</td><td>{{ $subject }}</td>
            <td colspan="3" class="recipient">Kepada<br>Yth. &nbsp; PPK {{ $hospital }}<br>di<br>{{ $judulHeader?->wilayah ?? 'Jakarta' }}</td>
        </tr>
    </table>

    <div class="opening">
        Bersama surat ini, mohon PPK {{ $hospital }} belanja dengan<br>
        no invoice <strong>{{ $usulan->no_usulan_belanja }}</strong> untuk melakukan pengadaan sebagai berikut terlampir:
    </div>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama / Jenis Barang</th>
                <th width="28%">Spesifikasi Merk/Ukuran</th>
                <th width="13%">Jumlah<br>Permintaan</th>
                <th width="14%">Harga Satuan</th>
                <th width="15%">Jumlah Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang ?? '-' }}</td>
                    <td>{{ $item->spesifikasi ?: '-' }}</td>
                    <td class="center">{{ rtrim(rtrim(number_format((float) $item->volume, 2, ',', '.'), '0'), ',') }} {{ $item->satuan_1 ?? '' }}</td>
                    <td class="right">{{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format((float) $item->total_anggaran, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="summary-label">Subtotal</td>
                <td class="summary-value">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="summary-label">PPN</td>
                <td class="summary-value">{{ number_format($totalPpn, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="summary-label"><strong>Total</strong></td>
                <td class="summary-value"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="closing">
        Demikian surat ini disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.
    </div>

    <div class="sign">
        Direktur {{ $hospital }}
        <div class="sign-name">dr. Mirsad, MPH</div>
        NIP 196909262002121003
    </div>
</body>
</html>
