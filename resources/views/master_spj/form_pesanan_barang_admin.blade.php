 <!-- Basic Form Inputs card start -->
 <div class="col-xl-12">
     <div class="card">
         <div class="card-block">
             <div class="card-header align-items-center d-flex">
                 <h4 class="card-title mb-0 flex-grow-1">Detail Komponen</h4>
                 <div class="flex-shrink-0">
                     <div class="form-check form-switch form-switch-right form-switch-md">
                         <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                         <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                     </div>
                 </div>
             </div><!-- end card header -->
             <form class="needs-validation" method="POST"
                 action="{{ route($routePrefix . '.updateSuratPesanan', $master_spj->id_master_spj) }}"
                 id="confirmSubmitForm" enctype="multipart/form-data" validate>
                 @csrf
                 @method('PUT')
                 <div class="card-body">
                     <p class="text-muted">Create horizontal forms with the grid by adding the <code>row</code>
                         class to
                         form groups and using the <code>col-*-*</code> class to specify the width of your labels
                         and
                         controls. Be sure to add <code>col-form-label</code> class to your
                         <code>&lt;label&gt;</code>s
                         as
                         well so they’re vertically centered with their associated form controls.
                     </p>
                     <div class="live-preview">
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="nameInput" class="form-label">Uraian Belanja</label>
                             </div>
                             <div class="col-lg-10">
                                 <textarea rows="2" cols="2" name="rincian_belanja" class="form-control" placeholder="Default textarea"> {{ old('rincian_belanja', is_array($master_spj->rincian_belanja) ? implode(', ', $master_spj->rincian_belanja) : $master_spj->rincian_belanja) }}</textarea>
                             </div>

                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="nameInput" class="form-label">Tanggal Surat Pesanan</label>
                             </div>
                             <div class="col-lg-4">
                                 <input type="date" class="form-control" name="tgl_surat_pesanan"
                                     value="{{ $master_spj->tgl_surat_pesanan }}"
                                     placeholder="Type your title in Placeholder">
                             </div>
                             <div class="col-lg-2">
                                 <label for="nameInput" class="form-label">Waktu Pekerjaan</label>
                             </div>
                             <div class="col-lg-4">
                                 <input type="date" name="jangka_waktu_pekerjaan"
                                     value="{{ $master_spj->jangka_waktu_pekerjaan }}" class="form-control"
                                     placeholder="Password input">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="websiteUrl" class="form-label">Spesifikasi</label>
                             </div>
                             <div class="col-lg-10">
                                 <select name="id_perusahaan" id="perusahaan" data-choices data-choices-sorting="true">
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
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="dateInput" class="form-label">Nomor Surat Pesanan</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text" class="form-control" name="no_surat_pesanan"
                                     value="{{ $master_spj->no_surat_pesanan }}" placeholder="No SP">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="timeInput" class="form-label">ID Paket</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text" name="idpaket" value="{{ $master_spj->idpaket }}"
                                     class="form-control" placeholder="No ID Paket">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="leaveemails" class="form-label">Admin Pendukung</label>
                             </div>
                             <div class="col-lg-10">
                                 <select name="id_admin_pendukung_ppk" data-choices data-choices-sorting="true">
                                     <option value="{{ $master_spj->id_admin_pendukung_ppk }}">
                                         {{ optional($master_spj->pendukung_ppk)->nama_pendukung_ppk ?? 'Pilih Pendukung PPK' }}
                                     </option>
                                     @foreach ($pendukung as $item)
                                         <option value="{{ $item->id_admin_pendukung_ppk }}">
                                             {{ $item->nama_pendukung_ppk }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="contactNumber" class="form-label">Tahun Berjalan</label>
                             </div>
                             <div class="col-lg-10">
                                 <select name="status_hutang" data-choices data-choices-sorting="true">
                                     <option value="{{ $master_spj->status_hutang }}" selected>
                                         {{ $master_spj->status_hutang ?? 'Pilih Status Hutang' }}
                                     </option>
                                     <option value="Tahun Berjalan">Tahun Berjalan</option>
                                     <option value="Hutang">Hutang</option>
                                 </select>
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="contactNumber" class="form-label">Harga Dasar</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text" name="harga_dasar" id="harga_dasar_pengadaan"
                                     value="{{ number_format($master_spj->harga_dasar, 0, '.', '.') }}"
                                     readonly="" class="form-control currency" placeholder="Harga Dasar"
                                     readonly="">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="meassageInput" class="form-label">PPN</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="number" step="0.01" min="0" max="100"
                                     class="form-control" id="ppn_pengadaan" name="ppn"
                                     onkeyup="spj_pengadaan();" value="{{ $master_spj->ppn }}" class="form-control"
                                     placeholder="Isi PPN">
                             </div>
                         </div>
                         <div class="row mb-3">
                             <div class="col-lg-2">
                                 <label for="meassageInput" class="form-label">Total Harga Bruto</label>
                             </div>
                             <div class="col-lg-10">
                                 <input type="text" onkeyup="spj_pengadaan();" id="harga_bruto" name="bruto"
                                     value="{{ number_format($master_spj->bruto, 0, '.', '.') }}"
                                     class="form-control currency" placeholder="Isi Harga Bruto">
                                 <input type="hidden" class="form-control" name="nama_lengkap"
                                     id="validationCustomUsername" value="{{ session('nama_lengkap') }}">
                                 <input type="hidden" class="form-control" name="id_user"
                                     id="validationCustomUsername" value="{{ session('id_user') }}">
                                 <input type="hidden" class="form-control" name="tahun_anggaran"
                                     id="validationCustomUsername" value="{{ session('nama_tahun_anggaran') }}">
                                 <input type="hidden" class="form-control" name="id_status_spj"
                                     id="validationCustomUsername" value="6">
                                 <input type="hidden" class="form-control" name="ket_verif_bendahara"
                                     id="validationCustomUsername">
                                 <input type="hidden" class="form-control" name="status_proses_pesanan"
                                     value="{{ session('status_proses_pesanan') }}">
                             </div>
                         </div>
                     </div>
                     @include('partials.submit_buttons')
                     <div class="d-none code-view">
                     </div>
                 </div>
             </form>
         </div>
     </div>
 </div>



 <!-- end row -->
