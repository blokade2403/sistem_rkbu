 @if ($errors->any())
     <div class="alert alert-danger alert-dismissible alert-additional shadow fade show mb-xl-3" role="alert">
         <div class="alert-body">
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             <div class="d-flex">
                 <div class="flex-shrink-0 me-3">
                     <i class="ri-alert-line fs-16 align-middle"></i>
                 </div>
                 <div class="flex-grow-1">
                     <h5 class="alert-heading">Perhatian!</h5>
                     <p class="mb-0">Ada kesalahan pada input anda.</p>
                 </div>
             </div>
         </div>
         <div class="alert-content">
             <ul class="mb-0">
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     </div>
 @endif
