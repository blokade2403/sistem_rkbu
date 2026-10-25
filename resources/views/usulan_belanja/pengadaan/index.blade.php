@extends('layouts.main')

@section('title', 'Alur Pengadaan')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Alur Pengadaan SPJ</h3>
        @if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin'))
            <div class="card-toolbar"><a class="btn btn-sm btn-light-primary" href="{{ route('usulan-belanja.pengadaan.assignments') }}">Penugasan Pendukung PPK</a></div>
        @endif
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="table-responsive"><table class="table table-row-dashed align-middle gy-4">
            <thead><tr><th>SPJ</th><th>Subkategori</th><th>Pengusul</th><th>Tahap</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($spjs as $spj)
                    <tr>
                        <td>{{ $spj->no_usulan_belanja }}-{{ str_pad((string)$spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $spj->usulanBelanja?->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td>
                        <td>{{ $spj->usulanBelanja?->nama_pengusul ?? '-' }}</td>
                        <td><span class="badge badge-light-primary">{{ $steps[$spj->pengadaan_step ?: 'input_pesanan'] ?? $spj->pengadaan_step }}</span>
                            @if($spj->pengadaan_step === 'upload_foto' && $spj->status_verifikasi_pengurus_barang === 'Revisi')<br><small class="text-danger">Perlu perbaikan: {{ $spj->keterangan_verif_pengurus_barang }}</small>@endif
                        </td>
                        <td><a class="btn btn-sm btn-light-primary" href="{{ route('usulan-belanja.pengadaan.show', $spj->id) }}">Buka</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-8">Tidak ada tugas pengadaan pada tahap Anda.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        {{ $spjs->links() }}
    </div>
</div>
@endsection
