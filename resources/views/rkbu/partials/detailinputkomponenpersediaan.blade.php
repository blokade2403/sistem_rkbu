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
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Sisa Stok s/d Des
                                 {{ $angka_kurang_2 }}</label>
                         </div>
                         <div class="col-lg-4">
                             <input type="number" value="{{ old('stok', $rkbuBarangJasa->stok ?? '') }}" id="sisa_stok"
                                 name="stok" onkeyup="total();" placeholder="1"
                                 aria-label="Amount (to the nearest dollar)" id="vol1" required
                                 class="form-control" placeholder="Isi Vol Kebutuhan">
                         </div>
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Prediksi Sisa Stok Tahun
                                 {{ $angka_tahun }}</label>
                         </div>
                         <div class="col-lg-4">
                             <div class="input-group">
                                 <input id="proyeksi_sisa_stok" name="proyeksi_sisa_stok" onkeyup="total();"
                                     value="{{ $rkbuBarangJasa->proyeksi_sisa_stok }}" class="form-control"
                                     placeholder="Prediksi Sisa Stok Tahun {{ $angka_tahun }}" readonly>
                                 <span class="input-group-text" id="basic-addon2"data-toggle="tooltip"
                                     data-placement="left" title=""
                                     data-original-title="Prediksi Sisa Stok s/d Desember <?php echo $angka_tahun; ?> = (Sisa Stok Desember <?php echo $angka_tahun - 2; ?> + Pengadaan Tahun <?php echo $angka_tahun - 1; ?>) - (Rata2 Pemakaian Per Bulan Selama <?php echo $angka_tahun - 1; ?> X Kebutuhan/Bulan)">#</span>
                             </div>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Rata-rata Pemakaian
                                 {{ $angka_kurang_1 }}</label>
                         </div>
                         <div class="col-lg-4">
                             <input type="number"
                                 value="{{ old('rata_rata_pemakaian', $rkbuBarangJasa->rata_rata_pemakaian ?? '') }}"
                                 id="rata_rata_pemakaian" name="rata_rata_pemakaian" onkeyup="total();"
                                 class="form-control" placeholder="Isi Vol Koefisien">
                         </div>
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Perkiraan Kebutuhan Tahun
                                 {{ $angka_tahun }}</label>
                         </div>
                         <div class="col-lg-4">
                             <div class="input-group">
                                 <input id="kebutuhan_tahun_x1" name="kebutuhan_tahun_x1" onkeyup="total();"
                                     value="{{ $rkbuBarangJasa->kebutuhan_tahun_x1 }}" class="form-control"
                                     placeholder="Perkiraan Kebutuhan Tahun {{ $angka_tahun }}" readonly>
                                 <span class="input-group-text" id="basic-addon2"data-toggle="tooltip"
                                     data-placement="left" title=""
                                     data-original-title="Kebutuhan Tahun <?php echo $angka_tahun; ?> = Rata2 Pemakaian Per Bulan Selama <?php echo $angka_tahun - 1; ?> X Kebutuhan/Bulan">#</span>
                             </div>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Pengadaan {{ $angka_kurang_1 }}</label>
                         </div>
                         <div class="col-lg-4">
                             <input type="number"
                                 value="{{ old('pengadaan_sebelumnya', $rkbuBarangJasa->pengadaan_sebelumnya ?? '') }}"
                                 id="pengadaan_sebelumnya" name="pengadaan_sebelumnya" onkeyup="total();"
                                 class="form-control" placeholder="Isi Vol Koefisien">
                         </div>
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Kebutuhan Tahun {{ $angka_tahun }} +
                                 Buffer</label>
                         </div>
                         <div class="col-lg-4">
                             <div class="input-group">
                                 <input id="kebutuhan_plus_buffer" name="kebutuhan_plus_buffer" onkeyup="total();"
                                     value="{{ $rkbuBarangJasa->kebutuhan_plus_buffer }}" class="form-control"
                                     placeholder="Kebutuhan Tahun {{ $angka_tahun }} + Buffer" readonly>
                                 <span class="input-group-text" id="basic-addon2"data-toggle="tooltip"
                                     data-placement="left" title=""
                                     data-original-title="Kebutuhan Tahun <?php echo $angka_tahun; ?> + Buffer = Perkiraan Kebutuhan Tahun <?php echo $angka_tahun; ?> + (Rata2 Pemakaian Per Bulan Selama <?php echo $angka_tahun - 1; ?> X Buffer Bulan)">#</span>
                             </div>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Kebutuhan (Bulan)</label>
                         </div>
                         <div class="col-lg-4">
                             <input type="number"
                                 value="{{ old('kebutuhan_per_bulan', $rkbuBarangJasa->kebutuhan_per_bulan ?? '') }}"
                                 id="kebutuhan_per_bulan" name="kebutuhan_per_bulan" onkeyup="total();"
                                 class="form-control" placeholder="Isi Vol Koefisien">
                         </div>
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Rencana Pengadaan Tahun
                                 {{ $angka_tahun }}</label>
                         </div>
                         <div class="col-lg-4">
                             <div class="input-group">
                                 <input id="rencana_pengadaan_tahun_x1" name="rencana_pengadaan_tahun_x1"
                                     onkeyup="total();" value="{{ $rkbuBarangJasa->rencana_pengadaan_tahun_x1 }}"
                                     class="form-control" placeholder="Rencana Pengadaan" readonly>
                                 <span class="input-group-text" id="basic-addon2"data-toggle="tooltip"
                                     data-placement="left" title=""
                                     data-original-title="Rencana Pengadaan Tahun <?php echo $angka_tahun; ?> = Kebutuhan Tahun <?php echo $angka_tahun; ?> + Buffer - Prediksi Sisa Stok s/d Desember <?php echo $angka_tahun; ?>">#</span>
                             </div>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Buffer</label>
                         </div>
                         <div class="col-lg-4">
                             <input type="number" value="{{ old('buffer', $rkbuBarangJasa->buffer ?? '') }}"
                                 id="buffer" name="buffer" onkeyup="total();" class="form-control"
                                 placeholder="Isi Vol Koefisien">
                         </div>

                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Satuan</label>
                         </div>
                         <div class="col-lg-10">
                             @php
                                 $selected = old('satuan_1', optional($rkbuBarangJasa)->satuan_1 ?? '');
                             @endphp
                             <select id="form-control choices-select" name="satuan_1" class="form-select"
                                 data-choices data-choices-sorting="true">
                                 <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>
                                     Pilih Satuan
                                 </option>
                                 @foreach ($uraian_satu as $item)
                                     <option value="{{ $item->nama_uraian_1 }}"
                                         {{ (string) $selected === (string) $item->nama_uraian_1 ? 'selected' : '' }}>
                                         {{ $item->nama_uraian_1 }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="websiteUrl" class="form-label">Spesifikasi</label>
                         </div>
                         <div class="col-lg-10">
                             <textarea name="spek" id="spek" rows="5" cols="5" class="form-control"
                                 placeholder="Spesifikasi Komponen">{{ old('spek', $rkbuBarangJasa->spek ?? '') }}</textarea>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="dateInput" class="form-label">Harga Satuan</label>
                         </div>
                         <div class="col-lg-10">
                             <input id="harga_satuan" name="harga_satuan" onkeyup="total();"
                                 value="{{ old('harga_satuan', $rkbuBarangJasa->harga_satuan ?? '') }}"
                                 class="form-control" placeholder="Harga Satuan">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="timeInput" class="form-label">PPN</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="number" step="0.01" min="0" max="100"
                                 class="form-control" id="persen"
                                 value="{{ old('ppn', $rkbuBarangJasa->ppn ?? '') }}" name="ppn"
                                 onkeyup="total();" placeholder="%">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="leaveemails" class="form-label">Total Anggaran</label>
                         </div>
                         <div class="col-lg-10">
                             <input name="total_anggaran" value="{{ $rkbuBarangJasa->total_anggaran }}"
                                 id="total_persediaan" class="form-control" placeholder="1,000,000" readonly>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Link Ekatalog</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="text" value="{{ old('link_ekatalog', $rkbuBarangJasa->link_ekatalog) }}"
                                 name="link_ekatalog" class="form-control" placeholder="http:\\ecatalog\12345.go.id">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="contactNumber" class="form-label">Penempatan</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="text" name="penempatan"
                                 value="{{ old('penempatan', $rkbuBarangJasa->penempatan) }}" class="form-control"
                                 placeholder="Penempatan Barang">
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
         <div class="card">
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
                 <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code> class to
                     form groups and using the <code>col-*-*</code> class to specify the width of your labels and
                     controls. Be sure to add <code>col-form-label</code> class to your <code>&lt;label&gt;</code>s as
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
                                     <a href="{{ asset('storage/' . $rkbuBarangJasa->upload_file_1) }}"
                                         target="_blank" class="label label-inverse"> Lihat Dokumen KAP
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
                                     <a href="{{ asset('storage/' . $rkbuBarangJasa->upload_file_2) }}"
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
                                     <a href="{{ asset('storage/' . $rkbuBarangJasa->upload_file_3) }}"
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
                                     <a href="{{ asset('storage/' . $rkbuBarangJasa->upload_file_4) }}"
                                         target="_blank" class="label label-inverse"> Lihat SPH 3
                                     </a>
                                 </div>
                             @endif
                         </div>
                     </div>
                     @include('partials.submit_buttons')
                 </div>
                 <div class="d-none code-view">
                 </div>
             </div>
         </div>
