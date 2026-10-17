 @foreach ($workflowSteps as $step)
     <div class="modal fade" id="kt_modal_new_target_form{{ $step->id }}" tabindex="-1"
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
                     <form id="kt_modal_new_target_form{{ $step->id }}"
                         class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                         action="{{ route('workflow_steps.update', $step->id) }}" method="post">
                         @csrf
                         @method('PUT')
                         <div class="mb-13 text-center">
                             <h1 class="mb-3">Set First Target</h1>
                             <div class="text-muted fw-bold fs-5">If you need more info, please check
                                 <a href="#" class="fw-bolder link-primary">Project Guidelines</a>.
                             </div>
                         </div>
                         <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="required fs-6 fw-bold mb-2">Workflow</label>
                         <select class="form-select" name="workflow_id" data-control="select2"
                             data-placeholder="Select an option">
                             <option data-select2-id="select2-data-63-6qoa">Select user...
                             </option>
                            @foreach ($workflows as $workflow)
    <option value="{{ $workflow->id }}"
        {{ $workflow->id == $step->workflow_id ? 'selected' : '' }}>
        {{ $workflow->nama_workflow }}
    </option>
@endforeach
                         </select>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-12 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Level Jabatan</label>
                             <select class="form-select" name="level_jabatan" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="" data-select2-id="select2-data-63-6qoa">Select user...
                                 </option>
                                 @foreach ($levelJabatans as $jabatan)
    <option value="{{ $jabatan->level_jabatan }}"
        {{ $jabatan->level_jabatan == $step->level_jabatan ? 'selected' : '' }}>
        {{ $jabatan->level_jabatan }}
    </option>
@endforeach
                                 {{-- @foreach ($levelJabatans as $item)
                                     <option value="{{ $item->level_jabatan }}" {{ $item->level_jabatan == $workflowSteps->level_jabatan ? 'selected' : '' }}>
                                         {{ $item->level_jabatan }}
                                     </option>
                                 @endforeach --}}
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-12 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Role</label>
                             <select class="form-select" name="role_id" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="" data-select2-id="select2-data-63-6qoa">Select user...
                                 </option>
                                 @foreach ($roles as $role)
                                     <option value="{{ $role->id }}" {{ $role->id == $step->role_id ? 'selected' : '' }}>
                                         {{ $role->nama_role }}
                                     </option>
                                 @endforeach
                             </select>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <!--end::Col-->
                     </div>
                      <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                             <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                 <span class="required">Urutan</span>
                                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                     data-bs-original-title="Kode Sub Kategori Rkbu"
                                     aria-label="Kode Sub Kategori Rkbu"></i>
                             </label>
                             <input type="number" class="form-control form-control-solid"
                                 placeholder="Enter Urutan" name="step_order"
                                 value="{{ $step->step_order }}" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                    <div class="form-check form-check-custom form-check-solid mb-5">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_required"
                            value="1"
                            {{ $step->is_required ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Required
                        </label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_pptk_step"
                            value="1"
                            {{ $step->is_pptk_step ? 'checked' : '' }}>
                        <label class="form-check-label">
                            PPTK Step
                        </label>
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
