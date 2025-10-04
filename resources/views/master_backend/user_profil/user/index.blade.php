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
                                        <th>Unit</th>
                                        <th>Level User</th>
                                        <th>Fase User</th>
                                        <th>Status User</th>
                                        <th>Status Edit</th>
                                        <th>Pendukung PPK</th>
                                        <th>Tagging PPTK</th>
                                        <th>Create At</th>
                                        <th>Action</th>
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
                                                        <h5>#{{ $item->username }}
                                                            ({{ $item->levelUser->nama_level_user }})
                                                        </h5>
                                                    </li>
                                                    <small class="text-muted">
                                                        <li class="py-0">Nama : {{ $item->nama_lengkap }}</li>
                                                        <li class="py-0">NIP : {{ $item->nip_user }}</li>
                                                    </small>
                                                </ul>

                                            </td>
                                            <td>{{ $item->ksp->nama_ksp ?? '-' }}</td>
                                            <td>{{ $item->pejabat->nama_pejabat ?? '-' }}</td>
                                            <td>{{ $item->unit->nama_unit ?? '-' }}</td>
                                            <td>{{ $item->levelUser->nama_level_user ?? '-' }}</td>
                                            <td>{{ $item->fase->nama_fase ?? '-' }}</td>
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
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('users.edit', $item->id_user)" :delete-url="route('users.destroy', $item->id_user)" />
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
