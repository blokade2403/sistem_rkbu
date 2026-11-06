<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RKBU Pegawai</title>
    <style>
        @page { margin: 28px 30px 36px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7px; color: #111; }
        .letterhead { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .letterhead td { border: 0; text-align: center; vertical-align: middle; padding: 2px; }
        .heading { line-height: 1.35; }
        .hospital { font-size: 9px; font-weight: bold; }
        .info { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info td { border: 0; padding: 2px 3px; vertical-align: top; }
        .info .label { width: 135px; }
        .info .colon { width: 10px; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items th, .items td { border: 0.6px solid #777; padding: 3px 2px; vertical-align: top; }
        .items th { text-align: center; font-weight: normal; }
        .items thead { display: table-header-group; }
        .items tr { page-break-inside: avoid; }
        .items .center { text-align: center; }
        .items .right { text-align: right; white-space: nowrap; }
        .items .group, .items .account { font-weight: bold; background: #eee; }
        .items .category-start { page-break-before: always; }
        .items .subtotal, .items .grand-total { font-weight: bold; background: #eee; }
        .signatures { width: 100%; border-collapse: collapse; margin: 32px 0 12px; page-break-inside: avoid; }
        .signatures td { border: 0; width: 33.33%; padding: 3px 8px; text-align: center; vertical-align: top; }
        .approval-date { margin: 24px 0 -26px; text-align: right; font-size: 8px; }
        .signatures .signature-space { height: 42px; }
        .signatures .sign-name { font-weight: bold; }
        .small { font-size: 6px; }
        .empty { text-align: center; padding: 20px; }
    </style>
</head>
<body>
    @php
        $firstEntry = $items->first();
        $firstRkbu = $firstEntry['rkbu'] ?? null;
        $isRba = ($filters['print_type'] ?? '') === 'rba';
        $groups = $items->groupBy(fn ($entry) => $isRba
            ? ($entry['rkbu']->subKategoriRkbu?->kategori?->id_jenis_kategori_rkbu ?? 'tanpa-jenis-kategori')
            : ($entry['rkbu']->sub_kategori_rkbu_id ?? 'tanpa-subkategori'));
        $subKegiatan = $firstRkbu?->subKegiatan;
        $kegiatan = $subKegiatan?->kegiatan;
        $program = $subKegiatan?->aktivitas?->program ?? $kegiatan?->program;
        $grandTotal = $items->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
        $allApprovals = $items
            ->flatMap(fn ($entry) => $entry['workflowInstances']->sortByDesc('created_at')->first()?->approvals ?? collect())
            ->where('status', 'approved');
        $signers = ($isRba
            ? $allApprovals->where('sequence', $allApprovals->max('sequence'))->sortByDesc('approved_at')->take(1)
            : $allApprovals->sortBy('sequence')
            ->unique(fn ($approval) => $approval->validatorUser?->id ?? $approval->position_id ?? $approval->workflowStep?->role_id ?? $approval->sequence)
            ->take(3))->values();
        $currency = fn ($value) => number_format((float) $value, 0, ',', '.');
        $rkbuYear = $firstRkbu?->tahunAnggaran?->nama_tahun_anggaran ?? $firstRkbu?->tahunAnggaran?->tahun ?? now()->year;
    @endphp

    @if ($isRba)
        @include('workflow.approval.partials.cover')
        @include('workflow.approval.partials.kertas_kerja')
        <div style="page-break-before: always;"></div>
    @endif

    <table class="letterhead">
        <tr><td class="heading">
            @if ($judulHeader?->header1)<div>{{ $judulHeader->header1 }}</div>@endif
            <div class="hospital">{{ $judulHeader?->nama_rs ?? 'Instansi' }}</div>
            @if ($judulHeader?->header6)<div>{{ $judulHeader->header6 }}</div>@endif
            <div>TAHUN ANGGARAN {{ $rkbuYear }}</div>
        </td></tr>
    </table>

    <table class="info">
        <tr><td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }}. {{ $program?->nama_program ?? '' }}</td></tr>
        <tr><td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }}. {{ $kegiatan?->nama_kegiatan ?? '' }}</td></tr>
        <tr><td class="label">Sub Kegiatan</td><td class="colon">:</td><td>{{ $subKegiatan?->kode_sub_kegiatan ?? '-' }}. {{ $subKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
        <tr><td class="label">Alokasi Anggaran</td><td class="colon">:</td><td>Rp. {{ $currency($grandTotal) }}</td></tr>
        <tr><td></td><td></td><td><em>( {{ \Illuminate\Support\Number::spell((int) $grandTotal, locale: 'id') }} Rupiah )</em></td></tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th rowspan="2" width="4%">No</th>
                <th rowspan="2" width="14%">Nama Pegawai</th>
                <th colspan="2" width="14%">Tempat Lahir</th>
                <th rowspan="2" width="9%">Jabatan</th>
                <th rowspan="2" width="8%">Pendidikan</th>
                <th rowspan="2" width="10%">NIP</th>
                <th colspan="2" width="12%">Masa Kerja</th>
                <th colspan="2" width="17%">Penghasilan</th>
                <th rowspan="2" width="12%">Total Penghasilan</th>
            </tr>
            <tr>
                <th width="8%">Tempat</th><th width="6%">Tgl Lahir</th>
                <th width="6%">TMT Tahun</th><th width="6%">TMT Bulan</th>
                <th width="11%">Total Remunerasi</th><th width="6%">Koefisien</th>
            </tr>
            <tr class="center">
                <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th>
                <th>9</th><th>10</th><th>12</th><th>13</th><th>14=(8+9+10)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($groups as $groupItems)
                @php
                    $groupItems = collect($groupItems);
                    $rkbu = $groupItems->first()['rkbu'];
                    $subKategori = $rkbu->subKategoriRkbu;
                    $kategori = $subKategori?->kategori;
                    $obyek = $kategori?->obyek_belanja;
                    $jenisKategori = $kategori?->jenis_kategori_rkbu;
                    $jenisBelanja = $rkbu->jenisBelanja ?? $jenisKategori?->jenis_belanja;
                    $groupTotal = $groupItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
                    $number = 0;
                @endphp
                @if ($jenisBelanja)<tr class="group {{ !$loop->first && $isRba ? 'category-start' : '' }}"><td colspan="12">{{ $jenisBelanja->kode_jenis_belanja }}. {{ $jenisBelanja->nama_jenis_belanja }}</td></tr>@endif
                @if ($jenisKategori)<tr class="group"><td colspan="12">{{ $jenisKategori->kode_jenis_kategori_rkbu }}. {{ $jenisKategori->nama_jenis_kategori_rkbu }}</td></tr>@endif
                @if ($obyek && !$isRba)<tr><td colspan="12">{{ $obyek->kode_obyek_belanja }}. {{ $obyek->nama_obyek_belanja }}</td></tr>@endif
                @if ($kategori && !$isRba)<tr><td colspan="12">{{ $kategori->kode_kategori_rkbu }}. {{ $kategori->nama_kategori_rkbu }}</td></tr>@endif
                @foreach ($groupItems->groupBy(fn ($entry) => $entry['rkbu']->sub_kategori_rkbu_id ?? 'tanpa-subkategori') as $subCategoryItems)
                    @php
                        $subCategoryItems = collect($subCategoryItems);
                        $subCategory = $subCategoryItems->first()['rkbu']->subKategoriRkbu;
                        $subCategoryTotal = $subCategoryItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
                    @endphp
                    @if ($isRba)<tr class="group"><td colspan="12">{{ $subCategory?->kode_sub_kategori_rkbu }}. {{ $subCategory?->nama_sub_kategori_rkbu ?? 'Tanpa subkategori' }}</td></tr>@endif
                    @php
                        $subCategoryRkbu = $subCategoryItems->first()['rkbu'];
                        $subCategoryAccountGroup = $subCategory?->sub_kategori_rekening;
                        $subCategoryAccountCategory = $subCategoryAccountGroup?->kategori_rekening;
                        $subCategoryCategory = $subCategory?->kategori;
                        $subCategoryAccount = $subCategoryRkbu->rekeningBelanja;
                    @endphp
                    @if ($isRba && $subCategory)
                        @if ($subCategoryAccountCategory)<tr><td colspan="12">{{ $subCategoryAccountCategory->kode_kategori_rekening }}. {{ $subCategoryAccountCategory->nama_kategori_rekening }}</td></tr>@endif
                        @if ($subCategoryAccountGroup)<tr><td colspan="12">{{ $subCategoryAccountGroup->kode_sub_kategori_rekening }}. {{ $subCategoryAccountGroup->nama_sub_kategori_rekening }}</td></tr>@endif
                        @if ($subCategoryCategory)<tr><td colspan="12">{{ $subCategoryCategory->kode_kategori_rkbu }}. {{ $subCategoryCategory->nama_kategori_rkbu }}</td></tr>@endif
                        <tr class="group"><td colspan="12">{{ $subCategory->kode_sub_kategori_rkbu }}. {{ $subCategory->nama_sub_kategori_rkbu }}</td></tr>
                    @endif
                    <tr class="account"><td colspan="12">{{ $isRba && $subCategoryAccount ? $subCategoryAccount->kode_rekening_belanja . '. ' . $subCategoryAccount->nama_rekening_belanja : ($subCategory?->nama_sub_kategori_rkbu ?? 'Belanja Pegawai') }}</td></tr>
                @foreach ($subCategoryItems as $entry)
                    @foreach ($entry['details'] as $row)
                        @php $number++; @endphp
                        <tr>
                            <td class="center">{{ $number }}</td>
                            <td class="center">{{ $row['Nama Pegawai'] ?? '-' }}</td>
                            <td class="center">{{ $row['Tempat Lahir'] ?? '-' }}</td>
                            <td class="center">{{ $row['Tanggal Lahir'] ?? '-' }}</td>
                            <td class="center">{{ $row['Jabatan'] ?? '-' }}</td>
                            <td class="center">{{ $row['Pendidikan'] ?? '-' }}</td>
                            <td class="center">{{ $row['NIP'] ?? '-' }}</td>
                            <td class="center">{{ $row['Tahun TMT'] ?? '-' }}</td>
                            <td class="center">{{ $row['Bulan TMT'] ?? '-' }}</td>
                            <td class="right">Rp. {{ $currency($row['Total Remunerasi'] ?? 0) }}</td>
                            <td class="center">{{ $row['Koefisien Remunerasi'] ?? '-' }}</td>
                            <td class="right">Rp. {{ $currency($row['Total Anggaran'] ?? 0) }}</td>
                        </tr>
                    @endforeach
                @endforeach
                    @endforeach
                    <tr class="subtotal"><td colspan="11">Total {{ $subCategory?->nama_sub_kategori_rkbu ?? 'Belanja Pegawai' }}</td><td class="right">Rp. {{ $currency($subCategoryTotal) }}</td></tr>
                @if ($isRba)<tr class="grand-total"><td colspan="11">TOTAL {{ $jenisKategori?->nama_jenis_kategori_rkbu ?? 'JENIS KATEGORI' }}</td><td class="right">Rp. {{ $currency($groupTotal) }}</td></tr>@endif
            @empty
                <tr><td colspan="12" class="empty">Tidak ada data RKBU Pegawai sesuai filter.</td></tr>
            @endforelse
            @if ($groups->isNotEmpty())
                <tr class="grand-total"><td colspan="11">TOTAL BELANJA PEGAWAI</td><td class="right">Rp. {{ $currency($grandTotal) }}</td></tr>
            @endif
        </tbody>
    </table>

    @if ($tanggalPengesahan)
        <div class="approval-date">{{ $tanggalPengesahan->kota ?: 'Jakarta' }}, {{ \Carbon\Carbon::parse($tanggalPengesahan->tanggal)->locale('id')->translatedFormat('d F Y') }}</div>
    @endif
    <table class="signatures">
        <tr>
            @forelse ($signers as $approval)
                @php
                    $jabatan = $approval->assignment?->position?->nama_jabatan
                        ?? $approval->validatorUser?->activeAssignment?->position?->nama_jabatan
                        ?? $approval->validatorUser?->position?->nama_jabatan
                        ?? $approval->position?->nama_jabatan
                        ?? $approval->workflowStep?->position?->nama_jabatan
                        ?? $approval->workflowStep?->validatorRole?->nama_validator
                        ?? 'Jabatan';
                @endphp
                <td>{{ $jabatan }}<br><span class="small">{{ strtoupper($approval->status ?? 'pending') }}</span></td>
            @empty
                <td colspan="3">Pejabat yang berwenang</td>
            @endforelse
        </tr>
        <tr>
            @forelse ($signers as $approval)
                <td class="signature-space"></td>
            @empty
                <td colspan="3" class="signature-space"></td>
            @endforelse
        </tr>
        <tr>
            @foreach ($signers as $approval)
                <td class="sign-name"><strong style="text-decoration: underline;">{{ $approval->validatorUser?->nama
                    ?? $approval->position?->nama_jabatan
                    ?? $approval->workflowStep?->position?->nama_jabatan
                    ?? $approval->workflowStep?->validatorRole?->nama_validator
                    ?? 'Pejabat' }}</strong><br><span class="small" style="font-weight: normal;">NIP. {{ $approval->validatorUser?->nip ?: '-' }}</span></td>
            @endforeach
        </tr>
    </table>
</body>
</html>
