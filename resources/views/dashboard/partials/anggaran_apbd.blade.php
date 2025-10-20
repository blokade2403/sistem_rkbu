<div class="row project-wrapper">
    <div class="col-xxl-8">
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
            <!-- Sales Overview-->
            <div class="col-lg-12 col-12">
                @php
                    $statusValidasi = '9cfb1f87-238b-4ea2-98f0-4255e578b1d1';
                @endphp
                <div class="card">
                    <div class="card-header">
                        <div class="container">
                            <h3>Program dan Kegiatan</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Total Anggaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programs as $program)
                                        @php
                                            $programTotal = $program->kegiatans->sum(function ($kegiatan) use (
                                                $statusValidasi,
                                            ) {
                                                return $kegiatan->sub_kegiatans->sum(function ($sub) use (
                                                    $statusValidasi,
                                                ) {
                                                    return $sub->aktivitass->sum(function ($aktivitas) use (
                                                        $statusValidasi,
                                                    ) {
                                                        return $aktivitas->rekening_belanjas->sum(function (
                                                            $rekening,
                                                        ) use ($statusValidasi) {
                                                            return $rekening->rkbus
                                                                ->where('id_status_validasi_rka', $statusValidasi)
                                                                ->sum('total_anggaran');
                                                        });
                                                    });
                                                });
                                            });
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $program->kode_program }}</strong></td>
                                            <td><strong>{{ $program->nama_program }}</strong></td>
                                            <td><strong>Rp {{ number_format($programTotal, 0, ',', '.') }}</strong></td>
                                        </tr>
                                        @foreach ($program->kegiatans as $kegiatan)
                                            @php
                                                $kegiatanTotal = $kegiatan->sub_kegiatans->sum(function ($sub) use (
                                                    $statusValidasi,
                                                ) {
                                                    return $sub->aktivitass->sum(function ($aktivitas) use (
                                                        $statusValidasi,
                                                    ) {
                                                        return $aktivitas->rekening_belanjas->sum(function (
                                                            $rekening,
                                                        ) use ($statusValidasi) {
                                                            return $rekening->rkbus
                                                                ->where('id_status_validasi_rka', $statusValidasi)
                                                                ->sum('total_anggaran');
                                                        });
                                                    });
                                                });
                                            @endphp
                                            <tr>
                                                <td>— {{ $kegiatan->kode_kegiatan }}</td>
                                                <td>{{ $kegiatan->nama_kegiatan }}</td>
                                                <td>Rp {{ number_format($kegiatanTotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @foreach ($kegiatan->sub_kegiatans as $sub)
                                                @php
                                                    $subTotal = $sub->aktivitass->sum(function ($aktivitas) use (
                                                        $statusValidasi,
                                                    ) {
                                                        return $aktivitas->rekening_belanjas->sum(function (
                                                            $rekening,
                                                        ) use ($statusValidasi) {
                                                            return $rekening->rkbus
                                                                ->where('id_status_validasi_rka', $statusValidasi)
                                                                ->sum('total_anggaran');
                                                        });
                                                    });
                                                @endphp
                                                <tr>
                                                    <td>—— {{ $sub->kode_sub_kegiatan }}</td>
                                                    <td>{{ $sub->nama_sub_kegiatan }}</td>
                                                    <td>Rp {{ number_format($subTotal, 0, ',', '.') }}</td>
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
        </div><!-- end row -->
    </div><!-- end col -->

    <div class="col-xxl-4">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title mb-0">Upcoming Schedules</h4>
            </div><!-- end cardheader -->
            <div class="card-body pt-0">
                <div class="upcoming-scheduled">
                    <input type="text" class="form-control flatpickr-input" data-provider="flatpickr"
                        data-date-format="d M, Y" data-deafult-date="today" data-inline-date="true" readonly="readonly">
                    <div class="flatpickr-calendar animate inline" tabindex="-1">
                        <div class="flatpickr-months"><span class="flatpickr-prev-month"><svg version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 17 17">
                                    <g></g>
                                    <path
                                        d="M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z">
                                    </path>
                                </svg></span>
                            <div class="flatpickr-month">
                                <div class="flatpickr-current-month"><select class="flatpickr-monthDropdown-months"
                                        aria-label="Month" tabindex="-1">
                                        <option class="flatpickr-monthDropdown-month" value="0" tabindex="-1">
                                            January</option>
                                        <option class="flatpickr-monthDropdown-month" value="1" tabindex="-1">
                                            February</option>
                                        <option class="flatpickr-monthDropdown-month" value="2" tabindex="-1">
                                            March</option>
                                        <option class="flatpickr-monthDropdown-month" value="3" tabindex="-1">
                                            April</option>
                                        <option class="flatpickr-monthDropdown-month" value="4" tabindex="-1">
                                            May</option>
                                        <option class="flatpickr-monthDropdown-month" value="5" tabindex="-1">
                                            June</option>
                                        <option class="flatpickr-monthDropdown-month" value="6" tabindex="-1">
                                            July</option>
                                        <option class="flatpickr-monthDropdown-month" value="7" tabindex="-1">
                                            August</option>
                                        <option class="flatpickr-monthDropdown-month" value="8" tabindex="-1">
                                            September</option>
                                        <option class="flatpickr-monthDropdown-month" value="9" tabindex="-1">
                                            October</option>
                                        <option class="flatpickr-monthDropdown-month" value="10" tabindex="-1">
                                            November</option>
                                        <option class="flatpickr-monthDropdown-month" value="11" tabindex="-1">
                                            December</option>
                                    </select>
                                    <div class="numInputWrapper"><input class="numInput cur-year" type="number"
                                            tabindex="-1" aria-label="Year"><span class="arrowUp"></span><span
                                            class="arrowDown"></span></div>
                                </div>
                            </div><span class="flatpickr-next-month"><svg version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 17 17">
                                    <g></g>
                                    <path
                                        d="M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z">
                                    </path>
                                </svg></span>
                        </div>
                        <div class="flatpickr-innerContainer">
                            <div class="flatpickr-rContainer">
                                <div class="flatpickr-weekdays">
                                    <div class="flatpickr-weekdaycontainer">
                                        <span class="flatpickr-weekday">
                                            Sun</span><span class="flatpickr-weekday">Mon</span><span
                                            class="flatpickr-weekday">Tue</span><span
                                            class="flatpickr-weekday">Wed</span><span
                                            class="flatpickr-weekday">Thu</span><span
                                            class="flatpickr-weekday">Fri</span><span class="flatpickr-weekday">Sat
                                        </span>
                                    </div>
                                </div>
                                <div class="flatpickr-days" tabindex="-1">
                                    <div class="dayContainer"><span class="flatpickr-day prevMonthDay"
                                            aria-label="September 28, 2025" tabindex="-1">28</span><span
                                            class="flatpickr-day prevMonthDay" aria-label="September 29, 2025"
                                            tabindex="-1">29</span><span class="flatpickr-day prevMonthDay"
                                            aria-label="September 30, 2025" tabindex="-1">30</span><span
                                            class="flatpickr-day " aria-label="October 1, 2025"
                                            tabindex="-1">1</span><span class="flatpickr-day "
                                            aria-label="October 2, 2025" tabindex="-1">2</span><span
                                            class="flatpickr-day " aria-label="October 3, 2025"
                                            tabindex="-1">3</span><span class="flatpickr-day "
                                            aria-label="October 4, 2025" tabindex="-1">4</span><span
                                            class="flatpickr-day " aria-label="October 5, 2025"
                                            tabindex="-1">5</span><span class="flatpickr-day "
                                            aria-label="October 6, 2025" tabindex="-1">6</span><span
                                            class="flatpickr-day " aria-label="October 7, 2025"
                                            tabindex="-1">7</span><span class="flatpickr-day "
                                            aria-label="October 8, 2025" tabindex="-1">8</span><span
                                            class="flatpickr-day " aria-label="October 9, 2025"
                                            tabindex="-1">9</span><span class="flatpickr-day "
                                            aria-label="October 10, 2025" tabindex="-1">10</span><span
                                            class="flatpickr-day " aria-label="October 11, 2025"
                                            tabindex="-1">11</span><span class="flatpickr-day "
                                            aria-label="October 12, 2025" tabindex="-1">12</span><span
                                            class="flatpickr-day " aria-label="October 13, 2025"
                                            tabindex="-1">13</span><span class="flatpickr-day "
                                            aria-label="October 14, 2025" tabindex="-1">14</span><span
                                            class="flatpickr-day " aria-label="October 15, 2025"
                                            tabindex="-1">15</span><span class="flatpickr-day "
                                            aria-label="October 16, 2025" tabindex="-1">16</span><span
                                            class="flatpickr-day today selected" aria-label="October 17, 2025"
                                            aria-current="date" tabindex="-1">17</span><span class="flatpickr-day "
                                            aria-label="October 18, 2025" tabindex="-1">18</span><span
                                            class="flatpickr-day " aria-label="October 19, 2025"
                                            tabindex="-1">19</span><span class="flatpickr-day "
                                            aria-label="October 20, 2025" tabindex="-1">20</span><span
                                            class="flatpickr-day " aria-label="October 21, 2025"
                                            tabindex="-1">21</span><span class="flatpickr-day "
                                            aria-label="October 22, 2025" tabindex="-1">22</span><span
                                            class="flatpickr-day " aria-label="October 23, 2025"
                                            tabindex="-1">23</span><span class="flatpickr-day "
                                            aria-label="October 24, 2025" tabindex="-1">24</span><span
                                            class="flatpickr-day " aria-label="October 25, 2025"
                                            tabindex="-1">25</span><span class="flatpickr-day "
                                            aria-label="October 26, 2025" tabindex="-1">26</span><span
                                            class="flatpickr-day " aria-label="October 27, 2025"
                                            tabindex="-1">27</span><span class="flatpickr-day "
                                            aria-label="October 28, 2025" tabindex="-1">28</span><span
                                            class="flatpickr-day " aria-label="October 29, 2025"
                                            tabindex="-1">29</span><span class="flatpickr-day "
                                            aria-label="October 30, 2025" tabindex="-1">30</span><span
                                            class="flatpickr-day " aria-label="October 31, 2025"
                                            tabindex="-1">31</span><span class="flatpickr-day nextMonthDay"
                                            aria-label="November 1, 2025" tabindex="-1">1</span><span
                                            class="flatpickr-day nextMonthDay" aria-label="November 2, 2025"
                                            tabindex="-1">2</span><span class="flatpickr-day nextMonthDay"
                                            aria-label="November 3, 2025" tabindex="-1">3</span><span
                                            class="flatpickr-day nextMonthDay" aria-label="November 4, 2025"
                                            tabindex="-1">4</span><span class="flatpickr-day nextMonthDay"
                                            aria-label="November 5, 2025" tabindex="-1">5</span><span
                                            class="flatpickr-day nextMonthDay" aria-label="November 6, 2025"
                                            tabindex="-1">6</span><span class="flatpickr-day nextMonthDay"
                                            aria-label="November 7, 2025" tabindex="-1">7</span><span
                                            class="flatpickr-day nextMonthDay" aria-label="November 8, 2025"
                                            tabindex="-1">8</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- end cardbody -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>
