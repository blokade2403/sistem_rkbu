@extends('layouts.main')
@section('title', 'Usulan Belanja')

@section('content')
<div class="card mb-5 mb-xxl-12">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold mb-0">Usulan Belanja</h3>
        </div>
        <div class="card-toolbar">
            <a id="usulan-export-excel" href="{{ route('usulan-belanja.export') }}" class="btn btn-light-success me-3">
                <i class="ki-duotone ki-file-down fs-2"></i> Download Excel
            </a>
            <a href="{{ route('usulan-belanja.create') }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i> Buat Usulan
            </a>
        </div>
    </div>

    <div class="card-body py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-6">
            <div class="col-md-4">
                <label for="usulan-year-filter" class="form-label">Tahun Anggaran</label>
                <select id="usulan-year-filter" class="form-select" data-control="select2" data-placeholder="Semua Tahun">
                    @foreach($tahunAnggarans as $tahun)
                        <option value="{{ $tahun->id }}" selected>{{ $tahun->nama_tahun_anggaran }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="usulan-status-filter" class="form-label">Status Proses</label>
                <select id="usulan-status-filter" class="form-select" data-control="select2" data-placeholder="Semua Status">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="proses">Dalam Proses</option>
                    <option value="revision">Perlu Revisi</option>
                    <option value="approved">Selesai</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="usulan-jenis-filter" class="form-label">Jenis Kategori RKBU</label>
                <select id="usulan-jenis-filter" class="form-select" data-control="select2" data-placeholder="Semua Jenis">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisKategoriRkbus as $jenis)
                        <option value="{{ $jenis->id_jenis_kategori_rkbu }}">{{ $jenis->nama_jenis_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="usulan-subkategori-filter" class="form-label">Subkategori RKBU</label>
                <select id="usulan-subkategori-filter" class="form-select" data-control="select2" data-placeholder="Semua Subkategori">
                    <option value="">Semua Subkategori</option>
                    @foreach($subKategoriRkbus as $subkategori)
                        <option value="{{ $subkategori->id_sub_kategori_rkbu }}" data-jenis="{{ $subkategori->id_jenis_kategori_rkbu }}">{{ $subkategori->nama_sub_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="button" id="usulan-filter-button" class="btn btn-primary">Filter</button>
                <button type="button" id="usulan-reset-button" class="btn btn-light">Reset</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-usulan-belanja" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th class="min-w-50px">No</th>
                        <th class="min-w-300px">No Inv</th>
                        <th class="min-w-400px">Status Validasi</th>
                        <th class="min-w-125px">Status Proses</th>
                        <th class="min-w-150px text-end">Total Nominal</th>
                        <th class="min-w-100px text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#table-usulan-belanja').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            order: [],
            ajax: {
                url: @json(route('usulan-belanja.data')),
                data: function (data) {
                    data.tahun_anggaran_id = $('#usulan-year-filter').val();
                    data.status = $('#usulan-status-filter').val();
                    data.jenis_kategori_rkbu_id = $('#usulan-jenis-filter').val();
                    data.sub_kategori_rkbu_id = $('#usulan-subkategori-filter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'no_inv', name: 'no_usulan_belanja', searchable: true, orderable: false },
                { data: 'status_validasi', name: 'status_validasi', searchable: false, orderable: false },
                { data: 'status_proses', name: 'status_usulan', searchable: false, orderable: false },
                { data: 'total_nominal', name: 'total_nominal', searchable: false, orderable: false, className: 'text-end fw-bold' },
                { data: 'aksi', name: 'aksi', searchable: false, orderable: false, className: 'text-end' }
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                processing: 'Memuat data...',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ usulan',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ usulan',
                infoEmpty: 'Belum ada usulan belanja',
                zeroRecords: 'Usulan belanja tidak ditemukan',
                emptyTable: 'Belum ada Usulan Belanja.',
                paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
            }
        });

        $('#usulan-filter-button').on('click', function () {
            table.ajax.reload();
        });

        $('#usulan-jenis-filter').on('change', function () {
            const jenisId = $(this).val();
            const subkategori = $('#usulan-subkategori-filter');
            subkategori.find('option').each(function (index) {
                if (index === 0) return;
                const visible = !jenisId || $(this).data('jenis') === jenisId;
                $(this).prop('disabled', !visible).prop('hidden', !visible);
            });
            if (subkategori.find(':selected').prop('disabled')) subkategori.val('').trigger('change.select2');
        });

        $('#usulan-reset-button').on('click', function () {
            $('#usulan-year-filter, #usulan-status-filter, #usulan-jenis-filter, #usulan-subkategori-filter').val('').trigger('change.select2');
            $('#usulan-subkategori-filter option').prop('disabled', false).prop('hidden', false);
            table.search('');
            table.ajax.reload();
        });

        $('#usulan-export-excel').on('click', function (event) {
            event.preventDefault();
            const params = new URLSearchParams();
            const year = $('#usulan-year-filter').val();
            const status = $('#usulan-status-filter').val();
            const jenis = $('#usulan-jenis-filter').val();
            const subkategori = $('#usulan-subkategori-filter').val();
            const search = table.search();
            if (year) params.set('tahun_anggaran_id', year);
            if (status) params.set('status', status);
            if (jenis) params.set('jenis_kategori_rkbu_id', jenis);
            if (subkategori) params.set('sub_kategori_rkbu_id', subkategori);
            if (search) params.set('search', search);
            const query = params.toString();
            window.location.href = this.href + (query ? '?' + query : '');
        });
    });
</script>
@endpush
