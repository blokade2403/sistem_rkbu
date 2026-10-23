<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Approval RKBU {{ $rkbu->nomor_rkbu }}</title>
    <style>
        @page { margin: 28px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
        h1 { text-align: center; font-size: 16px; margin: 0 0 4px; }
        h2 { font-size: 12px; margin: 18px 0 7px; padding-bottom: 4px; border-bottom: 1px solid #777; }
        .muted { color: #666; }
        .meta { text-align: center; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #777; padding: 5px; vertical-align: top; }
        th { background: #eee; text-align: left; }
        .number { text-align: right; white-space: nowrap; }
        .status { font-weight: bold; text-transform: uppercase; }
        .stage { page-break-inside: avoid; border: 1px solid #aaa; padding: 7px; margin-bottom: 7px; }
        .log { border-left: 2px solid #888; padding: 3px 0 3px 7px; margin: 4px 0 4px 8px; }
        .attachment { margin: 4px 0; }
        a { color: #0645ad; text-decoration: underline; }
    </style>
</head>
<body>
    <h1>DOKUMEN APPROVAL RKBU</h1>
    <div class="meta muted">{{ $module }} · Dicetak {{ $printedAt->format('d-m-Y H:i') }}</div>

    <h2>Informasi RKBU</h2>
    <table>
        <tbody>
            <tr><th width="18%">Nomor RKBU</th><td width="32%">{{ $rkbu->nomor_rkbu ?? '-' }}</td><th width="18%">Status</th><td width="32%" class="status">{{ $rkbu->status ?? '-' }}</td></tr>
            <tr><th>Unit</th><td>{{ $rkbu->unit?->nama_unit ?? '-' }}</td><th>Tahun Anggaran</th><td>{{ $rkbu->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td></tr>
            <tr><th>Subkategori</th><td>{{ $rkbu->subKategoriRkbu?->kode_sub_kategori_rkbu ?? '-' }} - {{ $rkbu->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td><th>Sumber Dana</th><td>{{ $rkbu->sumberDana?->nama_sumber_dana ?? '-' }}</td></tr>
            <tr><th>Pengusul</th><td>{{ $rkbu->creator?->nama ?? '-' }}</td><th>Approval Dicetak</th><td>{{ $approval->status }} · {{ $approval->created_at?->format('d-m-Y H:i') ?? '-' }}</td></tr>
        </tbody>
    </table>

    <h2>Rincian {{ $module }}</h2>
    <table>
        <thead>
            <tr><th width="4%">No.</th>@foreach ($detailColumns as $column)<th>{{ $column }}</th>@endforeach</tr>
        </thead>
        <tbody>
            @forelse ($detailRows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach ($detailColumns as $column)
                        @php $value = $row[$column] ?? null; @endphp
                        <td>{{ is_numeric($value) && preg_match('/Anggaran|Harga|Gaji|Remunerasi/i', $column) ? 'Rp ' . number_format((float) $value, 0, ',', '.') : ($value ?? '-') }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ count($detailColumns) + 1 }}">Tidak ada detail RKBU.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Riwayat Approval dan Keterangan</h2>
    @forelse ($workflowInstances as $attempt => $instance)
        <div class="stage">
            <strong>Pengajuan {{ $attempt + 1 }}</strong>
            <span class="muted">{{ $instance->submitted_at?->format('d-m-Y H:i') ?? $instance->created_at?->format('d-m-Y H:i') ?? '-' }}</span>
            @forelse ($instance->approvals->sortBy('sequence') as $stage)
                @php
                    $validator = $stage->validatorUser?->nama ?? $stage->position?->nama_jabatan ?? $stage->workflowStep?->position?->nama_jabatan ?? $stage->workflowStep?->validatorRole?->nama_validator ?? 'Validator';
                    $logs = $stage->logs->sortBy(fn ($log) => $log->aksi_at ?? $log->created_at);
                    $hasComment = $logs->contains(fn ($log) => filled($log->catatan));
                @endphp
                <div class="log">
                    <strong>Tahap {{ $stage->sequence ?? $loop->iteration }} · {{ $validator }}</strong>
                    — {{ ucfirst($stage->status) }}
                    @foreach ($logs as $log)
                        <div class="muted">{{ ucfirst($log->aksi) }} oleh {{ $log->user?->nama ?? $log->position?->nama_jabatan ?? 'Pengguna' }} · {{ $log->aksi_at?->format('d-m-Y H:i') ?? $log->created_at?->format('d-m-Y H:i') ?? '-' }}</div>
                        @if (filled($log->catatan))
                            <div>{{ $log->catatan }}</div>
                        @endif
                    @endforeach
                    @if (!$hasComment && filled($stage->catatan))
                        <div>{{ $stage->catatan }}</div>
                    @elseif ($logs->isEmpty() && !filled($stage->catatan))
                        <div class="muted">Belum ada keterangan.</div>
                    @endif
                </div>
            @empty
                <div class="muted">Belum ada tahap approval.</div>
            @endforelse
        </div>
    @empty
        <div class="muted">Belum ada riwayat approval.</div>
    @endforelse

    @if ($attachments->isNotEmpty())
        <h2>Dokumen Pendukung</h2>
        @foreach ($attachments as $attachment)
            <div class="attachment">{{ $attachment['label'] }}: <a href="{{ $attachment['url'] }}">{{ $attachment['name'] }}</a></div>
        @endforeach
    @endif
</body>
</html>
