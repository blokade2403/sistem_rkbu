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
                            @include('usulan_spj.partials.modal_invoice_usulan_spj')
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="example" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <th>No</th>
                                            <th>ID Sub</th>
                                            <th>Nomor Usulan Barang</th>
                                            <th>Detail Usulan</th>
                                            <th>Tambah Barang</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($id_usulan as $keyIndex => $key)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td class="text-wrap f-12"
                                                        style="white-space: normal; word-wrap: break-word;">
                                                        <br />{{ $key->subKategoriRkbu->kode_sub_kategori_rkbu }}.
                                                        {{ $key->subKategoriRkbu->nama_sub_kategori_rkbu }}
                                                    </td>
                                                    <td class="f-14 text-nowrap">
                                                        <strong><span
                                                                class="f-16 text-primary">{{ $key->no_usulan_barang }}</span>
                                                        </strong><br />
                                                        <span><small>Pengusul :
                                                                {{ $key->nama_pengusul_barang }}</small>
                                                        </span></br>
                                                        <span><small>Tanggal Usulan :
                                                                {{ $key->created_at }}</small>
                                                        </span>
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
                                                        <a href="{{ route($routePrefix . '.show', $key->id_spj) }}"
                                                            class="btn btn-out btn-mini btn-warning btn-square">
                                                            <i class="icofont icofont-cart-alt"></i>Lihat Komponen
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @include('usulan_spj.partials.status_usulan_spj')
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @if ($key->spjDetail->isEmpty())
                                                            <form
                                                                action="{{ route('usulan_spj_barjass.destroy', $key->id_spj) }}"
                                                                method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="m-r-15 text-danger"
                                                                    data-toggle="tooltip" data-bs-placement="bottom"
                                                                    title="Hapus Usulan SPJ">
                                                                    <span
                                                                        class="tf-icons mdi mdi-delete-forever mt-0"><i
                                                                            class="icofont icofont-trash"></i></span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="#" class="m-r-15 text-danger"
                                                                data-toggle="tooltip" data-bs-placement="bottom"
                                                                title="Hapus Terlebih dahulu Datanya Detailnya">
                                                                <i class="icofont icofont-warning-alt"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <th>No</th>
                                            <th>ID Sub</th>
                                            <th>Nomor Usulan Barang</th>
                                            <th>Detail Usulan</th>
                                            <th>Tambah Barang</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
    @include('usulan_spj.partials.modal_komponen_usulan_spj')
    @include('usulan_spj.partials.modal_usulan_spj_no_usulan_barang')
</x-layouts.main>
