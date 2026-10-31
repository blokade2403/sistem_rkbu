<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rencana Kebutuhan Barang Unit</title>
    <style>
        @page { margin: 24px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #111; }
        .letterhead { width: 100%; border-bottom: 2px solid #111; margin-bottom: 14px; padding-bottom: 7px; }
        .letterhead td { border: 0; text-align: center; vertical-align: middle; }
        .logo { width: 58px; height: 58px; }
        .heading { font-size: 10px; line-height: 1.35; }
        .heading .hospital { font-weight: bold; font-size: 12px; }
        .heading .report { font-weight: bold; font-size: 11px; }
        .info { width: 100%; margin: 10px 0 12px; border-collapse: collapse; }
        .info td { border: 0; padding: 2px 3px; vertical-align: top; }
        .label { width: 105px; }
        .colon { width: 10px; }
        .allocation { margin-top: 6px; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px; }
        .items th, .items td { border: 1px solid #777; padding: 4px 3px; vertical-align: top; }
        .items th { text-align: center; font-weight: normal; }
        .items .group { background: #eee; font-weight: bold; }
        .items .total { background: #eee; font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; white-space: nowrap; }
        .small { font-size: 7px; }
        .spec { font-style: italic; }
        .doc-meta { margin: 4px 0; color: #333; }
        .signatures { width: 100%; border-collapse: collapse; margin: 30px 0 14px; page-break-inside: avoid; }
        .signatures td { border: 0; text-align: center; vertical-align: top; width: 33.33%; padding: 3px 8px; }
        .sign-name { padding-top: 32px !important; text-decoration: underline; font-weight: bold; }
        .document { page-break-inside: auto; }
        .document + .document { margin-top: 20px; }
        .empty { text-align: center; padding: 25px; }
    </style>
</head>
<body>
    @php
        $header = $judulHeader;
        $logoPath = fn ($path) => $path ? public_path('storage/uploads/' . basename($path)) : null;
        $logoData = function ($path) use ($logoPath) {
            $fullPath = $logoPath($path);
            if (! $fullPath || ! is_file($fullPath)) return null;
            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        };
    @endphp

    @forelse ($items->groupBy(fn ($item) => $item['rkbu']->sub_kategori_rkbu_id ?? 'tanpa-subkategori') as $categoryItems)
        @php
            $categoryItems = collect($categoryItems);
            $item = $categoryItems->first();
            $rkbu = $item['rkbu'];
            $subKegiatan = $rkbu->subKegiatan;
            $kegiatan = $subKegiatan?->kegiatan;
            $program = $subKegiatan?->aktivitas?->program ?? $kegiatan?->program;
            $kategori = $rkbu->subKategoriRkbu?->kategori;
            $jenisKategori = $kategori?->jenis_kategori_rkbu;
            $rekening = $rkbu->subKategoriRkbu?->sub_kategori_rekening;
            $rekeningKategori = $rekening?->kategori_rekening;
            $total = $categoryItems->sum(fn ($categoryItem) => collect($categoryItem['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
            $signers = $categoryItems
                ->flatMap(fn ($categoryItem) => $categoryItem['workflowInstances']->sortByDesc('created_at')->first()?->approvals ?? collect())
                ->sortBy('sequence')
                ->unique(fn ($approval) => $approval->validatorUser?->id ?? $approval->position_id ?? $approval->workflowStep?->role_id ?? $approval->sequence)
                ->take(3)
                ->values();
            $rowNumber = 0;
        @endphp
        <section class="document">
            <table class="letterhead">
                <tr>
                    <td width="12%">
                        @if ($logo = $logoData($header?->gambar2))<img class="logo" src="{{ $logo }}">@endif
                    </td>
                    <td width="76%" class="heading">
                        @if ($header?->header1)<div>{{ $header->header1 }}</div>@endif
                        <div class="hospital">{{ $header?->nama_rs ?? 'Instansi' }}</div>
                        @if ($header?->header2)<div>{{ $header->header2 }}</div>@endif
                        @if ($header?->alamat_rs)<div class="small">{{ $header->alamat_rs }}</div>@endif
                        <!-- <div class="report">RENCANA KEBUTUHAN BARANG UNIT (RKBU)</div> -->
                        <div>TAHUN ANGGARAN {{ $rkbu->tahunAnggaran?->nama_tahun_anggaran ?? $rkbu->tahunAnggaran?->tahun ?? now()->year }}</div>
                    </td>
                    <td width="12%">
                        @if ($logo = $logoData($header?->gambar1))<img class="logo" src="{{ $logo }}">@endif
                    </td>
                </tr>
            </table>

            <table class="info">
                <tr><td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }} {{ $program?->nama_program ?? '' }}</td></tr>
                <tr><td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }} {{ $kegiatan?->nama_kegiatan ?? '' }}</td></tr>
                <tr><td class="label">Sub Kegiatan</td><td class="colon">:</td><td>{{ $subKegiatan?->kode_sub_kegiatan ?? '-' }} {{ $subKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
                <tr><td class="label">Unit / Pengusul</td><td class="colon">:</td><td>{{ $rkbu->unit?->nama_unit ?? '-' }} / {{ $rkbu->creator?->nama ?? '-' }}</td></tr>
                <tr><td class="label">Alokasi Anggaran</td><td class="colon">:</td><td>Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
                <tr><td class="label"></td><td class="colon"></td><td><em>({{ \Illuminate\Support\Number::spell((int) $total, locale: 'id') }} rupiah)</em></td></tr>
            </table>

            <div class="doc-meta">Subkategori RKBU: <strong>{{ $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu }} {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu }}</strong></div>

            <table class="items">
                <thead>
                    <tr>
                        <th rowspan="2" width="4%">No</th>
                        <th rowspan="2" width="19%">Nama / Jenis Barang</th>
                        <th rowspan="2" width="20%">Spesifikasi Merk/Ukuran</th>
                        <th rowspan="2" width="7%">Jumlah</th>
                        <th colspan="4" width="20%">Uraian Koefisien</th>
                        <th rowspan="2" width="13%">Harga Satuan</th>
                        <th rowspan="2" width="17%">Jumlah Biaya</th>
                    </tr>
                    <tr><th width="5%">Vol.1</th><th width="5%">Uraian</th><th width="5%">Vol.2</th><th width="5%">Uraian</th></tr>
                    <tr class="small"><th>1</th><th>2</th><th>3</th><th>4=5x7</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10=9x4</th></tr>
                </thead>
                <tbody>
                    @if ($jenisKategori || $rekeningKategori || $rekening)
                        <tr class="group"><td colspan="10">{{ $rekeningKategori?->kode_kategori_rekening }} {{ $rekeningKategori?->nama_kategori_rekening }} {{ $jenisKategori?->kode_jenis_kategori_rkbu }} {{ $jenisKategori?->nama_jenis_kategori_rkbu }}</td></tr>
                        <tr><td colspan="10">{{ $rekening?->kode_sub_kategori_rekening }} {{ $rekening?->nama_sub_kategori_rekening }}</td></tr>
                        <tr><td colspan="10">{{ $kategori?->kode_kategori_rkbu }} {{ $kategori?->nama_kategori_rkbu }}</td></tr>
                        <tr class="group"><td colspan="10">{{ $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu }} {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu }}</td></tr>
                    @endif
                    @foreach ($categoryItems as $categoryItem)
                        @php $document = $categoryItem['rkbu']; @endphp
                        @forelse ($categoryItem['details'] as $row)
                            @php
                                $rowNumber++;
                                $pegawai = $categoryItem['module'] === 'Pegawai';
                                $nama = $row['Nama Barang'] ?? $row['Nama Pegawai'] ?? '-';
                                $spek = $row['Spesifikasi'] ?? $row['Jabatan'] ?? '-';
                                $vol1 = $row['Volume 1'] ?? $row['Jumlah Volume'] ?? '-';
                                $unit1 = $row['Satuan 1'] ?? $row['Satuan'] ?? ($pegawai ? 'Bulan' : '-');
                                $vol2 = $row['Volume 2'] ?? '-';
                                $unit2 = $row['Satuan 2'] ?? '-';
                                $jumlah = $row['Jumlah Volume'] ?? $row['Jumlah Bulan'] ?? '-';
                                $harga = $row['Harga Satuan'] ?? ($pegawai ? $row['Gaji Pokok'] ?? 0 : 0);
                                $biaya = $row['Total Anggaran'] ?? $row['Total Gaji Pokok'] ?? 0;
                            @endphp
                            <tr>
                                <td class="center">{{ $rowNumber }}</td>
                                <td><strong>{{ $nama }}</strong><br><span class="small">Pengusul: {{ $document->creator?->nama ?? '-' }}<br>Nomor RKBU: {{ $document->nomor_rkbu ?? '-' }}</span></td>
                                <td class="spec">{{ $spek }}</td>
                                <td class="center">{{ $jumlah }}</td>
                                <td class="center">{{ $vol1 }}</td><td class="center">{{ $unit1 }}</td><td class="center">{{ $vol2 }}</td><td class="center">{{ $unit2 }}</td>
                                <td class="right">Rp {{ number_format((float) $harga, 0, ',', '.') }}</td>
                                <td class="right">Rp {{ number_format((float) $biaya, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    @endforeach
                    @if ($rowNumber === 0)
                        <tr><td colspan="10" class="empty">Tidak ada rincian RKBU.</td></tr>
                    @endif
                    <tr class="total"><td colspan="9">Total {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu ?? 'RKBU' }}</td><td class="right">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
                </tbody>
            </table>

            <table class="signatures">
                <tr>
                    @forelse ($signers as $approval)
                        @php $validator = $approval->validatorUser?->nama ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? $approval->workflowStep?->validatorRole?->nama_validator ?? 'Validator'; @endphp
                        <td>{{ $validator }}<br><span class="small">{{ strtoupper($approval->status ?? 'pending') }}</span></td>
                    @empty
                        <td colspan="3">Dicetak {{ $printedAt->format('d-m-Y') }}</td>
                    @endforelse
                </tr>
                <tr>
                    @foreach ($signers as $approval)
                        <td class="sign-name">{{ $approval->validatorUser?->nama ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? $approval->workflowStep?->validatorRole?->nama_validator ?? 'Validator' }}</td>
                    @endforeach
                </tr>
            </table>
        </section>
    @empty
        <p class="empty">Tidak ada data RKBU untuk filter yang dipilih.</p>
    @endforelse
</body>
</html>
