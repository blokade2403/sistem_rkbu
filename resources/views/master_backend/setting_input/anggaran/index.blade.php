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
                                        <th>Name Anggaran</th>
                                        <th>Jumlah Anggaran</th>
                                        <th>Tahun Anggaran</th>
                                        <th>Rekening Belanja</th>
                                        <th>Sumber Dana</th>
                                        <th>Create At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($anggarans as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">#&nbsp;{{ $item->nama_anggaran }}</span>
                                            </td>
                                            <td class="text-center">{{ number_format($item->jumlah_anggaran, 0, ',', ',') }}
                                            </td>
                                            <td class="text-center">{{ $item->tahun_anggaran }}
                                            <td>{{ $item->rekening_belanjas->kode_rekening_belanja }}
                                                {{ $item->rekening_belanjas->nama_rekening_belanja }}
                                            </td>
                                            <td>{{ $item->sumber_dana->nama_sumber_dana }}
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <x-partials.action-buttons :edit-url="route('anggarans.edit', $item->id_anggaran)" :delete-url="route('anggarans.destroy', $item->id_anggaran)" />
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
