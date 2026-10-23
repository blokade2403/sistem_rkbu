@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Histori Pergeseran Pagu — {{ $paguAnggaran->rekeningBelanja?->nama_rekening_belanja }}</h3>
            <div class="card-toolbar"><a href="{{ route('pagu_anggaran.index') }}" class="btn btn-sm btn-light">Kembali</a></div>
        </div>
        <div class="card-body">
            @forelse ($history as $entry)
                <div class="border rounded p-5 mb-4">
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <strong>{{ $entry->jenis_perubahan === 'rkbu' ? 'Perubahan RKBU' : 'Perubahan Usulan Belanja' }} — {{ $entry->rkbu?->nomor_rkbu ?? '-' }}</strong>
                        <span class="text-muted">{{ $entry->created_at?->format('d-m-Y H:i') }} · {{ $entry->user?->nama ?? '-' }} · Fase {{ $entry->fase_urutan }}</span>
                    </div>
                    <div class="mt-2">{{ $entry->alasan }}</div>
                    <div class="mt-2">Sebelum: Rp {{ number_format((float) $entry->nilai_sebelum, 0, ',', '.') }} · Sesudah: Rp {{ number_format((float) $entry->nilai_sesudah, 0, ',', '.') }} · Selisih: Rp {{ number_format((float) $entry->selisih, 0, ',', '.') }}</div>
                    @if ($entry->lampiran)<a class="d-inline-block mt-2" href="{{ asset('storage/' . $entry->lampiran) }}" target="_blank" rel="noopener">Buka lampiran bukti</a>@endif
                    @if ($entry->detail_sesudah)
                        <details class="mt-3"><summary>Rincian item dan perubahan</summary><pre class="mt-2 mb-0">{{ json_encode(['sebelum' => $entry->detail_sebelum, 'sesudah' => $entry->detail_sesudah], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></details>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-8">Belum ada histori perubahan.</div>
            @endforelse
            {{ $history->links() }}
        </div>
    </div>
@endsection
