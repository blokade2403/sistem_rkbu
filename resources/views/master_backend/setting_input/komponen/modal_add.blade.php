 <div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered mw-650px">
         <div class="modal-content rounded">
             <div class="modal-header pb-0 border-0 justify-content-end">
                 <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                     <span class="svg-icon svg-icon-1">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none">
                             <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                 transform="rotate(-45 6 17.3137)" fill="black"></rect>
                             <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                 transform="rotate(45 7.41422 6)" fill="black"></rect>
                         </svg>
                     </span>
                 </div>
             </div>
             <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                 <form id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                     action="{{ route('komponens.store') }}" method="post">
                     @csrf
                     <div class="mb-13 text-center">
                         <h1 class="mb-3">Set First Target</h1>
                         <div class="text-muted fw-bold fs-5">If you need more info, please check
                             <a href="#" class="fw-bolder link-primary">Project Guidelines</a>.
                         </div>
                     </div>
                      <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-12 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Jenis Kategori RKBU</label>
                             <select class="form-select" name="id_jenis_kategori_rkbu" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="" data-select2-id="select2-data-63-6qoa">Select an option...
                                 </option>
                                 @foreach ($jenis_kategori_rkbu as $item)
                                     <option value="{{ $item->id_jenis_kategori_rkbu }}">
                                         {{ $item->nama_jenis_kategori_rkbu }}
                                     </option>
                                 @endforeach
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Nama Barang</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nama Pegawai sesuai dengan data kepegawaian"
                                 aria-label="Nama Pegawai sesuai dengan data kepegawaian"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Nama Barang"
                             name="nama_barang" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Satuan</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nomor Induk Pegawai atau NRK Pegawai"
                                 aria-label="Nomor Induk Pegawai atau NRK Pegawai"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Satuan"
                             name="satuan" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-6 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Kode Barang</label>
                             <input type="text" class="form-control form-control-solid" placeholder="Enter Kode Barang"
                                 name="kode_barang" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Kode Komponen</label>
                             <div class="position-relative d-flex align-items-center">
                                 <input class="form-control form-control-solid" placeholder="Enter Kode Komponen" name="kode_komponen"
                                     type="text" required>
                             </div>
                             <!--end::Input-->
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Spesifikasi</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Password untuk login" aria-label="Password untuk login"></i>
                         </label>
                         <textarea class="form-control form-control-solid" placeholder="Enter Spesifikasi" name="spek" required></textarea>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <!--begin::Actions-->
                     <div class="text-center">
                         <button type="reset" id="kt_modal_new_target_cancel"
                             class="btn btn-light me-3">Cancel</button>
                         <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                             <span class="indicator-label">Submit</span>
                             <span class="indicator-progress">Please wait...
                                 <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                         </button>
                     </div>
                     <!--end::Actions-->
                     <div></div>
                 </form>
                 <!--end:Form-->
             </div>
             <!--end::Modal body-->
         </div>
         <!--end::Modal content-->
     </div>
     <!--end::Modal dialog-->
 </div>
