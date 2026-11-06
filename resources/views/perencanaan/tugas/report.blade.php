<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 34px 36px; }
        body { font-family: DejaVu Sans, sans-serif; color: #202a36; font-size: 10px; }
        h1 { margin: 0 0 4px; font-size: 17px; text-align: center; }
        .subtitle { margin: 0 0 20px; text-align: center; font-size: 11px; }
        .identity { margin: 0 0 16px; line-height: 1.7; }
        table { width: 100%; table-layout: fixed; border-collapse: collapse; }
        th, td { box-sizing: border-box; border: 1px solid #8c96a3; padding: 6px; vertical-align: top; overflow-wrap: anywhere; word-wrap: break-word; }
        th { background: #edf2f7; text-align: center; }
        .center { text-align: center; }
        .summary { margin-top: 12px; font-weight: bold; }
        .signatures { width: 100%; margin-top: 34px; border: 0; }
        .signatures td { width: 50%; border: 0; text-align: center; padding: 4px 12px; }
        .sign-space { height: 54px; }
        .name { font-weight: bold; text-decoration: underline; }
        .muted { color: #64748b; }
        @if (! $pdfMode)
        body { box-sizing: border-box; width: calc(100% - 40px); max-width: 900px; margin: 30px auto; padding: 30px; background: #fff; box-shadow: 0 8px 32px #1f375a18; font-size: 12px; }
        .preview-actions { display: flex; justify-content: space-between; align-items: center; margin: -5px 0 24px; font-family: Arial, sans-serif; }
        .preview-actions a { display: inline-block; padding: 10px 14px; border-radius: 7px; background: #edf2f7; color: #26364b; text-decoration: none; font-size: 12px; }
        .preview-actions a.primary { background: #246bfd; color: white; }
        @media print { body { max-width: none; margin: 0; padding: 0; box-shadow: none; } .preview-actions { display: none; } }
        @endif
    </style>
</head>
<body>
    @unless ($pdfMode)
        <div class="preview-actions"><a href="{{ route('perencanaan.tugas.index', ['month' => $period->format('Y-m')]) }}"><i class="fas fa-arrow-left"></i> Kembali ke tugas</a><a class="primary" href="{{ route('perencanaan.tugas.report.download', $period->format('Y-m')) }}"><i class="fas fa-file-pdf"></i> Unduh PDF</a></div>
    @endunless
    <h1>LAPORAN TUGAS STAF PERENCANAAN DAN ANGGARAN</h1>
    <p class="subtitle">Periode {{ $period->translatedFormat('F Y') }}</p>
    <div class="identity">
        Nama: <strong>{{ $user->nama }}</strong><br>
        NIP: {{ $user->nip ?: '-' }}<br>
        Jabatan: {{ $position?->nama_jabatan ?: 'Staf Perencanaan dan Anggaran' }}
    </div>
    <table>
        <thead><tr><th style="width: 5%">No.</th><th style="width: 14%">Tanggal informasi</th><th style="width: 31%">Uraian tugas</th><th style="width: 16%">Pemberi tugas</th><th style="width: 14%">Tenggat</th><th style="width: 10%">Selesai</th><th style="width: 10%">Status</th></tr></thead>
        <tbody>
        @forelse ($tasks as $index => $task)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $task->tanggal_informasi->format('d-m-Y') }}</td>
                <td>{{ $task->informasi_tugas }}@if($task->keterangan)<br><span class="muted">Keterangan: {{ $task->keterangan }}</span>@endif</td>
                <td>{{ $task->tugas_dari }}</td>
                <td>{{ $task->dateline_tugas->format('d-m-Y H:i') }}</td>
                <td>{{ $task->tanggal_selesai?->format('d-m-Y') ?: '-' }}</td>
                <td>{{ $task->tanggal_selesai ? 'Selesai' : ($task->dateline_tugas->isPast() ? 'Terlambat / berjalan' : 'Berjalan') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="center">Tidak ada tugas yang tercatat pada periode ini.</td></tr>
        @endforelse
        </tbody>
    </table>
    <p class="summary">Jumlah tugas: {{ $tasks->count() }} | Selesai: {{ $tasks->whereNotNull('tanggal_selesai')->count() }} | Belum selesai: {{ $tasks->whereNull('tanggal_selesai')->count() }}</p>
    <table class="signatures">
        <tr><td>Mengetahui,<br>{{ $supervisorPosition?->nama_jabatan ?: 'Atasan langsung' }}</td><td>{{ $period->translatedFormat('F Y') }}<br>Staf Perencanaan dan Anggaran</td></tr>
        <tr><td class="sign-space"></td><td class="sign-space"></td></tr>
        <tr><td class="name">{{ $supervisor?->nama ?: '........................................' }}</td><td class="name">{{ $user->nama }}</td></tr>
        <tr><td>NIP. {{ $supervisor?->nip ?: '................................' }}</td><td>NIP. {{ $user->nip ?: '................................' }}</td></tr>
    </table>
</body>
</html>
