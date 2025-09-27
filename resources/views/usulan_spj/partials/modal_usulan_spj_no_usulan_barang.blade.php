 <!-- Refer & Earn Modal -->

 <div class="modal fade" id="referAndEarn" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-simple modal-refer-and-earn">
         <div class="modal-content p-3 p-md-5">
             <div class="modal-body pt-3 pt-md-0 px-0 pb-md-0">
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 <div class="text-center mb-4">
                     <h3 class="mb-2">Form Tambah SPJ</h3>
                     <p class="text-center w-75 m-auto mt-1">
                         Usulan Tambah SPJ {{ session('nama_lengkap') }}
                     </p>
                 </div>
                 <div class="row py-2">
                     <div class="col-12 col-lg-4 px-4">
                         <div class="d-flex justify-content-center mb-3">
                             <div class="modal-refer-and-earn-step bg-label-primary">
                                 <i class="mdi mdi-message-outline mdi-36px"></i>
                             </div>
                         </div>
                         <div class="text-center">
                             <h6 class="fw-semibold mb-2">Ajukan Usulan Belanja</h6>
                             <p class="mb-lg-0">Kategori Belanja dan Komponen</p>
                         </div>
                     </div>
                     <div class="col-12 col-lg-4 px-4">
                         <div class="d-flex justify-content-center mb-3">
                             <div class="modal-refer-and-earn-step bg-label-primary">
                                 <i class="mdi mdi-content-paste mdi-36px"></i>
                             </div>
                         </div>
                         <div class="text-center">
                             <h6 class="fw-semibold mb-2">Validasi Data Perencanaan</h6>
                             <p class="mb-lg-0">Penyesuian RBA Belanja {{ session('nama_lengkap') }}</p>
                         </div>
                     </div>
                     <div class="col-12 col-lg-4 px-4">
                         <div class="d-flex justify-content-center mb-3">
                             <div class="modal-refer-and-earn-step bg-label-primary">
                                 <i class="mdi mdi-medal-outline mdi-36px"></i>
                             </div>
                         </div>
                         <div class="text-center">
                             <h6 class="fw-semibold mb-2">Proses Permintaan Barang</h6>
                             <p class="mb-0">Persetujuan dan Proses Pengadaan</p>
                         </div>
                     </div>
                 </div>
                 <hr class="my-4 mx-n3 mx-md-n5" />
                 <h5 class="pt-2">Pilih Usulan Belanja Yang Ingin di jadikan SPJ</h5>
                 <form class="row g-3" method="POST" action="{{ route('usulan_spj_barjass.add') }}">
                     @csrf
                     <div class="col-lg-10">
                         <label class="form-label fw-normal" for="modalRnFEmail">Input Usulan Belanja Sesuai dalam
                             RBA</label>
                         <select id="subKategoriSelect2" name="id_usulan_barang"
                             class="select2 form-select form-select-lg" data-allow-clear="true">
                             <option data-display="Select">Pilih Usulan Belanja</option>
                             @foreach ($usulan_barangs as $sub_kategori)
                                 <option value="{{ $sub_kategori->id_usulan_barang }}">
                                     {{ $sub_kategori->no_usulan_barang }}
                                     [{{ $sub_kategori->kode_sub_kategori_rkbu }}.{{ $sub_kategori->sub_kategori_rkbu->nama_sub_kategori_rkbu }}]
                                 </option>
                             @endforeach
                         </select>
                     </div>
                     <div class="col-lg-2 d-flex align-items-end">
                         <!-- Other hidden fields -->
                         <input type="hidden" name="id_user" value="{{ session('id_user') }}">
                         <input type="hidden" name="status_spj" value="Proses SPJ">
                         <button type="submit" class="btn btn-primary">Submit</button>
                     </div>
                 </form>

             </div>
         </div>
     </div>
 </div>
 <!--/ Refer & Earn Modal -->
