@extends('layouts.main')
@section('content')
    @include('master_backend.setting_input.target_sps.add_buttons', ['routePrefix' => $routePrefix])
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
                                        <th>Bulan</th>
                                        <th>Target</th>
                                        <th>Tahun Anggaran</th>
                                        <th>Create At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($targets as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <span class="fs-12">{{ $item->bulan }} -
                                                    {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</span>
                                            </td>
                                            <td class="text-center">Rp {{ number_format($item->target, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">{{ $item->nama_tahun_anggaran }}
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $item->id }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('target_sps.destroy', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Hapus data ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @include('master_backend.setting_input.target_sps.modal_edit')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> <!-- .card-->
        </div> <!-- .col-->
    </div> <!-- end row-->
    @include('master_backend.setting_input.target_sps.modal_create')
@endsection
