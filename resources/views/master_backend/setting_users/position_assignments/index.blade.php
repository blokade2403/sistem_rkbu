@extends('layouts.main')

@section('content')
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Tambah Penugasan Pejabat</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('position_assignments.store') }}" method="POST">
                @csrf
                <div class="row g-4 align-items-end">
                    <div class="col-md-3">
                        <label for="position_id" class="form-label required">Jabatan</label>
                        <select id="position_id" name="position_id" class="form-select" required>
                            <option value="">Pilih jabatan</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" @selected(old('position_id') === $position->id)>
                                    {{ $position->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label required">Pejabat (user aktif)</label>
                        <select id="user_id" name="user_id" class="form-select" required>
                            <option value="">Pilih pejabat</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id') === $user->id)>
                                    {{ $user->nama }} ({{ $user->nip }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_mulai" class="form-label required">Tanggal mulai</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" class="form-control"
                            value="{{ old('tanggal_mulai', today()->toDateString()) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_selesai" class="form-label">Tanggal selesai</label>
                        <input id="tanggal_selesai" type="date" name="tanggal_selesai" class="form-control"
                            value="{{ old('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" name="is_active" value="0">
                        <label class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                @checked(old('is_active', '1') === '1')>
                            <span class="form-check-label">Aktif</span>
                        </label>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mt-4 mb-0">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </form>
            <p class="text-muted mt-4 mb-0">
                Saat penugasan aktif baru dimulai, penugasan aktif yang sedang berlaku pada jabatan yang sama akan dinonaktifkan.
                Penugasan terjadwal mulai di masa depan berlaku sesuai tanggal mulai.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Penugasan Pejabat</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th>Pejabat</th>
                            <th>Periode</th>
                            <th>Status Assignment</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            @php
                                $effective = $assignment->is_active
                                    && $assignment->tanggal_mulai->lte(today())
                                    && (!$assignment->tanggal_selesai || $assignment->tanggal_selesai->gte(today()))
                                    && $assignment->user?->status_user === 'aktif';
                            @endphp
                            <tr>
                                <td>{{ $assignment->position?->nama_jabatan ?? 'Jabatan tidak ditemukan' }}</td>
                                <td>
                                    {{ $assignment->user?->nama ?? 'User tidak ditemukan' }}
                                    @if ($assignment->user?->nip)
                                        <div class="text-muted fs-7">{{ $assignment->user->nip }}</div>
                                    @endif
                                </td>
                                <td>
                                    {{ $assignment->tanggal_mulai->format('d-m-Y') }}
                                    &ndash;
                                    {{ $assignment->tanggal_selesai?->format('d-m-Y') ?? 'Tidak ditentukan' }}
                                </td>
                                <td>
                                    @if ($effective)
                                        <span class="badge badge-light-success">Aktif saat ini</span>
                                    @elseif ($assignment->is_active && $assignment->tanggal_mulai->gt(today()))
                                        <span class="badge badge-light-info">Terjadwal</span>
                                    @else
                                        <span class="badge badge-light-secondary">Tidak aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit-assignment-{{ $assignment->id }}">
                                        Edit
                                    </button>
                                    @if ($assignment->is_active)
                                        <form class="d-inline" action="{{ route('position_assignments.destroy', $assignment->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Nonaktifkan assignment pejabat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Nonaktifkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada penugasan pejabat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($assignments as $assignment)
        <div class="modal fade" id="edit-assignment-{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('position_assignments.update', $assignment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Penugasan Pejabat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label required">Jabatan</label>
                                <select name="position_id" class="form-select" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            @selected($assignment->position_id === $position->id)>
                                            {{ $position->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Pejabat</label>
                                <select name="user_id" class="form-select" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @selected($assignment->user_id === $user->id)>
                                            {{ $user->nama }} ({{ $user->nip }})
                                        </option>
                                    @endforeach
                                    @if ($assignment->user && $assignment->user->status_user !== 'aktif')
                                        <option value="{{ $assignment->user_id }}" selected>
                                            {{ $assignment->user->nama }} (nonaktif)
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label required">Tanggal mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control"
                                        value="{{ $assignment->tanggal_mulai->toDateString() }}" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Tanggal selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control"
                                        value="{{ $assignment->tanggal_selesai?->toDateString() }}">
                                </div>
                            </div>
                            <input type="hidden" name="is_active" value="0">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    @checked($assignment->is_active)>
                                <span class="form-check-label">Assignment diaktifkan</span>
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
