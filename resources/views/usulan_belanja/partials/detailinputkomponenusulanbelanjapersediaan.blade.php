         <!-- Page body start -->
         <div class="page-body">
             <div class="row">
                 <div class="col-sm-12">
                     <!-- Basic Form Inputs card start -->
                     <div class="card">
                         <div class="card-block">
                             <h4 class="sub-title">Detail Komponen Persediaan</h4>
                             <div class="row">
                                 <div class="col-sm-4">
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Rata-rata Pemakaian</label>
                                         <div class="col-sm-6">
                                             <input type="number" onkeyup="sum_farmasi();" name="rata2_pemakaian"
                                                 id="rata2_pemakaian" value="{{ $rkbuBarangJasa->rata2_pemakaian }}"
                                                 class="form-control" placeholder="Rata-rata Pemakaian" />
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Sisa Stok</label>
                                         <div class="col-sm-6">
                                             <input type="number" onkeyup="sum_farmasi();" name="sisa_stok"
                                                 id="sisa_stok" value="{{ $rkbuBarangJasa->sisa_stok }}"
                                                 class="form-control" placeholder="Sisa Stok" />
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Buffer Stok</label>
                                         <div class="col-sm-6">
                                             <input type="number" onkeyup="sum_farmasi();" name="buffer_stok"
                                                 id="buffer_stok" value="{{ $rkbuBarangJasa->buffer_stok }}"
                                                 class="form-control" placeholder="Buffer Stok" />
                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label class="col-sm-6 col-form-label">Vol Pengkali</label>
                                         <div class="col-sm-6">
                                             <input type="number" onkeyup="sum_farmasi();" name="pengkali"
                                                 id="pengkali"
                                                 value="{{ old('pengkali', $rkbuBarangJasa->pengkali ?? 1) }}"
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
                                                 <input id="harga_barang" name="harga_barang" onkeyup="sum_farmasi();"
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
                                                     class="form-control" onkeyup="sum_farmasi();" name="ppn"
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
                                 <label class="col-sm-2 col-form-label">Stok Minimal</label>
                                 <div class="col-sm-4">
                                     <div class="input-group input-group-primary">
                                         <span class="input-group-addon">
                                             <i class="icofont icofont-presentation"></i>
                                         </span>
                                         <input id="minimal_stok" name="stok_minimal"
                                             value="{{ $rkbuBarangJasa->stok_minimal }}"
                                             class="form-control bg-lighter" id="basic-addon21" aria-label="Username"
                                             aria-describedby="basic-addon21" readonly="" />
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-sm-2 col-form-label">Jumlah Usulan Barang</label>
                                 <div class="col-sm-4">
                                     <div class="input-group input-group-primary">
                                         <span class="input-group-addon">
                                             <i class="icofont icofont-presentation"></i>
                                         </span>
                                         <input id="usulan_barang" name="jumlah_usulan_barang"
                                             onkeyup="sum_farmasi();"
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
                                             id="total_usulan_barang_ppn" class="form-control"
                                             placeholder="1,000,000" readonly="">
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
