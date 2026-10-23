@extends('layouts.main')

@section('title', 'SPJ per Penyedia')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar SPJ per Perusahaan Penyedia</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('usulan-belanja.spj.providers') }}" class="row g-3 align-items-end mb-7">
            <div class="col-md-4">
                <label for="perusahaan_id" class="form-label">Perusahaan penyedia</label>
                <select name="perusahaan_id" id="perusahaan_id" class="form-select">
                    <option value="">Semua perusahaan</option>
                    @foreach($perusahaans as $perusahaan)
                        <option value="{{ $perusahaan->id_perusahaan }}" @selected(request('perusahaan_id') == $perusahaan->id_perusahaan)>{{ $perusahaan->nama_perusahaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status_spj" class="form-label">Status SPJ</label>
                <select name="status_spj" id="status_spj" class="form-select">
                    <option value="">Semua status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status_spj') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="q" class="form-label">Cari</label>
                <input type="search" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="No. usulan / pengusul">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="{{ route('usulan-belanja.spj.providers') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Perusahaan Penyedia</th>
                        <th>Nomor SPJ</th>
                        <th>Nomor Usulan</th>
                        <th>Pengusul</th>
                        <th>Jumlah Item</th>
                        <th>Total Volume</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                        <tr>
                            <td>{{ $spjs->firstItem() + $loop->index }}</td>
                            <td class="fw-bold">{{ $spj->nama_perusahaan }}</td>
                            <td>{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $spj->no_usulan_belanja }}</td>
                            <td>{{ $spj->nama_pengusul ?: '-' }}</td>
                            <td>{{ $spj->jumlah_item }}</td>
                            <td>{{ rtrim(rtrim(number_format((float) $spj->jumlah_volume, 3, ',', '.'), '0'), ',') }}</td>
                            <td><span class="badge badge-light-primary">{{ $spj->status_spj }}</span></td>
                            <td><a href="{{ route('usulan-belanja.spj.show', $spj->spj_id) }}" class="btn btn-sm btn-light-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-8">Belum ada SPJ untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $spjs->links() }}
    </div>
</div>
@endsection
