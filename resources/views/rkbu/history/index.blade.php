@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"></h4>
                </div><!-- end card header -->
                <div class="card-block">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="dt-fixedcolumns table" style="width:100%">
                                <thead>
                                    <tr class="text-center me-0">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Barang</th>
                                        <th colspan="3" class="table-light border-top text-center">Sebelum</th>
                                        <th colspan="3" class="table-success border-top text-center">Sesudah</th>
                                    </tr>
                                    <tr>
                                        <th>Detail Spesifikasi</th>
                                        <th>Vol</th>
                                        <th>Detail Harga</th>
                                        <th>Detail Spesifikasi</th>
                                        <th>Vol</th>
                                        <th>Detail Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($rkbu_history as $history)
                                        @php
                                            $data_sebelum = $history->data_sebelum;

                                            // Jika data_sebelum adalah string JSON yang di-encode dua kali
                                            if (is_string($data_sebelum)) {
                                                $decoded_once = json_decode($data_sebelum, true);

                                                // Jika hasil decode pertama masih berupa string JSON
                                                if (is_string($decoded_once)) {
                                                    $data_sebelum = json_decode($decoded_once, true);
                                                } else {
                                                    $data_sebelum = $decoded_once;
                                                }
                                            }

                                            $data_sesudah = $history->data_sesudah;

                                            if (is_string($data_sesudah)) {
                                                $decoded_once = json_decode($data_sesudah, true);

                                                if (is_string($decoded_once)) {
                                                    $data_sesudah = json_decode($decoded_once, true);
                                                } else {
                                                    $data_sesudah = $decoded_once;
                                                }
                                            }

                                            // Ambil sub kategori jika tersedia
                                            $sub_kategori_sebelum = \App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu::find(
                                                $data_sebelum['id_sub_kategori_rkbu'] ?? null,
                                            );
                                            $sub_kategori_sesudah = \App\Models\MasterBackend\SettingRkbu\SubKategoriRkbu::find(
                                                $data_sesudah['id_sub_kategori_rkbu'] ?? null,
                                            );
                                        @endphp

                                        <tr>
                                            <td>{{ $no++ }}</td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/companies/img-1.png') }}"
                                                        class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                    <div class="flex-1">
                                                        <h6 class="mt-0 mb-1 fs-14">
                                                            <a href=""
                                                                class="fs-16">{{ $data_sebelum['nama_barang'] ?? 'N/A' }}
                                                            </a>
                                                        </h6>
                                                        <p class="mb-0 fs-12 text-muted">
                                                            <span>{{ $history->id_rkbu }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <p>
                                                <ul class="list-unstyled product-desc-list">
                                                    <li class="py-0">
                                                        <span class="f-12">Kategori:
                                                            {{ $sub_kategori_sebelum->nama_sub_kategori_rkbu ?? 'N/A' }}</span></br>
                                                        <span class="f-12">Modified By:
                                                            {{ $history->user->nama_lengkap ?? 'N/A' }}</span>
                                                    </li></br>
                                                </ul>
                                                </p>
                                            </td>

                                            <td style="width: 200px">
                                                <span class="fa-xs"><strong>Sub Kategori:
                                                        {{ $sub_kategori_sebelum->nama_sub_kategori_rkbu ?? 'N/A' }}</strong></span><br />
                                                <span class="fs-10">Spek:
                                                    {{ $data_sebelum['spek'] ?? 'N/A' }}</span><br />
                                                <span class="badge bg-label-info">
                                                    <span class="fa-sm">
                                                        {{ \Carbon\Carbon::parse($data_sebelum['created_at'] ?? now())->format('Y-m-d H:i:s') }}
                                                    </span>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="fa-xs">
                                                    {{ ($data_sebelum['vol_1'] ?? 0) . ' ' . ($data_sebelum['satuan_1'] ?? '') }}
                                                    x
                                                    {{ ($data_sebelum['vol_2'] ?? 0) . ' ' . ($data_sebelum['satuan_2'] ?? '') }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="fa-xs">Harga Satuan Rp.
                                                    {{ number_format($data_sebelum['harga_satuan'] ?? 0) }}</span><br /><br />
                                                <span class="fa-xs badge bg-dark">Rp.
                                                    {{ number_format($data_sebelum['total_anggaran'] ?? 0) }}</span><br />
                                            </td>

                                            {{-- DATA SESUDAH --}}
                                            <td style="width: 200px">
                                                <span
                                                    class="fa-sm text-info"><strong>{{ $data_sesudah['nama_barang'] ?? 'N/A' }}</strong></span><br />
                                                <span class="fa-xs"><strong>Sub Kategori:
                                                        {{ $sub_kategori_sesudah->nama_sub_kategori_rkbu ?? 'N/A' }}</strong></span><br />
                                                <span class="fs-10">Spek:
                                                    {{ $data_sesudah['spek'] ?? 'N/A' }}</span><br />
                                                <span class="badge bg-label-warning">
                                                    <span class="badge bg-label-info">
                                                        <span class="fa-sm">
                                                            {{ $history->updated_at }}
                                                        </span>
                                                    </span>
                                                </span><br />
                                                <span class="fa-xs mt-3">Ket:
                                                    {{ $history->keterangan_status ?? 'N/A' }}</span><br /><br />

                                                {{-- Dokumen Pendukung --}}
                                                @if (!empty($history->upload_file_5))
                                                    <span class="fa-sm">Dokumen Pendukung:
                                                        <a href="{{ asset('storage/uploads/' . basename($history->upload_file_5)) }}"
                                                            target="_blank">
                                                            <span class="badge bg-primary">Lihat Dokumen</span>
                                                        </a>
                                                    </span><br />
                                                @else
                                                    <span class="fa-sm text-danger">Dok. Pendukung: No File.</span><br />
                                                @endif
                                            </td>

                                            <td>
                                                <span class="fa-xs">
                                                    {{ ($data_sesudah['vol_1'] ?? 0) . ' ' . ($data_sesudah['satuan_1'] ?? '') }}
                                                    x
                                                    {{ ($data_sesudah['vol_2'] ?? 0) . ' ' . ($data_sesudah['satuan_2'] ?? '') }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="fa-xs">Harga Satuan Rp.
                                                    {{ number_format($data_sesudah['harga_satuan'] ?? 0) }}</span><br /><br />
                                                <span class="fa-xs badge bg-dark">Rp.
                                                    {{ number_format($data_sesudah['total_anggaran'] ?? 0) }}</span><br /><br />
                                                <span class="fa-10 badge  badge-outline-danger">
                                                    Selisih: Rp.
                                                    {{ number_format(($data_sesudah['total_anggaran'] ?? 0) - ($data_sebelum['total_anggaran'] ?? 0)) }}
                                                </span><br />
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
