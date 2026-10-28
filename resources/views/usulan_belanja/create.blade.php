
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
                <label for="sumber-dana" class="form-label required">Sumber Dana</label>
                <select id="sumber-dana" name="sumber_dana_id" class="form-select" data-control="select2" required>
                    <option value="">Pilih Sumber Dana</option>
                    @foreach($sources as $source)
                        <option value="{{ $source['id'] }}" @selected(old('sumber_dana_id') === $source['id'])>{{ $source['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="jenis-kategori-rkbu" class="form-label required">Jenis Kategori RKBU</label>
                <select id="jenis-kategori-rkbu" name="jenis_kategori_rkbu_id" class="form-select" data-control="select2" required>
                    <option value="">Pilih Sumber Dana terlebih dahulu</option>
                    @foreach($jenisKategoris as $jenis)
                        <option value="{{ $jenis['id'] }}" data-sources="{{ $jenis['source_ids']->implode(',') }}"
                            @selected(old('jenis_kategori_rkbu_id') === $jenis['id'])>{{ $jenis['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="sub-kategori-rkbu" class="form-label required">Pilih Subkategori RKBU</label>
                <select id="sub-kategori-rkbu" name="sub_kategori_rkbu_id" class="form-select" data-control="select2" required>
                    <option value="">Pilih Jenis Kategori terlebih dahulu</option>
                    @foreach($rkbus as $rkbu)
                        <option value="{{ $rkbu->id }}" data-sumber="{{ $rkbu->sumber_dana_id }}" data-jenis="{{ $rkbu->id_jenis_kategori_rkbu }}"
                            @selected(old('sub_kategori_rkbu_id') === $rkbu->id)>
                            {{ $rkbu->nama_sub_kategori_rkbu ?? '-' }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    Subkategori menyesuaikan sumber dana dan jenis kategori. Item yang dapat dimasukkan berasal dari RKBU approved pada sumber dana dan subkategori tersebut.
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
    const sumber = document.getElementById('sumber-dana');
    const jenis = document.getElementById('jenis-kategori-rkbu');
    const subKategori = document.getElementById('sub-kategori-rkbu');
    const oldSumber = @json(old('sumber_dana_id'));
    const oldJenis = @json(old('jenis_kategori_rkbu_id'));
    const oldValue = @json(old('sub_kategori_rkbu_id'));

    function filterJenis() {
        const sumberId = sumber.value;
        Array.from(jenis.options).forEach(function (option, index) {
            if (index === 0) return;
            const allowedSources = (option.dataset.sources || '').split(',');
            option.hidden = !sumberId || !allowedSources.includes(sumberId);
            option.disabled = option.hidden;
        });
        if (!jenis.selectedOptions[0] || jenis.selectedOptions[0].disabled) jenis.value = '';
        jenis.options[0].text = sumberId ? 'Pilih Jenis Kategori RKBU' : 'Pilih Sumber Dana terlebih dahulu';
        filterSubkategori();
        if (window.jQuery && jQuery.fn.select2) jQuery(jenis).trigger('change.select2');
    }

    function filterSubkategori() {
        const sumberId = sumber.value;
        const jenisId = jenis.value;
        Array.from(subKategori.options).forEach(function (option, index) {
            if (index === 0) return;
            option.hidden = !sumberId || !jenisId || option.dataset.sumber !== sumberId || option.dataset.jenis !== jenisId;
            option.disabled = option.hidden;
        });
        if (!subKategori.selectedOptions[0] || subKategori.selectedOptions[0].disabled) {
            subKategori.value = '';
        }
        subKategori.options[0].text = jenisId ? 'Pilih Subkategori RKBU' : (sumberId ? 'Pilih Jenis Kategori terlebih dahulu' : 'Pilih Sumber Dana terlebih dahulu');
        if (window.jQuery && jQuery.fn.select2) jQuery(subKategori).trigger('change.select2');
    }

    function onSumberChange() {
        jenis.value = '';
        subKategori.value = '';
        filterJenis();
    }
    if (window.jQuery && jQuery.fn.select2) {
        jQuery(sumber).on('change', onSumberChange);
        jQuery(jenis).on('change', filterSubkategori);
    } else {
        sumber.addEventListener('change', onSumberChange);
        jenis.addEventListener('change', filterSubkategori);
    }
    sumber.value = oldSumber || '';
    jenis.value = oldJenis || '';
    filterJenis();
    if (oldValue) subKategori.value = oldValue;
    if (window.jQuery && jQuery.fn.select2) jQuery(subKategori).trigger('change.select2');
});
</script>
@endsection


