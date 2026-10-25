@extends('layouts.main')

@section('title', 'Rekap Usulan Belanja')

@section('content')
<div class="card ub-rekap-card">
    <div class="card-header ub-page-header">
        <div class="card-title d-flex align-items-center gap-3 mb-0">
            <span class="ub-heading-icon"><i class="ki-duotone ki-chart-simple-2 fs-2x text-primary"></i></span>
            <div><h3 class="mb-1">Rekap Usulan Belanja</h3><span class="text-muted fs-7">Pantau status dan nilai usulan belanja</span></div>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('usulan-belanja.rekap') }}" class="row g-3 mb-6 ub-filter-panel">
            <div class="col-md-3">
                <label for="rekap-jenis" class="form-label">Jenis Kategori RKBU</label>
                <select id="rekap-jenis" name="jenis_kategori_rkbu_id" class="form-select" data-control="select2">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisKategoriRkbus as $jenis)
                        <option value="{{ $jenis->id_jenis_kategori_rkbu }}" @selected(request('jenis_kategori_rkbu_id') === $jenis->id_jenis_kategori_rkbu)>{{ $jenis->nama_jenis_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="rekap-subkategori" class="form-label">Subkategori RKBU</label>
                <select id="rekap-subkategori" name="sub_kategori_rkbu_id" class="form-select" data-control="select2">
                    <option value="">Semua Subkategori</option>
                    @foreach($subKategoriRkbus as $subkategori)
                        <option value="{{ $subkategori->id_sub_kategori_rkbu }}" data-jenis="{{ $subkategori->jenis_kategori_rkbu_id }}" @selected(request('sub_kategori_rkbu_id') === $subkategori->id_sub_kategori_rkbu)>{{ $subkategori->nama_sub_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="rekap-pengusul" class="form-label">Pengusul</label>
                <select id="rekap-pengusul" name="pengusul_id" class="form-select" data-control="select2">
                    <option value="">Semua Pengusul</option>
                    @foreach($pengusuls as $pengusul)
                        <option value="{{ $pengusul->user_id }}" @selected(request('pengusul_id') === $pengusul->user_id)>{{ $pengusul->nama_pengusul }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="rekap-status" class="form-label">Status</label>
                <select id="rekap-status" name="status" class="form-select" data-control="select2">
                    <option value="">Semua Status</option>
                    <option value="proses" @selected(request('status') === 'proses')>Dalam Proses</option>
                    <option value="revision" @selected(request('status') === 'revision')>Perlu Revisi</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('usulan-belanja.rekap') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gy-4 ub-data-table">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Nomor Usulan</th>
                        <th>Jenis Kategori</th>
                        <th>Subkategori RKBU</th>
                        <th>Pengusul</th>
                        <th>Unit</th>
                        <th>Tahun Anggaran</th>
                        <th>Status</th>
                        <th class="text-end">Total Usulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usulans as $usulan)
                        @php
                            $jenis = $usulan->rkbu?->subKategoriRkbu?->kategori?->jenis_kategori_rkbu?->nama_jenis_kategori_rkbu ?? '-';
                            [$statusLabel, $statusColor] = match ($usulan->status_usulan) {
                                'proses' => ['Dalam Proses', 'primary'],
                                'revision' => ['Perlu Revisi', 'warning'],
                                'approved' => ['Disetujui', 'success'],
                                'rejected' => ['Ditolak', 'danger'],
                                default => [ucfirst($usulan->status_usulan), 'secondary'],
                            };
                        @endphp
                        <tr>
                            <td>{{ $usulans->firstItem() + $loop->index }}</td>
                            <td class="fw-bold">{{ $usulan->no_usulan_belanja }}</td>
                            <td>{{ $jenis }}</td>
                            <td>{{ $usulan->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td>
                            <td>{{ $usulan->nama_pengusul ?: $usulan->pengusul?->nama ?? '-' }}</td>
                            <td>{{ $usulan->unit?->nama_unit ?? '-' }}</td>
                            <td>{{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td>
                            <td><span class="badge badge-light-{{ $statusColor }}">{{ $statusLabel }}</span></td>
                            <td class="text-end fw-bold">Rp {{ number_format((float) $usulan->total_nominal, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('usulan-belanja.rekap.show', $usulan->id) }}" class="btn btn-sm btn-light-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-8">Belum ada usulan belanja yang diajukan untuk validasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $usulans->links() }}
    </div>
</div>
<style>
    .ub-rekap-card { overflow:hidden; border-radius:12px; }
    .ub-page-header { background:linear-gradient(110deg,#f2f7ff,#fff 70%); border-bottom:1px solid #e8edf5; }
    .ub-heading-icon { display:grid; place-items:center; width:46px; height:46px; flex:0 0 46px; border-radius:12px; background:#fff; box-shadow:0 3px 12px #3699ff18; }
    .ub-filter-panel { padding:16px; margin-inline:0; border:1px solid #edf0f5; border-radius:10px; background:#f9fafc; }
    .ub-data-table { min-width:1000px; }
    .ub-data-table thead th { color:#7e8299; background:#f5f8fa; font-size:11px; text-transform:uppercase; white-space:nowrap; }
    @media(max-width:767.98px) { .ub-page-header { padding:16px; } .ub-filter-panel { padding:12px; } }
</style>
@endsection

@push('scripts')
<script>
    $(function () {
        const jenis = $('#rekap-jenis');
        const subkategori = $('#rekap-subkategori');
        const selectedSubkategori = @json(request('sub_kategori_rkbu_id'));

        function filterSubkategori() {
            const jenisId = jenis.val();
            subkategori.find('option').each(function (index) {
                if (index === 0) return;
                const matches = !jenisId || String($(this).data('jenis')) === String(jenisId);
                $(this).prop('disabled', !matches).prop('hidden', !matches);
            });
            if (subkategori.find(':selected').prop('disabled')) subkategori.val('').trigger('change.select2');
        }

        jenis.on('change', filterSubkategori);
        filterSubkategori();
        if (selectedSubkategori && !subkategori.find('option:selected').prop('disabled')) {
            subkategori.val(selectedSubkategori).trigger('change.select2');
        }
    });
</script>
@endpush
