         <!-- Page body start -->
         <div class="page-body">
             <div class="row">
                 <div class="col-sm-12">
                     <!-- Basic Form Inputs card start -->
                     <div class="card">
                         <div class="card-block">
                             <h4 class="sub-title">Detail Pegawai</h4>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                                 <div class="col-sm-10">
                                     <input type="text"
                                         value="{{ old('tempat_lahir', $rkbuBarangJasa->tempat_lahir ?? '') }}"
                                         id="tempat_lahir" name="tempat_lahir" class="form-control"
                                         placeholder="Isi Tempat Lahir Pegawai">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                 <div class="col-sm-10">
                                     <input type="date" id="tanggal_lahir"
                                         value="{{ old('tanggal_lahir', $rkbuBarangJasa->tanggal_lahir ?? '') }}"
                                         name="tanggal_lahir" class="form-control" placeholder="Isi Vol Koefisien">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Jabatan</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="jabatan"
                                         value="{{ old('jabatan', $rkbuBarangJasa->jabatan ?? '') }}" name="jabatan"
                                         class="form-control" placeholder="Isi Jabatan">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Pendidikan</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="pendidikan"
                                         value="{{ old('pendidikan', $rkbuBarangJasa->pendidikan ?? '') }}"
                                         name="pendidikan" class="form-control" placeholder="Isi pendidikan">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Status</label>
                                 <div class="col-sm-10">
                                     <select name="status_kawin" class="form-control js-example-basic-single">
                                         <option value="">Pilih Status Pegawai</option>
                                         <option value="K0 Tidak Menikah">K0 Tidak Menikah</option>
                                         <option value="K1 Menikah">K1 Menikah</option>
                                         <option value="K2 Menikah 1 Anak">K2 Menikah 1 Anak</option>
                                         <option value="K3 Menikah 2 Anak">K3 Menikah 2 Anak</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Nomor Kontrak</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="nomor_kontrak"
                                         value="{{ old('nomor_kontrak', $rkbuBarangJasa->nomor_kontrak ?? '') }}"
                                         name="nomor_kontrak" class="form-control" placeholder="Isi Nomor Kontrak">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">TMT Pegawai</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="tmt_pegawai"
                                         value="{{ old('tmt_pegawai', $rkbuBarangJasa->tmt_pegawai ?? '') }}"
                                         name="tmt_pegawai" class="form-control" placeholder="Isi TMT Pegawai">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Tahun TMT</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="tahun_tmt"
                                         value="{{ old('tahun_tmt', $rkbuBarangJasa->tahun_tmt ?? '') }}"
                                         name="tahun_tmt" class="form-control" placeholder="Isi Tahun TMT">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Bulan TMT</label>
                                 <div class="col-sm-10">
                                     <input type="text" id="bulan_tmt"
                                         value="{{ old('bulan_tmt', $rkbuBarangJasa->bulan_tmt ?? '') }}"
                                         name="bulan_tmt" class="form-control" placeholder="Isi Bulan TMT">
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label class="col-sm-4 col-form-label">Besaran Gaji Pokok</label>
                                         <div class="col-sm-8">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2"><small> Rp.
                                                     </small></span>
                                                 <input id="gaji_pokok"
                                                     value="{{ old('gaji_pokok', $rkbuBarangJasa->gaji_pokok ?? '') }}"
                                                     name="gaji_pokok" onkeyup="tot_remunerasi();" class="form-control"
                                                     placeholder="Harga Satuan">
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <div class="col-sm-6">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2"><small> x bulan
                                                     </small></span>
                                                 <input type="number" step="0.01" min="0" max="100"
                                                     class="form-control" id="koefisien_gaji"
                                                     value="{{ old('koefisien_gaji', $rkbuBarangJasa->koefisien_gaji ?? '') }}"
                                                     name="koefisien_gaji" onkeyup="tot_remunerasi();"
                                                     placeholder="12">
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Total Anggaran Gaji</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2"><small> Rp.
                                             </small></span>
                                         <input name="total_gaji_pokok" id="tot_gaji_pokok"
                                             value="{{ $rkbuBarangJasa->total_gaji_pokok }}"
                                             onkeyup="tot_remunerasi();" class="form-control" placeholder="1,000,000"
                                             readonly="">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">BPJS Kesehatan</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2">%</span>
                                         <input type="number" step="0.01" min="0" max="10"
                                             name="bpjs_kesehatan" id="bpjs_kesehatan"
                                             value="{{ $rkbuBarangJasa->bpjs_kesehatan }}"
                                             onkeyup="tot_remunerasi();" class="form-control"
                                             placeholder="1,000,000">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">BPJS TK</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2">%</span>
                                         <input type="number" step="0.01" min="0" max="10"
                                             name="bpjs_tk" id="bpjs_tk" value="{{ $rkbuBarangJasa->bpjs_tk }}"
                                             onkeyup="tot_remunerasi();" class="form-control" placeholder="3%">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">BPJS JHT</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2">%</span>
                                         <input type="number" step="0.01" min="0" max="10"
                                             name="bpjs_jht" id="bpjs_jht" value="{{ $rkbuBarangJasa->bpjs_jht }}"
                                             onkeyup="tot_remunerasi();" class="form-control"
                                             placeholder="1,000,000">
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label class="col-sm-4 col-form-label">Besaran Remunerasi</label>
                                         <div class="col-sm-8">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2"><small> Rp.
                                                     </small></span>
                                                 <input id="remunerasi"
                                                     value="{{ old('remunerasi', $rkbuBarangJasa->remunerasi ?? '') }}"
                                                     name="remunerasi" onkeyup="tot_remunerasi();"
                                                     class="form-control" placeholder="Harga Satuan">
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <div class="col-sm-6">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2"><small> x bulan
                                                     </small></span>
                                                 <input type="number" step="0.01" min="0" max="100"
                                                     class="form-control" id="koefisien_remunerasi"
                                                     value="{{ old('koefisien_remunerasi', $rkbuBarangJasa->koefisien_remunerasi ?? '') }}"
                                                     name="koefisien_remunerasi" onkeyup="tot_remunerasi();"
                                                     placeholder="12">
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Total Anggaran Remunerasi</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2"><small> Rp.
                                             </small></span>
                                         <input name="total_remunerasi" id="remun"
                                             value="{{ $rkbuBarangJasa->total_remunerasi }}"
                                             onkeyup="tot_remunerasi();" class="form-control" placeholder="1,000,000"
                                             readonly="">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Total Penghasilan</label>
                                 <div class="col-sm-10">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2"><small> Rp.
                                             </small></span>
                                         <input name="total_anggaran" id="total_pegawai"
                                             value="{{ $rkbuBarangJasa->total_anggaran }}" class="form-control"
                                             placeholder="1,000,000" readonly="">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Skala Prioritas</label>
                                 <div class="col-sm-10">
                                     <div class="br-wrapper br-theme-bars-movie"><select id="example-movie"
                                             name="rating" autocomplete="off" style="display: none;">
                                             <option value="Prioritas 3"
                                                 {{ old('rating', $rkbuBarangJasa->rating ?? '') == 'Prioritas 3' ? 'selected' : '' }}>
                                                 Prioritas 3</option>
                                             <option value="Prioritas 2"
                                                 {{ old('rating', $rkbuBarangJasa->rating ?? '') == 'Prioritas 2' ? 'selected' : '' }}>
                                                 Prioritas 2</option>
                                             <option value="Prioritas 1"
                                                 {{ old('rating', $rkbuBarangJasa->rating ?? '') == 'Prioritas 1' ? 'selected' : '' }}>
                                                 Prioritas 1</option>
                                         </select>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Kategori RKBU</label>
                                 <div class="col-sm-10">
                                     <select id="subKategoriSelect" name="id_sub_kategori_rkbu"
                                         class="form-control js-example-basic-single">
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

                                     <input type="hidden" id="id_sub_kategori_rekening"
                                         name="id_sub_kategori_rekening"
                                         value="{{ old('id_sub_kategori_rekening', $rkbuBarangJasa->id_sub_kategori_rekening) }}"
                                         class="form-control" placeholder="Jenis Belanja">
                                     <input type="hidden" id="id_kode_rekening_belanja"
                                         name="id_kode_rekening_belanja" value="9cf603bb-bfd0-4b1e-8a24-7339459d9507"
                                         class="form-control" placeholder="ID Kategori RKBU" readonly>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label"></label>
                                 <div class="col-sm-10">
                                     <span id="nama_kategori_rekening"></span>
                                     <p><span id="nama_sub_kategori_rekening"></span></p>
                                 </div>
                             </div>
                         </div>
                     </div>

                     @include('rkbu.partials.formvalidasirkbu')

                     <div class="card">
                         <div class="card-block">
                             <h4 class="sub-title">Upload Data Penunjang</h4>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Upload Dokumen KAP</label>
                                 <div class="col-sm-7">
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
                         </div>
                         <div class="card-block card-header-right">
                             <a href="{{ route($routePrefix . '.index') }}" class="btn btn-sm btn-mat btn-inverse">
                                 <i class="icofont icofont-ui-reply"></i> Kembali
                             </a>
                             @if ($faseTahun === 'Penetapan')
                                 <button type="button" class="btn btn-sm btn-mat btn-primary" data-toggle="modal"
                                     data-target="#large-Modal"><i class="icofont icofont-ui-check"></i>
                                     Submit</button>
                             @else
                                 <button type="submit" class="btn btn-sm btn-mat btn-primary">
                                     <i class="icofont icofont-ui-check"></i> Submit
                                 </button>
                             @endif
                         </div>
                     </div>
                     <!-- Basic Form Inputs card end -->
                 </div>
             </div>
         </div>
         <!-- Page body end -->
