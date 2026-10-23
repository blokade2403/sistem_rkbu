@extends('layouts.main')

@section('title', 'SPJ dari Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">SPJ dari Usulan Belanja Disetujui</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Nomor Usulan</th>
                        <th>Subkategori</th>
                        <th>Pengusul / Unit</th>
                        <th>Tahun Anggaran</th>
                        <th>Item Usulan</th>
                        <th>SPJ</th>
                        <th>Item Masih Bersisa</th>
                        <th>Total Sisa Volume</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usulans as $usulan)
                        <tr>
                            <td>{{ $usulans->firstItem() + $loop->index }}</td>
                            <td class="fw-bold">{{ $usulan->no_usulan_belanja }}</td>
                            <td>{{ $usulan->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td>
                            <td>{{ $usulan->nama_pengusul ?: $usulan->pengusul?->nama ?? '-' }}<br><span class="text-muted fs-7">{{ $usulan->unit?->nama_unit ?? '-' }}</span></td>
                            <td>{{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td>
                            <td>{{ $usulan->item_count }}</td>
                            <td>
                                @forelse($usulan->spjs as $spj)
                                    <a href="{{ route('usulan-belanja.spj.show', $spj->id) }}" class="badge badge-light-primary me-1 mb-1">
                                        SPJ {{ $spj->nomor_urut ?? $loop->iteration }} ({{ $spj->details_count }} item)
                                    </a>
                                @empty
                                    <span class="text-muted">Belum dibuat</span>
                                @endforelse
                            </td>
                            <td>{{ $usulan->unassigned_item_count }}</td>
                            <td>{{ rtrim(rtrim(number_format((float) $usulan->unassigned_volume, 3, ',', '.'), '0'), ',') }}</td>
                            <td>
                                @if(($isAdmin || $usulan->user_id === auth()->id() || in_array($usulan->workflow_instance_id, $validatedWorkflowIds, true)) && (int) $usulan->unassigned_item_count > 0)
                                    <a href="{{ route('usulan-belanja.spj.create', $usulan->id) }}" class="btn btn-sm btn-primary">Buat / Tambah SPJ</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-8">Belum ada Usulan Belanja yang disetujui workflow.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $usulans->links() }}
    </div>
</div>
@endsection
