<div class="row project-wrapper">
    <div class="col-xxl-12">
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary rounded-2 fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-briefcase">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2">
                                        </rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-3">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                    Anggaran Pegawai</p>
                                <div class="d-flex align-items-center mb-3">
                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="text-muted">Rp.
                                            {{ number_format($total_anggaran_pegawai_admin_apbd, 0, ',', ',') }}</span>
                                    </h4>
                                </div>
                                <p class="text-muted text-truncate mb-0">Projects this month</p>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div><!-- end col -->

            <div class="col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning rounded-2 fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-award">
                                        <circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-muted mb-3">Anggaran Barjas</p>
                                <div class="d-flex align-items-center mb-3">
                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="text-muted">Rp.
                                            {{ number_format($total_anggaran_barjas_admin_apbd, 0, ',', ',') }}</span>
                                    </h4>
                                </div>
                                <p class="text-muted mb-0">Leads this month</p>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div><!-- end col -->

            <div class="col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info rounded-2 fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-3">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                    Anggaran Modal</p>
                                <div class="d-flex align-items-center mb-3">
                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="text-muted">Rp.
                                            {{ number_format($total_anggaran_modal_admin_apbd, 0, ',', ',') }}</span>
                                    </h4>
                                </div>
                                <p class="text-muted text-truncate mb-0">Work this month</p>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Program dan Kegiatan</h4>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                                <i class="ri-file-list-3-line align-middle"></i> Generate
                                Report
                            </button>
                        </div>
                    </div><!-- end card header -->
                    @include('partials.animate_progress_form')

                    <div class="card-header shadow-sm rounded-3 p-0">
                        <table class="table modern-table table-bordered align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Total Anggaran</th>
                                    <th>Realisasi</th>
                                    <th>Sisa</th>
                                    <th>% Realisasi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $statusValidasi = '9cfb1f87-238b-4ea2-98f0-4255e578b1d1'; @endphp

                                @foreach ($programs as $program)
                                    @php
                                        $programAnggaran = 0;
                                        $programRealisasi = 0;

                                        foreach ($program->kegiatans ?? collect() as $kegiatan) {
                                            foreach ($kegiatan->sub_kegiatans ?? collect() as $sub) {
                                                foreach ($sub->aktivitass ?? collect() as $act) {
                                                    foreach ($act->rekening_belanjas ?? collect() as $rek) {
                                                        foreach ($rek->rkbus ?? collect() as $rk) {
                                                            if ($rk->id_status_validasi_rka == $statusValidasi) {
                                                                $programAnggaran += $rk->total_anggaran;

                                                                // 🔑 REALISASI DARI UBD
                                                                foreach (
                                                                    $rk->usulan_barang_details ?? collect()
                                                                    as $ubd
                                                                ) {
                                                                    $real =
                                                                        $realisasi[$ubd->id_usulan_barang_detail] ??
                                                                        null;

                                                                    if ($real) {
                                                                        $programRealisasi +=
                                                                            ($real->realisasi ?? 0) +
                                                                            ($real->proses ?? 0);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $programSisa = $programAnggaran - $programRealisasi;
                                        $programPersen =
                                            $programAnggaran > 0 ? ($programRealisasi / $programAnggaran) * 100 : 0;
                                    @endphp

                                    <tr class="table-info fw-bold">
                                        <td>{{ $program->kode_program }}</td>
                                        <td>{{ $program->nama_program }}</td>
                                        <td class="white-nowrap"> {{ number_format($programAnggaran, 0, ',', '.') }}
                                        </td>
                                        <td class="white-nowrap"> {{ number_format($programRealisasi, 0, ',', '.') }}
                                        </td>
                                        <td class="white-nowrap"> {{ number_format($programSisa, 0, ',', '.') }}
                                        </td>
                                        <td class="white-nowrap">{{ number_format($programPersen, 2) }}%</td>
                                    </tr>

                                    @foreach ($program->kegiatans as $kegiatan)
                                        @php
                                            $kegiatanAnggaran = 0;
                                            $kegiatanRealisasi = 0;

                                            foreach ($kegiatan->sub_kegiatans as $sub) {
                                                foreach ($sub->aktivitass as $act) {
                                                    foreach ($act->rekening_belanjas as $rek) {
                                                        foreach ($rek->rkbus as $rk) {
                                                            if ($rk->id_status_validasi_rka == $statusValidasi) {
                                                                $kegiatanAnggaran += $rk->total_anggaran;

                                                                foreach (
                                                                    $rk->usulan_barang_details ?? collect()
                                                                    as $ubd
                                                                ) {
                                                                    $real =
                                                                        $realisasi[$ubd->id_usulan_barang_detail] ??
                                                                        null;
                                                                    if ($real) {
                                                                        $kegiatanRealisasi +=
                                                                            $real->realisasi + $real->proses;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $kegiatanSisa = $kegiatanAnggaran - $kegiatanRealisasi;
                                            $kegiatanPersen =
                                                $kegiatanAnggaran > 0
                                                    ? ($kegiatanRealisasi / $kegiatanAnggaran) * 100
                                                    : 0;
                                        @endphp

                                        <tr class="table-light kegiatan-row">
                                            <td>— {{ $kegiatan->kode_kegiatan }}</td>
                                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                                            <td class="white-nowrap">
                                                {{ number_format($kegiatanAnggaran, 0, ',', '.') }}</td>
                                            <td class="white-nowrap">
                                                {{ number_format($kegiatanRealisasi, 0, ',', '.') }}</td>
                                            <td class="white-nowrap">
                                                {{ number_format($kegiatanSisa, 0, ',', '.') }}
                                            </td>
                                            <td class="white-nowrap">{{ number_format($kegiatanPersen, 2) }}%</td>

                                        </tr>

                                        @foreach ($kegiatan->sub_kegiatans as $sub)
                                            @php
                                                $subAnggaran = 0;
                                                $subRealisasi = 0;

                                                foreach ($sub->aktivitass as $act) {
                                                    foreach ($act->rekening_belanjas as $rek) {
                                                        foreach ($rek->rkbus as $rk) {
                                                            if ($rk->id_status_validasi_rka == $statusValidasi) {
                                                                $subAnggaran += $rk->total_anggaran;

                                                                foreach ($rk->usulan_barang_details as $ubd) {
                                                                    $real =
                                                                        $realisasi[$ubd->id_usulan_barang_detail] ??
                                                                        null;
                                                                    if ($real) {
                                                                        $subRealisasi +=
                                                                            $real->realisasi + $real->proses;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                $subSisa = $subAnggaran - $subRealisasi;
                                                $subPersen =
                                                    $subAnggaran > 0 ? ($subRealisasi / $subAnggaran) * 100 : 0;
                                            @endphp


                                            <tr class="sub-row">
                                                <td>—— {{ $sub->kode_sub_kegiatan }}</td>
                                                <td>{{ $sub->nama_sub_kegiatan }}</td>
                                                <td class="white-nowrap">
                                                    {{ number_format($subAnggaran, 0, ',', '.') }}</td>
                                                <td class="white-nowrap">
                                                    {{ number_format($subRealisasi, 0, ',', '.') }}</td>
                                                <td class="white-nowrap"> {{ number_format($subSisa, 0, ',', '.') }}
                                                </td>
                                                <td class="white-nowrap">{{ number_format($subPersen, 2) }}%</td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

    </div><!-- end col -->


</div>
