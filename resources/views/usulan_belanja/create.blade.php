
@extends('layouts.main')

@section('title', 'Buat Usulan Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Buat Usulan Belanja</h3>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert {{ $errors->has('sub_kategori_rkbu_id') ? 'alert-warning' : 'alert-danger' }}" role="alert">
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
                <label for="jenis-kategori-rkbu" class="form-label required">Jenis Kategori RKBU</label>
                <select id="jenis-kategori-rkbu" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kategori RKBU" required>
                    <option value="">Pilih Jenis Kategori RKBU</option>
                    @foreach($rkbus->pluck('id_jenis_kategori_rkbu')->filter()->unique() as $jenisId)
                        <option value="{{ $jenisId }}">{{ $rkbus->firstWhere('id_jenis_kategori_rkbu', $jenisId)->nama_jenis_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="form-label required">
                    Pilih Subkategori RKBU
                </label>

                <select id="sub-kategori-rkbu" name="sub_kategori_rkbu_id"
                        class="form-select"
                        data-control="select2"
                        data-placeholder="Pilih Jenis Kategori terlebih dahulu"
                        required>
                    <option value="">Pilih Jenis Kategori terlebih dahulu</option>

                    @foreach($rkbus as $rkbu)
                        <option value="{{ $rkbu->id }}" data-jenis="{{ $rkbu->id_jenis_kategori_rkbu }}"
                            @selected(old('sub_kategori_rkbu_id') === $rkbu->id)>
                            {{ $rkbu->nama_sub_kategori_rkbu ?? '-' }}
                        </option>
                    @endforeach
                </select>

                <div class="form-text">
                    Pilih subkategori dari RKBU berstatus approved. Satu usulan dapat memuat item dari beberapa nomor RKBU dalam subkategori tersebut.
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenis = document.getElementById('jenis-kategori-rkbu');
    const subKategori = document.getElementById('sub-kategori-rkbu');
    const oldValue = @json(old('sub_kategori_rkbu_id'));

    function filterSubkategori() {
        const jenisId = jenis.value;
        Array.from(subKategori.options).forEach(function (option, index) {
            if (index === 0) return;
            option.hidden = !jenisId || option.dataset.jenis !== jenisId;
            option.disabled = option.hidden;
        });
        if (!subKategori.selectedOptions[0] || subKategori.selectedOptions[0].disabled) {
            subKategori.value = '';
        }
        subKategori.options[0].text = jenisId ? 'Pilih Subkategori RKBU' : 'Pilih Jenis Kategori terlebih dahulu';
        if (window.jQuery && jQuery.fn.select2) jQuery(subKategori).trigger('change.select2');
    }

    if (window.jQuery && jQuery.fn.select2) {
        jQuery(jenis).on('change', filterSubkategori);
    } else {
        jenis.addEventListener('change', filterSubkategori);
    }
    if (oldValue) {
        const oldOption = Array.from(subKategori.options).find(option => option.value === oldValue);
        if (oldOption) {
            jenis.value = oldOption.dataset.jenis;
            filterSubkategori();
            subKategori.value = oldValue;
        }
    }
    filterSubkategori();
});
</script>
@endsection


