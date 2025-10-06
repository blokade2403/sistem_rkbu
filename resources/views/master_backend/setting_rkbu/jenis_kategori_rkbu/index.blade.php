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
                                        <th>Name Jenis Kategori RKBU</th>
                                        <th>Kode Jenis Kategori RKBU</th>
                                        <th>Jenis Belanja</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($jenis_kategori_rkbus as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_jenis_kategori_rkbu }}</span>
                                                <p>ID:{{ $item->id_jenis_kategori_rkbu }}</p>
                                            </td>
                                            <td>{{ $item->kode_jenis_kategori_rkbu }}</td>
                                            <td>{{ $item->jenis_belanja->kode_jenis_belanja }}
                                                {{ $item->jenis_belanja->nama_jenis_belanja }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route(
                                                    'jenis_kategori_rkbus.edit',
                                                    $item->id_jenis_kategori_rkbu,
                                                )" :delete-url="route(
                                                    'jenis_kategori_rkbus.destroy',
                                                    $item->id_jenis_kategori_rkbu,
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
