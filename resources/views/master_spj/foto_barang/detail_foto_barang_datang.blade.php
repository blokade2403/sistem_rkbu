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
                                             $master_spj->foto_barang_datang_1_parsial_1,
                                             $master_spj->foto_barang_datang_2_parsial_1,
                                             $master_spj->foto_barang_datang_3_parsial_1,
                                             $master_spj->foto_barang_datang_1_parsial_2,
                                             $master_spj->foto_barang_datang_2_parsial_2,
                                             $master_spj->foto_barang_datang_3_parsial_2,
                                             $master_spj->foto_barang_datang_1_parsial_3,
                                             $master_spj->foto_barang_datang_2_parsial_3,
                                             $master_spj->foto_barang_datang_3_parsial_3,
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

                     @php
                         $firstKeranjang = $keranjang->first();
                     @endphp

                     <div class="col-xl-8">
                         <div class="mt-xl-0 mt-5">
                             <div class="d-flex">
                                 <div class="flex-grow-1">
                                     <h4> {{ $firstKeranjang->no_usulan_barang ?? 'N/A' }}</h4>
                                     <div class="hstack gap-3 flex-wrap">
                                         <div><a href="#"
                                                 class="text-primary d-block">{{ $master_spj->pendukung_ppk->nama_pendukung_ppk ?? 'Belum update' }}</a>
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
                                         <li class="py-1"><Small>Tanggal Verifikasi : </Small> <span
                                                 class="badge badge-soft-info">
                                                 {{ $master_spj->tgl_verif_pengurus_barang ?? 'belum verif pengurus barang' }}</span>
                                         </li>
                                         <li class="py-1"><Small>Status Verifikasi : </Small><span
                                                 class="badge badge-soft-info">
                                                 {{ $master_spj->status_verifikasi_pengurus_barang ?? 'belum verif pengurus barang' }}</span>
                                         </li>
                                     </div>
                                 </div>
                             </div>

                             @if (session('nama_level_user') == 'Pengurus Barang')
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
                                                     <option
                                                         value="{{ $master_spj->status_verifikasi_pengurus_barang }}">
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
                                             <div class="text-end">
                                                 <a href="{{ route($routePrefix . '.index') }}"
                                                     class="btn btn-secondary btn-label waves-effect waves-light">
                                                     <div class="d-flex">
                                                         <div class="flex-shrink-0">
                                                             <i
                                                                 class="ri-reply-fill label-icon align-middle fs-16 me-2"></i>
                                                         </div>
                                                         <div class="flex-grow-1">
                                                             Kembali
                                                         </div>
                                                     </div>
                                                 </a>
                                                 @if ($master_spj->status_verifikasi_pengurus_barang === 'Selesai')
                                                     <button type="button"
                                                         class="btn btn-outline-warning btn-load right ms-auto nexttab nexttab"
                                                         data-nexttab="pills-success-tab"><span
                                                             class="d-flex align-items-center">
                                                             <span class="flex-grow-1 me-2">
                                                                 Loading...
                                                             </span>
                                                             <span class="spinner-grow flex-shrink-0" role="status">
                                                                 <span class="visually-hidden">Loading...</span>
                                                             </span>
                                                         </span></button>
                                                 @else
                                                     <button type="submit"
                                                         class="btn btn-success btn-label waves-effect waves-light"><i
                                                             class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                         Success</button>
                                                 @endif

                                             </div>
                                         </div>
                                     </div>
                                 </form>
                             @endif

                             <div class="product-content mt-5">
                                 <h5 class="fs-14 mb-3">Product Description :</h5>
                                 <nav>
                                     <ul class="nav nav-tabs nav-tabs-custom nav-success" id="nav-tab"
                                         role="tablist">
                                         <li class="nav-item">
                                             <a class="nav-link active" id="nav-speci-tab" data-bs-toggle="tab"
                                                 href="#nav-speci" role="tab" aria-controls="nav-speci"
                                                 aria-selected="true">Detail Kode Rekening</a>
                                         </li>
                                         <li class="nav-item">
                                             <a class="nav-link" id="nav-detail-tab" data-bs-toggle="tab"
                                                 href="#nav-detail" role="tab" aria-controls="nav-detail"
                                                 aria-selected="false">Data Penyedia</a>
                                         </li>
                                         <li class="nav-item">
                                             <a class="nav-link" id="nav-detail-bast" data-bs-toggle="tab"
                                                 href="#nav-bast" role="tab" aria-controls="nav-bast"
                                                 aria-selected="false">Data BAST</a>
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
                                     <div class="tab-pane fade" id="nav-detail" role="tabpanel"
                                         aria-labelledby="nav-detail-tab">
                                         <div>
                                             <h5 class="font-size-14 mb-3">
                                                 <span
                                                     class="text-info">{{ $master_spj->perusahaan?->nama_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span>
                                                 <p class="fs-12">
                                                     {{ $master_spj->perusahaan?->alamat_perusahaan ?? 'Data Perusahaan Belum di Input' }}&nbsp;

                                                 </p>
                                             </h5>
                                             <p class="text-danger"> Waktu Pekerjaan :
                                                 {{ $master_spj->tgl_surat_pesanan ?? 'Tanggal Belum di Update' }}
                                                 s/d
                                                 {{ $master_spj->jangka_waktu_pekerjaan ?? 'Tanggal Belum di Update' }}
                                             </p>
                                             <div>
                                                 <p class="mb-2"><i
                                                         class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                     {{ $master_spj->perusahaan?->jabatan_perusahaan ?? 'Data Perusahaan Belum di Input' }}&nbsp;:
                                                     {{ $master_spj->perusahaan?->nama_direktur_perusahaan ?? 'Data Perusahaan Belum di Input' }}
                                                 </p>
                                                 <p class="mb-2"><i
                                                         class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                     No NPWP :
                                                     {{ $master_spj->perusahaan?->no_npwp ?? 'Data Perusahaan Belum di Input' }}
                                                 </p>
                                                 <p class="mb-2"><i
                                                         class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                     Email :
                                                     {{ $master_spj->perusahaan?->email_perusahaan ?? 'Data Perusahaan Belum di Input' }}
                                                 </p>
                                                 <p class="mb-0"><i
                                                         class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                                     Telepon:
                                                     {{ $master_spj->perusahaan?->tlp_perusahaan ?? 'Data Perusahaan Belum di Input' }}&nbsp;
                                                 </p>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="tab-pane fade" id="nav-bast" role="tabpanel"
                                         aria-labelledby="nav-detail-bast">
                                         <div>

                                             <div class="table-responsive">
                                                 <table class="table mb-0">
                                                     <tbody>
                                                         <tr>
                                                             <th scope="row" style="width: 200px;">
                                                                 No BA Pemeriksaan Pekerjaan</th>
                                                             <td> {{ $master_spj->no_ba_bp ?? 'null' }}</td>
                                                         </tr>
                                                         <tr>
                                                             <th scope="row">No BA Serah Terima Pekerjaan</th>
                                                             <td>{{ $master_spj->no_ba ?? 'null' }}</td>
                                                         </tr>
                                                         <tr>
                                                             <th scope="row">No BA Pemeriksaan Barang/ Pekerjaan
                                                             </th>
                                                             <td>{{ $master_spj->no_ba_hp ?? 'null' }}</td>
                                                         </tr>
                                                         <tr>
                                                             <th scope="row">No DPA</th>
                                                             <td>{{ $master_spj->no_dpa ?? 'null' }}</td>
                                                         </tr>
                                                     </tbody>
                                                 </table>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <!-- end card body -->
                             @if ($master_spj->status_pembayaran == 'Sudah di Bayar')
                                 <div class="mt-5">
                                     <div>
                                         <h5 class="fs-14 mb-3">Status Pembayaran</h5>
                                     </div>
                                     <div class="row gy-4 gx-0">
                                         <div class="col-lg-4">
                                             <div>
                                                 <div class="pb-3">
                                                     <div class="alert alert-success alert-dismissible alert-additional shadow fade show"
                                                         role="alert">
                                                         <div class="alert-body">
                                                             <div class="d-flex">
                                                                 <div class="flex-shrink-0 me-3">
                                                                     <i
                                                                         class="ri-notification-off-line fs-16 align-middle"></i>
                                                                 </div>
                                                                 <div class="flex-grow-1">
                                                                     <h5 class="alert-heading">
                                                                         Rp.
                                                                         {{ number_format($master_spj->pembayaran, 0, ',', ',') }},-
                                                                     </h5>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                         <div class="alert-content">
                                                             <p class="mb-0"> <small>Pembayaran <span
                                                                         class="fw-medium">Tanggal: </span>
                                                                     {{ \Carbon\Carbon::parse($master_spj->tanggal_pembayaran)->translatedFormat('d F Y') }}
                                                                 </small></p>
                                                         </div>
                                                     </div>
                                                 </div>

                                                 <div class="mt-3">
                                                     <div class="row align-items-center g-2">
                                                         <div class="col-auto">
                                                             <div class="p-2">
                                                                 <h6 class="mb-0">5 star</h6>
                                                             </div>
                                                         </div>
                                                         <div class="col">
                                                             <div class="p-2">
                                                                 <div
                                                                     class="progress bg-soft-success animated-progress progress-sm">
                                                                     <div class="progress-bar bg-success"
                                                                         role="progressbar" style="width: 100%"
                                                                         aria-valuenow="80.16" aria-valuemin="0"
                                                                         aria-valuemax="100"></div>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                         <div class="col-auto">
                                                             <div class="p-2">
                                                                 <h6 class="mb-0 text-muted">100%</h6>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <!-- end row -->
                                                 </div>
                                             </div>
                                         </div>
                                         <!-- end col -->

                                         <div class="col-lg-8">
                                             <div class="ps-lg-4">
                                                 <div class="d-flex flex-wrap align-items-start gap-3">
                                                     <h5 class="fs-14">Reviews: </h5>
                                                 </div>

                                                 <div class="me-lg-n3 pe-lg-4" data-simplebar
                                                     style="max-height: 225px;">
                                                     <ul class="list-unstyled mb-0">
                                                         <li class="py-2">
                                                             <div class="border border-dashed rounded p-3">
                                                                 <div class="d-flex align-items-start mb-3">
                                                                     <div class="hstack gap-3">
                                                                         <div
                                                                             class="badge rounded-pill bg-success mb-0">
                                                                             <i class="mdi mdi-star"></i>
                                                                             {{ $no_inv->nama_sumber_dana }}
                                                                         </div>
                                                                         <div class="vr"></div>
                                                                         <div class="flex-grow-1">
                                                                             <p class="text-muted mb-0">
                                                                                 {{ $master_spj->status_pembayaran }}.
                                                                             </p>
                                                                         </div>
                                                                     </div>
                                                                 </div>

                                                                 <div class="d-flex flex-grow-1 gap-2 mb-3">
                                                                     @if (!empty($master_spj->bukti_bayar))
                                                                         <a href="{{ asset('storage/' . $master_spj->bukti_bayar) }}"
                                                                             target="_blank"
                                                                             class="badge bg-primary-subtle text-primary text-decoration-none">
                                                                             Lihat Bukti Pembayaran
                                                                         </a>
                                                                     @else
                                                                         <span
                                                                             class="badge bg-secondary-subtle text-muted">Tidak
                                                                             Ada Bukti Pembayaran</span>
                                                                     @endif
                                                                 </div>

                                                                 <div class="d-flex align-items-end">
                                                                     <div class="flex-grow-1">
                                                                         <h5 class="fs-14 mb-0">
                                                                             Bendahara:
                                                                             {{ $master_spj->nama_validasi_keuangan }}
                                                                         </h5>
                                                                     </div>

                                                                     <div class="flex-shrink-0">
                                                                         <p class="text-muted fs-13 mb-0">
                                                                             Bulan Pembayaran :
                                                                             <span class="badge bg-info">
                                                                                 {{ $master_spj->bulan_pembayaran }}</span>
                                                                         </p>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </li>
                                                     </ul>
                                                 </div>
                                             </div>
                                         </div>
                                         <!-- end col -->
                                     </div>
                                     <!-- end Ratings & Reviews -->
                                 </div>
                             @endif
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
