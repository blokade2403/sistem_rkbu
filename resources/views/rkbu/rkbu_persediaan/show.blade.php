<div class="post d-flex flex-column-fluid" id="kt_post">
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap justify-content-start">
            <div class="d-flex flex-wrap">
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-6 fw-bolder">{{ $barjas->rkbu->subKategori->kode_sub_kategori_rkbu }}.
                            {{ $barjas->rkbu->subKategori->nama_sub_kategori_rkbu ?? '-' }}</div>
                    </div>
                    <div class="fw-bold fs-8 text-gray-400">Kode Rekening</div>
                </div>
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <span class="svg-icon svg-icon-1 svg-icon-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                    fill="black"></rect>
                                <path
                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="fw-bold fs-8 text-gray-400">Validasi KSP</div>
                </div>
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <span class="svg-icon svg-icon-1 svg-icon-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                    fill="black"></rect>
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="black"></rect>
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                    <div class="fw-bold fs-8 text-gray-400">Validasi Kabag</div>
                </div>
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-6 fw-bolder">BLUD</div>
                    </div>
                    <div class="fw-bold fs-8 text-gray-400">Sumber Dana</div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap flex-sm-nowrap mb-2">
            <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-50px h-50px w-lg-50px h-lg-50px me-2 mb-2">
                <img class="mw-50px mw-lg-20px" src="{{ asset('assets/media/svg/brand-logos/volicity-9.svg') }}"
                    alt="image" />
            </div>
            <div class="flex-grow-1">
                <div class="card mb-3 mb-xxl-12">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="d-flex flex-column">
                                    <a href="#"
                                        class="text-gray-900 text-hover-primary fs-6 fw-bolder">{{ $barjas->nama_barang }}</a>
                                    <span class="text-gray-400 fw-bold">Created: {{ $barjas->created_at }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-7">
                            <div class="text-gray-800 mb-5">
                                <span class="fw-bolder">Spesifikasi Barang :</span>
                                {{ $barjas->spesifikasi ?? '-' }}
                            </div>
                            <div class="d-flex flex-stack position-relative mt-8 mb-8">
                                <div class="position-absolute h-100 w-4px bg-warning rounded top-0 start-0">
                                </div>
                                <div class="fw-bold ms-6 w-100 text-gray-600">
                                    <div class="text-gray-800 mb-1">
                                        Quantity :
                                        <span class="fw-normal">{{ $barjas->volume_1 }} {{ $barjas->satuan_1 }} x
                                            {{ $barjas->volume_2 }} {{ $barjas->satuan_2 }}</span>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <div class="col-md-6">
                                            <div class="text-gray-800">
                                                Harga Satuan :
                                                <span class="text-hover-primary">
                                                    {{ number_format($barjas->harga_satuan) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-gray-800">
                                                PPN :
                                                <span class="text-hover-primary">
                                                    {{ $barjas->ppn }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fw-bolder text-gray-800 text-hover-primary d-block">
                                        Total Anggaran :
                                        <span class="text-primary">Rp. {{ $barjas->total_anggaran }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-stack position-relative mt-8 mb-8">
                                <div class="position-absolute h-100 w-4px bg-primary rounded top-0 start-0">
                                </div>
                                <div class="fw-bold ms-6 w-100 text-gray-600">
                                    <div class="text-gray-800 mb-1">
                                        Penempatan Barang :
                                        <span class="fw-normal">{{ $barjas->penempatan ?? '-' }}</span>
                                    </div>
                                    <div class="text-gray-800 mb-1">
                                        Skala Prioritas :
                                        <span class="fw-normal fw-bold text-active-info">Prioritas
                                            {{ $barjas->rating ?? '-' }}</span>
                                    </div>
                                    <div class="text-gray-800">
                                        ID RKBU :
                                        <span class="text-hover-primary">
                                            {{ $barjas->id }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-stack position-relative mt-8 mb-8">
                                <div class="position-absolute h-100 w-4px bg-danger rounded top-0 start-0">
                                </div>
                                <div class="fw-bold ms-6 w-100 text-gray-600">
                                    <div class="text-gray-800">
                                        Pengusul :
                                        <span class="text-hover-primary">
                                            {{ $barjas->rkbu->user->nama ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="text-gray-800">
                                        Unit :
                                        <span class="text-hover-primary">
                                            {{ $barjas->rkbu->unit->nama_unit ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="text-gray-800">
                                        Status RKBU :
                                        <span class="badge badge-light-primary">
                                            {{ strtoupper($barjas->rkbu->status ?? '-') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">

                                <div class="d-flex align-items-center">
                                    <div
                                        class="fs-6 fw-bolder
                                            @if ($barjas->rkbu->status == 'approved') text-success
                                            @elseif($barjas->rkbu->status == 'revision')
                                                text-warning
                                            @elseif($barjas->rkbu->status == 'rejected')
                                                text-danger
                                            @else
                                                text-primary @endif">
                                        {{ strtoupper($barjas->rkbu->status ?? '-') }}

                                    </div>
                                </div>

                                <div class="fw-bold fs-8 text-gray-400">
                                    Status RKBU
                                </div>

                                @php

                                    $approvalNote = \App\Models\Workflow\Approval::whereHas(
                                        'workflowInstance',
                                        function ($q) use ($barjas) {
                                            $q->where('dokumen_id', $barjas->rkbu->id);
                                        },
                                    )
                                        ->whereIn('status', ['revision', 'rejected'])
                                        ->latest()
                                        ->first();

                                @endphp

                                @if ($approvalNote)

                                    <div
                                        class="alert
    {{ $approvalNote->status == 'revision' ? 'alert-warning' : 'alert-danger' }}
    mt-5">

                                        <div class="fw-bold mb-2">

                                            @if ($approvalNote->status == 'revision')
                                                Catatan Revisi
                                            @else
                                                Alasan Penolakan
                                            @endif

                                        </div>

                                        <div>
                                            {{ $approvalNote->catatan }}
                                        </div>

                                        <div class="text-muted fs-8 mt-2">
                                            {{ $approvalNote->approved_at }}
                                        </div>

                                    </div>

                                @endif

                            </div>
                        </div>
                        <div
                            class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-3 mb-10">
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-bold ms-6 w-100 text-gray-600">
                                    <div class="text-gray-800 mb-1">
                                        Sisa Volume Barang :
                                        <span class="fw-normal">{{ $barjas->sisa_vol_rkbu }} {{ $barjas->satuan_1 }}
                                            / {{ $barjas->satuan_2 }}</span>
                                    </div>
                                    <div class="fw-bolder text-gray-800 text-hover-primary d-block">
                                        Sisa Anggaran :
                                        <span class="text-primary">Rp. {{ $barjas->sisa_anggaran_rkbu }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex align-items-start">
                                    <div class="symbol symbol-30px me-5 mt-1">
                                        <img alt="Icon" src="{{ asset('assets/media/svg/files/pdf.svg') }}" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="mb-3">
                                            <a class="fs-6 fw-bolder text-dark text-hover-primary" href="#">
                                                Dokumen Pendukung.pdf
                                            </a>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="text-gray-400">
                                                    KAP :
                                                    @if ($barjas->upload_file_1)
                                                        <a href="{{ asset('storage/' . $barjas->upload_file_1) }}"
                                                            target="_blank" class="text-hover-primary">
                                                            Lihat KAP
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="text-gray-400">
                                                    KAK :
                                                    @if ($barjas->upload_file_2)
                                                        <a href="{{ asset('storage/' . $barjas->upload_file_2) }}"
                                                            target="_blank" class="text-hover-primary">
                                                            Lihat KAK
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="text-gray-400">
                                                    SPH :
                                                    @if ($barjas->upload_file_3)
                                                        <a href="{{ asset('storage/' . $barjas->upload_file_3) }}"
                                                            target="_blank" class="text-hover-primary">
                                                            Lihat SPH
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="separator mb-4"></div>
                    Link Ekatalog :
                    {{ $barjas->link_ekatalog ?? '-' }}
                </div>
            </div>
        </div>
    </div>
    <div class="separator"></div>
</div>
