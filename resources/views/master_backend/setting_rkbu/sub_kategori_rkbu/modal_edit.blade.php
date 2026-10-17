 @foreach ($sub_kategori_rkbus as $item)
     <div class="modal fade" id="kt_modal_new_target_form{{ $item->id_sub_kategori_rkbu }}" tabindex="-1"
         aria-hidden="true">
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
                     <form id="kt_modal_new_target_form{{ $item->id_sub_kategori_rkbu }}"
                         class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                         action="{{ route('sub_kategori_rkbus.update', $item->id_sub_kategori_rkbu) }}" method="post">
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
                                 <span class="required">Kode Sub Kategori Rkbu</span>
                                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                     data-bs-original-title="Kode Sub Kategori Rkbu"
                                     aria-label="Kode Sub Kategori Rkbu"></i>
                             </label>
                             <input type="text" class="form-control form-control-solid"
                                 placeholder="Enter Kode Sub Kategori Rkbu" name="kode_sub_kategori_rkbu"
                                 value="{{ $item->kode_sub_kategori_rkbu }}" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                             <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                 <span class="required">Nama Sub Kategori Rkbu</span>
                                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                     data-bs-original-title="Nama Sub Kategori Rkbu"
                                     aria-label="Nama Sub Kategori Rkbu"></i>
                             </label>
                             <input type="text" class="form-control form-control-solid"
                                 placeholder="Enter Nama Sub Kategori Rkbu" name="nama_sub_kategori_rkbu"
                                 value="{{ $item->nama_sub_kategori_rkbu }}" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <div class="row g-9 mb-8">
                             <!--begin::Col-->
                             <div class="col-md-12 fv-row fv-plugins-icon-container">
                                 <label class="required fs-6 fw-bold mb-2">Kategori Rkbu</label>
                                 <select class="form-select" name="id_kategori_rkbu" data-control="select2"
                                     data-placeholder="Select an option">
                                     @foreach ($kategori_rkbus as $kategori_rkbu)
                                         <option value="{{ $kategori_rkbu->id_kategori_rkbu }}"
                                             {{ $item->id_kategori_rkbu == $kategori_rkbu->id_kategori_rkbu ? 'selected' : '' }}>
                                             {{ $kategori_rkbu->kode_kategori_rkbu }}.
                                             {{ $kategori_rkbu->nama_kategori_rkbu }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <div class="fv-plugins-message-container invalid-feedback"></div>
                             </div>
                             <!--end::Col-->
                         </div>
                         <div class="row g-9 mb-8">
                             <!--begin::Col-->
                             <div class="col-md-12 fv-row fv-plugins-icon-container">
                                 <label class="required fs-6 fw-bold mb-2">Sub Kategori Rekening</label>
                                 <select class="form-select" name="id_sub_kategori_rekening" data-control="select2"
                                     data-placeholder="Select an option">
                                     @foreach ($sub_kategori_rekenings as $sub_kategori_rekening)
                                         <option value="{{ $sub_kategori_rekening->id_sub_kategori_rekening }}"
                                             {{ $item->id_sub_kategori_rekening == $sub_kategori_rekening->id_sub_kategori_rekening ? 'selected' : '' }}>
                                             {{ $sub_kategori_rekening->kode_sub_kategori_rekening }}.
                                             {{ $sub_kategori_rekening->nama_sub_kategori_rekening }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <div class="fv-plugins-message-container invalid-feedback"></div>
                             </div>
                             <!--end::Col-->
                         </div>
                         <div class="row g-9 mb-8">
                             <!--begin::Col-->
                             <div class="col-md-12 fv-row">
                                 <label class="required fs-6 fw-bold mb-2">Status</label>
                                 <select class="form-select @error('status') is-invalid @enderror" name="status"
                                     data-control="select2">
                                     <option value="aktif"
                                         {{ old('status', $item->status) == 'aktif' ? 'selected' : '' }}>
                                         Aktif
                                     </option>
                                     <option value="tidak aktif"
                                         {{ old('status', $item->status) == 'tidak aktif' ? 'selected' : '' }}>
                                         Tidak Aktif
                                     </option>
                                 </select>
                                 @error('status')
                                     <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
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
