    <div class="col-xl-12">
        <div class="card">
            <div class="card-block">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Status Verifikasi Dokumen</h4>
                    <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="horizontal-form-showcode" class="form-label text-muted">Show Code</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="col-xl-12">
                        <div class="table-responsive">
                            <table class="table border-top">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-nowrap"></th>
                                        <th class="text-nowrap text-center">Pengurus Barang</th>
                                        <th class="text-nowrap text-center">PPBJ</th>
                                        <th class="text-nowrap text-center">PPK</th>
                                        <th class="text-nowrap text-center">PPTK</th>
                                        <th class="text-nowrap text-center">Verifikator</th>
                                        <th class="text-nowrap text-center">PPK(Keuangan)</th>
                                        <th class="text-nowrap text-center">Direktur</th>
                                        <th class="text-nowrap text-center">Bendahara</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-nowrap">Status Verifikasi</td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_pengurus_barang == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_ppbj == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_ppk == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_pptk == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_verifikator == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_ppk_keuangan == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_verifikasi_direktur == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check d-flex justify-content-center">
                                                @if ($master_spj->status_serah_terima_bendahara == 'Selesai')
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        checked disabled />
                                                @else
                                                    <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                        disabled />
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
