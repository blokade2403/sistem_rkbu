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
                        <div class="card-body">

                            <div class="row align-items-center">
                                <div class="col-md-1"></div>
                                <div class="col-md-2 mt-4 mb-3">
                                    <img src="{{ asset('storage/judul_header/' . basename($gambar2)) }}" width="105"
                                        height="75" class="img-fluid" />
                                </div>
                                <div class="col-md-6 text-center">
                                    <p class="mt-4 mb-1">{{ $judul_header1 }}</p>
                                    <h4 class="mt-1 mb-1">{{ $nama }}</h4>
                                    <p class="mb-1">{{ $alamat }} Telepon: {{ $tlp }}
                                        Faksimile: {{ $tlp }}</p>
                                    <p class="mt-1">Website: {{ $website }} | Email:
                                        {{ $email }}</p>
                                </div>
                                <div class="col-md-2 mt-4 mb-3 text-end">
                                    <img src="{{ asset('storage/judul_header/' . basename($gambar1)) }}" width="145"
                                        height="105" class="img-fluid" />
                                </div>
                            </div>

                            <hr>

                            <!-- Judul -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h5 class="fw-bold my-2">DATA USULAN BELANJA BARANG / JASA</h5>
                                </div>
                            </div>
                            <div class="card-block">
                                <div class="row invoive-info">
                                    <div class="col-md-4 col-xs-12 invoice-client-info">
                                        <h6>Kategori Usulan :</h6>
                                        <p class="m-0 m-t-10">{{ $no_inv->nama_sub_kategori_rkbu ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <h6>Information :</h6>
                                        <table
                                            class="table table-responsive invoice-table invoice-order table-borderless">
                                            <tbody>
                                                <tr>
                                                    <th>Tanggal:</th>
                                                    <td> {{ $no_inv->created_at ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pengusul :</th>
                                                    <td>{{ $no_inv->nama_pengusul_barang ?? 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <h6 class="m-b-20">Invoice :
                                            <span class="">
                                                #<strong>{{ $no_inv->no_usulan_barang ?? 'N/A' }}</strong></span>
                                        </h6>
                                        <h6 class="text-uppercase text-primary">Total Usulan :
                                            <span>Rp.{{ number_format($get_total ?? collect($keranjang)->sum('total_anggaran_usulan_barang'), 0, ',', '.') }}</span>
                                        </h6>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <div class="dt-responsive table-responsive">
                                                <table id="res-config" class="table table-bordered">
                                                    <thead class="table-light border-top">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Vol</th>
                                                            <th>PPN</th>
                                                            <th>Harga</th>
                                                            <th>Action</th>
                                                            <th>Spesifikasi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($keranjang as $key => $item)
                                                            <tr>
                                                                <td class="text-nowrap">
                                                                    {{ $key + 1 }}
                                                                </td>
                                                                <td class="text-wrap">
                                                                    <span
                                                                        class="text-primary f-16">#&nbsp;{{ $item->nama_barang }}</span><br />
                                                                    <span class="f-12">ID RKBU:
                                                                        {{ $item->id_rkbu }}</span>
                                                                </td>

                                                                <td>Rp.{{ number_format($item->harga_barang, 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ $item->jumlah_usulan_barang }}
                                                                    {{ $item->satuan_1_detail }}/{{ $item->satuan_2_detail }}
                                                                </td>
                                                                <td>Rp.{{ number_format(($item->harga_barang * $item->ppn) / 100, 0, ',', '.') }}
                                                                </td>
                                                                <td>Rp.{{ number_format($item->total_anggaran_usulan_barang, 0, ',', '.') }}
                                                                </td>
                                                                <td class="action-icon d-flex align-items-center">
                                                                    @include(
                                                                        'validasi_usulan_belanja.action_edit_keranjang_usulan_belanja',
                                                                        [
                                                                            'routePrefix' => $routePrefix,
                                                                        ]
                                                                    )
                                                                </td>
                                                                <td class="fy-2 text-wrap"><span
                                                                        class="fa-sm">{{ \Illuminate\Support\Str::limit($item->spek_detail, 150, '...') }}</span>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
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
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-responsive invoice-table invoice-total">
                                            <tbody>
                                                <tr>
                                                    <th>Sub Total :</th>
                                                    <td> {{ number_format($get_total - $ppn, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>PPN :</th>
                                                    <td> {{ number_format($ppn, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr class="text-info">
                                                    <td>
                                                        <hr>
                                                        <h5 class="text-primary">Total :</h5>
                                                    </td>
                                                    <td>
                                                        <hr>
                                                        <h5 class="text-primary">
                                                            {{ number_format($get_total, 0, ',', '.') }}</h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        @include('validasi_usulan_belanja.formvalidasiusulanbelanja', [
                                            'routePrefix' => $routePrefix,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        @include('validasi_usulan_belanja.action_keranjang_cek_usulan_belanja', [
                            'routePrefix' => $routePrefix,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</x-layouts.main>
