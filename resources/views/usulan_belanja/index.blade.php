
@extends('layouts.main')
@section('title', 'Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Daftar Usulan Belanja</h3>
        </div>

        <div class="card-toolbar">
            <a href="{{ route('usulan-belanja.create') }}"
               class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Buat Usulan
            </a>
        </div>
    </div>

    <div class="card-body py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              action="{{ route('usulan-belanja.index') }}"
              class="row g-3 mb-6">

            <div class="col-md-4">
                <label class="form-label">Tahun Anggaran</label>
                <select name="tahun_anggaran_id" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunAnggarans as $tahun)
                        <option value="{{ $tahun->id }}"
                            @selected(request('tahun_anggaran_id') === $tahun->id)>
                            {{ $tahun->nama_tahun_anggaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach([
                        'draft' => 'Draft',
                        'proses' => 'Proses Validasi',
                        'revision' => 'Revisi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ] as $value => $label)
                        <option value="{{ $value }}"
                            @selected(request('status') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit">
                    Filter
                </button>
                <a href="{{ route('usulan-belanja.index') }}"
                   class="btn btn-light">
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="text-gray-500 fw-bold text-uppercase">
                        <th>No.</th>
                        <th>No. Usulan</th>
                        <th>Pengusul</th>
                        <th>No. RKBU</th>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usulans as $usulan)
                        <tr>
                            <td>
                                {{ $usulans->firstItem() + $loop->index }}
                            </td>
                            <td class="fw-bold">
                                {{ $usulan->no_usulan_belanja }}
                            </td>
                            <td>{{ $usulan->nama_pengusul }}</td>
                            <td>
                                {{ $usulan->rkbu?->nomor_rkbu ?? '-' }}
                            </td>
                            <td>
                                {{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}
                            </td>
                            <td>
                                @php
                                    $badge = [
                                        'draft' => 'secondary',
                                        'proses' => 'warning',
                                        'revision' => 'info',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                    ][$usulan->status_usulan] ?? 'secondary';
                                @endphp

                                <span class="badge badge-light-{{ $badge }}">
                                    {{ strtoupper($usulan->status_usulan) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route(
                                    'usulan-belanja.show',
                                    $usulan->id
                                ) }}" class="btn btn-sm btn-light-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-10">
                                Belum ada Usulan Belanja.
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
