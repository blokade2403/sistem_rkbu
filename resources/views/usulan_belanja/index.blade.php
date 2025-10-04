@extends('layouts.main')
@section('content')
    @include('partials.add_buttons_belanja', ['routePrefix' => $routePrefix])
    @include('usulan_belanja.partials.modal_sub_kategori_usulan_belanja')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-block">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="responsive-reorder"
                                class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead>
                                    <th>No</th>
                                    <th>Usulan Barang</th>
                                    <th>Tambah Barang</th>
                                    <th>Status</th>
                                    <th witdh=100%>Detail Usulan</th>
                                    <th>Action</th>
                                    <th>Sub Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($id_usulan as $keyIndex => $key)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span
                                                            class="f-16 text-primary">#&nbsp;{{ $key->no_usulan_barang }}</span>
                                                    </li>
                                                    <li>
                                                        <span class="f-14">Pengusul :
                                                            {{ $key->nama_pengusul_barang }}</span></br>
                                                        <span class="f-14">Tanggal :
                                                            {{ $key->created_at }}</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <div class="br-wrapper br-theme-bars-movie">
                                                    <span class="f-12">Status Usulan</span>
                                                    <div class="br-widget">
                                                        <a href="#" data-rating-value="Bad" data-rating-text="Bad"
                                                            class="br-selected"></a>
                                                        <a href="#" data-rating-value="Mediocre"
                                                            data-rating-text="Mediocre" class="br-selected"></a>
                                                        <a href="#" data-rating-value="Good" data-rating-text="Good"
                                                            class=""></a>
                                                        <a href="#" data-rating-value="Awesome"
                                                            data-rating-text="Awesome" class=""></a>
                                                        <div class="br-current-rating f-12">
                                                            <span
                                                                class="badge badge-soft-info">{{ $key->status_usulan_barang }}
                                                            </span>
                                                            @if ($key->status_validasi_direktur == 'Revisi')
                                                                <span
                                                                    class="badge badge-soft-danger fs-110">{{ $key->status_validasi_direktur }}
                                                                    :
                                                                    {{ $key->keterangan_direktur }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target=".bs-example-modal-xl"
                                                    class="btn btn-primary btn-label waves-effect waves-light"
                                                    data-toggle="modal" data-target="#large-Modal{{ $keyIndex }}"
                                                    data-indextable="{{ $keyIndex }}"
                                                    data-id_usulan_barang="{{ $key->id_usulan_barang }}">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="ri-shield-cross-line label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            add data
                                                        </div>
                                                    </div>
                                                </button>

                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="{{ route($routePrefix . '.keranjang', $key->id_usulan_barang) }}"
                                                    class="btn btn-info btn-label waves-effect waves-light">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="ri-eye-close-fill label-icon align-middle fs-16 me-2"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            lihat keranjang
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="text-center align-middle">

                                                @if ($key->usulanBarangDetails->isEmpty())
                                                    <form
                                                        action="{{ route($routePrefix . '.delete', $key->id_usulan_barang) }}"
                                                        method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-label waves-effect waves-light"><i
                                                                class="ri-delete-bin-2-line label-icon align-middle fs-16 me-2 "></i>
                                                            Delete</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger btn-load">
                                                        <span class="d-flex align-items-center">
                                                            <span class="flex-grow-1 me-2">
                                                                Loading...
                                                            </span>
                                                            <span class="spinner-grow flex-shrink-0" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </span>
                                                        </span>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="f-16 text-wrap">
                                                <div class="br-wrapper br-theme-bars-movie">
                                                    {{ $key->sub_kategori_rkbu->kode_sub_kategori_rkbu }}.
                                                    {{ $key->sub_kategori_rkbu->nama_sub_kategori_rkbu }}
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- .card-->
        </div> <!-- .col-->
    </div> <!-- end row-->
    @include('usulan_belanja.partials.modal_komponen_usulan_barang')
    <!--  Extra Large modal example -->
@endsection
