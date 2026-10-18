    @extends('layouts.main')
    @section('content')
        <div class="card mb-5 mb-xxl-12">
            <div class="card-body pt-9 pb-0">
                @include('components.partials.table.headermenu')
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
                                @foreach ($sumberDanas as $sumberDana)
                                    <option value="{{ $sumberDana->id_sumber_dana }}">{{ $sumberDana->nama_sumber_dana }}</option>
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
                                <option value="">Semua Status Approval</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="revision">Revisi</option>
                            </select>
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
                                        <th>Detail Volume</th>
                                        <th>Harga Barang</th>
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
    @endsection
