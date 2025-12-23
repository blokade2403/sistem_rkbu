@extends('layouts.main')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card" id="demo">
                <div class="card-header border-bottom-dashed p-4">
                    <div class="d-sm-flex">
                        <div class="flex-grow-1">
                            <img src="{{ asset('storage/judul_header/' . basename($gambar1)) }}" width="105" height="75"
                                class="img-fluid" />
                            <img src="assets/images/logo-light.png" class="card-logo card-logo-light" alt="logo light"
                                height="17">
                            <div class="mt-sm-5 mt-4">
                                <h6 class="text-muted text-uppercase fw-semibold">Alamat</h6>
                                <p class="text-muted mb-1">{{ $alamat }} </p>
                                <p class="text-muted mb-0">Telepon: {{ $tlp }}
                                    Faksimile: {{ $tlp }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 mt-sm-0 mt-3">
                            <h6><span class="text-muted fw-normal">Email:</span> {{ $email }}</h6>
                            <h6><span class="text-muted fw-normal">Website:</span> <a href="javascript:void(0);"
                                    class="link-primary">{{ $website }}</a></h6>
                            <h6 class="mb-0"><span class="text-muted fw-normal">Contact No:</span> {{ $tlp }}
                            </h6>
                        </div>
                    </div>
                </div><!--end card-header-->
                <div class="row row-cols-md-3 row-cols-1">
                    <div class="col col-lg border-end">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Invoice No <i
                                    class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-space-ship-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span>#{{ $no_inv->no_usulan_barang ?? 'N/A' }}</span></h2>

                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col col-lg border-end">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Sub Kategori <i
                                    class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-1">
                                    </br>
                                    <strong>
                                        <p class="fs-14 mb-0 text-primary">
                                            {{ $no_inv->kode_sub_kategori_rkbu . ' ' . $no_inv->nama_sub_kategori_rkbu ?? 'N/A' }}
                                        </p>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col col-lg border-end">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Tanggal Usulan <i
                                    class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-pulse-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0"><span>{{ $no_inv->created_at ?? 'N/A' }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col col-lg">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">Total Usulan <i
                                    class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i>
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-service-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0">
                                        <span>Rp.
                                            {{ number_format($get_total ?? collect($keranjang)->sum('total_anggaran_usulan_barang'), 0, ',', ',') }}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="responsive-reorder"
                            class="table table-hover table-centered align-middle table-nowrap mb-0">
                            <thead class="table-light border-top">
                                <tr>
                                    <th>No</th>
                                    @php
                                        $isPegawai = $routePrefix === 'usulan_pegawai';
                                    @endphp

                                    @if ($isPegawai)
                                        <th>Nama Pegawai</th>
                                        <th>Remunerasi</th>
                                        <th>Vol</th>
                                        <th>Total Anggaran</th>
                                        <th>Action</th>
                                        <th>Jabatan</th>
                                    @else
                                        <th>Nama Barang</th>
                                        <th>Harga Satuan</th>
                                        <th>Vol</th>
                                        <th>PPN</th>
                                        <th>Total Anggaran</th>
                                        <th>Action</th>
                                        <th>Spesifikasi</th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keranjang as $key => $item)
                                    <tr>
                                        <td class="text-nowrap">
                                            {{ $key + 1 }}
                                        </td>
                                        @php
                                            $isPegawai = $routePrefix === 'usulan_pegawai';
                                        @endphp

                                        @if ($isPegawai)
                                            <td class="text-wrap">
                                                <span
                                                    class="text-primary f-16">#&nbsp;{{ $item->nama_pegawai }}</span><br />
                                                <span class="f-12">ID RKBU:
                                                    {{ $item->id_rkbu }}</span>
                                            </td>
                                            <td>Rp.{{ number_format($item->remunerasi, 0, ',', '.') }} </td>
                                            <td>{{ $item->jumlah_usulan_barang }}
                                                {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}
                                            </td>
                                            <td>Rp.{{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}
                                            </td>
                                        @else
                                            <td class="text-wrap">
                                                <span
                                                    class="text-primary f-16">#&nbsp;{{ $item->nama_barang }}</span><br />
                                                <span class="f-12">ID RKBU:
                                                    {{ $item->id_rkbu }}</span>
                                            </td>

                                            <td>Rp.{{ number_format($item->harga_barang, 0, ',', '.') }} </td>
                                            <td>{{ $item->jumlah_usulan_barang }}
                                                {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}
                                            </td>
                                            <td>Rp.{{ number_format(($item->harga_barang * $item->ppn) / 100, 0, ',', '.') }}
                                            </td>
                                            <td>Rp.{{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}
                                            </td>
                                        @endif

                                        <td class="action-icon d-flex align-items-center">
                                            <div class="hstack gap-2 ">
                                                <a href="{{ route($routePrefix . '.edit', $item->id_usulan_barang_detail) }}"
                                                    type="button" class="btn btn-primary btn-icon waves-effect waves-light"
                                                    data-toggle="tooltip" data-placement="top" title=""
                                                    data-original-title="Edit"><i class="ri-map-pin-line"></i></a>
                                                <form
                                                    action="{{ route($routePrefix . '.delete_detail', $item->id_usulan_barang_detail) }}"
                                                    method="POST" class="delete-form mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                            class="ri-delete-bin-5-line"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="fy-2 text-wrap"><span
                                                class="fa-sm">{{ \Illuminate\Support\Str::limit($item->spek_detail, 150, '...') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-end">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 w-auto ms-auto">
                                <tbody>
                                    <tr>
                                        <td class="text-start text-muted">Sub Total</td>
                                        <td class="text-end fw-medium">
                                            {{ number_format($get_total - $ppn, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start text-muted">PPN</td>
                                        <td class="text-end fw-medium">
                                            {{ number_format($ppn, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="text-start text-dark fw-semibold">Total</td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($get_total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <p class="mb-0">
                                <span class="fw-semibold">NOTES:</span> {{ $catatan ?? 'Tidak Ada Catatan' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-end">
                        <form action="{{ route($routePrefix . '.updateStatus', $invoice->id_usulan_barang) }}"
                            method="POST" id="confirmSubmitForm" enctype="multipart/form-data" validate>
                            @csrf
                            <a href="{{ route($routePrefix . '.index') }}"
                                class="btn btn-secondary btn-label waves-effect waves-light">
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
                                    class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Kirim</button>
                        </form>
                    </div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->
@endsection
