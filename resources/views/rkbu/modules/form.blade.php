@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title fs-3 fw-bolder">{{ $record ? 'Edit ' : 'Tambah ' }}{{ $config['title'] }}</h3>
        </div>

        @if (in_array('nama_barang', array_column($config['fields'], 'name'), true))
            @include('rkbu.rkbu_barjas.modal_komponen')
        @endif

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
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
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <label for="sub_kategori_rkbu_id" class="form-label fw-bold required mt-2 mb-3">
                                Sub Kategori RKBU
                            </label>
                        </div>
                        <div class="col-xl-9">
                            <select name="sub_kategori_rkbu_id" id="sub_kategori_rkbu_id"
                                class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Sub Kategori" required>
                                <option></option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id_sub_kategori_rkbu }}"
                                        @selected(old('sub_kategori_rkbu_id') === $subcategory->id_sub_kategori_rkbu)>
                                        {{ $subcategory->kode_sub_kategori_rkbu }} - {{ $subcategory->nama_sub_kategori_rkbu }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($subcategories->isEmpty())
                                <div class="form-text text-danger">Sub kategori aktif untuk modul ini belum tersedia.</div>
                            @endif
                        </div>
                    </div>
                @endif
                    
                @foreach ($config['fields'] as $field)
                    @php
                        $name = $field['name'];
                        $value = old($name, $record?->{$name});
                        $required = !empty($field['required']);
                    @endphp
                    <div class="row mb-8">
                        <div class="col-xl-3">
                            <label for="{{ $name }}"
                                class="form-label fw-bold {{ $required ? 'required' : '' }} mt-2 mb-3">
                                {{ $field['label'] }}
                            </label>
                        </div>
                        <div class="col-xl-9">
                            @if ($name === 'nama_barang' && isset($komponens))
                                <div class="input-group">
                                    <input type="text" name="{{ $name }}" id="{{ $name }}"
                                        class="form-control form-control-solid" value="{{ $value }}"
                                        placeholder="Pilih komponen atau input manual" @required($required)>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_new_komponen">
                                        Pilih Komponen
                                    </button>
                                </div>
                                <div class="form-text">
                                    Jika komponen tidak tersedia, silakan input manual pada field di atas.
                                </div>
                            @elseif ($name === 'satuan_1' && isset($uraian_satu))
                                <select name="{{ $name }}" id="{{ $name }}" class="form-select form-select-solid"
                                    data-control="select2" data-placeholder="Pilih Satuan">
                                    <option></option>
                                    @foreach ($uraian_satu as $item)
                                        <option value="{{ $item->nama_uraian_1 }}"
                                            @selected($value == $item->nama_uraian_1)>
                                            {{ $item->nama_uraian_1 }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif ($name === 'ppn' && isset($ppn))
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <span class="form-check-label fw-bold text-gray-700">{{ $ppn->ppn }}%</span>
                                    <input type="hidden" name="{{ $name }}" value="{{ $ppn->ppn }}">
                                </div>
                            @elseif ($field['type'] === 'textarea')
                                <textarea name="{{ $name }}" id="{{ $name }}" class="form-control form-control-solid" rows="3"
                                    @required($required)>{{ $value }}</textarea>
                            @elseif ($field['type'] === 'file')
                                <input type="file" name="{{ $name }}" id="{{ $name }}"
                                    class="form-control form-control-solid"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                @if ($record?->{$name})
                                    <a class="form-text d-inline-block" href="{{ asset('storage/' . $record->{$name}) }}"
                                        target="_blank" rel="noopener">Lihat lampiran saat ini</a>
                                @endif
                            @else
                                <input type="{{ $field['type'] }}" name="{{ $name }}" id="{{ $name }}"
                                    class="form-control form-control-solid" value="{{ $value }}"
                                    @if ($field['type'] === 'number') step="any" min="{{ $field['min'] ?? 0 }}" @endif
                                    @required($required)>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card-footer d-flex justify-content-end py-6">
                <a href="{{ route('rkbu.' . $config['route'] . '.index') }}"
                    class="btn btn-light btn-active-light-primary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
