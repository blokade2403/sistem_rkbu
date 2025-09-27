<x-layouts.main class="main">
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-header start -->
                <x-partials.breadcrumb>
                </x-partials.breadcrumb>
                <!-- Page-header end -->
                <!-- Page-body start -->
                <div class="page-body">
                    <!-- Basic row reorder table start -->
                    <div class="card">

                        <div class="card-header">
                            @include('usulan_belanja.partials.buttonusulan_belanja', [
                                'routePrefix' => $routePrefix,
                            ])
                            @include('usulan_belanja.partials.modal_sub_kategori_usulan_belanja')
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="responsive-reorder" class="table table-striped table-bordered wrap">
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
                                                                <a href="#" data-rating-value="Bad"
                                                                    data-rating-text="Bad" class="br-selected"></a>
                                                                <a href="#" data-rating-value="Mediocre"
                                                                    data-rating-text="Mediocre" class="br-selected"></a>
                                                                <a href="#" data-rating-value="Good"
                                                                    data-rating-text="Good" class=""></a>
                                                                <a href="#" data-rating-value="Awesome"
                                                                    data-rating-text="Awesome" class=""></a>
                                                                <div class="br-current-rating f-12"><label
                                                                        class="label label-inverse">{{ $key->status_usulan_barang }}
                                                                    </label>
                                                                    @if ($key->status_validasi_direktur == 'Revisi')
                                                                        <span class="f-12"><label
                                                                                class="label label-warning">{{ $key->status_validasi_direktur }}
                                                                                :
                                                                                {{ $key->keterangan_direktur }}</label></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button type="button"
                                                            class="btn btn-out btn-mini btn-primary btn-square"
                                                            data-toggle="modal"
                                                            data-target="#large-Modal{{ $keyIndex }}"
                                                            data-indextable="{{ $keyIndex }}"
                                                            data-id_usulan_barang="{{ $key->id_usulan_barang }}"><i
                                                                class="fa fa-plus"></i>Tambah Komponen
                                                        </button>

                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <a href="{{ route($routePrefix . '.keranjang', $key->id_usulan_barang) }}"
                                                            class="btn btn-out btn-mini btn-warning btn-square">
                                                            <i class="icofont icofont-cart-alt"></i>Lihat Komponen
                                                        </a>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if ($key->usulanBarangDetails->isEmpty())
                                                            <form
                                                                action="{{ route($routePrefix . '.delete', $key->id_usulan_barang) }}"
                                                                method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-muted"
                                                                    style="background: none; border: none; padding: 0; cursor: pointer;"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" data-original-title="Delete"><i
                                                                        class="icofont icofont-trash"></i></button>
                                                            </form>
                                                        @else
                                                            <a href="#"
                                                                class="btn btn-icon btn-outline-danger waves-effect disabled-link"
                                                                style="background: none; border: none; padding: 0; cursor: pointer;"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                title=""
                                                                data-original-title="Hapus Terlebih dahulu Data Detailnya">
                                                                <i class="icofont icofont-ui-close"></i>
                                                            </a>
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
                                        <tfoot>
                                            <th>No</th>
                                            <th>Usulan Barang</th>
                                            <th>Tambah Barang</th>
                                            <th>Status</th>
                                            <th witdh=100%>Detail Usulan</th>
                                            <th>Action</th>
                                            <th>Sub Kategori</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('usulan_belanja.partials.modal_komponen_usulan_barang')
</x-layouts.main>
