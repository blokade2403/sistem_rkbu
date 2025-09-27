         <!-- Page body start -->
         <div class="page-body">
             <div class="row">
                 <div class="col-sm-12">
                     <!-- Basic Form Inputs card start -->
                     <div class="card">
                         <div class="card-block">
                             <h4 class="sub-title">Detail Komponen</h4>
                             <div class="row">
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Volume</label>
                                         <div class="col-sm-6">
                                             <input type="text" onkeyup="sum_modal();" name="vol_1_detail"
                                                 id="vol_1" value="{{ $rkbuBarangJasa->vol_1_detail }}"
                                                 class="form-control" placeholder="John Doe" />
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Koefisien</label>
                                         <div class="col-sm-6">
                                             <input type="text" onkeyup="sum_modal();" name="vol_2_detail"
                                                 id="vol_2" value="{{ $rkbuBarangJasa->vol_2_detail }}"
                                                 class="form-control" placeholder="John Doe" />
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <div class="col-sm-6">
                                             @php
                                                 $selected = old(
                                                     'satuan_1_detail',
                                                     optional($rkbuBarangJasa)->satuan_1_detail ?? '',
                                                 );
                                             @endphp

                                             <select name="satuan_1_detail" id="satuan_1"
                                                 class="form-control js-example-basic-single">
                                                 <option value="" disabled
                                                     {{ $selected === '' ? 'selected' : '' }}>
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
                                     <div class="form-group row">
                                         <div class="col-sm-6">
                                             @php
                                                 $selected = old(
                                                     'satuan_2_detail',
                                                     optional($rkbuBarangJasa)->satuan_2_detail ?? '',
                                                 );
                                             @endphp

                                             <select name="satuan_2_detail" id="satuan_2"
                                                 class="form-control js-example-basic-single">
                                                 <option value="" disabled
                                                     {{ $selected === '' ? 'selected' : '' }}>
                                                     Pilih Satuan Koefisien
                                                 </option>

                                                 @foreach ($uraian_dua as $item)
                                                     <option value="{{ $item->nama_uraian_2 }}"
                                                         {{ (string) $selected === (string) $item->nama_uraian_2 ? 'selected' : '' }}>
                                                         {{ $item->nama_uraian_2 }}
                                                     </option>
                                                 @endforeach
                                             </select>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Spesifikasi</label>
                                 <div class="col-sm-10">
                                     <textarea name="spek_detail" id="spek" rows="5" cols="5" class="form-control"
                                         placeholder="Spesifikasi Komponen">{{ old('spek_detail', $rkbuBarangJasa->spek_detail ?? '') }}</textarea>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="form-group row">
                                         <label class="col-sm-4 col-form-label">Harga Satuan</label>
                                         <div class="col-sm-8">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2">Rp.</span>
                                                 <input id="harga_barang" name="harga_barang" onkeyup="sum_modal();"
                                                     value="{{ $rkbuBarangJasa->harga_barang }}"
                                                     class="form-control bg-lighter" id="basic-addon21"
                                                     aria-label="Username" aria-describedby="basic-addon21" />
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <div class="col-sm-8">
                                             <div class="input-group">
                                                 <span class="input-group-addon" id="basic-addon2">PPN</span>
                                                 <input type="number" step="0.01" min="0" max="100"
                                                     class="form-control" onkeyup="sum_modal();" name="ppn"
                                                     id="ppn" value="{{ $rkbuBarangJasa->ppn }}"
                                                     aria-label="Amount (to the nearest dollar)" />
                                                 <input type="number" name="total_ppn" id="hasil_ppn"
                                                     value="{{ $rkbuBarangJasa->total_ppn }}" class="form-control"
                                                     readonly />
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Total Usulan</label>
                                 <div class="col-sm-4">
                                     <div class="input-group input-group-primary">
                                         <span class="input-group-addon">
                                             <i class="icofont icofont-presentation"></i>
                                         </span>
                                         <input id="jumlah_usulan_barang" name="jumlah_usulan_barang"
                                             value="{{ $rkbuBarangJasa->jumlah_usulan_barang }}"
                                             class="form-control bg-lighter" id="basic-addon21" aria-label="Username"
                                             aria-describedby="basic-addon21" readonly="" />
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Total Anggaran Belanja</label>
                                 <div class="col-sm-4">
                                     <div class="input-group">
                                         <span class="input-group-addon" id="basic-addon2">Rp.</span>
                                         <input name="total_anggaran_usulan_barang"
                                             value="{{ $rkbuBarangJasa->total_anggaran_usulan_barang }}"
                                             id="hasil" class="form-control" placeholder="1,000,000"
                                             readonly="">
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="form-group row">
                             <label class="col-sm-2 col-form-label"></label>
                             <div class="card-block card-header-right">
                                 <a href="{{ route($routePrefix . '.keranjang', $rkbuBarangJasa->id_usulan_barang) }}"
                                     class="btn btn-sm btn-secondary btn-outline-secondary me-4">
                                     <i class="icofont icofont-circled-left"></i> Kembali
                                 </a>
                                 <button type="submit" class="btn btn-sm btn-primary btn-outline-primary me-4">
                                     <i class="icofont icofont-check-circled"></i> Submit
                                 </button>

                             </div>
                         </div>
                     </div>
                     <!-- Basic Form Inputs card end -->
                 </div>
             </div>
         </div>
         <!-- Page body end -->
