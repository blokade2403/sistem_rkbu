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
                            <table id="responsive-reorder" class="table align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Name Sub Kategori RKBU</th>
                                        <th>Kode Sub Kategori RKBU</th>
                                        <th>Kategori RKBU</th>
                                        <th>Sub Kategori Rekening</th>
                                        <th>Rekening Belanja</th>
                                        <th>Admin Pendukung PPK</th>
                                        <th>PPTK</th>
                                        <th>Status</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($sub_kategori_rkbus as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span
                                                            class="f-16">#&nbsp;{{ $item->nama_sub_kategori_rkbu }}</span>
                                                    </li>
                                                    <li><span class="f-12">ID:{{ $item->id_sub_kategori_rkbu }}</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>{{ $item->kode_sub_kategori_rkbu }}</td>
                                            <td>{{ $item->kategori_rkbu->kode_kategori_rkbu }}
                                                {{ $item->kategori_rkbu->nama_kategori_rkbu }}</td>
                                            <td>{{ $item->sub_kategori_rekening->kode_sub_kategori_rekening }}
                                                {{ $item->sub_kategori_rekening->nama_sub_kategori_rekening }}
                                            </td>
                                            <td>{{ $item->rekening_belanja->kode_rekening_belanjas }}
                                                {{ $item->rekening_belanja->nama_rekening_belanjas }}
                                            </td>
                                            <td>{{ $item->admin_pendukung->nama_pendukung_ppk }}</td>
                                            <td>{{ $item->pptks->nama_pptk ?? '-' }}</td>
                                            <td>
                                                @if ($item->status == 'aktif')
                                                    <label class="label label-inverse-primary">{{ $item->status }}</label>
                                                @else
                                                    <label class="label label-inverse-danger">{{ $item->status }}</label>
                                                @endif

                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route(
                                                    'sub_kategori_rkbus.edit',
                                                    $item->id_sub_kategori_rkbu,
                                                )" :delete-url="route(
                                                    'sub_kategori_rkbus.destroy',
                                                    $item->id_sub_kategori_rkbu,
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
