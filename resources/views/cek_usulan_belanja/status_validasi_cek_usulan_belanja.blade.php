<div class="card">
    <div class="card-header">
        <div class="d-flex">
            <h5 class="card-title flex-grow-1 mb-0">Status Validasi Usulan</h5>
            <div class="flex-shrink-0">
                <a href="javascript:void(0);" class="link-secondary">View Profile</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="profile-timeline">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                @if ($status->status_permintaan_barang == 'Perlu Validasi PPTK')
                    <div class="accordion-item border-0">
                        <div class="accordion-header" id="headingOne">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="ri-shopping-bag-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-0 fw-semibold">Usulan <span
                                                class="fw-normal">{{ $status->no_usulan_barang ?? 'N/A' }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body ms-2 ps-5 pt-0">
                                <h6 class="mb-1">Usulan belanja dibuat oleh {{ $status->nama_pengusul_barang }}</h6>
                                <p class="text-muted">
                                    {{ \Carbon\Carbon::parse($status->created_at)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <div class="accordion-header" id="headingFive">
                                <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                    href="#collapseFile" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <div class="avatar-title bg-light text-success rounded-circle shadow">
                                                <i class="mdi mdi-package-variant"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-0 fw-semibold">Perlu Validasi PPTK</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif ($status->status_permintaan_barang == 'Disetujui PPTK')
                    <div class="accordion-item border-0">
                        <div class="accordion-header" id="headingOne">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="ri-shopping-bag-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-0 fw-semibold">Usulan <span
                                                class="fw-normal">{{ $status->no_usulan_barang ?? 'N/A' }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="accordion-header" id="headingTwo">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="mdi mdi-gift-outline"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-1 fw-semibold">Valid PPTK</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body ms-2 ps-5 pt-0">
                                <h6 class="mb-1">Usulan Sudah di Validasi oleh
                                    {{ $status->ValidPptk->nama_lengkap ?? '-' }}
                                </h6>
                                <p class="text-muted mb-0">
                                    {{ \Carbon\Carbon::parse($status->tgl_validasi_pptk)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <div class="accordion-header" id="headingFive">
                                <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                    href="#collapseFile" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <div class="avatar-title bg-light text-success rounded-circle shadow">
                                                <i class="mdi mdi-package-variant"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-0 fw-semibold">Perlu Validasi Ka. Bagian</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif ($item->status_permintaan_barang == 'Disetujui Kabag')
                    <div class="accordion-item border-0">
                        <div class="accordion-header" id="headingOne">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="ri-shopping-bag-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-0 fw-semibold">Usulan <span
                                                class="fw-normal">{{ $status->no_usulan_barang ?? 'N/A' }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="accordion-header" id="headingTwo">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="mdi mdi-gift-outline"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-1 fw-semibold">Valid PPTK</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body ms-2 ps-5 pt-0">
                                <h6 class="mb-1">Usulan Sudah di Validasi oleh
                                    {{ $status->ValidPptk->nama_lengkap ?? '-' }}
                                </h6>
                                <p class="text-muted mb-0">
                                    {{ \Carbon\Carbon::parse($status->tgl_validasi_pptk)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="accordion-header" id="headingThree">
                            <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-success rounded-circle shadow">
                                            <i class="ri-truck-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fs-15 mb-1 fw-semibold">Validasi Ka. Bagian</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div id="collapseThree" class="accordion-collapse collapse show"
                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body ms-2 ps-5 pt-0">
                                <h6 class="fs-14">Data sudah divalidasi oleh
                                    {{ $item->validRka->nama_lengkap ?? '-' }}</h6>
                                <p class="text-muted mb-0">
                                    {{ \Carbon\Carbon::parse($item->tgl_validasi_kabag)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <div class="accordion-header" id="headingFour">
                                <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse"
                                    href="#collapseFile" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <div class="avatar-title bg-light text-success rounded-circle shadow">
                                                <i class="mdi mdi-package-variant"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-0 fw-semibold">Perlu Validasi Pimpinan</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="accordion-item border-0">
                    <div class="accordion-header" id="headingFour">
                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFour"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-light text-success rounded-circle shadow">
                                        <i class="ri-takeaway-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-0 fw-semibold">Out For Delivery</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="accordion-item border-0">
                    <div class="accordion-header" id="headingFive">
                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFile"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-light text-success rounded-circle shadow">
                                        <i class="mdi mdi-package-variant"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-0 fw-semibold">Delivered</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div><!--end accordion-->
        </div>
    </div>
</div><!--end card-->
