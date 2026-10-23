@extends('layouts.main')
@section('content')
   <div class="card mb-5 mb-xxl-12">
        <div class="card-body pt-9 pb-0">
            @include('rkbu.partials.headermenu')
            {{-- @include('rkbu.rkbu_persediaan.create') --}}
        </div>
    </div>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">RKBU Persediaan</span>
                    </h3>
                    <div class="card-toolbar">
                        <span id="persediaan-selected-count" class="text-muted me-3">0 dipilih</span>
                        <button type="button" class="btn btn-sm btn-primary" id="persediaan-bulk-submit" disabled>
                            <i class="fas fa-paper-plane me-1"></i> Ajukan Terpilih
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="persediaan-filter-form" action="{{ route('rkbu.persediaan.print') }}" method="GET" class="row g-3 align-items-end mb-5">
                        <div class="col-md-4 col-lg-3">
                            <label for="filter-sumber-dana" class="form-label">Sumber Dana</label>
                            <select id="filter-sumber-dana" name="sumber_dana_id" class="form-select">
                                <option value="">Semua Sumber Dana</option>
                                @foreach ($sumber_dana as $sumberDana)
                                    <option value="{{ $sumberDana->id_sumber_dana }}">
                                        {{ $sumberDana->nama_sumber_dana }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label for="persediaan-filter-subkategori" class="form-label">Subkategori RKBU</label>
                            <select id="persediaan-filter-subkategori" name="sub_kategori_rkbu_id" class="form-select"
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
                            <label for="filter-approval-status" class="form-label">Status Approval</label>
                            <select id="filter-approval-status" name="approval_status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach ($approval_statuses as $status => $label)
                                    <option value="{{ $status }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-3 d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary" id="persediaan-apply-filter">
                                Terapkan Filter
                            </button>
                            <button type="button" class="btn btn-light" id="persediaan-reset-filter">
                                Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                            </button>
                        </div>
                    </form>
                    <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4'], true) !!}
                         {{-- @include('rkbu.rkbu_persediaan.modal_edit') --}}
                        </div>
                    </div>
                    {{-- @include('rkbu.rkbu_persediaan.modal_detail') --}}
            </div>
        </div>
    </div>
    @include('rkbu.rkbu_persediaan.modal_show')
    @include('components.partials.calender')
    @push('scripts')
        <script>
            const persediaanTable = $('#rkbubarjas-table');

            persediaanTable.on('preXhr.dt', function (event, settings, data) {
                data.sumber_dana_id = $('#filter-sumber-dana').val();
                data.sub_kategori_rkbu_id = $('#persediaan-filter-subkategori').val();
                data.approval_status = $('#filter-approval-status').val();
            });

            $('#persediaan-apply-filter').on('click', function () {
                persediaanTable.DataTable().ajax.reload();
            });

            $('#persediaan-reset-filter').on('click', function () {
                $('#filter-sumber-dana, #persediaan-filter-subkategori, #filter-approval-status').val('').trigger('change');
                persediaanTable.DataTable().ajax.reload();
            });

            function updatePersediaanSelection() {
                if (!$.fn.DataTable.isDataTable(persediaanTable)) return;
                const table = persediaanTable.DataTable();
                const selectedCount = table.$('input[type="checkbox"]:checked:not(#persediaan-select-all)').length;
                $('#persediaan-selected-count').text(selectedCount + ' dipilih');
                $('#persediaan-bulk-submit').prop('disabled', selectedCount === 0);
            }

            persediaanTable.on('change', 'input[type="checkbox"]:not(#persediaan-select-all)', updatePersediaanSelection);
            persediaanTable.on('change', '#persediaan-select-all', function() {
                persediaanTable.DataTable().$('input[type="checkbox"]:not(:disabled):not(#persediaan-select-all)')
                    .prop('checked', this.checked);
                updatePersediaanSelection();
            });
            persediaanTable.on('draw.dt', function() {
                $('#persediaan-select-all').prop('checked', false);
                updatePersediaanSelection();
            });

            $('#persediaan-bulk-submit').on('click', function() {
                const ids = persediaanTable.DataTable()
                    .$('input[type="checkbox"]:checked:not(:disabled):not(#persediaan-select-all)')
                    .map(function() { return this.value; })
                    .get();
                if (!ids.length) return;

                Swal.fire({
                    title: 'Ajukan RKBU terpilih?',
                    text: ids.length + ' data Persediaan akan diajukan untuk proses validasi.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ajukan',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: '{{ route('rkbu.persediaan.bulk-submit') }}',
                        type: 'POST',
                        data: { ids: ids },
                        success: function(response) {
                            persediaanTable.DataTable().ajax.reload();
                            const failures = (response.results || []).filter(function(item) { return !item.success; });
                            const detail = failures.map(function(item) {
                                return $('<div>').text(item.message).html();
                            }).join('<br>');
                            Swal.fire({
                                icon: failures.length ? 'warning' : 'success',
                                title: failures.length ? 'Sebagian pengajuan gagal' : 'Berhasil',
                                html: response.message + (detail ? '<br><br>' + detail : '')
                            });
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Gagal mengajukan RKBU Persediaan terpilih.');
                        }
                    });
                });
            });
        </script>
        {!! $dataTable->scripts() !!}
    @endpush
@endsection
