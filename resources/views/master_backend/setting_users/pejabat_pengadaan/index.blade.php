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
                            <h5>Form Input</h5>
                            <span></span>
                            <div class="card-block">
                                <a href="{{ route('pejabat_pengadaans.create') }}"
                                    class="btn btn-sm btn-primary btn-outline-primary"><i
                                        class="icofont icofont-plus-square"></i>Tambah Data</a>
                                <button class="btn btn-sm btn-success btn-outline-success"><i
                                        class="icofont icofont-check-circled"></i>Success Button</button>
                                <button class="btn btn-sm btn-info btn-outline-info"><i
                                        class="icofont icofont-info-square"></i>Info Button</button>
                            </div>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="feather icon-maximize full-card"></i></li>
                                    <li><i class="feather icon-minus minimize-card"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="res-config" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Pejabat Pengadaan</th>
                                                <th>PPBJ</th>
                                                <th>Pengurus Barang</th>
                                                <th>Bendahara</th>
                                                <th>Direktur</th>
                                                <th>PPK Keuangan</th>
                                                <th>Tahun Anggaran</th>
                                                <th>Status</th>
                                                <th>Create At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pejabat_pengadaans as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-16">#&nbsp;{{ $item->nama_ppk }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->nip_ppk }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">ID:&nbsp;{{ $item->id_pejabat_pengadaan }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16">#&nbsp;{{ $item->nama_ppbj }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->nip_ppbj }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16">#&nbsp;{{ $item->nama_pengurus_barang }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->nip_pengurus_barang }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16">#&nbsp;{{ $item->nama_bendahara }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->nip_bendahara }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16">#&nbsp;{{ $item->nama_direktur }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->nip_direktur }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16">#&nbsp;{{ $item->ppk_keuangan->nama_ppk_keuangan }}</span>
                                                            </li>
                                                            <li><span
                                                                    class="f-12">NIP:&nbsp;{{ $item->ppk_keuangan->nip_ppk_keuangan }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>{{ $item->TahunAnggaran->nama_tahun_anggaran }}</td>
                                                    <td>
                                                        @if ($item->status == 'aktif')
                                                            <label
                                                                class="label label-inverse-primary">{{ $item->status }}</label>
                                                        @else
                                                            <label
                                                                class="label label-inverse-danger">{{ $item->status }}</label>
                                                        @endif

                                                    </td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>
                                                        <x-partials.action-buttons :edit-url="route(
                                                            'pejabat_pengadaans.edit',
                                                            $item->id_pejabat_pengadaan,
                                                        )"
                                                            :delete-url="route(
                                                                'pejabat_pengadaans.destroy',
                                                                $item->id_pejabat_pengadaan,
                                                            )" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Pejabat Pengadaan</th>
                                                <th>PPBJ</th>
                                                <th>Pengurus Barang</th>
                                                <th>Bendahara</th>
                                                <th>Direktur</th>
                                                <th>PPK Keuangan</th>
                                                <th>Tahun Anggaran</th>
                                                <th>Status</th>
                                                <th>Create At</th>
                                                <th>Action</th>
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

</x-layouts.main>
