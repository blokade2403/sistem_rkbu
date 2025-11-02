 <div class="col-xl-12">
     <div class="card-body p-0">
         <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-alert-triangle text-warning me-2 icon-sm">
                 <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                 </path>
                 <line x1="12" y1="9" x2="12" y2="13"></line>
                 <line x1="12" y1="17" x2="12.01" y2="17"></line>
             </svg>
             <div class="flex-grow-1 text-truncate">
                 Your free trial expired in <b>17</b> days.
             </div>
             <div class="flex-shrink-0">
                 <a href="pages-pricing.html" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
             </div>
         </div>
     </div> <!-- end card-body-->
     <div class="card">
         <div class="card-header">
             <h4 class="card-title mb-0">Proses Barang Datang Parsial 1</h4>
         </div><!-- end card header -->
         <div class="card-body form-steps">
             <form action="{{ route('master_spjs.UpdateParsial1', $master_spj->id_master_spj) }}" method="POST"
                 enctype="multipart/form-data">
                 @csrf
                 @method('PUT')
                 <div class="text-center pt-3 pb-4 mb-1">
                     <h5>Verifikasi Barang Datang</h5>
                 </div>
                 <div id="custom-progress-bar1" class="progress-nav mb-4">
                     <div class="progress" style="height: 1px;">
                         <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                             aria-valuemin="0" aria-valuemax="100">
                         </div>
                     </div>

                     <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill active" data-progressbar="custom-progress-bar"
                                 id="pills-gen-info-tab1" data-bs-toggle="pill" data-bs-target="#pills-gen-info1"
                                 type="button" role="tab" aria-controls="pills-gen-info"
                                 aria-selected="true">1</button>
                         </li>
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                 id="pills-info-desc-tab1" data-bs-toggle="pill" data-bs-target="#pills-info-desc1"
                                 type="button" role="tab" aria-controls="pills-info-desc"
                                 aria-selected="false">2</button>
                         </li>
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                 id="pills-success-tab1" data-bs-toggle="pill" data-bs-target="#pills-success1"
                                 type="button" role="tab" aria-controls="pills-success"
                                 aria-selected="false">3</button>
                         </li>
                     </ul>
                 </div>

                 <div class="tab-content">
                     <div class="tab-pane fade show active" id="pills-gen-info1" role="tabpanel"
                         aria-labelledby="pills-gen-info-tab1">
                         <div>
                             <div class="mb-4">
                                 <div>
                                     <h5 class="mb-1">Serah Terima Barang Datang</h5>
                                     <p class="text-muted">Proses dokumentasi barang datang</p>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-12">
                                     <div class="mb-3">
                                         <label class="form-label" for="gen-info-email-input">Status Barang
                                             Datang</label>
                                         <select name="status_proses_pengiriman_barang" id="perusahaan" data-choices
                                             data-choices-sorting="true"
                                             @if (!empty($master_spj->status_proses_pengiriman_barang)) disabled @endif>
                                             <option value="{{ $master_spj->status_proses_pengiriman_barang }}">
                                                 {{ $master_spj->status_proses_pengiriman_barang ?? 'Pilih Status' }}
                                             </option>
                                             <option value="Selesai">Selesai</option>
                                             <option value="Parsial 1">Parsial 1</option>
                                             <option value="Parsial 2">Parsial 2</option>
                                             <option value="Parsial 3">Parsial 3</option>
                                         </select>

                                         {{-- hidden input supaya value tetap terkirim jika select di-disable --}}
                                         @if (!empty($master_spj->status_proses_pengiriman_barang))
                                             <input type="hidden" name="status_proses_pengiriman_barang"
                                                 value="{{ $master_spj->status_proses_pengiriman_barang }}">
                                         @endif
                                     </div>
                                 </div>
                             </div>

                         </div>
                         <div class="d-flex align-items-start gap-3 mt-4">
                             <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                 data-nexttab="pills-info-desc-tab1"><i
                                     class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go
                                 to more info</button>
                         </div>
                     </div>
                     <!-- end tab pane -->

                     <div class="tab-pane fade" id="pills-info-desc1" role="tabpanel"
                         aria-labelledby="pills-info-desc-tab1">
                         <div>
                             <div class="text-center">
                                 {{-- Foto 1 --}}
                                 <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                     <img id="preview-img-1"
                                         src="{{ $master_spj->foto_barang_datang_1_parsial_1 ? asset('storage/' . $master_spj->foto_barang_datang_1_parsial_1) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                         class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                         alt="Foto Barang Datang 1">

                                     <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                         <input id="profile-img-file-input" name="foto_barang_datang_1_parsial_1"
                                             type="file" class="profile-img-file-input"
                                             accept="image/png, image/jpeg">
                                         <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                             <span class="avatar-title rounded-circle bg-light text-body">
                                                 <i class="ri-camera-fill"></i>
                                             </span>
                                         </label>
                                     </div>
                                 </div>

                                 {{-- Foto 2 --}}
                                 <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                     <img id="preview-img-2"
                                         src="{{ $master_spj->foto_barang_datang_2_parsial_1 ? asset('storage/' . $master_spj->foto_barang_datang_2_parsial_1) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                         class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                         alt="Foto Barang Datang 2">

                                     <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                         <input id="profile-img-file-input-2" name="foto_barang_datang_2_parsial_1"
                                             type="file" class="profile-img-file-input"
                                             accept="image/png, image/jpeg">
                                         <label for="profile-img-file-input-2" class="profile-photo-edit avatar-xs">
                                             <span class="avatar-title rounded-circle bg-light text-body">
                                                 <i class="ri-camera-fill"></i>
                                             </span>
                                         </label>
                                     </div>
                                 </div>

                                 {{-- Foto 3 --}}
                                 <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                     <img id="preview-img-3"
                                         src="{{ $master_spj->foto_barang_datang_3_parsial_1 ? asset('storage/' . $master_spj->foto_barang_datang_3_parsial_1) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                         class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                         alt="Foto Barang Datang 3">

                                     <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                         <input id="profile-img-file-input-3" name="foto_barang_datang_3_parsial_1"
                                             type="file" class="profile-img-file-input"
                                             accept="image/png, image/jpeg">
                                         <label for="profile-img-file-input-3" class="profile-photo-edit avatar-xs">
                                             <span class="avatar-title rounded-circle bg-light text-body">
                                                 <i class="ri-camera-fill"></i>
                                             </span>
                                         </label>
                                     </div>
                                 </div>

                                 <h5 class="fs-14">Foto Barang Datang</h5>
                             </div>
                             <div>
                                 <label class="form-label" for="gen-info-description-input">Description</label>
                                 <textarea class="form-control" name="keterangan_barang_datang_parsial_1" placeholder="Enter Description"
                                     id="gen-info-description-input" rows="2" @if (!empty($master_spj->keterangan_barang_datang_parsial_1)) readonly @endif>
                                     {{ old('keterangan_barang_datang_parsial_1', is_array($master_spj->keterangan_barang_datang_parsial_1) ? implode(', ', $master_spj->keterangan_barang_datang_parsial_1) : $master_spj->keterangan_barang_datang_parsial_1) }}
                                    </textarea>
                             </div>
                         </div>
                         <div class="d-flex align-items-start gap-3 mt-4">
                             <button type="button" class="btn btn-link text-decoration-none btn-label previestab"
                                 data-previous="pills-gen-info-tab1"><i
                                     class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                 Back to General</button>
                             @if ($master_spj->status_proses_pengiriman_barang === 'Selesai')
                                 <button type="button"
                                     class="btn btn-outline-warning btn-load right ms-auto nexttab nexttab"
                                     data-nexttab="pills-success-tab"><span class="d-flex align-items-center">
                                         <span class="flex-grow-1 me-2">
                                             Loading...
                                         </span>
                                         <span class="spinner-grow flex-shrink-0" role="status">
                                             <span class="visually-hidden">Loading...</span>
                                         </span>
                                     </span></button>
                             @else
                                 <button type="submit"
                                     class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                     data-nexttab="pills-success-tab1"><i
                                         class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                             @endif

                         </div>
                     </div>
                     <!-- end tab pane -->

                     <div class="tab-pane fade" id="pills-success1" role="tabpanel"
                         aria-labelledby="pills-success-tab1">
                         <div>
                             <div class="text-center">

                                 <div class="mb-4">
                                     <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                         colors="primary:#0ab39c,secondary:#405189"
                                         style="width:120px;height:120px"></lord-icon>
                                 </div>
                                 <h5>Well Done !</h5>
                                 <p class="text-muted">You have Successfully Update Data</p>
                             </div>
                         </div>
                     </div>
                     <!-- end tab pane -->
                 </div>
                 <!-- end tab content -->
             </form>

         </div>
         <!-- end card body -->
     </div>
     <!-- end card -->
 </div>
