 <div class="row">
     <div class="col-xl-3 col-md-6">
         <!-- card -->
         <div class="card card-animate">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div class="flex-grow-1 overflow-hidden">
                         <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                             Total Belanja BLUD</p>
                     </div>
                     <div class="flex-shrink-0">
                         @if ($selisih_pagu >= 0)
                             <h5 class="text-success fs-12 mb-0">
                                 <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu), 0, ',', ',' }}
                             </h5>
                         @else
                             <h5 class="text-danger fs-12 mb-0">
                                 <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu), 0, ',', ',' }}
                             </h5>
                         @endif
                     </div>
                 </div>
                 <div class="d-flex align-items-end justify-content-between mt-4">
                     <div>
                         <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span>Rp.
                                 {{ number_format($total_anggaran_admin), 0, ',', ',' }}</span>
                         </h4>
                         <p class="fs-10 text-muted text-truncate mb-0">Pagu Belanja BLUD</p>
                         <span class="fs-12">Rp. {{ number_format($total_pagu), 0, ',', ',' }}</span>
                     </div>
                     <div class="avatar-sm flex-shrink-0">
                         <span class="avatar-title bg-success rounded fs-3">
                             <i class="bx bx-dollar-circle"></i>
                         </span>
                     </div>
                 </div>
             </div><!-- end card body -->
         </div><!-- end card -->
     </div><!-- end col -->

     <div class="col-xl-3 col-md-6">
         <!-- card -->
         <div class="card card-animate">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div class="flex-grow-1 overflow-hidden">
                         <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                             Belanja Pegawai</p>
                     </div>
                     <div class="flex-shrink-0">
                         @if ($selisih_pagu_pegawai >= 0)
                             <h5 class="text-success fs-12 mb-0">
                                 <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_pegawai), 0, ',', ',' }}
                             </h5>
                         @else
                             <h5 class="text-danger fs-12 mb-0">
                                 <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_pegawai), 0, ',', ',' }}
                             </h5>
                         @endif
                     </div>
                 </div>
                 <div class="d-flex align-items-end justify-content-between mt-4">
                     <div>
                         <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span>Rp.
                                 {{ number_format($total_anggaran_pegawai_admin), 0, ',', ',' }}</span>
                         </h4>
                         <p class="fs-10 text-muted text-truncate mb-0">Pagu Belanja Pegawai BLUD</p>
                         <span class="fs-12">Rp. {{ number_format($pagu_pegawai), 0, ',', ',' }}</span>
                     </div>
                     <div class="avatar-sm flex-shrink-0">
                         <span class="avatar-title bg-primary rounded-circle fs-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-users">
                                 <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                 <circle cx="9" cy="7" r="4"></circle>
                                 <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                 <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                             </svg>
                         </span>
                     </div>
                 </div>
             </div><!-- end card body -->
         </div><!-- end card -->
     </div><!-- end col -->

     <div class="col-xl-3 col-md-6">
         <!-- card -->
         <div class="card card-animate">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div class="flex-grow-1 overflow-hidden">
                         <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                             Belanja Barang Jasa</p>
                     </div>
                     <div class="flex-shrink-0">
                         @if ($selisih_pagu_barjas >= 0)
                             <h5 class="text-success fs-12 mb-0">
                                 <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_barjas), 0, ',', ',' }}
                             </h5>
                         @else
                             <h5 class="text-danger fs-12 mb-0">
                                 <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_barjas), 0, ',', ',' }}
                             </h5>
                         @endif
                     </div>
                 </div>
                 <div class="d-flex align-items-end justify-content-between mt-4">
                     <div>
                         <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span>Rp.
                                 {{ number_format($total_anggaran_barjas_admin), 0, ',', ',' }}</span>
                         </h4>
                         <p class="fs-10 text-muted text-truncate mb-0">Pagu Belanja Barjas BLUD</p>
                         <span class="fs-12">Rp.
                             {{ number_format($pagu_barjas), 0, ',', ',' }}</span>
                     </div>
                     <div class="avatar-sm flex-shrink-0">
                         <span class="avatar-title bg-danger rounded-circle fs-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-activity">
                                 <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                             </svg>
                         </span>
                     </div>
                 </div>
             </div><!-- end card body -->
         </div><!-- end card -->
     </div><!-- end col -->

     <div class="col-xl-3 col-md-6">
         <!-- card -->
         <div class="card card-animate">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <div class="flex-grow-1 overflow-hidden">
                         <p class="text-uppercase fw-medium text-muted text-truncate mb-0">
                             Belanja Modal</p>
                     </div>
                     <div class="flex-shrink-0">
                         @if ($selisih_pagu_modal >= 0)
                             <h5 class="text-success fs-12 mb-0">
                                 <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_modal), 0, ',', ',' }}
                             </h5>
                         @else
                             <h5 class="text-danger fs-12 mb-0">
                                 <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                 Rp. {{ number_format($selisih_pagu_modal), 0, ',', ',' }}
                             </h5>
                         @endif

                     </div>
                 </div>
                 <div class="d-flex align-items-end justify-content-between mt-4">
                     <div>
                         <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span>Rp.
                                 {{ number_format($total_anggaran_modal_admin), 0, ',', ',' }}</span>
                         </h4>
                         <p class="fs-10 text-muted text-truncate mb-0">Pagu Belanja Modal BLUD</p>
                         <span class="fs-12">Rp.
                             {{ number_format($pagu_modal), 0, ',', ',' }}</span>
                     </div>
                     <div class="avatar-sm flex-shrink-0">
                         <span class="avatar-title bg-danger rounded fs-3">
                             <i class="bx bx-wallet"></i>
                         </span>
                     </div>
                 </div>
             </div><!-- end card body -->
         </div><!-- end card -->
     </div><!-- end col -->
 </div> <!-- end row-->
