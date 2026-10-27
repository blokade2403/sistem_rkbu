@extends('layouts.main')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">{{ $title }}</h1>
            <div class="text-muted">Tentukan tahun anggaran, lalu pilih rekening yang akan diberi pagu.</div>
        </div>
        <a href="{{ route('pagu_anggaran.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Kembali ke daftar</a>
    </div>

    <form method="POST" action="{{ $action }}">
        @csrf
        @if ($method !== 'POST') @method($method) @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="row g-6">
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-0 pt-6"><div><h2 class="card-title fw-bold">1. Konteks Pagu</h2><div class="text-muted fs-7">Setiap kombinasi tahun dan rekening hanya memiliki satu pagu.</div></div></div>
                    <div class="card-body pt-2">
                        <div class="mb-6">
                            <label for="tahun_anggaran_id" class="form-label required fw-semibold">Tahun anggaran</label>
                            <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select form-select-lg" required>
                                <option value="">Pilih tahun anggaran</option>
                                @foreach ($tahunAnggarans as $tahun)
                                    <option value="{{ $tahun->id }}" @selected(old('tahun_anggaran_id', $pagu->tahun_anggaran_id) === $tahun->id)>{{ $tahun->nama_tahun_anggaran }}{{ $tahun->status === 'aktif' ? ' · Aktif' : '' }}</option>
                                @endforeach
                            </select>
                            @error('tahun_anggaran_id')<div class="text-danger fs-7 mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-6">
                            <label for="rekening_id" class="form-label required fw-semibold">Rekening belanja</label>
                            <select id="rekening_id" name="id_kode_rekening_belanja" class="form-select form-select-lg" data-control="select2" data-placeholder="Cari kode atau nama rekening" required>
                                <option value="">Cari dan pilih rekening belanja</option>
                                @foreach ($rekeningBySource as $sumber => $opsiRekening)
                                    <optgroup label="{{ $sumber }}">
                                        @foreach ($opsiRekening as $rekening)
                                            @php($sumberRekening = $rekening->aktivitas?->sub_kegiatan?->sumber_danas?->nama_sumber_dana ?? '')
                                            <option value="{{ $rekening->id_kode_rekening_belanja }}" data-sumber="{{ $sumberRekening }}" @selected(old('id_kode_rekening_belanja', $pagu->id_kode_rekening_belanja) === $rekening->id_kode_rekening_belanja)>
                                                {{ $rekening->kode_rekening_belanja }} — {{ $rekening->nama_rekening_belanja }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div id="rekening-context" class="form-text mt-2">Daftar rekening dikelompokkan menurut sumber dana agar lebih mudah dicari.</div>
                            @error('id_kode_rekening_belanja')<div class="text-danger fs-7 mt-2">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-5">
                            <div class="col-md-6">
                                <label for="jenis_sumber_dana" class="form-label required fw-semibold">Klasifikasi pagu</label>
                                <select id="jenis_sumber_dana" name="jenis_sumber_dana" class="form-select" required>
                                    <option value="">Pilih klasifikasi</option>
                                    @foreach (['BLUD', 'Non BLUD'] as $jenis)
                                        <option value="{{ $jenis }}" @selected(old('jenis_sumber_dana', $pagu->jenis_sumber_dana) === $jenis)>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Klasifikasi akan mengikuti sumber dana rekening saat rekening dipilih.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="jumlah_pagu" class="form-label required fw-semibold">Target pagu</label>
                                <div class="input-group"><span class="input-group-text">Rp</span><input id="jumlah_pagu" type="number" name="jumlah_pagu" class="form-control" min="0" step="0.01" value="{{ old('jumlah_pagu', $pagu->jumlah_pagu) }}" placeholder="Contoh: 250000000" required></div>
                                @error('jumlah_pagu')<div class="text-danger fs-7 mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-3">
                        <a href="{{ route('pagu_anggaran.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check me-2"></i>Simpan Pagu</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card border-0 bg-light-primary h-100">
                    <div class="card-body p-7">
                        <span class="badge badge-primary mb-4">Panduan singkat</span>
                        <h3 class="fw-bold text-gray-900 mb-4">Pastikan konteksnya sesuai</h3>
                        <div class="d-flex gap-3 mb-4"><span class="pagu-step-number">1</span><div><strong>Pilih tahun</strong><div class="text-muted">Pagu berlaku khusus untuk satu tahun anggaran.</div></div></div>
                        <div class="d-flex gap-3 mb-4"><span class="pagu-step-number">2</span><div><strong>Cari rekening</strong><div class="text-muted">Gunakan kode atau nama rekening. Kelompok sumber dana ditampilkan sebagai pembantu.</div></div></div>
                        <div class="d-flex gap-3"><span class="pagu-step-number">3</span><div><strong>Isi target pagu</strong><div class="text-muted">Nilai ini menjadi batas RKBU pada rekening yang sama dan tahun yang dipilih.</div></div></div>
                        @if ($method !== 'POST')
                            <div class="alert alert-warning mt-7 mb-0"><i class="fas fa-triangle-exclamation me-2"></i>Perubahan pagu akan dicatat pada histori perubahan.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .pagu-step-number{width:28px;height:28px;border-radius:50%;background:#fff;color:#1689e8;font-weight:700;display:inline-flex;align-items:center;justify-content:center;flex:0 0 28px}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const account = document.getElementById('rekening_id');
            const source = document.getElementById('jenis_sumber_dana');
            const context = document.getElementById('rekening-context');
            if (!account || !source) return;

            const updateSource = (syncClassification = false) => {
                const selected = account.options[account.selectedIndex];
                const accountSource = (selected?.dataset?.sumber || '').trim().toLowerCase();
                if (syncClassification && accountSource.includes('non blud')) source.value = 'Non BLUD';
                else if (syncClassification && accountSource.includes('blud')) source.value = 'BLUD';
                if (context && selected?.value) {
                    context.textContent = selected.dataset.sumber
                        ? `Sumber dana rekening ini: ${selected.dataset.sumber}. Klasifikasi pagu sudah disesuaikan.`
                        : 'Sumber dana rekening belum diatur. Silakan tentukan klasifikasi pagu secara manual.';
                }
            };
            account.addEventListener('change', () => updateSource(true));
            updateSource();
        });
    </script>
@endsection
