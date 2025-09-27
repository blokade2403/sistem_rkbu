<x-layouts.main class="main">
    <x-slot:title></x-slot:title>
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
                            <h4 class="sub-title">Cek Usulan Belanja</h4>
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="example" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <th>No</th>
                                            <th>ID SUB</th>
                                            <th witdh=100%>Detail Usulan</th>
                                            {{-- <th>Status Usulan</th> --}}
                                            <th>Status Proses</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($usulan_barangs as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16 text-primary">#&nbsp;{{ $item->no_usulan_barang }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-14">Nama Pengusul :
                                                                    {{ $item->nama_pengusul_barang }}</span>
                                                            </li>
                                                            <li>
                                                                <span>
                                                                    {{ $item->sub_kategori_rkbu->kode_sub_kategori_rkbu }}.
                                                                    {{ $item->sub_kategori_rkbu->nama_sub_kategori_rkbu }}
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        @if ($item->status_usulan_barang == 'Selesai')
                                                            <div class="br-wrapper br-theme-bars-movie">
                                                                <span class="f-12">Status Usulan</span>
                                                                <div class="br-widget">
                                                                    <a href="#" data-rating-value="Bad"
                                                                        data-rating-text="Bad" class="br-selected"></a>
                                                                    <a href="#" data-rating-value="Mediocre"
                                                                        data-rating-text="Mediocre"
                                                                        class="br-selected"></a>
                                                                    <a href="#" data-rating-value="Good"
                                                                        data-rating-text="Good" class="br-selected"></a>
                                                                    <div class="br-current-rating f-12"><label
                                                                            class="label label-inverse">Selesai</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    {{-- <td class="text-center align-middle">
                                                        <a href="{{ route('cek_usulan_barang_barjass.show', $item->id_usulan_barang) }}"
                                                            class="btn btn-out btn-md btn-primary btn-square">
                                                            <i class="icofont icofont-cart-alt"></i>Lihat Detail
                                                            Usulan
                                                        </a>
                                                    </td> --}}
                                                    <td class="text-center align-middle">
                                                        @include('cek_usulan_belanja.status_cek_usulan_belanja')
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        @include(
                                                            'cek_usulan_belanja.action_validasi_usulan_belanja',
                                                            [
                                                                'routePrefix' => $routePrefix,
                                                            ]
                                                        )
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.main>
