 <h5>Form Input</h5>
 <div class="card-block">
     @if (session('status_edit') === 'aktif' && session('nama_level_user') === 'User')
         <a href="{{ route($routePrefix . '.create') }}" class="btn btn-sm btn-mat btn-primary"><i
                 class="icofont icofont-plus-square"></i>Tambah Data</a>
     @endif
     <a href="" class="btn btn-sm btn-mat btn-warning"><i class="icofont icofont-download"></i>Download</a>
     <a href="{{ route($routePrefix . '.downloadPDF') }}" class="btn btn-sm btn-mat btn-info"><i
             class="icofont icofont-print"></i>Print</a>
 </div>
