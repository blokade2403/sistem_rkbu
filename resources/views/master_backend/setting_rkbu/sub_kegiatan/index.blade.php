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
                                        <th>Name Sub Kegiatan</th>
                                        <th>Kode Sub Kegiatan</th>
                                        <th>Tujuan</th>
                                        <th>Indikator</th>
                                        <th>Sumber Dana</th>
                                        <th>Kegiatan</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($sub_kegiatans as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_sub_kegiatan }}</span>
                                                <p>ID:{{ $item->id_sub_kegiatan }}</p>
                                            </td>
                                            <td>{{ $item->kode_sub_kegiatan }}</td>
                                            <td>{{ $item->tujuan_sub_kegiatan }}</td>
                                            <td>{{ $item->indikator_sub_kegiatan }}</td>
                                            <td>{{ $item->sumber_danas->nama_sumber_dana }}</td>
                                            <td>{{ $item->kegiatan->kode_kegiatan }}
                                                {{ $item->kegiatan->nama_kegiatan }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('sub_kegiatans.edit', $item->id_sub_kegiatan)" :delete-url="route('sub_kegiatans.destroy', $item->id_sub_kegiatan)" />
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
