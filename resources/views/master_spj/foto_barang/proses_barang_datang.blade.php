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
             <h4 class="card-title mb-0">Proses Barang Datang</h4>
         </div>

         <div class="card-body form-steps">
             @php
                 $status = $master_spj->status_proses_pengiriman_barang;

                 $isParsial1 = $status === 'Parsial 1';
                 $isParsial2 = $status === 'Parsial 2';
                 $isParsial3 = $status === 'Parsial 3';

                 // Tentukan action form berdasarkan status
                 $formAction = match (true) {
                     $isParsial1 => route('master_spjs.UpdateParsial1', $master_spj->id_master_spj),
                     $isParsial2 => route('master_spjs.UpdateParsial2', $master_spj->id_master_spj),
                     $isParsial3 => route('master_spjs.UpdateParsial3', $master_spj->id_master_spj),
                     default => route('master_spjs.updateBarangDatang', $master_spj->id_master_spj),
                 };

                 // Tentukan suffix field
                 $suffix = $isParsial1
                     ? '_parsial_1'
                     : ($isParsial2
                         ? '_parsial_2'
                         : ($isParsial3
                             ? '_parsial_3'
                             : ''));

                 // Ambil data field sesuai status
                 $foto1 = $master_spj->{'foto_barang_datang_1' . $suffix};
                 $foto2 = $master_spj->{'foto_barang_datang_2' . $suffix};
                 $foto3 = $master_spj->{'   ' . $suffix};
                 $keterangan = $master_spj->{'keterangan_barang_datang' . $suffix};
             @endphp

             <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 @method('PUT')

                 <div class="text-center pt-3 pb-4 mb-1">
                     <h5>Verifikasi Barang Datang</h5>
                 </div>

                 {{-- Progress Bar --}}
                 <div id="custom-progress-bar" class="progress-nav mb-4">
                     <div class="progress" style="height: 1px;">
                         <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                             aria-valuemin="0" aria-valuemax="100"></div>
                     </div>

                     <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill active" data-progressbar="custom-progress-bar"
                                 id="pills-gen-info-tab" data-bs-toggle="pill" data-bs-target="#pills-gen-info"
                                 type="button" role="tab" aria-controls="pills-gen-info"
                                 aria-selected="true">1</button>
                         </li>
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                 id="pills-info-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-info-desc"
                                 type="button" role="tab" aria-controls="pills-info-desc"
                                 aria-selected="false">2</button>
                         </li>
                         <li class="nav-item" role="presentation">
                             <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                 id="pills-success-tab" data-bs-toggle="pill" data-bs-target="#pills-success"
                                 type="button" role="tab" aria-controls="pills-success"
                                 aria-selected="false">3</button>
                         </li>
                     </ul>
                 </div>

                 <div class="tab-content">
                     {{-- TAB 1 --}}
                     <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel"
                         aria-labelledby="pills-gen-info-tab">
                         <div>
                             <h5 class="mb-1">Serah Terima Barang Datang</h5>
                             <p class="text-muted">Proses dokumentasi barang datang</p>
                         </div>

                         <div class="row">
                             <div class="col-lg-12">
                                 <div class="mb-3">
                                     <label class="form-label">Status Barang Datang</label>
                                     <select name="status_proses_pengiriman_barang" class="form-select"
                                         {{ $status == 'Selesai' ? 'disabled' : '' }}>
                                         <option value="{{ $status }}">{{ $status ?? 'Pilih Status' }}</option>

                                         {{-- Disable Parsial sebelumnya --}}
                                         <option value="Parsial 1"
                                             {{ in_array($status, ['Parsial 1', 'Parsial 2', 'Parsial 3', 'Selesai']) ? 'disabled' : '' }}>
                                             Parsial 1</option>
                                         <option value="Parsial 2"
                                             {{ in_array($status, ['Parsial 2', 'Parsial 3', 'Selesai']) ? 'disabled' : '' }}>
                                             Parsial 2</option>
                                         <option value="Parsial 3"
                                             {{ in_array($status, ['Parsial 3', 'Selesai']) ? 'disabled' : '' }}>Parsial
                                             3</option>
                                         <option value="Selesai" {{ $status == 'Selesai' ? 'disabled' : '' }}>Selesai
                                         </option>
                                     </select>

                                     @if ($status == 'Selesai')
                                         <input type="hidden" name="status_proses_pengiriman_barang"
                                             value="{{ $status }}">
                                     @endif
                                 </div>
                             </div>
                         </div>

                         <div class="d-flex align-items-start gap-3 mt-4">
                             <button type="button" class="btn btn-success btn-label right ms-auto nexttab"
                                 data-nexttab="pills-info-desc-tab">
                                 <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i> Lanjut
                             </button>
                         </div>
                     </div>

                     {{-- TAB 2 --}}
                     <div class="tab-pane fade" id="pills-info-desc" role="tabpanel"
                         aria-labelledby="pills-info-desc-tab">
                         <div class="text-center">
                             @for ($i = 1; $i <= 3; $i++)
                                 @php
                                     $fotoVar = ${'foto' . $i};
                                 @endphp
                                 <div class="profile-user position-relative d-inline-block mx-2 mb-2">
                                     <img id="preview-img-{{ $i }}"
                                         src="{{ $fotoVar ? asset('storage/' . $fotoVar) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                         class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                         alt="Foto Barang Datang {{ $i }}">

                                     <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                         <input id="profile-img-file-input-{{ $i }}"
                                             name="foto_barang_datang{{ $i > 1 ? "_{$i}" : '' }}{{ $suffix }}"
                                             type="file" class="profile-img-file-input"
                                             accept="image/png, image/jpeg">
                                         <label for="profile-img-file-input-{{ $i }}"
                                             class="profile-photo-edit avatar-xs">
                                             <span class="avatar-title rounded-circle bg-light text-body">
                                                 <i class="ri-camera-fill"></i>
                                             </span>
                                         </label>
                                     </div>
                                 </div>
                             @endfor

                             <h5 class="fs-14 mt-3">Foto Barang Datang</h5>
                         </div>

                         <div class="mt-3">
                             <label class="form-label" for="desc-input">Keterangan Barang</label>
                             <textarea class="form-control" id="desc-input" name="keterangan_barang_datang{{ $suffix }}" rows="3"
                                 placeholder="Tuliskan keterangan barang datang..." @if (!empty($keterangan)) readonly @endif>{{ old('keterangan_barang_datang', $keterangan) }}</textarea>
                         </div>

                         <div class="d-flex align-items-start gap-3 mt-4">
                             <button type="button" class="btn btn-link text-decoration-none btn-label previestab"
                                 data-previous="pills-gen-info-tab">
                                 <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Kembali
                             </button>

                             @if ($status === 'Selesai')
                                 <button type="button" class="btn btn-outline-warning btn-load right ms-auto nexttab"
                                     data-nexttab="pills-success-tab">
                                     <span class="d-flex align-items-center">
                                         <span class="flex-grow-1 me-2">Loading...</span>
                                         <span class="spinner-grow flex-shrink-0" role="status"></span>
                                     </span>
                                 </button>
                             @else
                                 <button type="submit" class="btn btn-success btn-label right ms-auto nexttab"
                                     data-nexttab="pills-success-tab">
                                     <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i> Submit
                                 </button>
                             @endif
                         </div>
                     </div>

                     {{-- TAB 3 --}}
                     <div class="tab-pane fade" id="pills-success" role="tabpanel"
                         aria-labelledby="pills-success-tab">
                         <div class="text-center">
                             <div class="mb-4">
                                 <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                     colors="primary:#0ab39c,secondary:#405189"
                                     style="width:120px;height:120px"></lord-icon>
                             </div>
                             <h5>Berhasil!</h5>
                             <p class="text-muted">Data Barang Datang berhasil diperbarui.</p>
                         </div>
                     </div>
                 </div>
             </form>
         </div>



     </div>

     <!-- end card -->
 </div>
