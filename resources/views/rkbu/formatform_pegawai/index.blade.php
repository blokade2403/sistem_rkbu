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
                                <thead>
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Nama Pegawai</th>
                                        <th style="width: 7%;">Profil Pegawai</th>
                                        <th style="width: 10%;">Detail Jabatan</th>
                                        <th style="width: 5%;">Penggajian</th>
                                        <th style="width: 10%;">Detail Jaminan</th>
                                        <th>Detail Pendapatan</th>
                                        <th>Masa Kerja</th>
                                        <th>Status Usulan</th>
                                        <th>Tanggal Usulan</th>
                                        @if (session('nama_level_user') == 'Administrator')
                                            <th>ID</th>
                                        @endif
                                        <th>Action</th>
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
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span class="f-16">#&nbsp;{{ $rkbus->nama_pegawai }}</span>
                                                    </li>
                                                    <li>
                                                        <span class="f-12">NIP&nbsp;{{ $rkbus->nomor_kontrak }}</span>
                                                    </li>
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
                                            </td>
                                            <td>
                                                <ul class="list list-unstyled">
                                                    <li>
                                                        <span class="f-12">Lahir:
                                                            {{ $rkbus->tempat_lahir }}</span></br>
                                                        <span class="f-12">Tgl Lahir:
                                                            {{ $rkbus->tanggal_lahir }}</span></br>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <span class="f-12">Jabatan : {{ $rkbus->jabatan }}</span></br>
                                                <span class="f-12">Pendidikan :
                                                    {{ $rkbus->pendidikan }}</span><br />
                                                <span class="f-12">Status Kawin :
                                                    {{ $rkbus->id_status_kawin }}</span><br />
                                            </td>
                                            <td> <span class="f-12">Gaji Pokok
                                                    Rp.{{ number_format($rkbus->gaji_pokok, 0, ',', ',') }}</span><br />
                                                <span class="f-12">Koef. Gaji :
                                                    <strong>{{ $rkbus->koefisien_gaji }}</strong></span><br />
                                                <span class="f-12">Remunerasi
                                                    Rp.{{ number_format($rkbus->remunerasi, 0, ',', ',') }}</span><br />
                                                <span class="f-12">Koef. Remun :
                                                    {{ $rkbus->koefisien_remunerasi }}</span><br /></span>
                                            </td>
                                            <td>
                                                <span class="f-12">BPJS Kesehatan :
                                                    {{ $rkbus->bpjs_kesehatan }}</span><br />
                                                <span class="f-12">BPJS TK :
                                                    Rp.{{ $rkbus->bpjs_tk }}</span><br />
                                                <span class="f-12">BPJS JHT
                                                    :{{ $rkbus->bpjs_jht }}</span><br />
                                            </td>
                                            <td>
                                                <span class="f-12">Total Gaji Rp.
                                                    {{ number_format($rkbus->total_gaji_pokok, 0, ',', ',') }}</span><br />
                                                <span class="f-12">Total Remunerasi
                                                    Rp.{{ number_format($rkbus->total_remunerasi, 0, ',', ',') }}</span><br /><br />
                                                <span class="f-12">Total Anggaran: <br /><span class="badge bg-dark">Rp.
                                                        {{ number_format($rkbus->total_anggaran, 0, ',', ',') }}</span></span><br /><br />
                                            </td>
                                            <td>
                                                <span class="f-12">No Kontrak :
                                                    {{ $rkbus->nomor_kontrak }}</span><br />
                                                <span class="f-12">Tmt Pegawai :
                                                    {{ $rkbus->tmt_pegawai }}</span><br />
                                                <span class="f-12">Tmt Bulan :
                                                    {{ $rkbus->bulan_tmt }}</span><br />
                                                <span class="f-12">Tmt Tahun :
                                                    {{ $rkbus->tahun_tmt }}</span><br />
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
                                            <td>
                                                @if (session('nama_level_user') == 'User')
                                                    @if ($rkbus->id_status_validasi == '9cfb1edc-2263-401f-b249-361db4017932' || session('status_edit') == 'tidak aktif')
                                                        <button
                                                            class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No
                                                            Action
                                                        </button>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" :delete-url="route($routePrefix . '.destroy', $rkbus->id_rkbu)" />
                                                    @endif
                                                @elseif(session('nama_level_user') == 'Validasi')
                                                    @if ($rkbus->id_status_validasi_rka == '9cfb1f87-238b-4ea2-98f0-4255e578b1d1' || session('status_edit') == 'tidak aktif')
                                                        <button
                                                            class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No
                                                            Action
                                                        </button>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" :delete-url="route($routePrefix . '.destroy', $rkbus->id_rkbu)" />
                                                    @endif
                                                @elseif(session('nama_level_user') == 'Validasi RKA')
                                                    @if (session('status_edit') == 'tidak aktif')
                                                        <button
                                                            class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No
                                                            Action
                                                        </button>
                                                    @else
                                                        <x-partials.action-buttons :edit-url="route($routePrefix . '.edit', $rkbus->id_rkbu)" />
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 4%;">No</th>
                                        <th style="width: 10%;">Nama Pegawai</th>
                                        <th style="width: 7%;">Profil Pegawai</th>
                                        <th style="width: 10%;">Detail Jabatan</th>
                                        <th style="width: 5%;">Penggajian</th>
                                        <th style="width: 10%;">Detail Jaminan</th>
                                        <th>Detail Pendapatan</th>
                                        <th>Masa Kerja</th>
                                        <th>Status Usulan</th>
                                        <th>Tanggal Usulan</th>
                                        @if (session('nama_level_user') == 'Administrator')
                                            <th>ID</th>
                                        @endif
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
@endsection
