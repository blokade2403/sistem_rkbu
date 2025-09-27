 <!-- Basic Form Inputs card start -->
 <div class="card">
     <div class="card-header">
         <h4 class="sub-title">Proses Pesanan SPJ</h4>
         <span></span>
         <div class="card-header-right">
             <ul class="list-unstyled card-option">
                 <li><i class="feather icon-maximize full-card"></i></li>
                 <li><i class="feather icon-minus minimize-card"></i></li>
             </ul>
         </div>
     </div>
     <div class="card-block">
         <form class="needs-validation" method="POST"
             action="{{ route($routePrefix . '.updateSuratPesanan', $master_spj->id_master_spj) }}"
             id="confirmSubmitForm" enctype="multipart/form-data" validate>
             @csrf
             @method('PUT')
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Uraian Belanja</label>
                 <div class="col-sm-10">
                     <textarea rows="5" cols="5" name="rincian_belanja" class="form-control" placeholder="Default textarea"> {{ old('rincian_belanja', is_array($master_spj->rincian_belanja) ? implode(', ', $master_spj->rincian_belanja) : $master_spj->rincian_belanja) }}</textarea>
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Tanggal Surat Pesanan</label>
                 <div class="col-sm-10">
                     <input type="date" class="form-control" name="tgl_surat_pesanan"
                         value="{{ $master_spj->tgl_surat_pesanan }}" placeholder="Type your title in Placeholder">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Jangka waktu Pekerjaan</label>
                 <div class="col-sm-10">
                     <input type="date" name="jangka_waktu_pekerjaan"
                         value="{{ $master_spj->jangka_waktu_pekerjaan }}" class="form-control"
                         placeholder="Password input">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Perusahaan</label>
                 <div class="col-sm-10">
                     <select name="id_perusahaan" id="perusahaan" class="js-example-basic-single col-sm-12">
                         <option value="{{ $master_spj->id_perusahaan }}">
                             {{ optional($master_spj->perusahaan)->nama_perusahaan ?? 'Pilih Nama Perusahaan' }}
                         </option>
                         @foreach ($perusahaan as $key)
                             <option value="{{ $key->id_perusahaan }}">
                                 {{ $key->nama_perusahaan }}</option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Nomor Surat Pesanan</label>
                 <div class="col-sm-10">
                     <input type="text" class="form-control" name="no_surat_pesanan"
                         value="{{ $master_spj->no_surat_pesanan }}" placeholder="No SP">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">ID Paket</label>
                 <div class="col-sm-10">
                     <input type="text" name="idpaket" value="{{ $master_spj->idpaket }}" class="form-control"
                         placeholder="No ID Paket">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Admin Pendukung</label>
                 <div class="col-sm-10">
                     <select name="id_admin_pendukung_ppk" class="js-example-basic-single col-sm-12">
                         <option value="{{ $master_spj->id_admin_pendukung_ppk }}">
                             {{ optional($master_spj->pendukung_ppk)->nama_pendukung_ppk ?? 'Pilih Pendukung PPK' }}
                         </option>
                         @foreach ($pendukung as $item)
                             <option value="{{ $item->id_admin_pendukung_ppk }}">{{ $item->nama_pendukung_ppk }}
                             </option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Tahun Berjalan</label>
                 <div class="col-sm-10">
                     <select name="status_hutang" class="js-example-basic-single col-sm-12">
                         <option value="{{ $master_spj->status_hutang }}" selected>
                             {{ $master_spj->status_hutang ?? 'Pilih Status Hutang' }}
                         </option>
                         <option value="Tahun Berjalan">Tahun Berjalan</option>
                         <option value="Hutang">Hutang</option>
                     </select>
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Harga Dasar</label>
                 <div class="col-sm-10">
                     <input type="text" name="harga_dasar" id="harga_dasar_pengadaan"
                         value="{{ number_format($master_spj->harga_dasar, 0, '.', '.') }}" readonly=""
                         class="form-control currency" placeholder="Harga Dasar" readonly="">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">PPN</label>
                 <div class="col-sm-10">
                     <input type="number" step="0.01" min="0" max="100" class="form-control"
                         id="ppn_pengadaan" name="ppn" onkeyup="spj_pengadaan();" value="{{ $master_spj->ppn }}"
                         class="form-control" placeholder="Isi PPN">
                 </div>
             </div>
             <div class="form-group row">
                 <label class="col-sm-2 col-form-label">Total Harga Bruto</label>
                 <div class="col-sm-10">
                     <input type="text" onkeyup="spj_pengadaan();" id="harga_bruto" name="bruto"
                         value="{{ number_format($master_spj->bruto, 0, '.', '.') }}" class="form-control currency"
                         placeholder="Isi Harga Bruto">
                 </div>
             </div>
             <div class="row text-center">
                 <input type="hidden" class="form-control" name="nama_lengkap" id="validationCustomUsername"
                     value="{{ session('nama_lengkap') }}">
                 <input type="hidden" class="form-control" name="id_user" id="validationCustomUsername"
                     value="{{ session('id_user') }}">
                 <input type="hidden" class="form-control" name="tahun_anggaran" id="validationCustomUsername"
                     value="{{ session('nama_tahun_anggaran') }}">
                 <input type="hidden" class="form-control" name="id_status_spj" id="validationCustomUsername"
                     value="6">
                 <input type="hidden" class="form-control" name="ket_verif_bendahara"
                     id="validationCustomUsername">
                 <input type="hidden" class="form-control" name="status_proses_pesanan"
                     value="{{ session('status_proses_pesanan') }}">
             </div>
             <div class="card-block card-header-right">
                 <a href="{{ route($routePrefix . '.index') }}" class="btn btn-mat btn-inverse">
                     <i class="icofont icofont-ui-reply"></i> Kembali
                 </a>
                 <button type="submit" class="btn btn-mit btn-primary" data-toggle="tooltip" data-placement="top"
                     title="" data-original-title="Kirim Usulan Barang"><i
                         class="icofont icofont-ui-check"></i>Kirim
                     Usulan</button>
             </div>
         </form>
     </div>
 </div>
 <!-- Basic Form Inputs card end -->
