<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Daftar Inputan</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                        <i class="ri-file-list-3-line align-middle"></i> Generate
                        Report
                    </button>
                </div>
            </div><!-- end card header -->
            @include('partials.animate_progress_form')
            <div class="card-block">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered wrap">
                            <thead class="thead-primary">
                                <tr class="col-md-12">
                                    <th class="text-left fs-14 text" width="2%">#</th>
                                    <th class="text-left fs-14 text" width="34%">Kode Rek. Belanja / Keg. / Sub
                                        Kegiatan</th>
                                    <th class="text-center fs-14 text" width="11%">Anggaran</th>
                                    <th class="text-center fs-14 text" width="11%">Realisasi Anggaran</th>
                                    <th class="text-center fs-14 text" width="11%">Proses SPJ</th>
                                    <th class="text-center fs-14 text" width="11%">Sisa Anggaran</th>
                                    <th class="text-center fs-14 text" width="3%">%</th>
                                    {{-- @for ($i = 1; $i <= 12; $i++)
                                        <th>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</th>
                                    @endfor --}}
                                </tr>
                            </thead>

                            @if ($dataKosong)
                                <div class="alert alert-warning text-center">
                                    <strong>Data tidak tersedia!</strong>
                                </div>
                            @else
                                @foreach ($categories as $jenisBelanja)
                                    <thead class="fs-14 text-primary">
                                        <tr>
                                            <td colspan="2" class="fs-14">
                                                <strong>{{ $jenisBelanja->kode_jenis_belanja }}.
                                                    {{ $jenisBelanja->nama_jenis_belanja }}</strong>
                                            </td>
                                            <td class="text-center fs-14">
                                                <strong>{{ number_format($jenisBelanja->total_anggaran, 0, ',', ',') }}</strong>
                                            </td>
                                            <td class="text-center fs-14">
                                                <strong>{{ number_format($jenisBelanja->realisasi_anggaran, 0, ',', ',') }}</strong>
                                            </td>
                                            <td class="text-center fs-14">
                                                <strong>{{ number_format($jenisBelanja->proses_anggaran, 0, ',', ',') }}</strong>
                                            </td>
                                            <td class="text-center fs-14">
                                                {{ number_format($jenisBelanja->total_anggaran - ($jenisBelanja->realisasi_anggaran + $jenisBelanja->proses_anggaran), 0, ',', ',') }}
                                            </td>
                                            <td class="text-center fs-14">
                                                @if ($jenisBelanja->total_anggaran > 0)
                                                    {{ number_format(($jenisBelanja->realisasi_anggaran / $jenisBelanja->total_anggaran) * 100, 2, ',', '.') }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        </tr>
                                    </thead>

                                    @foreach ($jenisBelanja->jenis_kategori_rkbus as $jenisKategori)
                                        <thead>
                                            <tr class="fs-14"
                                                style="background-color: #e0e9f4; color: rgb(63, 63, 63); opacity: 212;">
                                                <th colspan="2">{{ $jenisKategori->kode_jenis_kategori_rkbu }}.
                                                    {{ $jenisKategori->nama_jenis_kategori_rkbu }}</th>
                                                <td class="text-center fs-14">
                                                    <small>{{ number_format($jenisKategori->total_anggaran, 0, ',', ',') }}</small>
                                                </td>
                                                <td class="text-center fs-14">
                                                    <small>{{ number_format($jenisKategori->realisasi_anggaran, 0, ',', ',') }}</small>
                                                </td>
                                                <td class="text-center fs-14">
                                                    <small>{{ number_format($jenisKategori->proses_anggaran, 0, ',', ',') }}</small>
                                                </td>
                                                <td class="text-center fs-14">
                                                    {{ number_format($jenisKategori->total_anggaran - ($jenisKategori->realisasi_anggaran + $jenisKategori->proses_anggaran), 0, ',', ',') }}
                                                </td>
                                                <td class="text-center fs-14">
                                                    @if ($jenisKategori->total_anggaran > 0)
                                                        {{ number_format(($jenisKategori->realisasi_anggaran / $jenisKategori->total_anggaran) * 100, 2, ',', '.') }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                            </tr>
                                        </thead>

                                        @foreach ($jenisKategori->obyek_belanjas as $obyekBelanja)
                                            @foreach ($obyekBelanja->kategori_rkbus as $kategoriRkbu)
                                                <thead>
                                                    <tr class="fs-14"
                                                        style="background-color: #f2f3f3; color: rgb(63, 63, 63); opacity: 212;">
                                                        <th colspan="2">
                                                            {{ $kategoriRkbu->kode_kategori_rkbu }}.
                                                            {{ $kategoriRkbu->nama_kategori_rkbu }}</th>
                                                        <td class="text-center fs-14">
                                                            <small>{{ number_format($kategoriRkbu->total_anggaran, 0, ',', ',') }}</small>
                                                        </td>
                                                        <td class="text-center fs-14">
                                                            <small>{{ number_format($kategoriRkbu->realisasi_anggaran, 0, ',', ',') }}</small>
                                                        </td>
                                                        <td class="text-center fs-14">
                                                            <small>{{ number_format($kategoriRkbu->proses_anggaran, 0, ',', ',') }}</small>
                                                        </td>
                                                        <td class="text-center fs-14">
                                                            {{ number_format($kategoriRkbu->total_anggaran - ($kategoriRkbu->realisasi_anggaran + $kategoriRkbu->proses_anggaran), 0, ',', ',') }}
                                                        </td>
                                                        <td class="text-center fs-14">
                                                            @if ($kategoriRkbu->total_anggaran > 0)
                                                                {{ number_format(($kategoriRkbu->realisasi_anggaran / $kategoriRkbu->total_anggaran) * 100, 2, ',', '.') }}%
                                                            @else
                                                                0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </thead>

                                                @foreach ($kategoriRkbu->sub_kategori_rkbus as $subKategoriRkbu)
                                                    <thead>
                                                        <tr class="fs-14">
                                                            <th></th>
                                                            <th class="text-left fs-14 text-nowrap">
                                                                <small>{{ $subKategoriRkbu->kode_sub_kategori_rkbu }}.
                                                                    {{ $subKategoriRkbu->nama_sub_kategori_rkbu }}</small>
                                                            </th>
                                                            <td class="text-center fs-14">
                                                                <small>{{ number_format($subKategoriRkbu->total_anggaran, 0, ',', ',') }}</small>
                                                            </td>
                                                            <td class="text-center fs-14">
                                                                <small>{{ number_format($subKategoriRkbu->realisasi_anggaran, 0, ',', ',') }}</small>
                                                            </td>
                                                            <td class="text-center fs-14">
                                                                <small>{{ number_format($subKategoriRkbu->proses_anggaran, 0, ',', ',') }}</small>
                                                            </td>
                                                            <td class="text-center fs-14">
                                                                {{ number_format($subKategoriRkbu->total_anggaran - ($subKategoriRkbu->realisasi_anggaran + $subKategoriRkbu->proses_anggaran), 0, ',', ',') }}
                                                            </td>
                                                            <td class="text-center fs-14">
                                                                @if ($subKategoriRkbu->total_anggaran > 0)
                                                                    {{ number_format(($subKategoriRkbu->realisasi_anggaran / $subKategoriRkbu->total_anggaran) * 100, 2, ',', '.') }}%
                                                                @else
                                                                    0%
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endif

                            <tfoot class="thead-primary">
                                <tr>
                                    <th></th>
                                    <th class="text-center fs-14 text">Total Anggaran</th>
                                    <th class="text-left fs-14 text">
                                        {{ number_format($total_anggaran_blud, 0, ',', ',') }}</th>
                                    <th class="text-left fs-14 text">
                                        {{ number_format($total_realisasi_blud, 0, ',', ',') }}</th>
                                    <th class="text-left fs-14 text">
                                        {{ number_format($total_proses_spj_blud, 0, ',', ',') }}</th>
                                    <th class="text-left fs-14 text">
                                        {{ number_format($total_anggaran_blud - ($total_realisasi_blud + $total_proses_spj_blud), 0, ',', ',') }}
                                    </th>
                                    <th class="text-center fs-14 text">
                                        @if ($total_anggaran_blud > 0)
                                            {{ number_format(($total_realisasi_blud / $total_anggaran_blud) * 100, 2, ',', '.') }}%
                                        @else
                                            0%
                                        @endif
                                    </th>
                                </tr>
                            </tfoot>
                        </table><!-- end table -->
                    </div>
                </div>
            </div>
        </div> <!-- .card-->
    </div> <!-- .col-->
</div> <!-- end row-->
