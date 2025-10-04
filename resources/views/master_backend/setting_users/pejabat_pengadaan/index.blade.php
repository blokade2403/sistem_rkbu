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
                                        <th>Pejabat Pengadaan</th>
                                        <th>PPBJ</th>
                                        <th>Pengurus Barang</th>
                                        <th>Bendahara</th>
                                        <th>Direktur</th>
                                        <th>PPK Keuangan</th>
                                        <th>Tahun Anggaran</th>
                                        <th>Status</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($pejabat_pengadaans as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_ppk }}</span>
                                                <p class="fs-12">NIP:&nbsp;{{ $item->nip_ppk }}</p>
                                                <p class="fs-12">ID:&nbsp;{{ $item->id_pejabat_pengadaan }}</p>
                                            </td>
                                            <td>{{ $item->nama_ppbj }}
                                                <p>NIP:&nbsp;{{ $item->nip_ppbj }}</p>
                                            </td>
                                            <td>
                                                {{ $item->nama_pengurus_barang }}
                                                <p>NIP:&nbsp;{{ $item->nip_pengurus_barang }}</p>
                                            </td>
                                            <td>
                                                {{ $item->nama_bendahara }}
                                                <p>NIP:&nbsp;{{ $item->nip_bendahara }}</p>
                                            </td>
                                            <td>
                                                {{ $item->nama_direktur }}
                                                <p>NIP:&nbsp;{{ $item->nip_direktur }}</p>
                                            </td>
                                            <td>
                                                {{ $item->ppk_keuangan->nama_ppk_keuangan }}
                                                <p>NIP:&nbsp;{{ $item->ppk_keuangan->nip_ppk_keuangan }}</p>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-label bg-danger">{{ $item->TahunAnggaran->nama_tahun_anggaran }}</span>
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
                                                <x-partials.action-buttons :edit-url="route(
                                                    'pejabat_pengadaans.edit',
                                                    $item->id_pejabat_pengadaan,
                                                )" :delete-url="route(
                                                    'pejabat_pengadaans.destroy',
                                                    $item->id_pejabat_pengadaan,
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
