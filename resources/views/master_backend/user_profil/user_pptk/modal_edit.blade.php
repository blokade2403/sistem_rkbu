 @foreach ($data as $item)
     <div class="modal fade" id="kt_modal_edit_user_{{ $item->id }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered mw-650px">
             <div class="modal-content rounded">

                 <!--begin::Modal header-->
                 <div class="modal-header pb-0 border-0 justify-content-end">
                     <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">

                         <span class="svg-icon svg-icon-1">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none">

                                 <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                     transform="rotate(-45 6 17.3137)" fill="black" />

                                 <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                     transform="rotate(45 7.41422 6)" fill="black" />
                             </svg>
                         </span>
                     </div>
                 </div>
                 <!--end::Modal header-->

                 <!--begin::Modal body-->
                 <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">

                     <form action="{{ route('user-pptk.update', $item->id) }}" method="POST" class="form">
                         @csrf
                         @method('PUT')
                         <div class="mb-13 text-center">
                             <h1 class="mb-3">Edit User</h1>

                             <div class="text-muted fw-bold fs-5">
                                 Silahkan ubah data user sesuai kebutuhan
                             </div>
                         </div>
                         <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                             <div class="col-md-12 fv-row fv-plugins-icon-container">
                                 <label class="required fs-6 fw-bold mb-2">User</label>
                                 <select class="form-select" name="user_id" data-control="select2"
                                     data-placeholder="Select an option">
                                     <option value="" data-select2-id="select2-data-63-6qoa">Select user...
                                     </option>
                                     @foreach ($users as $user)
                                         <option value="{{ $user->id }}"
                                             {{ $item->user_id == $user->id ? 'selected' : '' }}>
                                             {{ $user->nama }}
                                             -
                                             {{ $user->position->nama_jabatan ?? '' }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <div class="fv-plugins-message-container invalid-feedback"></div>
                             </div>
                         </div>
                         <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                             <div class="col-md-12 fv-row fv-plugins-icon-container">
                                 <label class="required fs-6 fw-bold mb-2">Kategori PPTK</label>
                                 <select class="form-select" name="pptk_kategori_id" data-control="select2"
                                     data-placeholder="Select an option">
                                     <option value="" data-select2-id="select2-data-63-6qoa">Select kategori...
                                     </option>
                                     @foreach ($pptks as $pptk)
                                         <option value="{{ $pptk->id }}"
                                             {{ $item->pptk_kategori_id == $pptk->id ? 'selected' : '' }}>
                                             {{ $pptk->nama_kategori }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <div class="fv-plugins-message-container invalid-feedback"></div>
                             </div>
                         </div>
                       <div class="d-flex flex-stack mb-8">
    <div class="me-5">
        <label class="fs-6 fw-bold">
            Status Aktif User
        </label>
        <div class="fs-7 fw-bold text-muted">
            Status aktif untuk login
        </div>
    </div>

    <input type="hidden" name="is_active" value="0">

    <label class="form-check form-switch form-check-custom form-check-solid">
        <input
            class="form-check-input"
            type="checkbox"
            name="is_active"
            value="1"
            {{ $item->is_active ? 'checked' : '' }}
        >

        <span class="form-check-label fw-bold text-muted">
            Aktif
        </span>
    </label>
</div>
                         <div class="text-center">

                             <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
                                 Cancel
                             </button>

                             <button type="submit" class="btn btn-primary">
                                 <span class="indicator-label">
                                     Update User
                                 </span>
                             </button>

                         </div>

                     </form>

                 </div>
                 <!--end::Modal body-->

             </div>
         </div>
     </div>
 @endforeach
