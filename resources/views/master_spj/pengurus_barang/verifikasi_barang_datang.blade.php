     <div class="col-lg-12">
         @include('partials.head_form')
         <div class="card">
             <div class="card-header">
                 <h4 class="card-title mb-0">Proses Verifikasi Barang Datang</h4>
             </div><!-- end card header -->
             <div class="card-body">
                 <div class="row gx-lg-5">
                     <div class="col-xl-4 col-md-8 mx-auto">
                         <div class="product-img-slider sticky-side-div">
                             <!-- Swiper utama -->
                             <div class="swiper product-thumbnail-slider p-2 rounded bg-light">
                                 <div class="swiper-wrapper">
                                     @php
                                         $images = [
                                             $master_spj->foto_barang_datang,
                                             $master_spj->foto_barang_datang_2,
                                             $master_spj->foto_barang_datang_3,
                                         ];
                                     @endphp

                                     @forelse ($images as $img)
                                         @if ($img)
                                             <div class="swiper-slide">
                                                 <img src="{{ asset('storage/' . $img) }}" alt="Foto Barang Datang"
                                                     class="img-fluid d-block rounded" />
                                             </div>
                                         @endif
                                     @empty
                                         <div class="swiper-slide">
                                             <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                 alt="Foto Default" class="img-fluid d-block rounded" />
                                         </div>
                                     @endforelse
                                 </div>

                                 <div class="swiper-button-next bg-white shadow"></div>
                                 <div class="swiper-button-prev bg-white shadow"></div>
                             </div>

                             <!-- Swiper navigasi -->
                             <div class="swiper product-nav-slider mt-2">
                                 <div class="swiper-wrapper">
                                     @forelse ($images as $img)
                                         @if ($img)
                                             <div class="swiper-slide">
                                                 <div class="nav-slide-item">
                                                     <img src="{{ asset('storage/' . $img) }}" alt="Thumbnail"
                                                         class="img-fluid d-block rounded" />
                                                 </div>
                                             </div>
                                         @endif
                                     @empty
                                         <div class="swiper-slide">
                                             <div class="nav-slide-item">
                                                 <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}"
                                                     alt="Default Thumbnail" class="img-fluid d-block rounded" />
                                             </div>
                                         </div>
                                     @endforelse
                                 </div>
                             </div>
                         </div>

                     </div>
                     <!-- end col -->

                     <div class="col-xl-8">
                         <div class="mt-xl-0 mt-5">
                             <div class="d-flex">
                                 <div class="flex-grow-1">
                                     <h4>{{ $no_inv->no_usulan_barang }}</h4>
                                     <div class="hstack gap-3 flex-wrap">
                                         <div><a href="#"
                                                 class="text-primary d-block">{{ $master_spj->pendukung_ppk->nama_pendukung_ppk }}</a>
                                         </div>
                                         <div class="vr"></div>
                                         <div class="text-muted">ID Paket : <span
                                                 class="text-body fw-medium">{{ $master_spj->idpaket }}</span>
                                         </div>
                                         <div class="vr"></div>
                                         <div class="text-muted">Update Foto : <span
                                                 class="text-body fw-medium">{{ $master_spj->tgl_barang_datang ?? 'Belum Update' }}</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="row mt-4">
                                 <div class="col-lg-4 col-sm-6">
                                     <div class="p-2 border border-dashed rounded">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm me-2">
                                                 <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                     <i class="ri-money-dollar-circle-fill"></i>
                                                 </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                 <p class="text-muted mb-1">Total Anggaran :</p>
                                                 <h5 class="mb-0">{{ number_format($get_total, 0, ',', '.') }}</h5>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <!-- end col -->
                                 <div class="col-lg-4 col-sm-6">
                                     <div class="p-2 border border-dashed rounded">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm me-2">
                                                 <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                     <i class="ri-file-copy-2-fill"></i>
                                                 </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                 <p class="text-muted mb-1">Tgl. Surat Pesanan :</p>
                                                 <h5 class="mb-0">{{ $master_spj->tgl_surat_pesanan }}</h5>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <!-- end col -->
                                 <div class="col-lg-4 col-sm-6">
                                     <div class="p-2 border border-dashed rounded">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm me-2">
                                                 <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                     <i class="ri-stack-fill"></i>
                                                 </div>
                                             </div>
                                             <div class="flex-grow-1">
                                                 <p class="text-muted mb-1">No Surat Pesanan :</p>
                                                 <h5 class="mb-0">{{ $master_spj->no_surat_pesanan }}</h5>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <!-- end col -->
                             </div>

                             <div class="mt-4 text-muted">
                                 <h5 class="fs-14">Catatan :</h5>
                                 <p>{{ $master_spj->keterangan_barang_datang ?? 'Tidak ada keterangan barang datang.' }}
                                 </p>
                             </div>

                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="mt-3">
                                         <h5 class="fs-14">Rincian Belanja :</h5>
                                         <ul class="list-unstyled">
                                             <li class="py-1"><i
                                                     class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                 {{ $master_spj->rincian_belanja }}</li>
                                         </ul>
                                     </div>
                                 </div>
                                 <div class="col-sm-6">
                                     <div class="mt-3">
                                         <h5 class="fs-14">Validasi Pengurus Barang :</h5>
                                         <ul class="list-unstyled product-desc-list">
                                             <li class="py-1"><Small>Tanggal Verifikasi : </Small> <span
                                                     class="badge badge-soft-info">
                                                     {{ $master_spj->tgl_verif_pengurus_barang ?? 'belum verif pengurus barang' }}</span>
                                             </li>
                                             <li class="py-1"><Small>Status Verifikasi : </Small><span
                                                     class="badge badge-soft-info">
                                                     {{ $master_spj->status_verifikasi_pengurus_barang ?? 'belum verif pengurus barang' }}</span>
                                             </li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>

                             <form action="{{ route('master_spjs.updateVerifPB', $master_spj->id_master_spj) }}"
                                 method="POST" enctype="multipart/form-data">
                                 @csrf
                                 @method('PUT')
                                 <div class="row">
                                     <div class="col-md-2"></div>
                                     <div class="col-md-8">
                                         <div class="mb-3">
                                             <label class="form-label" for="gen-info-email-input">Status Barang
                                                 Datang</label>
                                             <select name="status_verifikasi_pengurus_barang" id="perusahaan"
                                                 data-choices data-choices-sorting="true"
                                                 @if (!empty($master_spj->status_verifikasi_pengurus_barang)) disabled @endif>
                                                 <option value="{{ $master_spj->status_verifikasi_pengurus_barang }}">
                                                     {{ $master_spj->status_verifikasi_pengurus_barang ?? 'Pilih Status' }}
                                                 </option>
                                                 <option value="Selesai">Selesai</option>
                                                 <option value="Revisi">Revisi</option>
                                             </select>

                                             {{-- hidden input supaya value tetap terkirim jika select di-disable --}}
                                             @if (!empty($master_spj->status_verifikasi_pengurus_barang))
                                                 <input type="hidden" name="status_verifikasi_pengurus_barang"
                                                     value="{{ $master_spj->status_verifikasi_pengurus_barang }}">
                                             @endif
                                         </div>
                                     </div>
                                     <div class="col-md-2"></div>
                                 </div>
                                 <div class="row mb-3">
                                     <div class="col-md-2"></div>
                                     <div class="col-md-8">
                                         <label class="form-label"
                                             for="gen-info-description-input">Description</label>
                                         <textarea class="form-control" name="keterangan_verif_pengurus_barang" placeholder="Enter Description"
                                             id="gen-info-description-input" rows="2" @if (!empty($master_spj->keterangan_verif_pengurus_barang)) readonly @endif>
                                     {{ old('keterangan_verif_pengurus_barang', is_array($master_spj->keterangan_verif_pengurus_barang) ? implode(', ', $master_spj->keterangan_verif_pengurus_barang) : $master_spj->keterangan_verif_pengurus_barang) }}
                                    </textarea>
                                     </div>
                                     <div class="col-md-2"></div>
                                 </div>
                                 <div class="row">
                                     <div class="col-md-10">
                                         @include('partials.submit_buttons')
                                     </div>
                                 </div>
                             </form>

                             <div class="product-content mt-5">
                                 <h5 class="fs-14 mb-3">Rincian Kode Rekening :</h5>
                                 <nav>
                                     <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab"
                                         role="tablist">
                                         <li class="nav-item">
                                             <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab"
                                                 href="#nav-speci" role="tab" aria-controls="nav-speci"
                                                 aria-selected="true">Specification</a>
                                         </li>
                                     </ul>
                                 </nav>
                                 <div class="tab-content border border-top-0 p-4" id="nav-tabContent">
                                     <div class="tab-pane fade show active" id="nav-speci" role="tabpanel"
                                         aria-labelledby="nav-speci-tab">
                                         <div class="table-responsive">
                                             <table class="table mb-0">
                                                 <tbody>
                                                     <tr>
                                                         <th scope="row" style="width: 200px;">
                                                             Rekening Belanja</th>
                                                         <td> {{ $no_inv->kode_rekening_belanja }}.
                                                             {{ $no_inv->nama_rekening_belanja }}</td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Kategori</th>
                                                         <td>{{ $no_inv->kode_kategori_rkbu }}.
                                                             {{ $no_inv->nama_kategori_rkbu }}</td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Sub Kategori</th>
                                                         <td> {{ $no_inv->kode_sub_kategori_rkbu }}.
                                                             {{ $no_inv->nama_sub_kategori_rkbu }}</td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Jenis Belanja</th>
                                                         <td>{{ $no_inv->kode_jenis_kategori_rkbu }}.
                                                             {{ $no_inv->nama_jenis_kategori_rkbu }}</td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Sumber Dana</th>
                                                         <td>{{ $no_inv->nama_sumber_dana }}</td>
                                                     </tr>
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <!-- product-content -->


                             <!-- end card body -->
                         </div>
                     </div>
                     <!-- end col -->
                 </div>
                 <!-- end row -->
             </div>
             <!-- end card body -->
         </div>
         <!-- end card -->
     </div>
     <!-- end col -->
