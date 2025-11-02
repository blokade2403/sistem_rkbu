         <div class="card">
             <div class="card-header align-items-center d-flex">
                 <h4 class="card-title mb-0 flex-grow-1">Detail Pegawai</h4>
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
                             <label for="nameInput" class="form-label">Tempat/Tgl Lahir</label>
                         </div>
                         <div class="col-lg-5">
                             <input type="text"
                                 value="{{ old('tempat_lahir', $rkbuBarangJasa->tempat_lahir ?? '') }}"
                                 name="tempat_lahir" class="form-control" placeholder="tempat lahir pegawai">
                         </div>
                         <div class="col-lg-2">
                             <input type="date"
                                 value="{{ old('tanggal_lahir', $rkbuBarangJasa->tanggal_lahir ?? '') }}"
                                 name="tanggal_lahir" class="form-control" placeholder="tanggal lahir pegawai">
                         </div>
                         <div class="col-lg-3">
                             @php
                                 $selected = old('status_kawin', optional($rkbuBarangJasa)->status_kawin ?? '');
                             @endphp
                             <select id="form-control choices-select" name="status_kawin" class="form-select"
                                 data-choices data-choices-sorting="true">
                                 <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>
                                     Pilih Status Kawin
                                 </option>
                                 <option value="K0 Tidak Menikah">K0 Tidak Menikah</option>
                                 <option value="K1 Menikah">K1 Menikah</option>
                                 <option value="K2 Menikah 1 Anak">K2 Menikah 1 Anak</option>
                                 <option value="K3 Menikah 2 Anak">K3 Menikah 2 Anak</option>
                             </select>
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Jabatan/Pendidikan</label>
                         </div>
                         <div class="col-lg-5">
                             <input type="text" id="vol2"
                                 value="{{ old('jabatan', $rkbuBarangJasa->jabatan ?? '') }}" name="jabatan"
                                 class="form-control" placeholder="Isi jabatan pegawai">
                         </div>
                         <div class="col-lg-5">
                             <input type="text" id="pendidikan"
                                 value="{{ old('pendidikan', $rkbuBarangJasa->pendidikan ?? '') }}" name="pendidikan"
                                 class="form-control" placeholder="Pendidikan terakhir pegawai">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">No Kontrak</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="text"
                                 value="{{ old('nomor_kontrak', $rkbuBarangJasa->nomor_kontrak ?? '') }}"
                                 name="nomor_kontrak" class="form-control" placeholder="nomor kontrak pegawai">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">TMT Pegawai</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="date" value="{{ old('tmt_pegawai', $rkbuBarangJasa->tmt_pegawai ?? '') }}"
                                 name="tmt_pegawai" class="form-control" placeholder="tmt pegawai">
                         </div>
                     </div>
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="nameInput" class="form-label">Tahun/Bulan TMT Pegawai</label>
                         </div>
                         <div class="col-lg-5">
                             <input type="text" value="{{ old('tahun_tmt', $rkbuBarangJasa->tahun_tmt ?? '') }}"
                                 name="tahun_tmt" class="form-control" placeholder="tahun tmt pegawai">
                         </div>
                         <div class="col-lg-5">
                             <input type="text" value="{{ old('bulan_tmt', $rkbuBarangJasa->bulan_tmt ?? '') }}"
                                 name="bulan_tmt" class="form-control" placeholder="bulan tmt pegawai">
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
                 <h4 class="card-title mb-0 flex-grow-1">Detail Penghasilan</h4>
                 <div class="flex-shrink-0">
                     <div class="form-check form-switch form-switch-right form-switch-md">
                         <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                         <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                     </div>
                 </div>
             </div><!-- end card header -->
             <div class="card-body">
                 <div class="live-preview">
                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="gaji_pokok" class="form-label">Harga Gaji Pokok</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="text" id="gaji_pokok" name="gaji_pokok"
                                 value="{{ number_format(old('gaji_pokok', $rkbuBarangJasa->gaji_pokok ?? 0), 0, ',', '.') }}"
                                 onkeyup="tot_remunerasi();" class="form-control bg-lighter" />
                         </div>
                     </div>

                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="koefisien_gaji" class="form-label">Koefisien Gaji</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="number" id="koefisien_gaji" name="koefisien_gaji"
                                 value="{{ old('koefisien_gaji', $rkbuBarangJasa->koefisien_gaji ?? 0) }}"
                                 onkeyup="tot_remunerasi();" class="form-control bg-lighter" />
                         </div>
                     </div>

                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="bpjs_kesehatan" class="form-label">BPJS Kesehatan (%)</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="number" id="bpjs_kesehatan" name="bpjs_kesehatan"
                                 value="{{ old('bpjs_kesehatan', $rkbuBarangJasa->bpjs_kesehatan ?? 0) }}"
                                 onkeyup="tot_remunerasi();" class="form-control" />
                         </div>
                     </div>

                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="bpjs_tk" class="form-label">BPJS TK (%)</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="number" id="bpjs_tk" name="bpjs_tk"
                                 value="{{ old('bpjs_tk', $rkbuBarangJasa->bpjs_tk ?? 0) }}"
                                 onkeyup="tot_remunerasi();" class="form-control" />
                         </div>
                     </div>

                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="bpjs_jht" class="form-label">BPJS JHT (%)</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="number" id="bpjs_jht" name="bpjs_jht"
                                 value="{{ old('bpjs_jht', $rkbuBarangJasa->bpjs_jht ?? 0) }}"
                                 onkeyup="tot_remunerasi();" class="form-control" />
                         </div>
                     </div>

                     <div class="row mb-3">
                         <div class="col-lg-2">
                             <label for="remunerasi" class="form-label">Harga Remunerasi</label>
                         </div>
                         <div class="col-lg-10">
                             <input type="text" id="remunerasi" name="remunerasi"
                                 value="{{ number_format(old('remunerasi', $rkbuBarangJasa->remunerasi ?? 0), 0, ',', '.') }}"
                                 onkeyup="tot_remunerasi();" class="form-control bg-lighter" />
                         </div>
                     </div>
                 </div>

                 <div class="row mb-3">
                     <div class="col-lg-2">
                         <label for="koefisien_remunerasi" class="form-label">Koefisien Remunerasi</label>
                     </div>
                     <div class="col-lg-10">
                         <input type="number" id="koefisien_remunerasi" name="koefisien_remunerasi"
                             value="{{ old('koefisien_remunerasi', $rkbuBarangJasa->koefisien_remunerasi ?? 0) }}"
                             onkeyup="tot_remunerasi();" class="form-control bg-lighter" />
                     </div>
                 </div>

                 <div class="row mb-3">
                     <div class="col-lg-2">
                         <label for="tot_gaji_pokok" class="form-label">Total Gaji Pokok</label>
                     </div>
                     <div class="col-lg-10">
                         <input type="text" id="tot_gaji_pokok" name="total_gaji_pokok"
                             value="{{ number_format(old('total_gaji_pokok', $rkbuBarangJasa->total_gaji_pokok ?? 0), 0, ',', '.') }}"
                             class="form-control bg-lighter" readonly />
                     </div>
                 </div>

                 <div class="row mb-3">
                     <div class="col-lg-2">
                         <label for="remun" class="form-label">Total Remunerasi</label>
                     </div>
                     <div class="col-lg-10">
                         <input type="text" id="remun" name="total_remunerasi"
                             value="{{ number_format(old('total_remunerasi', $rkbuBarangJasa->total_remunerasi ?? 0), 0, ',', '.') }}"
                             class="form-control bg-lighter" readonly />
                     </div>
                 </div>

                 <div class="row mb-3">
                     <div class="col-lg-2">
                         <label for="total_pegawai" class="form-label">Total Penghasilan</label>
                     </div>
                     <div class="col-lg-10">
                         <input type="text" id="total_pegawai" name="total_anggaran"
                             value="{{ number_format(old('total_anggaran', $rkbuBarangJasa->total_anggaran ?? 0), 0, ',', '.') }}"
                             class="form-control bg-lighter" readonly />
                     </div>
                 </div>

                 @include('partials.submit_buttons')
             </div>

             <div class="d-none code-view">
             </div>
         </div>
         </div>
