@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penguncian Pagu Anggaran</h3>
            <div class="card-toolbar">
                <a href="{{ route('pagu_anggaran.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Pagu
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            <div class="alert alert-light-info">
                Pagu membatasi nilai RKBU dan nilai Usulan Belanja secara terpisah berdasarkan tahun serta Rekening Belanja.
                Validasi total RKBU diberlakukan mulai fase dengan urutan 2.
            </div>
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle">
                    <thead>
                        <tr>
                            <th>Tahun</th><th>Klasifikasi Dana</th><th>Rekening Belanja</th>
                            <th class="text-end">Pagu</th><th class="text-end">Total RKBU</th>
                            <th class="text-end">Total Usulan</th><th class="text-end">Sisa Pagu RKBU</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pagus as $pagu)
                            <tr>
                                <td>{{ $pagu->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td>
                                <td><span class="badge badge-light-{{ $pagu->jenis_sumber_dana === 'BLUD' ? 'primary' : 'warning' }}">{{ $pagu->jenis_sumber_dana }}</span></td>
                                <td>{{ $pagu->rekeningBelanja?->kode_rekening_belanja }} — {{ $pagu->rekeningBelanja?->nama_rekening_belanja }}</td>
                                <td class="text-end">Rp {{ number_format((float) $pagu->jumlah_pagu, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format((float) $pagu->rkbu_terpakai, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format((float) $pagu->usulan_terpakai, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format(max(0, (float) $pagu->jumlah_pagu - (float) $pagu->rkbu_terpakai), 0, ',', '.') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('pagu_anggaran.history', $pagu) }}" class="btn btn-sm btn-light-info">Histori</a>
                                    <a href="{{ route('pagu_anggaran.edit', $pagu) }}" class="btn btn-sm btn-light-primary">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-8">Belum ada pagu anggaran yang ditetapkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
