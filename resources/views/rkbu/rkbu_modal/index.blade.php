@extends('layouts.main')
@section('content')
   <div class="card mb-5 mb-xxl-12">
        <div class="card-body pt-9 pb-0">
            @include('rkbu.partials.headermenu')
            {{-- @include('rkbu.rkbu_modal.create') --}}
        </div>
    </div>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">RKBU Modal</span>
                    </h3>
                    <div class="card-toolbar">
                        <span id="barjas-selected-count" class="text-muted me-3">0 dipilih</span>
                        <button type="button" class="btn btn-sm btn-primary" id="barjas-bulk-submit" disabled>
                            <i class="fas fa-paper-plane me-1"></i> Ajukan Terpilih
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="modal-filter-form" action="{{ route('rkbu.modal_kantor.print') }}" method="GET" class="row g-3 align-items-end mb-5">
                        <div class="col-md-4 col-lg-3">
                            <label for="modal-filter-sumber-dana" class="form-label">Sumber Dana</label>
                            <select id="modal-filter-sumber-dana" name="sumber_dana_id" class="form-select">
                                <option value="">Semua Sumber Dana</option>
                                @foreach ($sumber_dana as $sumberDana)
                                    <option value="{{ $sumberDana->id_sumber_dana }}">{{ $sumberDana->nama_sumber_dana }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label for="modal-filter-subkategori" class="form-label">Subkategori RKBU</label>
                            <select id="modal-filter-subkategori" name="sub_kategori_rkbu_id" class="form-select"
                                data-control="select2" data-placeholder="Semua Subkategori RKBU">
                                <option value="">Semua Subkategori RKBU</option>
                                @foreach ($sub_kategori_rkbu as $subKategori)
                                    <option value="{{ $subKategori->id_sub_kategori_rkbu }}">
                                        {{ $subKategori->kode_sub_kategori_rkbu }} - {{ $subKategori->nama_sub_kategori_rkbu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <label for="modal-filter-status" class="form-label">Status Approval</label>
                            <select id="modal-filter-status" name="approval_status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach ($approval_statuses as $status => $label)
                                    <option value="{{ $status }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-3 d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary" id="modal-apply-filter">Terapkan Filter</button>
                            <button type="button" class="btn btn-light" id="modal-reset-filter">Reset</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                            </button>
                        </div>
                    </form>
                    <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4'], true) !!}
                         {{-- @include('rkbu.rkbu_modal.modal_edit') --}}
                        </div>
                    </div>
                    {{-- @include('rkbu.rkbu_modal.modal_detail') --}}
            </div>
        </div>
    </div>
    @include('rkbu.rkbu_modal.modal_show')
    @include('components.partials.calender')
    @push('scripts')
        <script>
            $('#rkbumodalkantor-table').on('preXhr.dt', function (event, settings, data) {
                data.sumber_dana_id = $('#modal-filter-sumber-dana').val();
                data.sub_kategori_rkbu_id = $('#modal-filter-subkategori').val();
                data.approval_status = $('#modal-filter-status').val();
            });

            $('#modal-apply-filter').on('click', function () {
                $('#rkbumodalkantor-table').DataTable().ajax.reload();
            });

            $('#modal-reset-filter').on('click', function () {
                $('#modal-filter-sumber-dana, #modal-filter-subkategori, #modal-filter-status').val('').trigger('change');
                $('#rkbumodalkantor-table').DataTable().ajax.reload();
            });
        </script>
        {!! $dataTable->scripts() !!}
    @endpush
@endsection
