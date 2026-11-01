<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>e-RBA Barang dan Jasa</title>
    <style>
        @page { margin: 24px 30px 32px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #111; }
        .letterhead { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .letterhead td { border: 0; text-align: center; vertical-align: middle; padding: 2px; }
        .logo { max-width: 52px; max-height: 52px; }
        .heading { line-height: 1.35; }
        .hospital { font-size: 12px; font-weight: bold; }
        .report-title { font-size: 10px; }
        .summary { width: 100%; border-collapse: collapse; margin: 0 0 14px; }
        .summary td { border: 0; padding: 2px 3px; vertical-align: top; }
        .summary .label { width: 105px; }
        .summary .colon { width: 10px; }
        .budget-group { background: #eee; font-weight: bold; }
        .items { width: 99.8%; border-collapse: collapse; table-layout: fixed; border-right: 1px solid #555; }
        .items th, .items td { border: 0.6px solid #555; padding: 4px 3px; vertical-align: top; }
        .items th:last-child, .items td:last-child { border-right: 1px solid #555; }
        .items th { text-align: center; font-weight: normal; }
        .items thead { display: table-header-group; }
        .items tr { page-break-inside: avoid; }
        .items .right { text-align: right; white-space: nowrap; }
        .items .center { text-align: center; }
        .items .total { background: #eee; font-weight: bold; }
        .empty { text-align: center; padding: 20px; }
    </style>
</head>
<body>
    @php
        $header = $judulHeader;
        $groupedItems = $items->groupBy(fn ($entry) => $entry['rkbu']->id_kode_rekening_belanja
            ?? $entry['rkbu']->sub_kategori_rkbu_id
            ?? 'tanpa-rekening');
        $firstEntry = $items->first();
        $firstRkbu = $firstEntry['rkbu'] ?? null;
        $program = $firstRkbu?->subKegiatan?->aktivitas?->program ?? $firstRkbu?->subKegiatan?->kegiatan?->program;
        $kegiatan = $firstRkbu?->subKegiatan?->kegiatan;
        $subKegiatan = $firstRkbu?->subKegiatan;
        $grandTotal = $items->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
        $currency = fn ($amount) => number_format((float) $amount, 0, ',', '.');
    @endphp

    <table class="letterhead">
        <tr>
            {{-- <td width="12%">@if ($headerLogoLeft)<img class="logo" src="{{ $headerLogoLeft }}">@endif</td> --}}
            <td width="76%" class="heading">
                @if ($header?->header1)<div>{{ $header->header1 }}</div>@endif
                <div>{{ $header?->nama_rs ?? 'Instansi' }}</div>
                @if ($header?->header2)<div>{{ $header->header2 }}</div>@endif
                {{-- @if ($header?->alamat_rs)<div>{{ $header->alamat_rs }}</div>@endif --}}
                {{-- <div class="report-title">RINGKASAN RENCANA BISNIS DAN ANGGARAN</div> --}}
                <div>TAHUN ANGGARAN {{ $firstRkbu?->tahunAnggaran?->nama_tahun_anggaran ?? $firstRkbu?->tahunAnggaran?->tahun ?? now()->year }}</div>
            </td>
            {{-- <td width="12%">@if ($headerLogoRight)<img class="logo" src="{{ $headerLogoRight }}">@endif</td> --}}
        </tr>
    </table>

    {{-- <table class="summary">
        <tr><td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }} {{ $program?->nama_program ?? '' }}</td></tr>
        <tr><td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }} {{ $kegiatan?->nama_kegiatan ?? '' }}</td></tr>
        <tr><td class="label">Sub Kegiatan</td><td class="colon">:</td><td>{{ $subKegiatan?->kode_sub_kegiatan ?? '-' }} {{ $subKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
        <tr><td class="label">Unit / Pengusul</td><td class="colon">:</td><td>{{ $firstRkbu?->unit?->nama_unit ?? '-' }} / {{ $firstRkbu?->creator?->nama ?? '-' }}</td></tr>
        <tr><td class="label">Alokasi Anggaran</td><td class="colon">:</td><td>Rp {{ $currency($grandTotal) }}</td></tr>
        <tr><td></td><td></td><td><em>({{ \Illuminate\Support\Number::spell((int) $grandTotal, locale: 'id') }} rupiah)</em></td></tr>
    </table> --}}

    <table class="items">
        <thead>
            <tr>
                <th rowspan="2" width="5%">No</th>
                <th rowspan="2" width="35%">Uraian</th>
                <th rowspan="2" width="12%">Satuan</th>
                <th rowspan="2" width="10%">Volume</th>
                <th rowspan="2" width="14%">Harga Satuan</th>
                <th rowspan="2" width="12%">Pajak</th>
                <th rowspan="2" width="12%">Jumlah</th>
            </tr>
            <tr></tr>
            <tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7= (4x5)+(4x5x6)</th></tr>
        </thead>
        <tbody>
            @forelse ($groupedItems as $groupItems)
                @php
                    $groupItems = collect($groupItems);
                    $groupTotal = $groupItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
                    $groupRkbu = $groupItems->first()['rkbu'];
                    $groupName = $groupRkbu->rekeningBelanja?->nama_rekening_belanja
                        ?? $groupRkbu->subKategoriRkbu?->nama_sub_kategori_rkbu;
                    $line = 0;
                @endphp
                <tr class="budget-group"><td colspan="7">{{ $groupName ?? 'Rekening belanja belum ditentukan' }}</td></tr>
                @foreach ($groupItems as $entry)
                    @foreach ($entry['details'] as $row)
                        @php
                            $line++;
                            $volume = (float) ($row['Jumlah Volume'] ?? $row['Jumlah Bulan'] ?? $row['Volume 1'] ?? 0);
                            $price = (float) ($row['Harga Satuan'] ?? $row['Gaji Pokok'] ?? 0);
                            $taxRate = (float) ($row['PPN (%)'] ?? 0);
                            $taxAmount = $volume * $price * $taxRate / 100;
                            $amount = (float) ($row['Total Anggaran'] ?? $row['Total Gaji Pokok'] ?? 0);
                            $unit = $row['Satuan'] ?? $row['Satuan 1'] ?? $row['Bulan'] ?? '-';
                            $description = $row['Nama Barang'] ?? $row['Nama Pegawai'] ?? '-';
                        @endphp
                        <tr>
                            <td class="center">{{ $line }}</td>
                            <td>{{ $description }}</td>
                            <td class="center">{{ $unit }}</td>
                            <td class="center">{{ rtrim(rtrim(number_format($volume, 2, ',', '.'), '0'), ',') }}</td>
                            <td class="right">{{ $currency($price) }}</td>
                            <td class="right">{{ $currency($taxAmount) }}</td>
                            <td class="right">{{ $currency($amount) }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="total"><td colspan="6">Total {{ $groupName ?? 'Barang dan Jasa' }}</td><td class="right">{{ $currency($groupTotal) }}</td></tr>
            @empty
                <tr><td class="empty" colspan="7">Tidak ada RKBU sesuai filter.</td></tr>
            @endforelse
            @if ($groupedItems->isNotEmpty())
                <tr class="total"><td colspan="6">TOTAL RENCANA BELANJA BARANG DAN JASA</td><td class="right">{{ $currency($grandTotal) }}</td></tr>
            @endif
        </tbody>
    </table>
</body>
</html>
