     <div class="card">
         <div class="card-header">
             <h4 class="sub-title">Data Detail Komponen Belanja</h4>
             <span></span>
             <div class="card-header-right">
                 <ul class="list-unstyled card-option">
                     <li><i class="feather icon-maximize full-card"></i></li>
                     <li><i class="feather icon-minus minimize-card"></i></li>
                 </ul>
             </div>
         </div>
         <div class="card-block">
             <div class="table-responsive mb-4 text-nowrap">
                 <table id="example" class="table table-striped">
                     <thead class="table-light border-top">
                         <tr>
                             <th>No</th>
                             <th>Nama Barang</th>
                             <th>Spesifikasi</th>
                             <th>Harga Satuan</th>
                             <th>Vol</th>
                             <th>PPN</th>
                             <th>Harga</th>
                             <th>Action</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($keranjang as $key => $item)
                             <tr>
                                 <td>{{ $key + 1 }}
                                 </td>
                                 <td class="text-wrap">
                                     <span class="text-primary f-16">#&nbsp;{{ $item->nama_barang }}</span><br />
                                     <span class="f-12">ID RKBU:
                                         {{ $item->id_rkbu }}</span>
                                 </td>
                                 <td class="text-wrap" style="white-space: normal; word-wrap: break-word;">
                                     <span class="fa-sm">{{ $item->spek_detail }}</span>
                                 </td>
                                 <td>Rp.
                                     {{ number_format($item->harga_barang, 0, ',', '.') }}
                                 </td>
                                 <td>{{ $item->jumlah_usulan_barang }}
                                     {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}
                                 </td>
                                 <td>Rp.
                                     {{ number_format(($item->harga_barang * $item->ppn) / 100, 0, ',', '.') }}
                                 </td>
                                 <td>Rp.
                                     {{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}
                                 </td>
                                 <td>
                                     <a href="{{ route($routePrefix . '.editkomponen', $item->id_usulan_barang_detail) }}"
                                         class="m-r-15 text-primary" data-toggle="tooltip" data-placement="top"
                                         title="Edit" data-original-title="Edit"><i
                                             class="icofont icofont-ui-edit"></i></a>
                                     <form action="{{ route($routePrefix . '.destroy', $item->id_spj_detail) }}"
                                         method="POST" style="display:inline;">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="text-danger"
                                             style="background: none; border: none; padding: 0; cursor: pointer;"
                                             onclick="return confirm('Yakin ingin hapus?')">
                                             <i class="icofont icofont-trash"></i>
                                         </button>
                                     </form>
                                 </td>
                             </tr>
                         @endforeach
                     </tbody>
                 </table>

                 <!-- Total -->
                 <div class="card-body">
                     <div class="row invoive-info">
                         <div class="col-md-9 col-xs-12 invoice-client-info">
                             <h6></h6>
                             <table>
                                 <tbody>
                                     <tr>
                                         <td colspan="4" class="align-top px-2 py-3">
                                             <p><span class="fw-semibold">Status
                                                     Sebelumnya:</span></p>
                                             <span class="badge bg-warning mb-3"></span>
                                             <span class="badge bg-success mb-3"></span>
                                             <span class="badge bg-info mb-3"></span>
                                             <p><span class="fw-semibold">Sub Kategori:
                                                     {{ $item->kode_sub_kategori_rkbu }}.{{ $item->nama_sub_kategori_rkbu }}</span>
                                             </p>
                                             <span>Tidak Ada File Pendukung</span><br />
                                             <span class="fa-xs">Tanggal Upload:
                                             </span>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                         <div class="col-md-3 col-sm-12">
                             <table>
                                 <tbody>
                                     <tr>
                                         <td class="text-end px-4 py-3">
                                             <p><span class="fw-semibold">Sub
                                                     Total:</span>
                                                 {{ number_format($get_total - $ppn, 0, ',', '.') }}
                                             </p>
                                             <p><span class="fw-semibold">PPN:</span>
                                                 {{ number_format($ppn, 0, ',', '.') }}
                                             </p>
                                             <p><span class="fw-semibold">Total:</span>
                                                 {{ number_format($get_total, 0, ',', '.') }}
                                             </p>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
