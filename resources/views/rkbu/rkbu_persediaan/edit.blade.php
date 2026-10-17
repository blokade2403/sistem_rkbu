@extends('layouts.main')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <form class="form form-submit" action="{{ route('rkbu.persediaan.update', $persediaan->id) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card mb-10">
                    <div class="card-header">
                        <div class="card-title fs-3 fw-bolder">Ubah RKBU Persediaan</div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold required">Nama Barang / Komponen</label>
                            </div>
                            <div class="col-xl-9">
                                <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                                    value="{{ old('nama_barang', $persediaan->nama_barang) }}" required>
                            </div>
                        </div>

                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Sisa Stok s/d Des {{ $angka_kurang_2 }}</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="number" id="sisa_stok_rkbu" name="stok" min="0" required
                                    class="form-control form-control-solid"
                                    value="{{ old('stok', $persediaan->stok) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Rata-rata Pemakaian {{ $angka_kurang_1 }}</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="number" id="rata_rata_pemakaian_rkbu" name="rata_rata_pemakaian"
                                    min="0" class="form-control form-control-solid"
                                    value="{{ old('rata_rata_pemakaian', $persediaan->rata_rata_pemakaian) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Pengadaan {{ $angka_kurang_1 }}</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="number" id="pengadaan_sebelumnya_rkbu" name="pengadaan_sebelumnya"
                                    min="0" class="form-control form-control-solid"
                                    value="{{ old('pengadaan_sebelumnya', $persediaan->pengadaan_sebelumnya) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Kebutuhan (Bulan)</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="number" id="kebutuhan_per_bulan_rkbu" name="kebutuhan_per_bulan"
                                    min="0" class="form-control form-control-solid"
                                    value="{{ old('kebutuhan_per_bulan', $persediaan->kebutuhan_per_bulan) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Buffer</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="number" id="buffer_rkbu" name="buffer" min="0"
                                    class="form-control form-control-solid"
                                    value="{{ old('buffer', $persediaan->buffer) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Satuan</label>
                            </div>
                            <div class="col-xl-6">
                                <select class="form-select" name="satuan_1" data-control="select2"
                                    data-placeholder="Pilih Satuan">
                                    <option value="">Pilih Satuan</option>
                                    @foreach ($uraian_satu as $item)
                                        <option value="{{ $item->nama_uraian_1 }}"
                                            {{ old('satuan_1', $persediaan->satuan_1) == $item->nama_uraian_1 ? 'selected' : '' }}>
                                            {{ $item->nama_uraian_1 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php
                            $derivedFields = [
                                [
                                    'id' => 'proyeksi_sisa_stok_rkbu',
                                    'name' => 'proyeksi_sisa_stok',
                                    'label' => "Prediksi Sisa Stok Tahun {$angka_tahun}",
                                    'value' => $persediaan->proyeksi_sisa_stok,
                                ],
                                [
                                    'id' => 'kebutuhan_tahun_x1_rkbu',
                                    'name' => 'kebutuhan_tahun_x1',
                                    'label' => "Perkiraan Kebutuhan Tahun {$angka_tahun}",
                                    'value' => $persediaan->kebutuhan_tahun_x1,
                                ],
                                [
                                    'id' => 'kebutuhan_plus_buffer_rkbu',
                                    'name' => 'kebutuhan_plus_buffer',
                                    'label' => "Kebutuhan Tahun {$angka_tahun} + Buffer",
                                    'value' => $persediaan->kebutuhan_plus_buffer,
                                ],
                                [
                                    'id' => 'rencana_pengadaan_tahun_x1_rkbu',
                                    'name' => 'rencana_pengadaan_tahun_x1',
                                    'label' => "Rencana Pengadaan Tahun {$angka_tahun}",
                                    'value' => $persediaan->rencana_pengadaan_tahun_x1,
                                ],
                            ];
                        @endphp
                        @foreach ($derivedFields as $field)
                            <div class="row mb-8">
                                <div class="col-xl-3">
                                    <label class="form-label fw-bold">{{ $field['label'] }}</label>
                                </div>
                                <div class="col-xl-6">
                                    <input id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                        value="{{ old($field['name'], $field['value']) }}"
                                        class="form-control form-control-solid" readonly>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Spesifikasi</label>
                            </div>
                            <div class="col-xl-9">
                                <textarea name="spesifikasi" id="spesifikasi" class="form-control form-control-solid"
                                    rows="5">{{ old('spesifikasi', $persediaan->spesifikasi) }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold required">Harga Satuan</label>
                            </div>
                            <div class="col-xl-3">
                                <input type="text" class="form-control form-control-solid" name="harga_satuan"
                                    id="harga_satuan" required
                                    value="{{ old('harga_satuan', number_format($persediaan->harga_satuan, 0, ',', '.')) }}">
                                <input type="hidden" id="harga_satuan_number_rkbu"
                                    value="{{ (int) $persediaan->harga_satuan }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">PPN</label>
                            </div>
                            <div class="col-xl-9">
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <input type="hidden" name="is_ppn" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_ppn" name="is_ppn"
                                        value="1"
                                        {{ old('is_ppn', $persediaan->ppn > 0) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-gray-400 ms-3">
                                        {{ $ppn->ppn }}%
                                    </label>
                                    <input type="hidden" name="ppn" id="ppn" value="{{ $ppn->ppn }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Total Anggaran</label>
                            </div>
                            <div class="col-xl-6">
                                <input id="total_persediaan_rkbu" name="total_anggaran"
                                    value="{{ old('total_anggaran', number_format($persediaan->total_anggaran, 0, ',', '.')) }}"
                                    class="form-control form-control-solid" readonly>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Penempatan</label>
                            </div>
                            <div class="col-xl-6">
                                <input type="text" class="form-control form-control-solid" name="penempatan"
                                    value="{{ old('penempatan', $persediaan->penempatan) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Link E-Katalog</label>
                            </div>
                            <div class="col-xl-9">
                                <input type="text" class="form-control form-control-solid" name="link_ekatalog"
                                    value="{{ old('link_ekatalog', $persediaan->link_ekatalog) }}">
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold">Skala Prioritas</label>
                            </div>
                            <div class="col-xl-9">
                                <div class="row g-5">
                                    @foreach ([1 => 'Usulan lebih diutamakan (wajib)', 2 => 'Usulan diutamakan', 3 => 'Usulan tidak diutamakan'] as $rating => $description)
                                        <div class="col-md-4">
                                            <label class="btn btn-outline btn-outline-dashed btn-outline-default d-flex text-start p-6
                                                {{ old('rating', $persediaan->rating ?? 1) == $rating ? 'active' : '' }}">
                                                <input class="form-check-input" type="radio" name="rating"
                                                    value="{{ $rating }}"
                                                    {{ old('rating', $persediaan->rating ?? 1) == $rating ? 'checked' : '' }}>
                                                <span class="ms-5">
                                                    <span class="fs-4 fw-bolder mb-1 d-block">Prioritas {{ $rating }}</span>
                                                    <span class="fw-bold fs-8 text-gray-600">{{ $description }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <label class="form-label fw-bold required">Sub Kategori RKBU</label>
                            </div>
                            <div class="col-xl-9">
                                <select class="form-select" name="sub_kategori_rkbu_id" data-control="select2"
                                    data-placeholder="Pilih Sub Kategori RKBU" required>
                                    <option value="">Pilih Sub Kategori RKBU</option>
                                    @foreach ($sub_kategori_rkbu as $item)
                                        <option value="{{ $item->id_sub_kategori_rkbu }}"
                                            {{ old('sub_kategori_rkbu_id', $persediaan->rkbu->sub_kategori_rkbu_id) == $item->id_sub_kategori_rkbu ? 'selected' : '' }}>
                                            {{ $item->kode_sub_kategori_rkbu }} - {{ $item->nama_sub_kategori_rkbu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if (auth()->user()->roles->contains('nama_role', 'Administrator'))
                            <div class="row mb-8">
                                <div class="col-xl-3">
                                    <label class="form-label fw-bold">Sub Kegiatan</label>
                                </div>
                                <div class="col-xl-9">
                                    <select id="sub_kegiatan" name="sub_kegiatan_id" class="form-select"
                                        data-control="select2" data-placeholder="Pilih Sub Kegiatan">
                                        <option value="">Pilih Sub Kegiatan</option>
                                        @foreach ($sub_kegiatans as $subKegiatan)
                                            <option value="{{ $subKegiatan->id_sub_kegiatan }}"
                                                {{ old('sub_kegiatan_id', $persediaan->rkbu->sub_kegiatan_id) == $subKegiatan->id_sub_kegiatan ? 'selected' : '' }}>
                                                {{ $subKegiatan->kode_sub_kegiatan }} - {{ $subKegiatan->nama_sub_kegiatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                            <div class="row w-100">
                                @foreach (range(1, 5) as $fileNumber)
                                    @php $fileField = "upload_file_{$fileNumber}"; @endphp
                                    <div class="col-xl-3 mb-3">
                                        <label class="form-label fw-bold mt-2">Upload KAP {{ $fileNumber }}</label>
                                    </div>
                                    <div class="col-xl-9 mb-3">
                                        @if ($persediaan->{$fileField})
                                            <div class="mb-2">
                                                <a href="{{ asset('storage/' . $persediaan->{$fileField}) }}"
                                                    target="_blank" rel="noopener" class="badge badge-light-primary">
                                                    Lihat File KAP {{ $fileNumber }}
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" name="{{ $fileField }}" class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <small class="text-muted">
                                        Format file: PDF, JPG, PNG, DOC, XLS; maksimal 5 MB per file.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6">
                        <a href="{{ route($routePrefix . '.index') }}"
                            class="btn btn-light btn-active-light-primary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
