@extends('layouts.main')
@section('content')
    <div class="row">
        <!-- Basic row reorder table start -->
        <div class="card">
            <div class="card-header">
                <div class="col-xl-12">
                    @include('partials.animate_progress_form')
                    {{-- @include('rkbu.partials.filter_rkbu', ['routePrefix' => $routePrefix]) --}}
                    <div class="card-block">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="responsive-reorder"
                                    class="table table-hover table-centered align-middle table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Usulan</th>
                                            <th>Uraian SPJ</th>
                                            <th>Detail Biaya</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Detail Pengusul</th>
                                            <th>Detail Perusahaan</th>
                                            <th>Rincian Belanja</th>
                                            <th>Detail SPJ</th>
                                            <th style="width: 10%;">Resource</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($no_inv as $key)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td class="text-wrap" style="white-space: normal; word-wrap: break-word;">
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span
                                                                class="fs-16 text-primary">#&nbsp;{{ $key->no_usulan_barang }}</span>
                                                        </li>
                                                        <li>
                                                            <span
                                                                class="fs-12">&nbsp;{{ $key->sub_kategori_rkbu->kode_sub_kategori_rkbu }}.
                                                                {{ $key->sub_kategori_rkbu->nama_sub_kategori_rkbu }}</span>
                                                        </li>
                                                        <li></br>
                                                            <span class="fs-12">&nbsp;Tahun Anggaran :
                                                                {{ $key->tahun_anggaran }}</span>
                                                        </li>
                                                        <li>
                                                            <p>
                                                                <span
                                                                    class="fs-10 badge badge-outline-danger">&nbsp;{{ $key->status_hutang ?? 'Isi data Terlebih Dahulu' }}</span>
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12"> &nbsp;</span>
                                                        </li>

                                                        <li>
                                                            <span class="fs-12"> Nomor SPK :
                                                                {{ $key->nomor_spk ?? '-' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="fs-12"> Nomor SP :
                                                                {{ $key->no_surat_pesanan ?? '-' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="fs-12"> ID Paket :
                                                                {{ $key->idpaket ?? '-' }}</span>
                                                        </li>

                                                    </ul>
                                                </td>

                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12">&nbsp;</span>
                                                        </li>
                                                        <li>
                                                            <span class="fs-12">Netto: <strong>Rp.
                                                                    {{ number_format($key->harga_dasar) }}</strong></span>
                                                        </li>
                                                        <li>
                                                            <span class="fs-12">PPN : {{ $key->ppn ?? '0' }}
                                                                %</span>
                                                        </li>
                                                        <li>
                                                            Bruto :<span class="badge badge-soft-info fs-12"> Rp.
                                                                {{ number_format($key->bruto) }}</span>
                                                        </li></br>
                                                        <li>
                                                            <span class="fs-12">Pembayaran : Rp.
                                                                {{ number_format($key->pembayaran) }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="fs-12">Tanggal Pembayaran :<br />
                                                                {{ $key->tanggal_pembayaran }}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    @include('master_spj.partials.status_spj')
                                                </td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12">Pengusul
                                                                :&nbsp;{{ $key->nama_pengusul_barang }}</span></br>
                                                            <span class="fs-12">Tgl Usulan
                                                                :&nbsp;{{ $key->created_at }}</span></br>
                                                            <span class="fs-12">Unit
                                                                :&nbsp;
                                                                {{ $key->user->unit->nama_unit }}</span>
                                                        </li>

                                                    </ul>
                                                </td>
                                                <td>
                                                    @if ($key->status_pembayaran == 'Sudah di Bayar')
                                                        <a href="{{ route($routePrefix . '.edit', $key->id_master_spj) }}"
                                                            class="btn btn-outline-success btn-load right ms-auto nexttab nexttab"
                                                            data-nexttab="pills-success-tab"><span
                                                                class="d-flex align-items-center">
                                                                <span class="flex-grow-1 me-2">
                                                                    Selesai
                                                                </span>
                                                                <span class="spinner-grow flex-shrink-0" role="status">
                                                                    <span class="visually-hidden">Selesai</span>
                                                                </span>
                                                            </span></a>
                                                    @else
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="View">
                                                                <a href="" class="text-primary d-inline-block">
                                                                    <i class="ri-eye-fill fs-16"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="Edit">
                                                                <a href="{{ route($routePrefix . '.edit', $key->id_master_spj) }}"
                                                                    class="text-primary d-inline-block edit-item-btn">
                                                                    <i class="ri-pencil-fill fs-16"></i>
                                                                </a>
                                                            </li>
                                                            <form action="" method="POST" class="delete-form mb-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-danger d-inline-block remove-item-btn"
                                                                    data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top" title="Remove"><i
                                                                        class="ri-delete-bin-5-fill fs-16"></i>
                                                                </button>
                                                            </form>
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12">&nbsp;</span>
                                                        </li>

                                                        <li>
                                                            <span class="fs-12">
                                                                Tanggal Penyerahan SPJ :
                                                                </br>
                                                                <small class="text-danger">
                                                                    @if ($key->tanggal_penyerahan_spj)
                                                                        {{ $key->tanggal_penyerahan_spj }}
                                                                    @else
                                                                        <span class="badge badge-outline-secondary">
                                                                            SPJ Proses Verifikasi
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            </span>
                                                        </li>

                                                        <li>
                                                            @if (empty($key->tanggal_revisi_spj))
                                                                <small class="fs-12">
                                                                    Tanggal Revisi SPJ :
                                                                    </br><small class="fs-12">Tidak Ada Revisi</small>
                                                                </small>
                                                            @else
                                                                <small class="fs-12">
                                                                    Tanggal Revisi SPJ :
                                                                    </br><small
                                                                        class="text-danger">{{ $key->tanggal_revisi_spj }}</small>
                                                                </small>
                                                            @endif
                                                        </li>

                                                        {{-- 🔽 Tambahan: hasil upload SPJ --}}
                                                        <li class="mt-2">
                                                            <small class="fs-12 fw-bold text-dark">Upload Dokumen
                                                                SPJ:</small>
                                                            <br>

                                                            {{-- File SPJ 1 --}}
                                                            @if (!empty($key->upload_spj_1))
                                                                <a href="{{ asset('storage/' . $key->upload_spj_1) }}"
                                                                    target="_blank"
                                                                    class="badge bg-primary-subtle text-primary text-decoration-none">
                                                                    Lihat SPJ 2
                                                                </a>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-muted">Belum ada
                                                                    SPJ 1</span>
                                                            @endif

                                                            {{-- File SPJ 2 --}}
                                                            @if (!empty($key->upload_spj_2))
                                                                <a href="{{ asset('storage/' . $key->upload_spj_2) }}"
                                                                    target="_blank"
                                                                    class="badge bg-primary-subtle text-primary text-decoration-none">
                                                                    Lihat SPJ 2
                                                                </a>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-muted">Belum ada
                                                                    SPJ 2</span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </td>

                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12"> Nama PT
                                                                :&nbsp;{{ $key->perusahaan?->nama_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                            <span class="fs-12"> Alamat PT
                                                                :&nbsp;{{ $key->perusahaan?->alamat_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                            <span class="fs-12"> Telp PT
                                                                :&nbsp;{{ $key->perusahaan?->tlp_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                            <span class="fs-12"> Nama Direktur
                                                                :&nbsp;{{ $key->perusahaan?->nama_direktur_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list list-unstyled">
                                                        <li>
                                                            <span class="fs-12"> Uraian Belanja :
                                                                {{ $key->rincian_belanja }}</span></br>
                                                            <span class="fs-12"> Keterangan Validasi Keuangan:
                                                                <br /><small class="badge bg-label-danger">
                                                                    {{ $key->keterangan_spj }}</small></span>
                                                            @if (session('nama_level_user') == 'Administrator')
                                                                <p>
                                                                    <span class="fs-10">ID Master:
                                                                        {{ $key->id_master_spj }}</span></br>
                                                                    <span class="fs-10">ID SPJ:
                                                                        {{ $key->id_spj }}</span>
                                                                </p>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    @include('master_spj.partials.resource')
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Usulan</th>
                                            <th>Uraian SPJ</th>
                                            <th>Detail Biaya</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Detail Pengusul</th>
                                            <th>Detail Perusahaan</th>
                                            <th>Rincian Belanja</th>
                                            <th>Detail SPJ</th>
                                            <th>Resource</th>
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
@endsection
