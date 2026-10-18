
@extends('layouts.main')

@section('title', 'Validasi Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Validasi Usulan Belanja</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor Usulan</th>
                        <th>Pengusul</th>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usulans as $usulan)
                        <tr>
                            <td>{{ $usulans->firstItem() + $loop->index }}</td>
                            <td>{{ $usulan->no_usulan_belanja }}</td>
                            <td>{{ $usulan->nama_pengusul }}</td>
                            <td>
                                {{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}
                            </td>
                            <td>
                                <span class="badge badge-light-warning">
                                    {{ strtoupper($usulan->status_usulan) }}
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-light-primary"
                                   href="{{ route(
                                       'usulan-belanja.show',
                                       $usulan->id
                                   ) }}">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8">
                                Tidak ada usulan yang menunggu validasi Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $usulans->links() }}
    </div>
</div>
@endsection
