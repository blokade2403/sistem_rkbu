@extends('layouts.main')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Riwayat Aset</h1>
            <div class="text-muted">Riwayat pencatatan, perubahan, usulan penghapusan, dan penghapusan aset.</div>
        </div>
        <a href="{{ route('asset-module.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Aset</a>
    </div>

    <section class="card border-0 shadow-sm">
        <div class="card-body pt-4">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7"><th>Waktu</th><th>No. Register / Aset</th><th>Aktivitas</th><th>Perubahan</th><th>Keterangan</th><th>Petugas</th></tr></thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td class="text-nowrap">{{ $history->created_at?->format('d-m-Y H:i') }}</td>
                                <td><div class="fw-bold">{{ $history->asset_register ?: '-' }}</div><div>{{ $history->asset_name }}</div></td>
                                <td>{{ match ($history->action) { 'created' => 'Pencatatan aset', 'updated' => 'Perubahan data', 'deletion_proposed' => 'Usulan penghapusan / reklas', 'deleted' => 'Record aset dihapus', default => ucfirst(str_replace('_', ' ', $history->action)) } }}</td>
                                <td>
                                    @forelse ($history->changes as [$field, $oldValue, $newValue])
                                        <div><strong>{{ $field }}:</strong> {{ $oldValue }} &rarr; {{ $newValue }}</div>
                                    @empty
                                        -
                                    @endforelse
                                </td>
                                <td>{{ $history->description ?: '-' }}</td>
                                <td>{{ $history->user?->nama ?: 'Akun dihapus' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-10">Belum ada riwayat aset.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $histories->links() }}
        </div>
    </section>
@endsection
