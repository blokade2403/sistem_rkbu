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
                                        <th>Detail Perusahaan</th>
                                        <th>Telp</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                        <th>Create At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($perusahaans as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <ul class="list-unstyled product-desc-list">
                                                    <li class="py-0">
                                                        <h6 class="mb-0"><span
                                                                class="fa-sm text-primary">{{ $item->nama_perusahaan }}</span></strong>
                                                        </h6>
                                                    </li>
                                                    <small class="text-muted">
                                                        <span class="text-muted">NPWP :
                                                            {{ $item->no_npwp ?? '-' }}</span>
                                                    </small>
                                                </ul>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/products/img-1.png') }}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                    <div class="flex-1">
                                                        <h6 class="mt-0 mb-1 fs-14">
                                                            <a href="apps-ecommerce-product-details.html"
                                                                class="text-reset">{{ $item->nama_direktur_perusahaan }}
                                                                ({{ $item->jabatan_perusahaan }})
                                                            </a>
                                                        </h6>
                                                        <p class="mb-0 fs-12 text-muted">
                                                            Email: <span>{{ $item->email_perusahaan }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->tlp_perusahaan ?? '-' }}</td>
                                            <td>{{ $item->alamat_perusahaan ?? '-' }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $item->id_perusahaan)" :delete-url="route($routePrefix . '.destroy', $item->id_perusahaan)" />
                                            </td>
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
