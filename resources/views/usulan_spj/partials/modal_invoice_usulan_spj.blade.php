 <div class="modal fade bs-example-modal-lg" id="large-Modal" tabindex="-1" role="dialog"
     aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="myExtraLargeModalLabel">Form Usulan Belanja</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="col-lg-12">
                 <div class="card-body">
                     <div class="d-flex flex-wrap justify-content-evenly">
                         <p class="text-muted mb-0"><i
                                 class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2 rounded-circle shadow"></i>Completed
                         </p>
                         <p class="text-muted mb-0"><i
                                 class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2 rounded-circle shadow"></i>In
                             Progress</p>
                         <p class="text-muted mb-0"><i
                                 class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2 rounded-circle shadow"></i>To
                             Do</p>
                     </div>
                 </div>
                 <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0" style="height: 6px;">
                     <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%"
                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                     <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%"
                         aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20"
                         aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
             </div>
             <div class="modal-body">
                 <h6 class="fs-15">Pilih Kategori Sub Kegiatan</h6>
                 <div class="d-flex">
                     <div class="flex-shrink-0">
                         <i class="ri-checkbox-circle-fill text-success"></i>
                     </div>
                     <div class="flex-grow-1 ms-2">
                         <p class="text-muted mb-0">Kategori sub kegiatan ini adalah kategori yang terdapat komponen
                             yang sudah divalidasi.</p>
                     </div>
                 </div>
                 <form class="row g-3" method="POST" action="{{ route($routePrefix . '.add') }}">
                     @csrf
                     <div class="col-lg-12">
                         <select id="subKategoriSelect2" name="id_usulan_barang" class="form-select" data-choices
                             data-choices-sorting="true">
                             <option data-display="Select">Pilih Sub Kategori RKBU</option>
                             @foreach ($usulan_barangs as $sub_kategori)
                                 <option value="{{ $sub_kategori->id_usulan_barang }}">
                                     {{ $sub_kategori->no_usulan_barang }}
                                     [{{ $sub_kategori->kode_sub_kategori_rkbu }}.{{ $sub_kategori->sub_kategori_rkbu->nama_sub_kategori_rkbu }}]
                                 </option>
                             @endforeach
                         </select>
                     </div>
                     <div class="col-lg-2 d-flex align-items-end">
                         <input type="hidden" name="id_user" value="{{ session('id_user') }}">
                         <input type="hidden" name="status_spj" value="Proses SPJ">
                     </div>
             </div>
             <div class="modal-footer">
                 <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium"
                     data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                 <button type="submit" class="btn btn-primary ">Save changes</button>
             </div>
             </form>
         </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->
