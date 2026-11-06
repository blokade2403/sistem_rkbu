<style>
    .kertas-letterhead { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .kertas-letterhead td { border: 0; padding: 2px; text-align: center; line-height: 1.35; }
    .kertas-hospital { font-size: 12px; font-weight: bold; }
    .kertas-report-title { font-size: 10px; font-weight: bold; }
    .kertas-metadata { width: 100%; border-collapse: collapse; margin: 8px 0 12px; }
    .kertas-metadata td { border: 0; padding: 2px 3px; vertical-align: top; }
    .kertas-label { width: 95px; }
    .kertas-colon { width: 10px; }
    .kertas-items { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .kertas-items th, .kertas-items td { border: 1px solid #777; padding: 4px 5px; vertical-align: top; }
    .kertas-items th { text-align: center; font-weight: normal; }
    .kertas-items .center { text-align: center; }
    .kertas-items .right { text-align: right; white-space: nowrap; }
    .kertas-items .group, .kertas-items .total { background: #eee; font-weight: bold; }
    .kertas-items .account-parent { font-weight: bold; }
    .kertas-empty { text-align: center; padding: 20px; }
    .kertas-signatures { width: 100%; border-collapse: collapse; margin-top: 36px; page-break-inside: avoid; }
    .kertas-signatures td { width: 33.33%; border: 0; padding: 3px 8px; text-align: center; vertical-align: top; }
    .kertas-signature-space { height: 48px; }
    .kertas-small { font-size: 7px; }
    .kertas-page-break { page-break-before: always; }
</style>
@php
    $kertasHeader = $judulHeader;
    $kertasCatalogByCode = $rekeningCatalog->keyBy('kode_rekening_belanja');
    $kertasCurrency = fn ($amount) => number_format((float) $amount, 0, ',', '.');
    $kertasSubKegiatanGroups = $items->groupBy(fn ($entry) => $entry['rkbu']->sub_kegiatan_id ?? 'tanpa-sub-kegiatan');
@endphp

@if ($kertasSubKegiatanGroups->isEmpty())
    <p class="kertas-empty">Tidak ada RKBU yang sudah disetujui untuk module dan filter yang dipilih.</p>
@endif
@foreach ($kertasSubKegiatanGroups as $kertasSubKegiatanItems)
    @php
        $kertasSubKegiatanItems = collect($kertasSubKegiatanItems);
        $kertasFirst = $kertasSubKegiatanItems->first()['rkbu'];
        $kertasSubKegiatan = $kertasFirst->subKegiatan;
        $kertasKegiatan = $kertasSubKegiatan?->kegiatan;
        $kertasProgram = $kertasSubKegiatan?->aktivitas?->program ?? $kertasKegiatan?->program;
        $kertasLeafTotals = [];
        foreach ($kertasSubKegiatanItems as $entry) {
            $rkbu = $entry['rkbu'];
            $account = $rkbu->rekeningBelanja;
            $code = $account?->kode_rekening_belanja ?? $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu ?? 'TANPA-KODE';
            $name = $account?->nama_rekening_belanja ?? $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu ?? 'Rekening belum ditentukan';
            $kertasLeafTotals[$code] ??= ['code' => $code, 'name' => $name, 'amount' => 0, 'is_leaf' => true];
            $kertasLeafTotals[$code]['amount'] += collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0));
        }
        $kertasNodeTotals = [];
        foreach ($kertasLeafTotals as $leaf) {
            $parts = explode('.', $leaf['code']);
            $prefixes = [];
            for ($length = 1; $length <= count($parts); $length++) $prefixes[] = implode('.', array_slice($parts, 0, $length));
            foreach ($prefixes as $code) {
                $kertasNodeTotals[$code] ??= ['code' => $code, 'name' => $kertasCatalogByCode->get($code)?->nama_rekening_belanja, 'amount' => 0, 'is_leaf' => false];
                $kertasNodeTotals[$code]['amount'] += $leaf['amount'];
            }
        }
        $kertasLeafCodes = array_keys($kertasLeafTotals);
        $kertasLedgerRows = collect($kertasNodeTotals)->map(function ($row) use ($kertasLeafTotals, $kertasLeafCodes, $kertasCatalogByCode) {
            $row['is_leaf'] = ! collect($kertasLeafCodes)->contains(fn ($leafCode) => str_starts_with($leafCode, $row['code'] . '.'));
            $row['name'] = $row['name'] ?: ($kertasLeafTotals[$row['code']]['name'] ?? $kertasCatalogByCode->get($row['code'])?->nama_rekening_belanja ?? '');
            return $row;
        })->filter(fn ($row) => $row['is_leaf'] || filled($row['name']))->sortBy('code', SORT_NATURAL)->values();
        $kertasSubTotal = $kertasSubKegiatanItems->sum(fn ($entry) => collect($entry['details'])->sum(fn ($row) => (float) ($row['Total Anggaran'] ?? 0)));
    @endphp

    @if (!$loop->first)<div class="kertas-page-break"></div>@endif
    <table class="kertas-letterhead">
        <tr><td>
            @if ($kertasHeader?->header1)<div>{{ $kertasHeader->header1 }}</div>@endif
            <div class="kertas-hospital">{{ $kertasHeader?->nama_rs ?? 'Instansi' }}</div>
            @if ($kertasHeader?->header2)<div>{{ $kertasHeader->header2 }}</div>@endif
            <div class="kertas-report-title">KERTAS KERJA RENCANA BISNIS DAN ANGGARAN</div>
            <div>TAHUN ANGGARAN {{ $kertasFirst->tahunAnggaran?->nama_tahun_anggaran ?? $kertasFirst->tahunAnggaran?->tahun ?? now()->year }}</div>
        </td></tr>
    </table>

    <table class="kertas-metadata">
        <tr><td class="kertas-label">Program</td><td class="kertas-colon">:</td><td>{{ $kertasProgram?->kode_program ?? '-' }} {{ $kertasProgram?->nama_program ?? '' }}</td></tr>
        <tr><td class="kertas-label">Kegiatan</td><td class="kertas-colon">:</td><td>{{ $kertasKegiatan?->kode_kegiatan ?? '-' }} {{ $kertasKegiatan?->nama_kegiatan ?? '' }}</td></tr>
        <tr><td class="kertas-label">Sub Kegiatan</td><td class="kertas-colon">:</td><td>{{ $kertasSubKegiatan?->kode_sub_kegiatan ?? '-' }} {{ $kertasSubKegiatan?->nama_sub_kegiatan ?? '' }}</td></tr>
        <tr><td class="kertas-label">Indikator</td><td class="kertas-colon">:</td><td>Dana yang dibutuhkan</td></tr>
        <tr><td class="kertas-label">Target Kinerja</td><td class="kertas-colon">:</td><td>Rp. {{ $kertasCurrency($kertasSubTotal) }}</td></tr>
    </table>

    <table class="kertas-items">
        <thead><tr><th width="7%">No</th><th width="28%">Kode Rekening</th><th>Uraian</th><th width="22%">Jumlah Anggaran</th></tr>
            <tr class="kertas-small"><th>1</th><th>2</th><th>3</th><th>4</th></tr>
        </thead>
        <tbody>
            @forelse ($kertasLedgerRows as $row)
                <tr class="{{ !$row['is_leaf'] ? 'account-parent' : '' }}">
                    <td class="center">{{ $row['is_leaf'] ? $loop->iteration : '' }}</td>
                    <td>{{ $row['code'] }}</td>
                    <td style="padding-left: {{ $row['is_leaf'] ? 5 : 5 + (substr_count($row['code'], '.') * 8) }}px;">{{ $row['name'] }}</td>
                    <td class="right">{{ $kertasCurrency($row['amount']) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="kertas-empty">Tidak ada rekening pada data RKBU yang disetujui.</td></tr>
            @endforelse
            <tr class="total"><td colspan="3">TOTAL ANGGARAN</td><td class="right">{{ $kertasCurrency($kertasSubTotal) }}</td></tr>
        </tbody>
    </table>

    @php
        $kertasApprovals = $kertasSubKegiatanItems
            ->flatMap(fn ($entry) => $entry['workflowInstances']->sortByDesc('created_at')->first()?->approvals ?? collect())
            ->where('status', 'approved');
        $kertasSigners = (($filters['print_type'] ?? '') === 'rba'
            ? $kertasApprovals->where('sequence', $kertasApprovals->max('sequence'))->sortByDesc('approved_at')->take(1)
            : $kertasApprovals->sortBy('sequence')
            ->unique(fn ($approval) => $approval->validatorUser?->id ?? $approval->position_id ?? $approval->sequence)
            ->take(3))->values();
    @endphp
    <table class="kertas-signatures">
        <tr>
            @forelse ($kertasSigners as $approval)
                <td>{{ $approval->assignment?->position?->nama_jabatan ?? $approval->validatorUser?->activeAssignment?->position?->nama_jabatan ?? $approval->validatorUser?->position?->nama_jabatan ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? 'Pejabat' }}</td>
            @empty
                <td colspan="3">Pejabat yang berwenang</td>
            @endforelse
        </tr>
        <tr>@foreach ($kertasSigners as $approval)<td class="kertas-signature-space"></td>@endforeach</tr>
        <tr>
            @foreach ($kertasSigners as $approval)
                <td><strong><u>{{ $approval->validatorUser?->nama ?? $approval->position?->nama_jabatan ?? $approval->workflowStep?->position?->nama_jabatan ?? 'Pejabat' }}</u></strong><br><span class="kertas-small">NIP. {{ $approval->validatorUser?->nip ?: '-' }}</span></td>
            @endforeach
        </tr>
    </table>
@endforeach
