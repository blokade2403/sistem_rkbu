 @foreach ($users as $item)
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

                     <form action="{{ route('users.update', $item->id) }}" method="POST" class="form">
                         @csrf
                         @method('PUT')

                         <!--begin::Heading-->
                         <div class="mb-13 text-center">
                             <h1 class="mb-3">Edit User</h1>

                             <div class="text-muted fw-bold fs-5">
                                 Silahkan ubah data user sesuai kebutuhan
                             </div>
                         </div>
                         <!--end::Heading-->

                         <!-- Nama -->
                         <div class="d-flex flex-column mb-8 fv-row">
                             <label class="required fs-6 fw-bold mb-2">
                                 Nama User
                             </label>

                             <input type="text" class="form-control form-control-solid" name="nama"
                                 value="{{ $item->nama }}" required>
                         </div>

                         <!-- NIP -->
                         <div class="d-flex flex-column mb-8 fv-row">
                             <label class="required fs-6 fw-bold mb-2">
                                 NIP User
                             </label>

                             <input type="text" class="form-control form-control-solid" name="nip"
                                 value="{{ $item->nip }}" required>
                         </div>

                         <!-- Username & Email -->
                         <div class="row g-9 mb-8">

                             <div class="col-md-6 fv-row">
                                 <label class="required fs-6 fw-bold mb-2">
                                     Username
                                 </label>

                                 <input type="text" class="form-control form-control-solid" name="username"
                                     value="{{ $item->username }}" required>
                             </div>

                             <div class="col-md-6 fv-row">
                                 <label class="required fs-6 fw-bold mb-2">
                                     Email
                                 </label>

                                 <input type="email" class="form-control form-control-solid" name="email"
                                     value="{{ $item->email }}" required>
                             </div>

                         </div>

                         <!-- Password -->
                         <div class="d-flex flex-column mb-8 fv-row">
                             <label class="fs-6 fw-bold mb-2">
                                 Password
                             </label>

                             <input type="password" class="form-control form-control-solid" name="password"
                                 placeholder="Kosongkan jika tidak diubah">

                             <small class="text-muted">
                                 Kosongkan jika password tidak ingin diubah
                             </small>
                         </div>

                         <!-- Unit & Jabatan -->
                         <div class="row g-9 mb-8">

                             <!-- Unit -->
                             <div class="col-md-6 fv-row">
                                 <label class="required fs-6 fw-bold mb-2">
                                     Unit
                                 </label>

                                 <select class="form-select form-select-solid" name="unit_id" data-control="select2"
                                     required>
                                     @foreach ($units as $unit)
                                         <option value="{{ $unit->id }}"
                                             {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                             {{ $unit->nama_unit }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>

                             <!-- Jabatan -->
                             <div class="col-md-6 fv-row">
                                 <label class="required fs-6 fw-bold mb-2">
                                     Jabatan
                                 </label>

                                 <select class="form-select form-select-solid" name="position_id" data-control="select2"
                                     required>
                                     @foreach ($positions as $position)
                                         <option value="{{ $position->id }}"
                                             {{ $item->position_id == $position->id ? 'selected' : '' }}>
                                             {{ $position->nama_jabatan }}
                                         </option>
                                     @endforeach
                                 </select>
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
                             <input type="hidden" name="status_user" value="nonaktif">
                             <label class="form-check form-switch form-check-custom form-check-solid">
                                 <input class="form-check-input" type="checkbox" name="status_user" value="aktif"
                                     {{ $item->status_user == 'aktif' ? 'checked' : '' }}>
                                 <span class="form-check-label fw-bold text-muted">
                                     Aktif
                                 </span>
                             </label>
                         </div>
                         <!-- Status Edit -->
                         <div class="d-flex flex-stack mb-8">
                             <div class="me-5">
                                 <label class="fs-6 fw-bold">
                                     Status Aktif Edit User
                                 </label>
                                 <div class="fs-7 fw-bold text-muted">
                                     Status aktif untuk edit user
                                 </div>
                             </div>
                             <!-- hidden fallback -->
                             <input type="hidden" name="status_edit" value="nonaktif">
                             <label class="form-check form-switch form-check-custom form-check-solid">
                                 <input class="form-check-input" type="checkbox" name="status_edit" value="aktif"
                                     {{ $item->status_edit == 'aktif' ? 'checked' : '' }}>
                                 <span class="form-check-label fw-bold text-muted">
                                     Aktif
                                 </span>
                             </label>
                         </div>
                         <!-- Roles -->
                         <div class="d-flex flex-column mb-8 fv-row">

                             <label class="required fs-6 fw-bold mb-2">
                                 Roles
                             </label>

                             <select name="roles[]" class="form-select form-select-solid" data-control="select2"
                                 multiple required>
                                 @foreach ($roles as $role)
                                     <option value="{{ $role->id }}"
                                         {{ $item->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                         {{ $role->nama_role }}
                                     </option>
                                 @endforeach
                             </select>

                         </div>

                         <!-- Submit -->
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
