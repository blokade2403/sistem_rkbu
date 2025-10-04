 <ul class="list-inline hstack gap-2 mb-0">
     <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
         <a href="{{ route($routePrefix . '.show', $item->id_usulan_barang) }}" class="text-primary d-inline-block">
             <i class="ri-eye-fill fs-16"></i>
         </a>
     </li>
     <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
         title="Edit">
         <a href="javascript:void(0)" class="text-primary d-inline-block edit-item-btn"
             data-id="{{ $item->id_usulan_barang }}" data-no="{{ $item->no_usulan_barang }}"
             data-kategori="{{ $item->sub_kategori_rkbu->kode_sub_kategori_rkbu }}. {{ $item->sub_kategori_rkbu->nama_sub_kategori_rkbu }}"
             data-pengusul="{{ $item->nama_pengusul_barang }}" data-status="{{ $item->status_permintaan_barang }}"
             {{-- data-total="{{ $get_total }}" --}} data-route="{{ route($routePrefix . '.updateValidasi', $item->no_usulan_barang) }}"
             data-bs-toggle="modal" data-bs-target="#showModal">
             <i class="ri-pencil-fill fs-16"></i>
         </a>
     </li>
     <li class="list-inline-item remove" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
         title="Remove">
         <a data-bs-toggle="modal" href="#deleteOrder" class="text-danger d-inline-block remove-item-btn">
             <i class="ri-delete-bin-5-fill fs-16"></i>
         </a>
     </li>
 </ul>
