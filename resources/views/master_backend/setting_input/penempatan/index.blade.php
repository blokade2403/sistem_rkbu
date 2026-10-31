@extends('layouts.main')

@section('content')
    @include('components.partials.table.headermenu', ['title' => $title])
    @include('master_backend.setting_input.penempatan.modal_add')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><div class="fw-bold mb-1">Periksa kembali data yang dimasukkan:</div><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-5 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div><h2 class="fs-4 fw-bold text-gray-900 mb-1">Daftar Penempatan</h2><div class="text-muted fs-7">Kelola lokasi, gedung, ruangan, dan penanggung jawab aset.</div></div>
            <span class="badge bg-light-primary text-primary px-3 py-2">{{ $penempatans->count() }} lokasi</span>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gy-4 mb-0">
                    <thead class="text-muted"><tr><th class="ps-4">No</th><th>Lokasi Barang</th><th>Gedung</th><th>Tempat / Ruangan</th><th>Penanggung Jawab</th><th class="text-center">Aset</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                        @forelse ($penempatans as $index => $penempatan)
                            <tr>
                                <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-gray-800">{{ $penempatan->lokasi_barang }}</td>
                                <td>{{ $penempatan->gedung ?: '—' }}</td>
                                <td>{{ $penempatan->tempat_lokasi }}</td>
                                <td>{{ $penempatan->penanggung_jawab ?: '—' }}</td>
                                <td class="text-center"><span class="badge {{ ($assetCounts[$penempatan->id_penempatan] ?? 0) ? 'bg-light-info text-info' : 'bg-light text-muted' }}">{{ $assetCounts[$penempatan->id_penempatan] ?? 0 }}</span></td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPenempatan{{ $penempatan->id }}"><i class="fas fa-pen me-1"></i>Edit</button>
                                    <form action="{{ route('penempatans.destroy', $penempatan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data penempatan ini?')">@csrf @method('DELETE')<button type="submit" class="btn btn-light-danger btn-sm" @disabled(($assetCounts[$penempatan->id_penempatan] ?? 0) > 0) title="{{ ($assetCounts[$penempatan->id_penempatan] ?? 0) > 0 ? 'Masih digunakan oleh aset' : 'Hapus penempatan' }}"><i class="fas fa-trash me-1"></i>Hapus</button></form>
                                </td>
                            </tr>
                            @include('master_backend.setting_input.penempatan.modal_edit', ['penempatan' => $penempatan])
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-10">Belum ada data penempatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
