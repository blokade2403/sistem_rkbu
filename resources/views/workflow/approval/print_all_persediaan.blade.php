<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Rencana Bisnis dan Anggaran - Persediaan</title>
    <style>
        @page { margin: 28px 30px 36px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7px; color: #111; }
        .document { page-break-inside: auto; }
        .document + .document { page-break-before: always; }
        .letterhead { width: 100%; border-collapse: collapse; border-bottom: 1.4px solid #333; margin: 0 0 28px; }
        .letterhead td { border: 0; vertical-align: middle; }
        .letterhead .logo-cell { width: 10%; text-align: center; }
        .letterhead .logo { max-width: 50px; max-height: 50px; }
        .heading { text-align: center; line-height: 1.35; font-size: 8px; padding: 0 4px 7px; }
        .heading .institution { font-weight: bold; }
        .summary { width: 100%; border-collapse: collapse; margin: 0 0 16px; font-size: 7.5px; }
        .summary td { padding: 2px 3px; vertical-align: top; }
        .summary .label { width: 105px; }
        .summary .colon { width: 10px; }
        .summary .total { font-weight: bold; padding-top: 10px; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items th, .items td { border: 0.6px solid #777; padding: 3px 2px; vertical-align: middle; }
        .items th { text-align: center; font-weight: normal; }
        .items .group { font-weight: bold; }
        .items .light-group { font-weight: normal; }
        .items .item-name { font-weight: bold; }
        .items .spec { font-size: 6px; }
        .center { text-align: center; }
        .right { text-align: right; white-space: nowrap; }
        .total-row { font-weight: bold; }
        .empty { text-align: center; padding: 20px; }
    </style>
</head>
<body>
    @forelse ($items->groupBy(fn ($entry) => $entry['rkbu']->sub_kategori_rkbu_id ?? 'tanpa-subkategori') as $groupItems)
        @php
            $groupItems = collect($groupItems);
            $firstEntry = $groupItems->first();
            $rkbu = $firstEntry['rkbu'];
            $subKegiatan = $rkbu->subKegiatan;
            $kegiatan = $subKegiatan?->kegiatan;
            $program = $subKegiatan?->aktivitas?->program ?? $kegiatan?->program;
            $kategori = $rkbu->subKategoriRkbu?->kategori;
            $jenisKategori = $kategori?->jenis_kategori_rkbu;
            $rekening = $rkbu->subKategoriRkbu?->sub_kategori_rekening;
            $rekeningKategori = $rekening?->kategori_rekening;
            $year = (int) preg_replace('/\D+/', '', (string) ($rkbu->tahunAnggaran?->nama_tahun_anggaran ?? now()->year));
            $total = $groupItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
            $rowNumber = 0;
        @endphp
        <section class="document">
            <table class="letterhead">
                <tr>
                    <td class="logo-cell">@if($headerLogoLeft)<img class="logo" src="{{ $headerLogoLeft }}">@endif</td>
                    <td width="80%" class="heading">
                        @if($judulHeader?->header1)<div>{{ $judulHeader->header1 }}</div>@endif
                        <div class="institution">{{ $judulHeader?->nama_rs ?? '' }}</div>
                        @if($judulHeader?->header2)<div>{{ $judulHeader->header2 }}</div>@endif
                        <!-- @if($judulHeader?->header4)<div class="institution">{{ $judulHeader->header4 }}</div>@endif -->
                        <!-- @if($judulHeader?->header5)<div>{{ $judulHeader->header5 }}</div>@endif
                        @if($judulHeader?->header6)<div>{{ $judulHeader->header6 }}</div>@endif -->
                        <!-- @if($judulHeader?->alamat_rs)<div>{{ $judulHeader->alamat_rs }}</div>@endif
                        @if($judulHeader?->tlp_rs)<div>Telp. {{ $judulHeader->tlp_rs }}</div>@endif -->
                        <!-- @if($judulHeader?->header3)<div>Website: {{ $judulHeader->header3 }}</div>@endif -->
                        <!-- @if($judulHeader?->kode_pos)<div style="text-align: right; padding-right: 8%">Kode Pos : {{ $judulHeader->kode_pos }}</div>@endif
                        <div><strong>RINGKASAN RENCANA BISNIS DAN ANGGARAN</strong></div> -->
                        <div>TAHUN ANGGARAN {{ $year }}</div>
                    </td>
                    <td class="logo-cell">@if($headerLogoRight)<img class="logo" src="{{ $headerLogoRight }}">@endif</td>
                </tr>
            </table>

            <table class="summary">
                <tr><td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }} {{ $program?->nama_program ?? '' }}</td></tr>
                <tr><td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }} {{ $kegiatan?->nama_kegiatan ?? '' }}</td></tr>
                <tr><td class="label">Sub Kegiatan</td><td class="colon">:</td><td>{{ $subKegiatan?->kode_sub_kegiatan ?? '-' }} {{ $subKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
                <tr><td class="label total">Total Anggaran</td><td class="colon total">:</td><td class="total">Rp. {{ number_format((float) $total, 0, ',', '.') }}</td></tr>
                <tr><td></td><td></td><td><em>( {{ \Illuminate\Support\Number::spell((int) $total, locale: 'id') }} rupiah )</em></td></tr>
            </table>

            <table class="items">
                <thead>
                    <tr>
                        <th rowspan="2" width="4%">No</th>
                        <th rowspan="2" width="20%">Nama / Jenis Barang</th>
                        <th rowspan="2" width="5%">Satuan</th>
                        <th rowspan="2" width="7%">Sisa Stok<br>Desember {{ $year - 2 }}</th>
                        <th rowspan="2" width="7%">Rata2 Pemakaian<br>per Bulan {{ $year - 1 }}</th>
                        <th rowspan="2" width="7%">Pengadaan<br>Tahun {{ $year - 1 }}</th>
                        <th colspan="3" width="21%">Uraian Koefisien</th>
                        <th rowspan="2" width="5%">Buffer</th>
                        <th rowspan="2" width="7%">Rencana<br>Pengadaan {{ $year }}</th>
                        <th rowspan="2" width="8%">Harga Satuan</th>
                        <th rowspan="2" width="4%">PPN</th>
                        <th rowspan="2" width="8%">Jumlah</th>
                    </tr>
                    <tr>
                        <th width="7%">Prediksi Sisa Stok {{ $year }}</th>
                        <th width="7%">Kebutuhan Tahunan {{ $year }}</th>
                        <th width="7%">Perkiraan Kebutuhan {{ $year }}</th>
                    </tr>
                    <tr class="center">
                        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th>
                        <th>7=(4+6)-(5×12)</th><th>8=(5×12)</th><th>9=8+(5×Buffer)</th>
                        <th>10</th><th>11=9−7</th><th>12</th><th>13</th><th>14=(11×12)+(13)</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($jenisKategori || $rekeningKategori || $rekening)
                        <tr class="group"><td colspan="14">{{ $rekeningKategori?->kode_kategori_rekening }} {{ $rekeningKategori?->nama_kategori_rekening }} {{ $jenisKategori?->kode_jenis_kategori_rkbu }} {{ $jenisKategori?->nama_jenis_kategori_rkbu }}</td></tr>
                        <tr class="light-group"><td colspan="14">{{ $rekening?->kode_sub_kategori_rekening }} {{ $rekening?->nama_sub_kategori_rekening }}</td></tr>
                        <tr class="light-group"><td colspan="14">{{ $kategori?->kode_kategori_rkbu }} {{ $kategori?->nama_kategori_rkbu }}</td></tr>
                        <tr class="group"><td colspan="14">{{ $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu }} {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu }}</td></tr>
                    @endif
                    @foreach ($groupItems as $entry)
                        @php $currentRkbu = $entry['rkbu']; @endphp
                        @forelse ($entry['details'] as $row)
                            @php $rowNumber++; @endphp
                            <tr>
                                <td class="center">{{ $rowNumber }}</td>
                                <td><span class="item-name">{{ $row['Nama Barang'] ?? '-' }}</span><br><span class="spec">Spesifikasi: {{ $row['Spesifikasi'] ?? '-' }}</span></td>
                                <td class="center">{{ $row['Satuan'] ?? '-' }}</td>
                                <td class="center">{{ $row['Stok'] ?? 0 }}</td>
                                <td class="center">{{ $row['Rata-rata Pemakaian'] ?? 0 }}</td>
                                <td class="center">{{ $row['Pengadaan Sebelumnya'] ?? 0 }}</td>
                                <td class="center">{{ $row['Proyeksi Sisa Stok'] ?? 0 }}</td>
                                <td class="center">{{ $row['Kebutuhan Tahun X1'] ?? 0 }}</td>
                                <td class="center">{{ $row['Kebutuhan + Buffer'] ?? 0 }}</td>
                                <td class="center">{{ $row['Buffer'] ?? 0 }}</td>
                                <td class="center">{{ $row['Rencana Pengadaan Tahun X1'] ?? $row['Jumlah Volume'] ?? 0 }}</td>
                                <td class="right">{{ number_format((float) ($row['Harga Satuan'] ?? 0), 0, ',', '.') }}</td>
                                <td class="center">{{ number_format((float) ($row['PPN (%)'] ?? 0), 2, ',', '.') }}%</td>
                                <td class="right">Rp. {{ number_format((float) ($row['Total Anggaran'] ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    @endforeach
                    @if ($rowNumber === 0)
                        <tr><td colspan="14" class="empty">Tidak ada rincian persediaan.</td></tr>
                    @endif
                    <tr class="total-row"><td colspan="13">Total {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu ?? 'Persediaan' }}</td><td class="right">Rp. {{ number_format((float) $total, 0, ',', '.') }}</td></tr>
                </tbody>
            </table>
        </section>
    @empty
        <p class="empty">Tidak ada data persediaan untuk filter yang dipilih.</p>
    @endforelse
</body>
</html>
