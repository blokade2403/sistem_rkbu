 @foreach ($jenis_kategori_rkbus as $item)
      <div class="modal fade" id="kt_modal_new_target_form{{ $item->id_jenis_kategori_rkbu }}" tabindex="-1" aria-hidden="true">
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
                 <form id="kt_modal_new_target_form{{ $item->id_jenis_kategori_rkbu }}" class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                     action="{{ route('jenis_kategori_rkbus.update', $item->id_jenis_kategori_rkbu) }}" method="post">
                     @csrf
                     @method('PUT')
                     <div class="mb-13 text-center">
                         <h1 class="mb-3">Set First Target</h1>
                         <div class="text-muted fw-bold fs-5">If you need more info, please check
                             <a href="#" class="fw-bolder link-primary">Project Guidelines</a>.
                         </div>
                     </div>
                      <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Kode Jenis Kategori RKBU</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Kode Jenis Kategori RKBU"
                                 aria-label="Kode Jenis Kategori RKBU"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Kode Jenis Kategori RKBU"
                             name="kode_jenis_kategori_rkbu" value="{{ $item->kode_jenis_kategori_rkbu }}" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Nama Jenis Kategori RKBU</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nama Jenis Kategori RKBU"
                                 aria-label="Nama Jenis Kategori RKBU"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Nama Jenis Kategori RKBU"
                             name="nama_jenis_kategori_rkbu" value="{{ $item->nama_jenis_kategori_rkbu }}" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-12 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Jenis Belanja</label>
                             <select class="form-select" name="id_jenis_belanja" data-control="select2"
                                 data-placeholder="Select an option">
                                 @foreach ($jenis_belanjas as $jenis_belanja)
                                     <option value="{{ $jenis_belanja->id_jenis_belanja }}">{{ $jenis_belanja->kode_jenis_belanja }}. {{ $jenis_belanja->nama_jenis_belanja }}
                                     </option>
                                         {{ $jenis_belanja->kode_jenis_belanja }}. {{ $jenis_belanja->nama_jenis_belanja }}
                                     </option>
                                 @endforeach
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                     </div>
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

 @endforeach
