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
                            <table id="responsive-reorder"
                                class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>KSP</th>
                                        <th>Atasan</th>
                                        <th>Fase User</th>
                                        <th>Action</th>
                                        <th>Status User</th>
                                        <th>Status Edit</th>
                                        <th>Pendukung PPK</th>
                                        <th>Tagging PPTK</th>
                                        <th>Create At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <ul class="list-unstyled product-desc-list">
                                                    <li class="py-0">
                                                        <h6 class="mb-0"><span
                                                                class="fa-sm text-primary">{{ $item->unit->nama_unit }}</span></strong>
                                                        </h6>
                                                    </li>
                                                    <small class="text-muted">
                                                        <span class="text-muted">Status User :
                                                            {!! $item->status_user === 'aktif'
                                                                ? '<span class="badge bg-success">Aktif</span>'
                                                                : '<span class="badge bg-danger">Non Aktif</span>' !!}</span>
                                                    </small>
                                                </ul>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/products/img-1.png') }}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                    <div class="flex-1">
                                                        <h6 class="mt-0 mb-1 fs-14">
                                                            <a href="apps-ecommerce-product-details.html"
                                                                class="text-reset">{{ $item->nama_lengkap }}
                                                                ({{ $item->levelUser->nama_level_user }})
                                                            </a>
                                                        </h6>
                                                        <p class="mb-0 fs-12 text-muted">
                                                            Username: <span>{{ $item->username }}</span></br>
                                                            <small>{{ $item->id_user }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->ksp->nama_ksp ?? '-' }}</td>
                                            <td>{{ $item->pejabat->nama_pejabat ?? '-' }}</td>
                                            <td>{{ $item->fase->nama_fase ?? '-' }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('users.edit', $item->id_user)" :delete-url="route('users.destroy', $item->id_user)" />
                                            </td>
                                            <td>
                                                @if ($item->status_user == 'aktif')
                                                    <span
                                                        class="badge badge-soft-success badge-border">{{ $item->status_user }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-danger badge-border">{{ $item->status_user }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->status_edit }}</td>
                                            <td>{{ $item->nama_pendukung_ppk ?: 'Tidak di Tagging' }}</td>
                                            <td>{{ $item->pptks->nama_pptk ?? 'Tidak di Tagging' }}</td>
                                            <td>{{ $item->created_at }}</td>

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
