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
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Nama Instansi</th>
                                        <th style="width: 10%;">Tlp</th>
                                        <th style="width: 5%;">Wilayah</th>
                                        <th style="width: 10%;">Kode Pos</th>
                                        <th>Header 1</th>
                                        <th>Header 2</th>
                                        <th>Header 3</th>
                                        <th>Header 4</th>
                                        <th>Header 5</th>
                                        <th>Header 6</th>
                                        <th style="width: 7%;">Alamat</th>
                                        <th>Gambar 1</th>
                                        <th>Gambar 2</th>
                                        <th>Gambar 3</th>
                                        <th>Gambar 4</th>
                                        <th>Gambar 5</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($judul_headers as $judul_headers)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#{{ $judul_headers->nama_rs }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $judul_headers->tlp_rs }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->wilayah }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->kode_pos }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header2 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header3 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header4 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header5 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">{{ $judul_headers->header6 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $judul_headers->alamat_rs }}</span>
                                            </td>
                                            <td>
                                                @if (!empty($judul_headers->gambar1))
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar1)) }}"
                                                            alt="Gambar 1" width="140" height="120"
                                                            class="rounded avatar-xl shadow">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($judul_headers->gambar2))
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar2)) }}"
                                                            alt="Gambar 1" width="120" height="120"
                                                            class="rounded avatar-xl shadow">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($judul_headers->header7))
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/judul_header/' . basename($judul_headers->header7)) }}"
                                                            alt="Gambar 1" width="120" height="120"
                                                            class="rounded avatar-xl shadow">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($judul_headers->gambar3))
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar3)) }}"
                                                            alt="Gambar 1" width="120" height="120"
                                                            class="rounded avatar-xl shadow">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($judul_headers->gambar4))
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar4)) }}"
                                                            alt="Gambar 1" width="120" height="120"
                                                            class="rounded avatar-xl shadow">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('judul_headers.edit', $judul_headers->id_judul_header)" :delete-url="route(
                                                    'judul_headers.destroy',
                                                    $judul_headers->id_judul_header,
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


{{-- <x-layouts.main class="main">
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
                                <a href="{{ route('judul_headers.create') }}"
                                    class="btn btn-sm btn-primary btn-outline-primary"><i
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
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="res-config" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 4%;">No</th>
                                                <th style="width: 10%;">Nama</th>
                                                <th style="width: 10%;">Tlp</th>
                                                <th style="width: 5%;">Wilayah</th>
                                                <th style="width: 10%;">Kode Pos</th>
                                                <th>Header 1</th>
                                                <th>Header 2</th>
                                                <th>Header 3</th>
                                                <th>Header 4</th>
                                                <th>Header 5</th>
                                                <th>Header 6</th>
                                                <th style="width: 7%;">Alamat</th>
                                                <th>Gambar 1</th>
                                                <th>Gambar 2</th>
                                                <th>Gambar 3</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($judul_headers as $judul_headers)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        <span class="fs-12">#{{ $judul_headers->nama_rs }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">#&nbsp;{{ $judul_headers->tlp_rs }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->wilayah }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->kode_pos }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header1 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header2 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header3 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header4 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header5 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fs-12">{{ $judul_headers->header6 }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fs-12">#&nbsp;{{ $judul_headers->alamat_rs }}</span>
                                                    </td>
                                                    <td>
                                                        @if (!empty($judul_headers->gambar1))
                                                            <div class="mt-2">
                                                                <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar1)) }}"
                                                                    alt="Gambar 1" width="140" height="120"
                                                                    class="mt-1 border rounded">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($judul_headers->gambar2))
                                                            <div class="mt-2">
                                                                <img src="{{ asset('storage/judul_header/' . basename($judul_headers->gambar2)) }}"
                                                                    alt="Gambar 1" width="120" height="120"
                                                                    class="mt-1 border rounded">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <li>
                                                            <span class="fs-12">{{ $judul_headers->header7 }}
                                                            </span>
                                                        </li>
                                                    </td>
                                                    <td>
                                                        <x-partials.action-buttons :edit-url="route(
                                                            'judul_headers.edit',
                                                            $judul_headers->id_judul_header,
                                                        )"
                                                            :delete-url="route(
                                                                'judul_headers.destroy',
                                                                $judul_headers->id_judul_header,
                                                            )" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="width: 4%;">No</th>
                                                <th style="width: 10%;">Nama</th>
                                                <th style="width: 7%;">Alamat</th>
                                                <th style="width: 10%;">Tlp</th>
                                                <th style="width: 5%;">Wilayah</th>
                                                <th style="width: 10%;">Kode Pos</th>
                                                <th>Gambar 1</th>
                                                <th>Gambar 2</th>
                                                <th>Header 1</th>
                                                <th>Header 2</th>
                                                <th>Header 3</th>
                                                <th>Header 4</th>
                                                <th>Header 5</th>
                                                <th>Header 6</th>
                                                <th>Header 7</th>
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
    </div>
</x-layouts.main> --}}
