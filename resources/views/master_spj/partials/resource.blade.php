<div class="container-fluid px-0 mx-0">
    <div class="row gx-0">
        <div class="col-sm-4">
            @if ($key->status_proses_pesanan == 'Selesai')
                <div class="border rounded border-dashed p-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light text-secondary rounded fs-24 shadow">
                                    <i class="ri-file-ppt-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fs-10 mb-1"><a href="#" class="text-body text-truncate d-block">SP</a></h5>
                            <div class="fs-10">2.2MB</div>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <a href="{{ route('spj_surat_pesanan.downloadPDF_Spj_surat_pesanan', $key->id_master_spj) }}"
                                class="btn btn-icon text-muted btn-sm fs-14 shadow-none">
                                <i class="ri-download-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-sm-4">
            @if ($key->status_proses_bast == 'Selesai')
                <div class="border rounded border-dashed p-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light text-secondary rounded fs-24 shadow">
                                    <i class="ri-file-ppt-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fs-10 mb-1"><a href="#" class="text-body text-truncate d-block">BAST
                                    Pemeriksa
                                    Pekerjaan</a></h5>
                            <div class="fs-10 mb-1">2.4MB</div>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <a href="{{ route('bast_pk.downloadPDF_BAST_PK', $key->id_master_spj) }}"
                                class="btn btn-icon text-muted btn-sm fs-14 shadow-none">
                                <i class="ri-download-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-sm-4">
            @if ($key->status_proses_bast == 'Selesai')
                <div class="border rounded border-dashed p-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light text-secondary rounded fs-24 shadow">
                                    <i class="ri-file-ppt-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fs-10 mb-1"><a href="#" class="text-body text-truncate d-block">BAST Barang
                                    /
                                    Pekerjaan</a></h5>
                            <div class="fs-10 mb-1">2.4MB</div>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <a href="{{ route('bast_bp.downloadPDF_BAST_BP', $key->id_master_spj) }}"
                                class="btn btn-icon text-muted btn-sm fs-14 shadow-none">
                                <i class="ri-download-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-sm-4">
            @if ($key->status_proses_bast == 'Selesai')
                <div class="border rounded border-dashed p-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light text-secondary rounded fs-24 shadow">
                                    <i class="ri-file-ppt-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fs-10 mb-1"><a href="#" class="text-body text-truncate d-block">BAST Hasil
                                    Pekerjaan</a></h5>
                            <div class="fs-10 mb-1">2.4MB</div>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <a href="{{ route('bast_hp.downloadPDF_BAST_HP', $key->id_master_spj) }}"
                                class="btn btn-icon text-muted btn-sm fs-14 shadow-none">
                                <i class="ri-download-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
