<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kertas Kerja RKBU</title>
    <style>
        @page { margin: 28px 34px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #111; }
        .letterhead { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .letterhead td { border: 0; padding: 2px; text-align: center; line-height: 1.35; }
        .hospital { font-size: 12px; font-weight: bold; }
        .report-title { font-size: 10px; font-weight: bold; }
        .metadata { width: 100%; border-collapse: collapse; margin: 8px 0 12px; }
        .metadata td { border: 0; padding: 2px 3px; vertical-align: top; }
        .label { width: 95px; }
        .colon { width: 10px; }
        .items { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items th, .items td { border: 1px solid #777; padding: 4px 5px; vertical-align: top; }
        .items th { text-align: center; font-weight: normal; }
        .items .center { text-align: center; }
        .items .right { text-align: right; white-space: nowrap; }
        .items .group { background: #eee; font-weight: bold; }
        .items .total { background: #eee; font-weight: bold; }
        .items .account-parent { font-weight: bold; }
        .empty { text-align: center; padding: 20px; }
        .signatures { width: 100%; border-collapse: collapse; margin-top: 36px; page-break-inside: avoid; }
        .signatures td { width: 33.33%; border: 0; padding: 3px 8px; text-align: center; vertical-align: top; }
        .signature-space { height: 48px; }
        .small { font-size: 7px; }
        .page-break { page-break-before: always; }
        .cover-page { position: relative; height: 765px; page-break-after: always; text-align: center; }
        .cover-headings { padding-top: 76px; font-size: 13px; line-height: 1.35; font-weight: bold; text-transform: uppercase; }
        .cover-institution { margin-top: 10px; font-size: 23px; line-height: 1.25; font-weight: bold; text-transform: uppercase; }
        .cover-logo { display: block; width: 105px; height: 120px; margin: 85px auto 65px; object-fit: contain; }
        .cover-title { margin-top: 0; font-size: 23px; line-height: 1.4; font-weight: bold; text-transform: uppercase; }
        .cover-year { margin-top: 28px; font-size: 22px; font-weight: bold; }
        .cover-address { position: absolute; right: 20px; bottom: 7px; left: 20px; font-size: 11px; line-height: 1.5; font-weight: bold; }
    </style>
</head>
<body>
    @php
        $header = $judulHeader;
        $firstRkbu = $items->first()['rkbu'] ?? null;
        $catalogByCode = $rekeningCatalog->keyBy('kode_rekening_belanja');
        $currency = fn ($amount) => number_format((float) $amount, 0, ',', '.');
        $subKegiatanGroups = $items->groupBy(fn ($entry) => $entry['rkbu']->sub_kegiatan_id ?? 'tanpa-sub-kegiatan');
    @endphp

    <section class="cover-page">
        <div class="cover-headings">
            @foreach (collect([$header?->header1, $header?->header2, $header?->header3, $header?->header4, $header?->header5, $header?->header6])->filter() as $coverHeading)
                <div>{{ $coverHeading }}</div>
            @endforeach
        </div>
        <div class="cover-institution">{{ $header?->nama_rs ?? 'Instansi' }}</div>
        @if ($coverLogo)<img class="cover-logo" src="{{ $coverLogo }}" alt="Lambang instansi">@endif
        <div class="cover-title">RENCANA BISNIS DAN ANGGARAN</div>
        <div class="cover-year">TAHUN ANGGARAN {{ $firstRkbu?->tahunAnggaran?->nama_tahun_anggaran ?? $firstRkbu?->tahunAnggaran?->tahun ?? now()->year }}</div>
        <div class="cover-address">
            <div>{{ $header?->alamat_rs }}</div>
            @if ($header?->tlp_rs)<div>Telepon: {{ $header->tlp_rs }}</div>@endif
        </div>
    </section>

    @if ($subKegiatanGroups->isEmpty())
        <p class="empty">Tidak ada RKBU yang sudah disetujui untuk module dan filter yang dipilih.</p>
    @endif
    @foreach ($subKegiatanGroups as $subKegiatanItems)
        @php
            $subKegiatanItems = collect($subKegiatanItems);
            $first = $subKegiatanItems->first()['rkbu'];
            $subKegiatan = $first->subKegiatan;
            $kegiatan = $subKegiatan?->kegiatan;
            $program = $subKegiatan?->aktivitas?->program ?? $kegiatan?->program;
            $leafTotals = [];
            foreach ($subKegiatanItems as $entry) {
                $rkbu = $entry['rkbu'];
                $account = $rkbu->rekeningBelanja;
                $code = $account?->kode_rekening_belanja ?? $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu ?? 'TANPA-KODE';
                $name = $account?->nama_rekening_belanja ?? $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu ?? 'Rekening belum ditentukan';
                $leafTotals[$code] ??= ['code' => $code, 'name' => $name, 'amount' => 0, 'is_leaf' => true];
                $leafTotals[$code]['amount'] += collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0));
            }
            $nodeTotals = [];
            foreach ($leafTotals as $leaf) {
                $parts = explode('.', $leaf['code']);
                $prefixes = [];
                for ($length = 1; $length <= count($parts); $length++) $prefixes[] = implode('.', array_slice($parts, 0, $length));
                foreach ($prefixes as $code) {
                    $nodeTotals[$code] ??= ['code' => $code, 'name' => $catalogByCode->get($code)?->nama_rekening_belanja, 'amount' => 0, 'is_leaf' => false];
                    $nodeTotals[$code]['amount'] += $leaf['amount'];
                }
            }
            $leafCodes = array_keys($leafTotals);
            $ledgerRows = collect($nodeTotals)->map(function ($row) use ($leafTotals, $leafCodes, $catalogByCode) {
                $row['is_leaf'] = ! collect($leafCodes)->contains(fn ($leafCode) => str_starts_with($leafCode, $row['code'] . '.'));
                $row['name'] = $row['name'] ?: ($leafTotals[$row['code']]['name'] ?? $catalogByCode->get($row['code'])?->nama_rekening_belanja ?? '');
                return $row;
            })->filter(fn ($row) => $row['is_leaf'] || filled($row['name']))->sortBy('code', SORT_NATURAL)->values();
            $subTotal = $subKegiatanItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
        @endphp

        @if (!$loop->first)<div class="page-break"></div>@endif
        <table class="letterhead">
            <tr><td>
                @if ($header?->header1)<div>{{ $header->header1 }}</div>@endif
                <div class="hospital">{{ $header?->nama_rs ?? 'Instansi' }}</div>
                @if ($header?->header2)<div>{{ $header->header2 }}</div>@endif
                <div class="report-title">KERTAS KERJA RENCANA BISNIS DAN ANGGARAN</div>
                <div>TAHUN ANGGARAN {{ $first->tahunAnggaran?->nama_tahun_anggaran ?? $first->tahunAnggaran?->tahun ?? now()->year }}</div>
            </td></tr>
        </table>

        <table class="metadata">
            <tr><td class="label">Program</td><td class="colon">:</td><td>{{ $program?->kode_program ?? '-' }} {{ $program?->nama_program ?? '' }}</td></tr>
            <tr><td class="label">Kegiatan</td><td class="colon">:</td><td>{{ $kegiatan?->kode_kegiatan ?? '-' }} {{ $kegiatan?->nama_kegiatan ?? '' }}</td></tr>
            <tr><td class="label">Sub Kegiatan</td><td class="colon">:</td><td>{{ $subKegiatan?->kode_sub_kegiatan ?? '-' }} {{ $subKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
            <tr><td class="label">Indikator</td><td class="colon">:</td><td>Dana yang dibutuhkan</td></tr>
            <tr><td class="label">Target Kinerja</td><td class="colon">:</td><td>Rp. {{ $currency($subTotal) }}</td></tr>
        </table>

        <table class="items">
            <thead><tr><th width="7%">No</th><th width="28%">Kode Rekening</th><th>Uraian</th><th width="22%">Jumlah Anggaran</th></tr>
                <tr class="small"><th>1</th><th>2</th><th>3</th><th>4</th></tr>
            </thead>
            <tbody>
                @forelse ($ledgerRows as $row)
                    <tr class="{{ !$row['is_leaf'] ? 'account-parent' : '' }}">
                        <td class="center">{{ $row['is_leaf'] ? $loop->iteration : '' }}</td>
                        <td>{{ $row['code'] }}</td>
                        <td style="padding-left: {{ $row['is_leaf'] ? 5 : 5 + (substr_count($row['code'], '.') * 8) }}px;">{{ $row['name'] }}</td>
                        <td class="right">{{ $currency($row['amount']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Tidak ada rekening pada data RKBU yang disetujui.</td></tr>
                @endforelse
                <tr class="total"><td colspan="3">TOTAL ANGGARAN</td><td class="right">{{ $currency($subTotal) }}</td></tr>
            </tbody>
        </table>

        @php
            $signers = $subKegiatanItems
                ->flatMap(fn ($entry) => $entry['workflowInstances']->sortByDesc('created_at')->first()?->approvals ?? collect())
                ->where('status', 'approved')->sortBy('sequence')
                ->unique(fn ($approval) => $approval->validatorUser?->id ?? $approval->position_id ?? $approval->sequence)
                ->take(3)->values();
        @endphp
        <table class="signatures">
            <tr>
                @forelse ($signers as $approval)
                    <td>{{ $approval->assignment?->position?->nama_jabatan ?? $approval->validatorUser?->activeAssignment?->position?->nama_jabatan ?? $approval->validatorUser?->position?->nama_jabatan ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? 'Pejabat' }}</td>
                @empty
                    <td colspan="3">Pejabat yang berwenang</td>
                @endforelse
            </tr>
            <tr>@foreach ($signers as $approval)<td class="signature-space"></td>@endforeach</tr>
            <tr>
                @foreach ($signers as $approval)
                    <td><strong><u>{{ $approval->validatorUser?->nama ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? 'Pejabat' }}</u></strong><br><span class="small">NIP. {{ $approval->validatorUser?->nip ?: '-' }}</span></td>
                @endforeach
            </tr>
        </table>
    @endforeach
</body>
</html>
