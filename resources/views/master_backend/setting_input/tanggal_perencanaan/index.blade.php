@extends('layouts.main')

@section('content')
    @include('master_backend.setting_input.tanggal_perencanaan.modal_add')

    <div class="card border-0 shadow-sm" style="border-radius:16px">
        <div class="card-header border-0 pt-6 pb-3 flex-wrap">
            <div>
                <h3 class="card-title fw-bolder fs-2 text-gray-900">Tanggal Pengesahan RKBU / RBA</h3>
                <div class="text-muted mt-1">Atur tanggal dan kota pengesahan berdasarkan tahun anggaran dan fase.</div>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tanggal_perencanaan_add">
                <i class="fas fa-plus me-2"></i>Tambah tanggal
            </button>
        </div>
        <div class="card-body pt-2">
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center"><i class="fas fa-check-circle me-3"></i>{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger"><div class="fw-bold mb-1">Periksa kembali data.</div><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @include('components.partials.table.showentrysearch')
            <div class="table-responsive">
                <table id="kt_customers_table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead><tr class="fw-bold text-muted">
                        <th>No.</th><th>Tahun Anggaran</th><th>Fase</th><th>Tanggal Pengesahan</th><th>Kota</th><th>No. DPA</th><th>Status</th><th class="text-end">Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($tanggal_perencanaans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="fw-bold text-gray-800">{{ $item->tahunAnggaran?->nama_tahun_anggaran ?? '—' }}</span></td>
                                <td>{{ $item->fase?->nama_fase ?? '—' }}</td>
                                <td><span class="badge badge-light-primary">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span></td>
                                <td>{{ $item->kota ?: '—' }}</td>
                                <td>{{ $item->no_dpa ?: '—' }}</td>
                                <td><span class="badge {{ $item->status === 'aktif' ? 'badge-light-success' : 'badge-light-secondary' }}">{{ ucfirst($item->status) }}</span></td>
                                <td class="text-end text-nowrap">
                                    <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modal_tanggal_perencanaan_edit_{{ $item->getKey() }}" title="Edit"><i class="fas fa-pen"></i></button>
                                    <form action="{{ route('tanggal_perencanaans.destroy', $item) }}" method="POST" class="d-inline form-delete">@csrf @method('DELETE')<button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-10">Belum ada tanggal pengesahan yang dicatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($tanggal_perencanaans as $item)
        @include('master_backend.setting_input.tanggal_perencanaan.modal_edit', ['tanggal' => $item])
    @endforeach
@endsection

@push('scripts')
<script>
document.querySelectorAll('.tanggal-tahun-select').forEach(function(select) {
    const syncFase = function() {
        const option = select.options[select.selectedIndex];
        const form = select.closest('form');
        const faseId = option ? (option.dataset.faseId || '') : '';
        const faseNama = option ? (option.dataset.faseNama || '') : '';
        form.querySelector('input[name="fase_id"]').value = faseId;
        form.querySelector('.tanggal-fase-label').value = faseNama || 'Pilih tahun anggaran terlebih dahulu';
    };
    select.addEventListener('change', syncFase);
    syncFase();
});
</script>
@endpush
