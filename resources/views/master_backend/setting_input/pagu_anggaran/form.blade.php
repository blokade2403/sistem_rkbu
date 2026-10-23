@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header"><h3 class="card-title">{{ $title }}</h3></div>
        <form method="POST" action="{{ $action }}">
            @csrf
            @if ($method !== 'POST') @method($method) @endif
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div class="mb-5">
                    <label class="form-label required">Tahun Anggaran</label>
                    <select name="tahun_anggaran_id" class="form-select" required>
                        <option value="">Pilih Tahun Anggaran</option>
                        @foreach ($tahunAnggarans as $tahun)
                            <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id', $pagu->tahun_anggaran_id) === $tahun->id)>
                                {{ $tahun->nama_tahun_anggaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label class="form-label required">Rekening Belanja</label>
                    <select name="id_kode_rekening_belanja" class="form-select" data-control="select2" required>
                        <option value="">Pilih Rekening Belanja</option>
                        @foreach ($rekenings as $rekening)
                            @php $sumber = $rekening->aktivitas?->sub_kegiatan?->sumber_danas?->nama_sumber_dana; @endphp
                            <option value="{{ $rekening->id_kode_rekening_belanja }}" @selected(old('id_kode_rekening_belanja', $pagu->id_kode_rekening_belanja) === $rekening->id_kode_rekening_belanja)>
                                {{ $rekening->kode_rekening_belanja }} — {{ $rekening->nama_rekening_belanja }}{{ $sumber ? ' (' . $sumber . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label class="form-label required">Klasifikasi Sumber Dana</label>
                    <select name="jenis_sumber_dana" class="form-select" required>
                        <option value="">Pilih Klasifikasi</option>
                        @foreach (['BLUD', 'Non BLUD'] as $jenis)
                            <option value="{{ $jenis }}" @selected(old('jenis_sumber_dana', $pagu->jenis_sumber_dana) === $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Klasifikasi ini mengikuti rekening belanja yang dipilih dan digunakan untuk pelaporan pagu.</div>
                </div>
                <div class="mb-5">
                    <label class="form-label required">Target Pagu (Rp)</label>
                    <input type="number" name="jumlah_pagu" class="form-control" min="0" step="0.01" value="{{ old('jumlah_pagu', $pagu->jumlah_pagu) }}" required>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('pagu_anggaran.index') }}" class="btn btn-light">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
