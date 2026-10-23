@extends('layouts.main')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-10">
                <div class="card-header">
                    <div class="card-title fs-3 fw-bolder">{{ $record ? 'Edit' : 'Tambah' }} RKBU Pegawai</div>
                </div>

                <form id="rkbu-pegawai-form" class="form" action="{{ $action }}" method="POST">
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-8">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!$record)
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-8">
                                <div class="row w-100 align-items-center">
                                    <div class="col-xl-3">
                                        <label for="sub_kategori_rkbu_id" class="form-label fw-bold required">Sub Kategori RKBU</label>
                                    </div>
                                    <div class="col-xl-9">
                                        <select name="sub_kategori_rkbu_id" id="sub_kategori_rkbu_id"
                                            class="form-select form-select-solid" data-control="select2"
                                            data-placeholder="Pilih Sub Kategori RKBU" required>
                                            <option value=""></option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id_sub_kategori_rkbu }}"
                                                    @selected(old('sub_kategori_rkbu_id') === $subcategory->id_sub_kategori_rkbu)>
                                                    {{ $subcategory->kode_sub_kategori_rkbu }} - {{ $subcategory->nama_sub_kategori_rkbu }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($subcategories->isEmpty())
                                            <div class="form-text text-danger">Subkategori RKBU aktif belum tersedia.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card card-bordered mb-8">
                            <div class="card-header min-h-50px">
                                <h3 class="card-title fw-bolder">Identitas Pegawai</h3>
                            </div>
                            <div class="card-body">
                                @foreach ($config['fields'] as $field)
                                    @continue(in_array($field['name'], ['gaji_pokok', 'remunerasi', 'koefisien_remunerasi', 'koefisien_gaji', 'bpjs_kesehatan', 'bpjs_tk', 'bpjs_jht'], true))
                                    @php
                                        $name = $field['name'];
                                        $required = !empty($field['required']);
                                        $value = old($name, $record?->{$name});
                                    @endphp
                                    <div class="row mb-6">
                                        <div class="col-xl-3">
                                            <label for="{{ $name }}" class="form-label fw-bold {{ $required ? 'required' : '' }} mt-2">
                                                {{ $field['label'] }}
                                            </label>
                                        </div>
                                        <div class="col-xl-9">
                                            <input type="{{ $field['type'] }}" name="{{ $name }}" id="{{ $name }}"
                                                class="form-control form-control-solid" value="{{ $value }}"
                                                @if ($field['type'] === 'number') step="any" min="{{ $field['min'] ?? 0 }}" @endif
                                                @required($required)>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card card-bordered mb-8">
                            <div class="card-header min-h-50px">
                                <h3 class="card-title fw-bolder">Rincian Gaji dan Remunerasi</h3>
                            </div>
                            <div class="card-body">
                                @foreach ($config['fields'] as $field)
                                    @continue(!in_array($field['name'], ['gaji_pokok', 'remunerasi', 'koefisien_remunerasi', 'koefisien_gaji', 'bpjs_kesehatan', 'bpjs_tk', 'bpjs_jht'], true))
                                    @php
                                        $name = $field['name'];
                                        $required = !empty($field['required']);
                                        $value = old($name, $record?->{$name});
                                    @endphp
                                    <div class="row mb-6">
                                        <div class="col-xl-3">
                                            <label for="{{ $name }}" class="form-label fw-bold {{ $required ? 'required' : '' }} mt-2">
                                                {{ $field['label'] }}{{ str_starts_with($name, 'bpjs_') ? ' (%)' : '' }}
                                            </label>
                                        </div>
                                        <div class="col-xl-9">
                                            <input type="number" name="{{ $name }}" id="{{ $name }}"
                                                class="form-control form-control-solid pegawai-calc-input" value="{{ $value }}"
                                                step="any" min="{{ $field['min'] ?? 0 }}" @required($required)>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                            <div class="row w-100">
                                <div class="col-md-6 mb-3"><span class="fw-bold">Jumlah Bulan:</span> <span id="pegawai-preview-bulan">{{ $record?->jumlah_volume ?? 0 }}</span></div>
                                <div class="col-md-6 mb-3"><span class="fw-bold">Total Gaji Pokok:</span> Rp <span id="pegawai-preview-gaji">{{ number_format((float) ($record?->total_gaji_pokok ?? 0), 0, ',', '.') }}</span></div>
                                <div class="col-md-6 mb-3"><span class="fw-bold">Total Remunerasi:</span> Rp <span id="pegawai-preview-remunerasi">{{ number_format((float) ($record?->total_remunerasi ?? 0), 0, ',', '.') }}</span></div>
                                <div class="col-md-6 mb-3"><span class="fw-bold">Total Anggaran:</span> Rp <span id="pegawai-preview-total">{{ number_format((float) ($record?->total_anggaran ?? 0), 0, ',', '.') }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6">
                        <a href="{{ route('rkbu.pegawai.index') }}" class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan RKBU Pegawai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const number = (id) => Number.parseFloat(document.getElementById(id)?.value || 0) || 0;
            const rupiah = (value) => new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
            const updatePreview = () => {
                const gajiPokok = number('gaji_pokok');
                const remunerasi = number('remunerasi');
                const koefisienGaji = number('koefisien_gaji');
                const koefisienRemunerasi = number('koefisien_remunerasi');
                const totalGaji = gajiPokok * koefisienGaji;
                const totalRemunerasi = remunerasi * koefisienRemunerasi;
                const bpjs = gajiPokok * (number('bpjs_kesehatan') + number('bpjs_tk') + number('bpjs_jht')) / 100 * koefisienGaji;
                document.getElementById('pegawai-preview-bulan').textContent = koefisienRemunerasi;
                document.getElementById('pegawai-preview-gaji').textContent = rupiah(totalGaji);
                document.getElementById('pegawai-preview-remunerasi').textContent = rupiah(totalRemunerasi);
                document.getElementById('pegawai-preview-total').textContent = rupiah(totalGaji + totalRemunerasi + bpjs);
            };
            document.querySelectorAll('.pegawai-calc-input').forEach((input) => input.addEventListener('input', updatePreview));
            updatePreview();
        })();
    </script>
@endsection
