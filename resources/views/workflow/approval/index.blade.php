    @extends('layouts.main')
    @section('content')
        <div class="card approval-hero mb-6 shadow-sm">
            <div class="card-body p-5 p-lg-6">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <span class="approval-hero-icon" aria-hidden="true"><i class="fas fa-clipboard-check"></i></span>
                    <div>
                        <h1 class="fs-2 fw-bold text-gray-900 mb-1">Approval Saya</h1>
                        <div class="text-muted">Tinjau usulan RKBU dan kelola persetujuan sesuai kewenangan Anda.</div>
                    </div>
                </div>
                @isset($paguAccountSummary)
                    <div class="approval-account-summary">
                        @include('components.partials.table.pagu-summary')
                    </div>
                @endisset
            </div>
        </div>
        <div class="row gy-5 g-xl-8">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Approval RKBU</span>
                        </h3>
                        <div class="card-toolbar d-flex flex-wrap align-items-center gap-2">
                            <select id="approval-module-filter" class="form-select form-select-sm w-auto" data-control="select2">
                                <option value="all" {{ $module === 'all' ? 'selected' : '' }}>Semua Menu</option>
                                <option value="barjas" {{ $module === 'barjas' ? 'selected' : '' }}>RKBU Barjas</option>
                                <option value="pegawai" {{ $module === 'pegawai' ? 'selected' : '' }}>RKBU Pegawai</option>
                                <option value="persediaan" {{ $module === 'persediaan' ? 'selected' : '' }}>RKBU Persediaan</option>
                                <option value="modal" {{ $module === 'modal' ? 'selected' : '' }}>RKBU Modal</option>
                                <option value="modal-kantor" {{ $module === 'modal-kantor' ? 'selected' : '' }}>RKBU Modal Kantor</option>
                                <option value="modal-alkes" {{ $module === 'modal-alkes' ? 'selected' : '' }}>RKBU Modal Alkes</option>
                            </select>
                            <select id="approval-sumber-dana-filter" class="form-select form-select-sm w-auto" data-control="select2">
                                <option value="">Semua Sumber Dana</option>
                                <option value="__none">Sumber Dana Belum Ditentukan</option>
                                @foreach ($sumberDanas as $sumberDana)
                                    <option value="{{ $sumberDana->id_sumber_dana }}">{{ $sumberDana->nama_sumber_dana }}</option>
                                @endforeach
                            </select>
                            <select id="approval-sub-kegiatan-filter" class="form-select form-select-sm w-auto" data-control="select2"
                                data-placeholder="Semua Sub Kegiatan">
                                <option value="">Semua Sub Kegiatan Saat Ini</option>
                                @foreach ($subKegiatans as $subKegiatan)
                                    <option value="{{ $subKegiatan->id_sub_kegiatan }}">
                                        {{ $subKegiatan->kode_sub_kegiatan }} - {{ $subKegiatan->nama_sub_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="approval-subkategori-filter" class="form-select form-select-sm w-auto" data-control="select2"
                                data-placeholder="Semua Subkategori RKBU">
                                <option value="">Semua Subkategori RKBU</option>
                                @foreach ($subKategoris as $subKategori)
                                    <option value="{{ $subKategori->id_sub_kategori_rkbu }}">
                                        {{ $subKategori->kode_sub_kategori_rkbu }} - {{ $subKategori->nama_sub_kategori_rkbu }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="approval-status-filter" class="form-select form-select-sm w-auto" data-control="select2">
                                <option value="" {{ auth()->user()->hasRole('Super Admin') ? '' : 'selected' }}>Semua Status Approval</option>
                                <option value="pending" {{ auth()->user()->hasRole('Super Admin') ? 'selected' : '' }}>Pending</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="revision">Revisi</option>
                            </select>
                            @if (auth()->user()->hasRole('Super Admin'))
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bulkTaggingModal">
                                    <i class="fas fa-tags me-1"></i> Tagging Massal
                                </button>
                            @endif
                            <a id="approval-print-all" href="{{ route('workflowapproval.print-all') }}"
                                class="btn btn-sm btn-light-primary" target="_blank" rel="noopener">
                                <i class="fas fa-file-pdf me-1"></i> Cetak Semua RKBU
                            </a>
                            <span id="approval-selected-count" class="text-muted">0 dipilih</span>
                            @unless ($isSuperAdmin)
                                <button type="button" class="btn btn-sm btn-success" id="approval-bulk-approve" disabled>Approve Terpilih</button>
                            @endunless
                            <button type="button" class="btn btn-sm btn-warning" id="approval-bulk-revision" disabled>Revision Terpilih</button>
                            <button type="button" class="btn btn-sm btn-danger" id="approval-bulk-reject" disabled>Reject Terpilih</button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- @include('components.partials.table.showentrysearch') --}}
                        <div class="table-responsive">
                            <table id="tableApproval"
                                class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bolder text-muted">
                                        <th><input type="checkbox" class="form-check-input" id="approval-select-all" aria-label="Pilih semua approval pada halaman"></th>
                                        <th>No</th>
                                        <th>Detail Komponen</th>
                                        <th>Detail Persediaan</th>
                                        <th>Volume / Sisa</th>
                                        <th>Harga / PPN</th>
                                        <th>Total Anggaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.partials.calender')
        @include('workflow.approval.model_detail')
        @if (auth()->user()->hasRole('Super Admin'))
            @include('workflow.approval.bulk_tagging')
        @endif
        <style>
            .approval-hero { position: relative; overflow: hidden; border: 0; border-radius: 1rem; background: linear-gradient(115deg, #f1f7ff 0%, #fff 72%); }
            .approval-hero::after { content: ''; position: absolute; width: 210px; height: 210px; right: -55px; top: -90px; border-radius: 50%; background: rgba(54, 153, 255, .08); pointer-events: none; }
            .approval-hero > .card-body { position: relative; z-index: 1; }
            .approval-hero-icon { display: inline-flex; flex: 0 0 52px; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: .9rem; background: #e8f3ff; color: #3699ff; font-size: 1.35rem; }
            .approval-account-summary { margin-top: 1.25rem; }
            .approval-account-summary table { margin-bottom: 0; }
            .approval-account-summary th { white-space: nowrap; color: #78849a; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
            .approval-account-summary td { vertical-align: middle; }
            #tableApproval .persediaan-info-card { min-width: 270px; max-width: 350px; white-space: normal; line-height: 1.35; }
            #tableApproval td { vertical-align: top; }
            @media (max-width: 575.98px) {
                .approval-hero > .card-body { padding: 1.15rem !important; }
            }
        </style>
    @endsection
