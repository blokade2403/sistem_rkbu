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
                                        <th>Name Aktivitas</th>
                                        <th>Kode Aktivitas</th>
                                        <th>Program</th>
                                        <th>Sub Kegiatan</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($aktivitas as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_aktivitas }}</span>
                                                <p>ID:{{ $item->id_aktivitas }}</p>
                                            </td>
                                            <td>{{ $item->kode_aktivitas }}</td>
                                            <td>{{ $item->program->kode_program }}
                                                {{ $item->program->nama_program }}</td>
                                            <td>{{ $item->sub_kegiatan->kode_sub_kegiatan }}
                                                {{ $item->sub_kegiatan->nama_sub_kegiatan }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('aktivitas.edit', $item->id_aktivitas)" :delete-url="route('aktivitas.destroy', $item->id_aktivitas)" />
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
