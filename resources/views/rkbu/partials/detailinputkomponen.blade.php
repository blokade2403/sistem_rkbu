         <div class="card">
             <div class="card-header align-items-center d-flex">
                 <h4 class="card-title mb-0 flex-grow-1">Detail Komponen</h4>
                 <div class="flex-shrink-0">
                     <div class="form-check form-switch form-switch-right form-switch-md">
                         <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                         <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                     </div>
                 </div>
             </div><!-- end card header -->
             <div class="card-body">
                 <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
                     form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                     controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
                     well so they’re vertically centered with their associated form controls.</p>
                 <div class="live-preview">
                     <div class="row mb-3">
                         <!-- ==================== HTML ==================== -->
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="vol1" class="form-label">Volume</label>
                             </div>
                             <div class="col-lg-5">
                                 <input type="number" id="vol1"
                                     value="{{ old('vol_1', $rkbuBarangJasa->vol_1 ?? '') }}" name="vol_1"
                                     class="form-control" placeholder="Isi Vol Kebutuhan">
                             </div>
                             <div class="col-lg-5">
                                 <!-- select tetap seperti semula -->
                                 <select id="satuan_1" name="satuan_1" class="form-select" data-choices
                                     data-choices-sorting="true">
                                     <option value="" disabled
                                         {{ old('satuan_1', optional($rkbuBarangJasa)->satuan_1 ?? '') === '' ? 'selected' : '' }}>
                                         Pilih Satuan
                                     </option>
                                     @foreach ($uraian_satu as $item)
                                         <option value="{{ $item->nama_uraian_1 }}"
                                             {{ (string) old('satuan_1', optional($rkbuBarangJasa)->satuan_1 ?? '') === (string) $item->nama_uraian_1 ? 'selected' : '' }}>
                                             {{ $item->nama_uraian_1 }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="vol2" class="form-label">Koefisien</label>
                             </div>
                             <div class="col-lg-5">
                                 <input type="number" id="vol2"
                                     value="{{ old('vol_2', $rkbuBarangJasa->vol_2 ?? '') }}" name="vol_2"
                                     class="form-control" placeholder="Isi Vol Koefisien">
                             </div>
                             <div class="col-lg-5">
                                 <select id="satuan_2" name="satuan_2" class="form-select" data-choices
                                     data-choices-sorting="true">
                                     <option value="" disabled
                                         {{ old('satuan_2', optional($rkbuBarangJasa)->satuan_2 ?? '') === '' ? 'selected' : '' }}>
                                         Pilih Satuan
                                     </option>
                                     @foreach ($uraian_dua as $item)
                                         <option value="{{ $item->nama_uraian_2 }}"
                                             {{ (string) old('satuan_2', optional($rkbuBarangJasa)->satuan_2 ?? '') === (string) $item->nama_uraian_2 ? 'selected' : '' }}>
                                             {{ $item->nama_uraian_2 }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="spek" class="form-label">Spesifikasi</label>
                             </div>
                             <div class="col-lg-10">
                                 <textarea name="spek" id="spek" rows="5" cols="5" class="form-control"
                                     placeholder="Spesifikasi Komponen">{{ old('spek', $rkbuBarangJasa->spek ?? '') }}</textarea>
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="harga_barang" class="form-label">Harga Satuan</label>
                             </div>
                             <div class="col-lg-10">
                                 <!-- gunakan type="text" supaya bisa berisi format ribuan -->
                                 <input type="text" id="harga_barang"
                                     value="{{ old('harga_satuan', $rkbuBarangJasa->harga_satuan ?? '') }}"
                                     name="harga_satuan" class="form-control" placeholder="Harga Satuan">
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="persen" class="form-label">PPN</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="number" step="0.01" min="0" max="100" id="persen"
                                     value="{{ old('ppn', $rkbuBarangJasa->ppn ?? '') }}" name="ppn"
                                     class="form-control" placeholder="%">
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="hasil" class="form-label">Total Anggaran</label>
                             </div>
                             <div class="col-lg-10">
                                 <input id="hasil" name="total_anggaran" class="form-control" readonly
                                     value="{{ number_format($rkbuBarangJasa->total_anggaran ?? 0, 0, ',', '.') }}">
                             </div>
                         </div>

                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="contactNumber" class="form-label">Link Ekatalog</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text"
                                     value="{{ old('link_ekatalog', $rkbuBarangJasa->link_ekatalog) }}"
                                     name="link_ekatalog" class="form-control"
                                     placeholder="http:\\ecatalog\12345.go.id">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="contactNumber" class="form-label">Penempatan</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text" name="penempatan"
                                     value="{{ old('penempatan', $rkbuBarangJasa->penempatan) }}"
                                     class="form-control" placeholder="Penempatan Barang">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="meassageInput" class="form-label">Skala Prioritas</label>
                             </div>
                             <div class="col-lg-10">
                                 <select name="rating" class="form-select" data-choices data-choices-sorting="true">
                                     <option value="" disabled
                                         {{ old('rating', $rkbuBarangJasa->rating) ? '' : 'selected' }}>
                                         Pilih Prioritas Usulan
                                     </option>
                                     <option value="Prioritas 1"
                                         {{ old('rating', $rkbuBarangJasa->rating) == 'Prioritas 1' ? 'selected' : '' }}>
                                         Prioritas 1
                                     </option>
                                     <option value="Prioritas 2"
                                         {{ old('rating', $rkbuBarangJasa->rating) == 'Prioritas 2' ? 'selected' : '' }}>
                                         Prioritas 2
                                     </option>
                                     <option value="Prioritas 3"
                                         {{ old('rating', $rkbuBarangJasa->rating) == 'Prioritas 3' ? 'selected' : '' }}>
                                         Prioritas 3
                                     </option>
                                 </select>
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="meassageInput" class="form-label">Kategori RKBU</label>
                             </div>
                             <div class="col-lg-10">
                                 <select id="subKategoriSelect" name="id_sub_kategori_rkbu" class="form-select"
                                     data-choices data-choices-sorting="true">
                                     @php
                                         $selectedId = old(
                                             'id_sub_kategori_rkbu',
                                             optional($rkbuBarangJasa)->id_sub_kategori_rkbu,
                                         );
                                     @endphp

                                     <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                                         Pilih Kategori RKBU
                                     </option>

                                     @foreach ($sub_kategori_rkbus as $sub)
                                         <option value="{{ $sub->id_sub_kategori_rkbu }}"
                                             {{ (string) $selectedId === (string) $sub->id_sub_kategori_rkbu ? 'selected' : '' }}>
                                             {{ $sub->kode_sub_kategori_rkbu }}.
                                             {{ $sub->nama_sub_kategori_rkbu }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <input type="hidden" id="id_sub_kategori_rekening" name="id_sub_kategori_rekening"
                                     value="{{ old('id_sub_kategori_rekening', $rkbuBarangJasa->id_sub_kategori_rekening) }}"
                                     class="form-control" placeholder="Jenis Belanja">
                                 <input type="hidden" id="id_kode_rekening_belanja" name="id_kode_rekening_belanja"
                                     value="9cf603bb-bfd0-4b1e-8a24-7339459d9507" class="form-control"
                                     placeholder="ID Kategori RKBU" readonly>
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="contactNumber" class="form-label"></label>
                             </div>
                             <div class="col-lg-10">
                                 <span id="nama_kategori_rekening"></span>
                                 <p><span id="nama_sub_kategori_rekening"></span></p>
                             </div>
                         </div>
                         @if (session('nama_level_user') == 'Administrator')
                             @include('rkbu.partials.form_rekening_belanja_admin')
                         @endif
                     </div>
                     <div class="d-none code-view">
                     </div>
                 </div>
             </div>
             @include('rkbu.partials.formvalidasirkbu')
             <div class="card-header align-items-center d-flex">
                 <h4 class="card-title mb-0 flex-grow-1">Upload Data Penunjang</h4>
                 <div class="flex-shrink-0">
                     <div class="form-check form-switch form-switch-right form-switch-md">
                         <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                         <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                     </div>
                 </div>
             </div><!-- end card header -->
             <div class="card-body">
                 <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class
                     to
                     form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                     controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s
                     as
                     well so they’re vertically centered with their associated form controls.</p>
                 <div class="live-preview">
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Upload Dokumen KAP</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="file" name="upload_file_1" id="formFileMultiple" multiple
                                 class="form-control">

                             {{-- kalau ada file tersimpan (hanya di edit) --}}
                             @if (isset($rkbuBarangJasa) && !empty($rkbuBarangJasa->upload_file_1))
                                 <div class="mt-2">
                                     <a href="{{ asset('storage/uploads/' . basename($rkbuBarangJasa->upload_file_1)) }}"
                                         target="_blank" class="label label-inverse"><span
                                             class="fs-10 badge bg-info">Lihat Dokumen KAP</span>
                                     </a>
                                 </div>
                             @endif
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Upload SPH 1</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="file" name="upload_file_2" id="formFile" class="form-control">
                             @if (isset($rkbuBarangJasa) && !empty($rkbuBarangJasa->upload_file_2))
                                 <div class="mt-2">
                                     <a href="{{ asset('storage/uploads/' . basename($rkbuBarangJasa->upload_file_2)) }}"
                                         target="_blank" class="label label-inverse"> Lihat SPH 1
                                     </a>
                                 </div>
                             @endif
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Upload SPH 2</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="file" name="upload_file_3" id="formFileMultiple" multiple
                                 class="form-control">
                             @if (isset($rkbuBarangJasa) && !empty($rkbuBarangJasa->upload_file_3))
                                 <div class="mt-2">
                                     <a href="{{ asset('storage/uploads/' . basename($rkbuBarangJasa->upload_file_3)) }}"
                                         target="_blank" class="label label-inverse"> Lihat SPH 2
                                     </a>
                                 </div>
                             @endif
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Upload SPH 3</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="file" name="upload_file_4" id="formFileMultiple" class="form-control">
                             @if (isset($rkbuBarangJasa) && !empty($rkbuBarangJasa->upload_file_4))
                                 <div class="mt-2">
                                     <a href="{{ asset('storage/uploads/' . basename($rkbuBarangJasa->upload_file_4)) }}"
                                         target="_blank" class="label label-inverse"> Lihat SPH 3
                                     </a>
                                 </div>
                             @endif
                         </div>
                     </div>
                     @include('partials.submit_buttons_rkbu')
                 </div>
                 <div class="d-none code-view">
                 </div>
             </div>
