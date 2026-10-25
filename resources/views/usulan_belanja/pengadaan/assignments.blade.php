@extends('layouts.main')
@section('title', 'Penugasan Pendukung PPK')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Penugasan Pendukung PPK per Subkategori RKBU</h3><div class="card-toolbar"><a href="{{ route('usulan-belanja.pengadaan.index') }}" class="btn btn-light">Antrean Pengadaan</a></div></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <p class="text-muted">Pilih satu user ber-role Pendukung PPK untuk setiap subkategori. User tersebut menerima tugas unggah foto barang datang untuk SPJ pada subkategori itu.</p>
        <div class="table-responsive"><table class="table table-row-dashed align-middle gy-4"><thead><tr><th>Subkategori RKBU</th><th>User Pendukung PPK</th><th>Aksi</th></tr></thead><tbody>
            @foreach($subKategoris as $subKategori)
                @php($assigned = $subKategori->pendukungPpk->first())
                <tr><td>{{ $subKategori->kode_sub_kategori_rkbu }} — {{ $subKategori->nama_sub_kategori_rkbu }}</td>
                    <td><form id="assign-{{ $subKategori->id_sub_kategori_rkbu }}" method="POST" action="{{ route('usulan-belanja.pengadaan.assignments.update', $subKategori->id_sub_kategori_rkbu) }}">@csrf @method('PUT')
                        <select name="user_id" class="form-select" required><option value="">Pilih Pendukung PPK</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected($assigned?->id === $user->id)>{{ $user->nama }} ({{ $user->username }})</option>@endforeach</select>
                    </form>@if($users->isEmpty())<small class="text-danger">Belum ada user dengan role Pendukung PPK.</small>@endif</td>
                    <td><button type="submit" form="assign-{{ $subKategori->id_sub_kategori_rkbu }}" class="btn btn-sm btn-primary">Simpan</button>
                        @if($assigned)<form method="POST" action="{{ route('usulan-belanja.pengadaan.assignments.update', $subKategori->id_sub_kategori_rkbu) }}" class="d-inline">@csrf @method('PUT')<input type="hidden" name="user_id" value=""><button class="btn btn-sm btn-light-danger">Hapus</button></form>@endif
                    </td>
                </tr>
            @endforeach
        </tbody></table></div>
    </div>
</div>
@endsection
