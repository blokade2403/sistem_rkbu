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
                        <table class="table table-modern wrap">
                            <thead class="thead-primary">
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="34%">Kode Rek. Belanja / Kegiatan</th>
                                    <th class="text-center" width="11%">Anggaran</th>
                                    <th class="text-center" width="11%">Realisasi</th>
                                    <th class="text-center" width="11%">Proses SPJ</th>
                                    <th class="text-center" width="11%">SPJ Keuangan</th>
                                    <th class="text-center" width="11%">Sisa</th>
                                    <th class="text-center" width="3%">%</th>
                                </tr>
                            </thead>

                            @foreach ($categories as $jenisBelanja)
                                <thead class="level-1">
                                    <tr>
                                        <th colspan="2">
                                            {{ $jenisBelanja->kode_jenis_belanja }}.
                                            {{ $jenisBelanja->nama_jenis_belanja }}
                                        </th>
                                        <th class="text-right">{{ number_format($jenisBelanja->total_anggaran) }}</th>
                                        <th class="text-right">{{ number_format($jenisBelanja->realisasi_anggaran) }}
                                        </th>
                                        <th class="text-right">{{ number_format($jenisBelanja->proses_anggaran) }}</th>
                                        <th class="text-right">
                                            {{ number_format($jenisBelanja->proses_anggaran_bendahara) }}</th>
                                        <th class="text-right">
                                            {{ number_format($jenisBelanja->total_anggaran - ($jenisBelanja->realisasi_anggaran + $jenisBelanja->proses_anggaran + $jenisBelanja->proses_anggaran_bendahara)) }}
                                        </th>
                                        <th class="text-center">
                                            {{ $jenisBelanja->total_anggaran > 0 ? number_format(($jenisBelanja->realisasi_anggaran / $jenisBelanja->total_anggaran) * 100, 2) : 0 }}%
                                        </th>
                                    </tr>
                                </thead>

                                @foreach ($jenisBelanja->jenis_kategori_rkbus as $jenisKategori)
                                    <thead class="level-2">
                                        <tr>
                                            <th colspan="2">
                                                {{ $jenisKategori->kode_jenis_kategori_rkbu }}.
                                                {{ $jenisKategori->nama_jenis_kategori_rkbu }}
                                            </th>
                                            <td class="text-right">
                                                <small>{{ number_format($jenisKategori->total_anggaran) }}</small>
                                            </td>
                                            <td class="text-right">
                                                <small>{{ number_format($jenisKategori->realisasi_anggaran) }}</small>
                                            </td>
                                            <td class="text-right">
                                                <small>{{ number_format($jenisKategori->proses_anggaran) }}</small>
                                            </td>
                                            <td class="text-right">
                                                <small>{{ number_format($jenisKategori->proses_anggaran_bendahara) }}</small>
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($jenisKategori->total_anggaran - ($jenisKategori->realisasi_anggaran + $jenisKategori->proses_anggaran + $jenisKategori->proses_anggaran_bendahara)) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $jenisKategori->total_anggaran > 0 ? number_format(($jenisKategori->realisasi_anggaran / $jenisKategori->total_anggaran) * 100, 2) : 0 }}%
                                            </td>
                                        </tr>
                                    </thead>

                                    @foreach ($jenisKategori->obyek_belanjas as $obyek)
                                        @foreach ($obyek->kategori_rkbus as $kategori)
                                            <thead class="level-3">
                                                <tr>
                                                    <th colspan="2">
                                                        {{ $kategori->kode_kategori_rkbu }}.
                                                        {{ $kategori->nama_kategori_rkbu }}
                                                    </th>
                                                    <td class="text-right">
                                                        <small>{{ number_format($kategori->total_anggaran) }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        <small>{{ number_format($kategori->realisasi_anggaran) }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        <small>{{ number_format($kategori->proses_anggaran) }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        <small>{{ number_format($kategori->proses_anggaran_bendahara) }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($kategori->total_anggaran - ($kategori->realisasi_anggaran + $kategori->proses_anggaran + $kategori->proses_anggaran_bendahara)) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $kategori->total_anggaran > 0 ? number_format(($kategori->realisasi_anggaran / $kategori->total_anggaran) * 100, 2) : 0 }}%
                                                    </td>
                                                </tr>
                                            </thead>

                                            @foreach ($kategori->sub_kategori_rkbus as $sub)
                                                <thead class="level-4">
                                                    <tr>
                                                        <th></th>
                                                        <th>{{ $sub->kode_sub_kategori_rkbu }}.
                                                            {{ $sub->nama_sub_kategori_rkbu }}</th>
                                                        <td class="text-right">
                                                            <small>{{ number_format($sub->total_anggaran) }}</small>
                                                        </td>
                                                        <td class="text-right">
                                                            <small>{{ number_format($sub->realisasi_anggaran) }}</small>
                                                        </td>
                                                        <td class="text-right">
                                                            <small>{{ number_format($sub->proses_anggaran) }}</small>
                                                        </td>
                                                        <td class="text-right">
                                                            <small>{{ number_format($sub->proses_anggaran_bendahara) }}</small>
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($sub->total_anggaran - ($sub->realisasi_anggaran + $sub->proses_anggaran + $sub->proses_anggaran_bendahara)) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $sub->total_anggaran > 0 ? number_format(($sub->realisasi_anggaran / $sub->total_anggaran) * 100, 2) : 0 }}%
                                                        </td>
                                                    </tr>
                                                </thead>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach


                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Total Anggaran</th>
                                    <th class="text-right">{{ number_format($total_anggaran_blud) }}</th>
                                    <th class="text-right">{{ number_format($total_realisasi_blud) }}</th>
                                    <th class="text-right">{{ number_format($total_proses_spj_blud) }}</th>
                                    <th class="text-right">{{ number_format($total_proses_spj_bendahara_blud) }}</th>
                                    <th class="text-right">
                                        {{ number_format($total_anggaran_blud - ($total_realisasi_blud + $total_proses_spj_blud + $total_proses_spj_bendahara_blud)) }}
                                    </th>
                                    <th class="text-center">
                                        {{ $total_anggaran_blud > 0 ? number_format(($total_realisasi_blud / $total_anggaran_blud) * 100, 2) : 0 }}%
                                    </th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- .card-->
    </div> <!-- .col-->
</div> <!-- end row-->
