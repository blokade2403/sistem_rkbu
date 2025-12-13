@extends('layouts.main')
@section('content')
    @include('partials.add_buttons', ['routePrefix' => $routePrefix])
    @include('rkbu.partials.selisihlebihrkbu')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                @include('rkbu.partials.filter_rkbu_user', ['routePrefix' => $routePrefix])
                <div class="card-block">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="responsive-reorder"
                                class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Nama Barang</th>
                                        <th style="width: 7%;">Detail Quantity</th>
                                        <th style="width: 10%;">Harga Satuan</th>
                                        <th style="width: 5%;">Total Vol</th>
                                        <th style="width: 10%;">Total Anggaran</th>
                                        <th>Pengusul</th>
                                        <th>Action</th>
                                        <th>Detail Anggaran</th>
                                        <th style="width: 10%; white-space: normal; word-wrap: break-word;">
                                            Spesifikasi</th>
                                        <th>Dokumen Usulan</th>
                                        <th>Status Usulan</th>
                                        <th>Tanggal Usulan</th>
                                        @if (session('nama_level_user') == 'Administrator')
                                            <th>ID</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($rkbus as $rkbus)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <ul class="list-unstyled product-desc-list">
                                                    <li>
                                                        <span class="f-12">
                                                            Sumber Dana : &nbsp;
                                                            @php
                                                                $sumberDana =
                                                                    $rkbus->rekening_belanjas->aktivitas->sub_kegiatan
                                                                        ->sumber_danas->nama_sumber_dana ??
                                                                    'Tidak ada data';
                                                            @endphp
                                                            @if ($sumberDana === 'BLUD')
                                                                <span
                                                                    class="badge badge-outline-primary">{{ $sumberDana }}</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-outline-danger">{{ $sumberDana }}</span>
                                                            @endif
                                                        </span><span class="f-12"><span
                                                                class="badge bg-info">{{ $rkbus->rating }}</span></span>
                                                        @include('rkbu.partials.status_badge_validasi')
                                                    </li>
                                                </ul>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/companies/img-1.png') }}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                    <div class="flex-1">
                                                        <h6 class="mt-0 mb-1 fs-14">
                                                            <a href="" class="fs-16">{{ $rkbus->nama_barang }}
                                                            </a>
                                                        </h6>
                                                        <p class="mb-0 fs-12 text-muted">
                                                            <span>{{ $rkbus->created_at }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <p>
                                                <ul class="list-unstyled product-desc-list">
                                                    <li class="py-0">
                                                        <span class="f-12">User:
                                                            {{ $rkbus->user->nama_lengkap }}</span></br>
                                                        <span class="f-12">KSP: {{ $rkbus->user->ksp->nama_ksp }}</span>
                                                    </li></br>
                                                    <li> <span
                                                            class="f-12">{{ $rkbus->subKategoriRkbu->kode_sub_kategori_rkbu . $rkbus->subKategoriRkbu->nama_sub_kategori_rkbu }}</span>
                                                    </li>
                                                    <li> <span class="f-12 text-muted">Penempatan:
                                                            {{ $rkbus->penempatan }}</span>
                                                    </li>
                                                </ul>
                                                </p>
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span class="f-12">{{ $rkbus->vol_1 }}
                                                            {{ $rkbus->satuan_1 }} x
                                                            {{ $rkbus->vol_2 }} {{ $rkbus->satuan_2 }}</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span class="f-12">Rp.
                                                            {{ number_format($rkbus->harga_satuan) }}</span>
                                                    </li>
                                                    <li><span class="f-12">PPN : {{ $rkbus->ppn ?? '-' }}
                                                            %</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td> <span class="f-12">{{ $rkbus->jumlah_vol }}
                                                    {{ $rkbus->satuan_1 }}/{{ $rkbus->satuan_2 }}</span>
                                            </td>
                                            <td> <span class="f-12">Rp.
                                                    {{ number_format($rkbus->total_anggaran) }}</span>
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li><span
                                                            class="f-12">Pengusul:&nbsp;{{ $rkbus->user->nama_lengkap }}
                                                            </br><span class="f-12">
                                                                KSP:&nbsp;{{ $rkbus->user->ksp->nama_ksp }}</span>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                @if (session('nama_level_user') == 'User')
                                                    @if ($rkbus->id_status_validasi == '9cfb1edc-2263-401f-b249-361db4017932' || session('status_edit') == 'tidak aktif')
                                                        <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-load">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status">
                                                                        <span class="visually-hidden"> No action...</span>
                                                                    </span>
                                                                    <span class="flex-grow-1 ms-2">
                                                                        No action...
                                                                    </span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" :delete-url="route($routePrefix . '.destroy', $rkbus->id_rkbu)" />
                                                    @endif
                                                @elseif(session('nama_level_user') == 'Validasi')
                                                    @if ($rkbus->id_status_validasi_rka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' || session('status_edit') == 'tidak aktif')
                                                        <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-load">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status">
                                                                        <span class="visually-hidden"> No action...</span>
                                                                    </span>
                                                                    <span class="flex-grow-1 ms-2">
                                                                        No action...
                                                                    </span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" :delete-url="route($routePrefix . '.destroy', $rkbus->id_rkbu)" />
                                                    @endif
                                                @elseif(session('nama_level_user') == 'Validasi RKA')
                                                    @if (session('status_edit') == 'tidak aktif')
                                                        <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-load">
                                                                <span class="d-flex align-items-center">
                                                                    <span class="spinner-grow flex-shrink-0" role="status">
                                                                        <span class="visually-hidden"> No action...</span>
                                                                    </span>
                                                                    <span class="flex-grow-1 ms-2">
                                                                        No action...
                                                                    </span>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" :delete-url="route($routePrefix . '.destroy', $rkbus->id_rkbu)" />
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span
                                                            class="f-12">&nbsp;{{ $rkbus->subKategoriRkbu->kode_sub_kategori_rkbu }}.
                                                            {{ $rkbus->subKategoriRkbu->nama_sub_kategori_rkbu }}</span>
                                                    </li>
                                                    <li><span class="fs-12 badge badge-soft-danger badge-border text-left">
                                                            Sisa
                                                            Volume:&nbsp;{{ $rkbus->sisa_vol_rkbu }}
                                                            {{ $rkbus->satuan_1 }}/{{ $rkbus->satuan_2 }}</span>
                                                        <span class="fs-12 badge badge-soft-danger badge-border text-left">
                                                            Sisa
                                                            Anggaran:&nbsp;Rp.
                                                            {{ number_format($rkbus->sisa_anggaran_rkbu) }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li><span class="f-12 text-wrap">Spek:&nbsp;{{ $rkbus->spek }}</span>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <span class="f-12">link e-kat:
                                                    @if (strlen($rkbus->link_ekatalog) <= 3)
                                                        {{ $rkbus->link_ekatalog }}
                                                    @else
                                                        <a href="{{ $rkbus->link_ekatalog }}"><span
                                                                class="badge badge-soft-primary">Lihat Link
                                                                e-katalog</span></a>
                                                    @endif
                                                </span></br>
                                                @if (!empty($rkbus->upload_file_1))
                                                    <span class="f-12">Dok. KAP :
                                                        <a href="{{ asset('storage/uploads/' . basename($rkbus->upload_file_1)) }}"
                                                            target="_blank">
                                                            <span class="badge bg-primary">Lihat Dokumen
                                                                KAP</span>
                                                        </a>
                                                    </span><br />
                                                @else
                                                    <span class="f-12 text-danger">Dok. KAP : No
                                                        File.</span><br />
                                                @endif
                                                @if (!empty($rkbus->upload_file_2))
                                                    <span class="f-12">Dok. SPH1 :
                                                        <a href="{{ asset('storage/uploads/' . basename($rkbus->upload_file_2)) }}"
                                                            target="_blank">
                                                            <span class="badge bg-primary">Lihat Dokumen SPH
                                                                1</span>
                                                        </a>
                                                    </span><br />
                                                @else
                                                    <span class="f-12 text-danger">Dok. SPH1 : No
                                                        File.</span><br />
                                                @endif
                                                @if (!empty($rkbus->upload_file_3))
                                                    <span class="f-12">Dok. SPH2 :
                                                        <a href="{{ asset('storage/uploads/' . basename($rkbus->upload_file_3)) }}"
                                                            target="_blank">
                                                            <span class="badge bg-primary">Lihat
                                                                Dokumen</span>
                                                        </a>
                                                    </span><br />
                                                @else
                                                    <span class="f-12 text-danger">Dok. SPH2 : No
                                                        File.</span><br />
                                                @endif
                                                @if (!empty($rkbus->upload_file_4))
                                                    <span class="f-12">Dok. SPH3 :
                                                        <a href="{{ asset('storage/uploads/' . basename($rkbus->upload_file_4)) }}"
                                                            target="_blank">
                                                            <span class="badge bg-primary">Lihat
                                                                Dokumen</span>
                                                        </a>
                                                    </span><br />
                                                @else
                                                    <span class="f-12 text-danger">Dok. SPH3 : No
                                                        File.</span><br />
                                                @endif
                                            </td>
                                            <td>
                                                @include('rkbu.partials.status_rkbu')
                                            </td>
                                            </td>
                                            @if (session('nama_level_user') == 'Administrator')
                                                <td>
                                                    <span class="f-12 text-wrap">ID
                                                        RKBU:&nbsp;{{ $rkbus->id_rkbu }}</span></br>
                                                    <span class="f-12 text-wrap">ID Sub Kategori
                                                        RKBU:&nbsp;{{ $rkbus->id_sub_kategori_rkbu }}</span>
                                                </td>
                                            @endif
                                            <td>
                                                <span class="f-12 text-wrap">#&nbsp;{{ $rkbus->created_at }}</span>
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
