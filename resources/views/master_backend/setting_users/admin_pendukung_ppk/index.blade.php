@extends('layouts.main')
@section('content')
    @include('partials.add_buttons_backend', ['routePrefix' => $routePrefix])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                @include('partials.animate_progress')
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-block">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Name Pendukung PPK</th>
                                        <th>Jabatan Pendukung PPK</th>
                                        <th>Pejabat Pengadaan</th>
                                        <th>PPTK</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($admin_pendukungs as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span
                                                    class="fs-16 text-primary">#&nbsp;{{ $item->nama_pendukung_ppk }}</span>
                                                <p class="fs-12">NRK:{{ $item->nrk }}</p>
                                                <p class="fs-12">ID:{{ $item->id_admin_pendukung_ppk }}</p>
                                            </td>
                                            <td>{{ $item->jabatan_pendukung_ppk }}</td>
                                            <td>
                                                {{ $item->pejabat_pengadaan->nama_ppk }}
                                                <p>NRK:&nbsp;{{ $item->pejabat_pengadaan->nip_ppk }}</p>
                                                <p>ID:&nbsp;{{ $item->id_pejabat_pengadaan }}</p>
                                            </td>
                                            <td>
                                                {{ $item->pptk->nama_pptk }}
                                                <p>NRK:&nbsp;{{ $item->pptk->nip_pptk }}</p>
                                                <p>ID:&nbsp;{{ $item->id_pptk }}</p>
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route(
                                                    'admin_pendukungs.edit',
                                                    $item->id_admin_pendukung_ppk,
                                                )" :delete-url="route(
                                                    'admin_pendukungs.destroy',
                                                    $item->id_admin_pendukung_ppk,
                                                )" />
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
@endsection
