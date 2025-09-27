<x-layouts.main class="main">
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
                        {{-- @include('rkbu.partials.filter_rkbu', ['routePrefix' => $routePrefix]) --}}
                        <div class="card-block">
                            <div class="table-responsive">
                                <div class="dt-responsive table-responsive">
                                    <table id="responsive-reorder" class="table table-striped table-bordered wrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Usulan</th>
                                                <th>Uraian SPJ</th>
                                                <th>Detail Biaya</th>
                                                <th>Status</th>
                                                <th>Detail Pengusul</th>
                                                <th>Detail SPJ</th>
                                                <th>Detail Perusahaan</th>
                                                <th>Rincian Belanja</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($no_inv as $key)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td class="text-wrap"
                                                        style="white-space: normal; word-wrap: break-word;">
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span
                                                                    class="f-16 text-primary">#&nbsp;{{ $key->no_usulan_barang }}</span>
                                                            </li>
                                                            <li>
                                                                <span
                                                                    class="f-12">&nbsp;{{ $key->sub_kategori_rkbu->kode_sub_kategori_rkbu }}.
                                                                    {{ $key->sub_kategori_rkbu->nama_sub_kategori_rkbu }}</span>
                                                            </li>
                                                            <li></br>
                                                                <span class="f-12">&nbsp;Tahun Anggaran :
                                                                    {{ $key->tahun_anggaran }}</span>
                                                            </li>
                                                            <li>
                                                                <p>
                                                                    <span
                                                                        class="f-12">&nbsp;{{ $key->status_hutang ?? 'Isi data Terlebih Dahulu' }}</span>
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12"> &nbsp;</span>
                                                            </li>

                                                            <li>
                                                                <span class="f-12"> Nomor SPK :
                                                                    {{ $key->nomor_spk ?? '-' }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12"> Nomor SP :
                                                                    {{ $key->no_surat_pesanan ?? '-' }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12"> ID Paket :
                                                                    {{ $key->idpaket ?? '-' }}</span>
                                                            </li>

                                                        </ul>
                                                    </td>

                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12">&nbsp;</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12"><strong>Rp.
                                                                        {{ number_format($key->harga_dasar) }}</strong></span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12">PPN : {{ $key->ppn ?? '0' }}
                                                                    %</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12">Bruto : Rp.
                                                                    {{ number_format($key->bruto) }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12">Pembayaran : Rp.
                                                                    {{ number_format($key->pembayaran) }}</span>
                                                            </li>
                                                            <li>
                                                                <span class="f-12">Tanggal Pembayaran :<br />
                                                                    {{ $key->tanggal_pembayaran }}</span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        @include('master_spj.status_spj')
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12">Nama Pengusul
                                                                    :&nbsp;{{ $key->nama_pengusul_barang }}</span></br>
                                                                <span class="f-12">Tanggal Usulan
                                                                    :&nbsp;{{ $key->created_at }}</span></br>
                                                                <span class="f-12">Unit
                                                                    :&nbsp; {{ $key->user->unit->nama_unit }}</span>
                                                            </li>

                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12">&nbsp;</span>
                                                            </li>
                                                            <li><span class="f-12">Tanggal Penyerahan SPJ :
                                                                    </br><small class="text-danger">
                                                                        @if ($key->tanggal_penyerahan_spj)
                                                                            {{ $key->tanggal_penyerahan_spj }}
                                                                        @else
                                                                            <span class="badge bg-warning">SPJ Proses
                                                                                Verifikasi</span>
                                                                        @endif
                                                                    </small></small><br />
                                                                    @if ($key->tanggal_revisi_spj == '')
                                                                        <small class="fs-12"> Tanggal Revisi SPJ :
                                                                            </br><small class="fs-12">Tidak Ada
                                                                                Revisi</small>
                                                                        @else
                                                                            <small class="fs-12"> Tanggal Revisi SPJ :
                                                                                </br><small class="text-danger">
                                                                                    {{ $key->tanggal_revisi_spj }}
                                                                                </small></small><br />
                                                                    @endif
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12"> Nama PT
                                                                    :&nbsp;{{ $key->perusahaan?->nama_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                                <span class="f-12"> Alamat PT
                                                                    :&nbsp;{{ $key->perusahaan?->alamat_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                                <span class="f-12"> Telp PT
                                                                    :&nbsp;{{ $key->perusahaan?->tlp_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                                <span class="f-12"> Nama Direktur
                                                                    :&nbsp;{{ $key->perusahaan?->nama_direktur_perusahaan ?? 'Data Perusahaan Belum di Input' }}</span></br>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="list list-unstyled">
                                                            <li>
                                                                <span class="f-12"> Uraian Belanja :
                                                                    {{ $key->rincian_belanja }}</span></br>
                                                                <span class="f-12"> Keterangan Validasi Keuangan:
                                                                    <br /><small class="badge bg-label-danger">
                                                                        {{ $key->keterangan_spj }}</small></span>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        @if ($key->status_pembayaran == 'Sudah di Bayar')
                                                            <button
                                                                class="btn btn-sm btn-out-dashed btn-disabled btn-square disabled">No
                                                                Action
                                                            </button>
                                                        @else
                                                            <a href="{{ route($routePrefix . '.edit', $key->id_master_spj) }}"
                                                                class="m-r-15 text-primary" data-toggle="tooltip"
                                                                data-placement="top" title="Edit"
                                                                data-original-title="Edit"><i
                                                                    class="icofont icofont-ui-edit"></i></a>
                                                            <a href="javascript:void(0);"
                                                                onclick="showDetailMasterSpj('{{ $key->id_master_spj }}')"
                                                                class="m-r-15 text-primary" data-toggle="tooltip"
                                                                data-placement="top" title="Detail Data"
                                                                data-original-title="Detail Data"><i
                                                                    class="icofont icofont-ui-copy"></i></a>
                                                        @endif
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
                                                <th>Detail Pengusul</th>
                                                <th>Detail SPJ</th>
                                                <th>Detail Perusahaan</th>
                                                <th>Rincian Belanja</th>
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
    <div class="modal fade" id="modalDetailMasterSpj" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Konten Detail akan diisi oleh JavaScript -->
                    <div id="modalDetailContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
