@extends('layouts.main')

@section('content')
    <div class="card mb-5 mb-xxl-12">
        <div class="card-body pt-9 pb-0">
            @include('rkbu.partials.headermenu')
        </div>
    </div>

    <div class="row gy-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title"><span class="card-label fw-bolder fs-3 mb-1">RKBU Pegawai</span></h3>
                    <div class="card-toolbar">
                        <span id="pegawai-selected-count" class="text-muted me-3">0 dipilih</span>
                        <button type="button" class="btn btn-sm btn-primary" id="pegawai-bulk-submit" disabled>
                            <i class="fas fa-paper-plane me-1"></i> Ajukan Terpilih
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row g-3 align-items-end mb-5">
                        <div class="col-md-4 col-lg-3">
                            <label for="pegawai-filter-sumber-dana" class="form-label">Sumber Dana</label>
                            <select id="pegawai-filter-sumber-dana" class="form-select">
                                <option value="">Semua Sumber Dana</option>
                                @foreach ($sumber_dana as $sumberDana)
                                    <option value="{{ $sumberDana->id_sumber_dana }}">{{ $sumberDana->nama_sumber_dana }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <label for="pegawai-filter-subkategori" class="form-label">Subkategori RKBU</label>
                            <select id="pegawai-filter-subkategori" class="form-select" data-control="select2"
                                data-placeholder="Semua Subkategori RKBU">
                                <option value="">Semua Subkategori RKBU</option>
                                @foreach ($sub_kategori_rkbu as $subcategory)
                                    <option value="{{ $subcategory->id_sub_kategori_rkbu }}">
                                        {{ $subcategory->kode_sub_kategori_rkbu }} - {{ $subcategory->nama_sub_kategori_rkbu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <label for="pegawai-filter-status" class="form-label">Status Approval</label>
                            <select id="pegawai-filter-status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach ($approval_statuses as $status => $label)
                                    <option value="{{ $status }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-3 d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary" id="pegawai-apply-filter">Terapkan Filter</button>
                            <button type="button" class="btn btn-light" id="pegawai-reset-filter">Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.partials.calender')
    @push('scripts')
        <script>
            (() => {
                const tableSelector = '#rkbupegawai-table';
                const table = $(tableSelector);
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                const selectedIds = new Set();
                const checkboxes = () => table.length && $.fn.DataTable.isDataTable(tableSelector)
                    ? Array.from(table.DataTable().$('.pegawai-row-checkbox:not(:disabled)'))
                    : [];
                const refreshSelection = () => {
                    const available = checkboxes();
                    available.forEach((checkbox) => { checkbox.checked = selectedIds.has(checkbox.value); });
                    const selectedOnPage = available.filter((checkbox) => checkbox.checked);
                    document.getElementById('pegawai-selected-count').textContent = `${selectedIds.size} dipilih`;
                    document.getElementById('pegawai-bulk-submit').disabled = selectedIds.size === 0;
                    const selectAll = document.getElementById('pegawai-select-all');
                    if (selectAll) {
                        selectAll.checked = available.length > 0 && selectedOnPage.length === available.length;
                        selectAll.indeterminate = selectedOnPage.length > 0 && selectedOnPage.length < available.length;
                    }
                };

                table.on('preXhr.dt', (event, settings, data) => {
                    data.sumber_dana_id = document.getElementById('pegawai-filter-sumber-dana').value;
                    data.sub_kategori_rkbu_id = document.getElementById('pegawai-filter-subkategori').value;
                    data.approval_status = document.getElementById('pegawai-filter-status').value;
                });
                table.on('draw.dt', refreshSelection);
                document.getElementById('pegawai-apply-filter').addEventListener('click', () => table.DataTable().ajax.reload());
                document.getElementById('pegawai-reset-filter').addEventListener('click', () => {
                    ['pegawai-filter-sumber-dana', 'pegawai-filter-subkategori', 'pegawai-filter-status']
                        .forEach((id) => { document.getElementById(id).value = ''; });
                    $('#pegawai-filter-subkategori').trigger('change');
                    table.DataTable().ajax.reload();
                });

                document.addEventListener('change', (event) => {
                    if (event.target.id === 'pegawai-select-all') {
                        checkboxes().forEach((checkbox) => {
                            checkbox.checked = event.target.checked;
                            if (event.target.checked) selectedIds.add(checkbox.value);
                            else selectedIds.delete(checkbox.value);
                        });
                        refreshSelection();
                    } else if (event.target.classList.contains('pegawai-row-checkbox')) {
                        if (event.target.checked) selectedIds.add(event.target.value);
                        else selectedIds.delete(event.target.value);
                        refreshSelection();
                    }
                });

                document.getElementById('pegawai-bulk-submit').addEventListener('click', async () => {
                    const ids = Array.from(selectedIds);
                    if (!ids.length) return;
                    if (ids.length > 100) {
                        window.alert('Maksimal 100 RKBU dapat diajukan sekaligus.');
                        return;
                    }
                    if (!window.confirm(`Ajukan ${ids.length} RKBU Pegawai terpilih?`)) return;
                    try {
                        const response = await fetch(@json(route('rkbu.pegawai.bulk-submit')), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                            body: JSON.stringify({ ids }),
                        });
                        const result = await response.json();
                        if (!response.ok) throw new Error(result.message || 'Gagal mengajukan RKBU terpilih.');
                        const failures = (result.results || []).filter((item) => !item.success);
                        const detail = failures.map((item) => item.message).filter(Boolean).join('\n');
                        window.alert([result.message || 'Pengajuan selesai.', detail].filter(Boolean).join('\n\n'));
                        if (!failures.length) selectedIds.clear();
                        else failures.forEach((item) => selectedIds.add(item.id));
                        table.DataTable().ajax.reload(null, false);
                    } catch (error) {
                        window.alert(error.message || 'Gagal mengajukan RKBU Pegawai terpilih.');
                    }
                });

                document.addEventListener('click', async (event) => {
                    const submit = event.target.closest('.pegawai-submit');
                    const remove = event.target.closest('.pegawai-delete');
                    if (submit) {
                        try {
                            const response = await fetch(submit.dataset.url, {
                                method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                            });
                            const result = await response.json();
                            if (!response.ok) throw new Error(result.message || 'Gagal mengajukan RKBU Pegawai.');
                            window.alert(result.message || 'Pengajuan selesai.');
                            table.DataTable().ajax.reload(null, false);
                        } catch (error) {
                            window.alert(error.message || 'Gagal mengajukan RKBU Pegawai.');
                        }
                    }
                    if (remove && window.confirm('Hapus data RKBU Pegawai ini?')) {
                        const response = await fetch(remove.dataset.url, {
                            method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        });
                        const result = await response.json();
                        window.alert(result.message || (response.ok ? 'Data berhasil dihapus.' : 'Data gagal dihapus.'));
                        table.DataTable().ajax.reload(null, false);
                    }
                });

                refreshSelection();
            })();
        </script>
        {!! $dataTable->scripts() !!}
    @endpush
@endsection
