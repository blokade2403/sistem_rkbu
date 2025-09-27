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
                                <a href="{{ route('kategori_rekenings.create') }}"
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
                            <div class="dt-responsive table-responsive">
                                <table id="basic-row-reorder" class="table table-striped table-bordered nowrap">

                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name Kategori Rekening</th>
                                            <th>Kode Kategori Rekening</th>
                                            <th>Create At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($kategori_rekenings as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span
                                                                class="f-16">#&nbsp;{{ $item->nama_kategori_rekening }}</span>
                                                        </li>
                                                        <li><span
                                                                class="f-12">ID:{{ $item->id_kategori_rekening }}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>{{ $item->kode_kategori_rekening }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <x-partials.action-buttons :edit-url="route(
                                                        'kategori_rekenings.edit',
                                                        $item->id_kategori_rekening,
                                                    )" :delete-url="route(
                                                        'kategori_rekenings.destroy',
                                                        $item->id_kategori_rekening,
                                                    )" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Name Kategori Rekening</th>
                                            <th>Kode Kategori Rekening</th>
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

</x-layouts.main>
