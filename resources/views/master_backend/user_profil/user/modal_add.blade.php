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
                     action="{{ route('users.store') }}" method="post">
                     @csrf
                     <div class="mb-13 text-center">
                         <h1 class="mb-3">Set First Target</h1>
                         <div class="text-muted fw-bold fs-5">If you need more info, please check
                             <a href="#" class="fw-bolder link-primary">Project Guidelines</a>.
                         </div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Nama User</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nama Pegawai sesuai dengan data kepegawaian"
                                 aria-label="Nama Pegawai sesuai dengan data kepegawaian"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Nama User"
                             name="nama" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">NIP User</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nomor Induk Pegawai atau NRK Pegawai"
                                 aria-label="Nomor Induk Pegawai atau NRK Pegawai"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter NIP User"
                             name="nip" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-6 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Username</label>
                             <input type="text" class="form-control form-control-solid" placeholder="Enter Username"
                                 name="username" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Email</label>
                             <div class="position-relative d-flex align-items-center">
                                 <input class="form-control form-control-solid" placeholder="Enter Email" name="email"
                                     type="email" required>
                             </div>
                             <!--end::Input-->
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Password</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Password untuk login" aria-label="Password untuk login"></i>
                         </label>
                         <input type="password" class="form-control form-control-solid" placeholder="Enter Password"
                             name="password" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-6 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Unit</label>
                             <select class="form-select" name="unit_id" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="" data-select2-id="select2-data-63-6qoa">Select user...
                                 </option>
                                 @foreach ($units as $unit)
                                     <option value="{{ $unit->id }}">
                                         {{ $unit->nama_unit }}
                                     </option>
                                 @endforeach
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                         <!--begin::Col-->
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Jabatan</label>
                             <select class="form-select" name="position_id" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="" data-select2-id="select2-data-63-6qoa">Select user...
                                 </option>
                                 @foreach ($positions as $position)
                                     <option value="{{ $position->id }}">
                                         {{ $position->nama_jabatan }}
                                     </option>
                                 @endforeach
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="d-flex flex-stack mb-8">
                         <!--begin::Label-->
                         <div class="me-5">
                             <label class="fs-6 fw-bold">Status Aktif User</label>
                             <div class="fs-7 fw-bold text-muted">
                                 Status aktif untuk login
                             </div>
                         </div>
                         <!--end::Label-->

                         <!--begin::Switch-->
                         <label class="form-check form-switch form-check-custom form-check-solid">
                             <input class="form-check-input" type="checkbox" name="status_user" value="aktif"
                                 checked>
                             <span class="form-check-label fw-bold text-muted">
                                 Aktif
                             </span>
                         </label>
                         <!--end::Switch-->
                     </div>

                     <div class="d-flex flex-stack mb-8">
                         <!--begin::Label-->
                         <div class="me-5">
                             <label class="fs-6 fw-bold">Status Aktif Edit User</label>
                             <div class="fs-7 fw-bold text-muted">
                                 Status aktif untuk mengedit user
                             </div>
                         </div>
                         <!--end::Label-->

                         <!--begin::Switch-->
                         <label class="form-check form-switch form-check-custom form-check-solid">
                             <input class="form-check-input" type="checkbox" name="status_edit" value="aktif"
                                 checked>
                             <span class="form-check-label fw-bold text-muted">
                                 Aktif
                             </span>
                         </label>
                         <!--end::Switch-->
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row">
                         <!--begin::Label-->
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Roles</span>

                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                 title="Pilih satu atau lebih role user"></i>
                         </label>
                         <select name="roles[]" class="form-select form-select-solid" data-control="select2"
                             data-placeholder="Pilih Roles" data-allow-clear="true" multiple required>
                             @foreach ($roles as $role)
                                 <option value="{{ $role->id }}">
                                     {{ $role->nama_role }}
                                 </option>
                             @endforeach
                         </select>
                     </div>
                     <div class="mb-15 fv-row">
                         <!--begin::Wrapper-->
                         <div class="d-flex flex-stack">
                             <!--begin::Label-->
                             <div class="fw-bold me-5">
                                 <label class="fs-6">Notifications</label>
                                 <div class="fs-7 text-muted">Allow Notifications by Phone or Email</div>
                             </div>
                             <!--end::Label-->
                             <!--begin::Checkboxes-->
                             <div class="d-flex align-items-center">
                                 <!--begin::Checkbox-->
                                 <label class="form-check form-check-custom form-check-solid me-10">
                                     <input class="form-check-input h-20px w-20px" type="checkbox"
                                         name="communication[]" value="email" checked="checked">
                                     <span class="form-check-label fw-bold">Email</span>
                                 </label>
                                 <!--end::Checkbox-->
                                 <!--begin::Checkbox-->
                                 <label class="form-check form-check-custom form-check-solid">
                                     <input class="form-check-input h-20px w-20px" type="checkbox"
                                         name="communication[]" value="phone">
                                     <span class="form-check-label fw-bold">Phone</span>
                                 </label>
                                 <!--end::Checkbox-->
                             </div>
                             <!--end::Checkboxes-->
                         </div>
                         <!--end::Wrapper-->
                     </div>
                     <!--end::Input group-->
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
