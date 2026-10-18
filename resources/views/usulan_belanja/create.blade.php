
@extends('layouts.main')

@section('title', 'Buat Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Buat Usulan Belanja</h3>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('usulan-belanja.store') }}">
            @csrf

            <div class="mb-6">
                <label class="form-label required">
                    Pilih RKBU
                </label>

                <select name="rkbu_id"
                        class="form-select"
                        data-control="select2"
                        data-placeholder="Pilih RKBU"
                        required>
                    <option value="">Pilih RKBU</option>

                    @foreach($rkbus as $rkbu)
                        <option value="{{ $rkbu->id }}"
                            @selected(old('rkbu_id') === $rkbu->id)>
                            {{ $rkbu->nomor_rkbu }}
                            — {{ $rkbu->nama_sub_kategori_rkbu ?? '-' }}
                        </option>
                    @endforeach
                </select>

                <div class="form-text">
                    Hanya RKBU berstatus approved yang belum memiliki Usulan Belanja yang dapat dipilih.
                    Form ini hanya dapat digunakan oleh validator urutan pertama workflow Usulan Belanja.
                </div>
            </div>

            @if($rkbus->isEmpty())
                <div class="alert alert-info">
                    Tidak ada RKBU approved yang tersedia untuk diusulkan. RKBU yang sudah memiliki
                    dokumen Usulan Belanja tidak ditampilkan.
                </div>
            @endif

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('usulan-belanja.index') }}"
                   class="btn btn-light">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Usulan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
