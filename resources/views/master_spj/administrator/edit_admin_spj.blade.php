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
               <h4 class="card-title mb-0">Administrator Set</h4>
           </div><!-- end card header -->
           <div class="card-body">
               <p class="text-muted">Use <code>nav-border-top nav-justified</code> class to create nav tabs with border
                   at top
                   with justified tabs position.</p>
               <!-- Nav tabs -->
               <ul class="nav nav-tabs nav-justified nav-border-top nav-border-top-success mb-3" role="tablist">
                   <li class="nav-item">
                       <a class="nav-link active" data-bs-toggle="tab" href="#nav-border-justified-surat-pesanan"
                           role="tab" aria-selected="true">
                           <i class="ri-home-5-line align-middle me-1"></i> SP
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-profile" role="tab"
                           aria-selected="false">
                           <i class="ri-user-line me-1 align-middle"></i> BAST
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Tukar Faktur
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages2" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf PPK
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages3" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf PPBJ
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages4" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf PPTK
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages5" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf Verifikator
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages6" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf PPK Keu.
                       </a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" data-bs-toggle="tab" href="#nav-border-justified-messages7" role="tab"
                           aria-selected="false">
                           <i class="ri-question-answer-line align-middle me-1"></i>Verf Direktur
                       </a>
                   </li>
               </ul>
               <div class="tab-content text-muted">
                   <div class="tab-pane active" id="nav-border-justified-surat-pesanan" role="tabpanel">
                       @include('master_spj.administrator.form_surat_pesanan')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-profile" role="tabpanel">
                       @include('master_spj.administrator.form_bast')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages" role="tabpanel">
                       @include('master_spj.administrator.form_tukar_faktur')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages2" role="tabpanel">
                       @include('master_spj.ppk.form_validasi_ppk')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages3" role="tabpanel">
                       @include('master_spj.ppbj.form_validasi_ppbj')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages4" role="tabpanel">
                       @include('master_spj.pptk.form_validasi_pptk')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages5" role="tabpanel">
                       @include('master_spj.verifikator.form_validasi_verifikator')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages6" role="tabpanel">
                       @include('master_spj.ppk_keuangan.form_validasi_ppk_keuangan')
                   </div>
                   <div class="tab-pane" id="nav-border-justified-messages7" role="tabpanel">
                       @include('master_spj.direktur.form_validasi_direktur')
                   </div>
               </div>
           </div><!-- end card-body -->
       </div>
   </div>
