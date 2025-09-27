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
                                <a href="{{ route('ksps.create') }}" class="btn btn-sm btn-primary btn-outline-primary"><i
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
                                            <th>Name KSP</th>
                                            <th>Jabatan</th>
                                            <th>Atasan</th>
                                            <th>Status</th>
                                            <th>Create At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($ksps as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="f-16">#&nbsp;{{ $item->nama_ksp }}</span>
                                                        </li>
                                                        <li><span class="f-12">NIP:{{ $item->nip_ksp }}</span>
                                                        </li>
                                                        <li><span class="f-12">ID:{{ $item->id_ksp }}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>{{ $item->fungsional->jabatan_fungsional }}</td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span
                                                                class="f-16">#&nbsp;{{ $item->pejabat->nama_pejabat }}</span>
                                                        </li>
                                                        <li><span
                                                                class="f-12">NIP:{{ $item->pejabat->nip_pejabat }}</span>
                                                        </li>
                                                        <li><span class="f-12">ID:{{ $item->id_pejabat }}</span>
                                                        </li>
                                                    </ul>
                                                </td>
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
                                                    <x-partials.action-buttons :edit-url="route('ksps.edit', $item->id_ksp)" :delete-url="route('ksps.destroy', $item->id_ksp)" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Name KSP</th>
                                            <th>Jabatan</th>
                                            <th>Atasan</th>
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

</x-layouts.main>
