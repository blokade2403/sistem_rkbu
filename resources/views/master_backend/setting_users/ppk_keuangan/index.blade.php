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
                                        <th>Name PPK Keuangan</th>
                                        <th>Status</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($ppk_keuangans as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_ppk_keuangan }}</span>
                                                <p class="fs-12">NIP:&nbsp;{{ $item->nip_ppk_keuangan }}</p>
                                                <p class="fs-12">ID:&nbsp;{{ $item->id_ppk_keuangan }}</p>
                                            </td>
                                            <td class="text-center">
                                                @if ($item->status == 'aktif')
                                                    <span
                                                        class="badge badge-soft-success badge-border">{{ $item->status }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-danger badge-border">{{ $item->status }}</span>
                                                @endif

                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('ppk_keuangans.edit', $item->id_ppk_keuangan)" :delete-url="route('ppk_keuangans.destroy', $item->id_ppk_keuangan)" />
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
