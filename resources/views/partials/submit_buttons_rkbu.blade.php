 <div class="text-end">
     @if ($faseTahun === 'Penetapan')
         <button type="button" class="btn btn-success btn-label waves-effect waves-light" data-bs-toggle="modal"
             data-bs-target=".bs-example-modal-xl"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
             Save changes</button>
         <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary btn-label waves-effect waves-light">
             <div class="d-flex">
                 <div class="flex-shrink-0">
                     <i class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                 </div>
                 <div class="flex-grow-1">
                     Kembali
                 </div>
             </div>
         </a>
     @else
         <button type="submit" class="btn btn-success btn-label waves-effect waves-light"><i
                 class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save changes</button>
         <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary btn-label waves-effect waves-light">
             <div class="d-flex">
                 <div class="flex-shrink-0">
                     <i class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                 </div>
                 <div class="flex-grow-1">
                     Kembali
                 </div>
             </div>
         </a>
     @endif
     {{-- <a href="{{ route($routePrefix . '.index') }}" class="btn btn-secondary btn-label waves-effect waves-light">
         <div class="d-flex">
             <div class="flex-shrink-0">
                 <i class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
             </div>
             <div class="flex-grow-1">
                 Kembali
             </div>
         </div>
     </a>
     <button type="submit" class="btn btn-success btn-label waves-effect waves-light"><i
             class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Success</button> --}}
 </div>
