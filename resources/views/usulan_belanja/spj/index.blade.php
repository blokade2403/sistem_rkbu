@extends('layouts.main')

@section('title', 'SPJ dari Usulan Belanja')

@section('content')
<div class="card ub-spj-index-card">
    <div class="card-header ub-page-header">
        <div class="card-title d-flex align-items-center gap-3 mb-0">
            <span class="ub-heading-icon"><i class="ki-duotone ki-cheque fs-2x text-primary"></i></span>
            <div><h3 class="mb-1">SPJ dari Usulan Belanja Disetujui</h3><span class="text-muted fs-7">Kelola pembagian item usulan ke dalam SPJ</span></div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('usulan-belanja.spj.index') }}" class="row g-3 mb-6 ub-filter-panel">
            <div class="col-md-4">
                <label for="filter-no-usulan" class="form-label">Nomor Usulan</label>
                <input type="search" id="filter-no-usulan" name="no_usulan" class="form-control" value="{{ request('no_usulan') }}" placeholder="Cari nomor usulan">
            </div>
            <div class="col-md-4">
                <label for="filter-sub-kategori" class="form-label">Subkategori</label>
                <select id="filter-sub-kategori" name="sub_kategori_id" class="form-select" data-control="select2" data-placeholder="Semua subkategori">
                    <option value="">Semua subkategori</option>
                    @foreach($subKategoris as $subKategori)
                        <option value="{{ $subKategori->id_sub_kategori_rkbu }}" @selected(request('sub_kategori_id') === $subKategori->id_sub_kategori_rkbu)>{{ $subKategori->nama_sub_kategori_rkbu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-pengusul" class="form-label">Pengusul</label>
                <select id="filter-pengusul" name="pengusul_id" class="form-select" data-control="select2" data-placeholder="Semua pengusul">
                    <option value="">Semua pengusul</option>
                    @foreach($pengusuls as $pengusul)
                        <option value="{{ $pengusul->user_id }}" @selected(request('pengusul_id') === $pengusul->user_id)>{{ $pengusul->nama_pengusul ?: $pengusul->pengusul?->nama ?? $pengusul->user_id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('usulan-belanja.spj.index') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4 ub-data-table">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Nomor Usulan</th>
                        <th>Subkategori</th>
                        <th>Pengusul / Unit</th>
                        <th>Tahun Anggaran</th>
                        <th>Item Usulan</th>
                        <th>SPJ</th>
                        <th>Item Masih Bersisa</th>
                        <th>Total Sisa Volume</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usulans as $usulan)
                        <tr>
                            <td>{{ $usulans->firstItem() + $loop->index }}</td>
                            <td class="fw-bold">{{ $usulan->no_usulan_belanja }}</td>
                            <td>{{ $usulan->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</td>
                            <td>{{ $usulan->nama_pengusul ?: $usulan->pengusul?->nama ?? '-' }}<br><span class="text-muted fs-7">{{ $usulan->unit?->nama_unit ?? '-' }}</span></td>
                            <td>{{ $usulan->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td>
                            <td>{{ $usulan->item_count }}</td>
                            <td>
                                @forelse($usulan->spjs as $spj)
                                    <a href="{{ route('usulan-belanja.spj.show', $spj->id) }}" class="badge badge-light-primary me-1 mb-1">
                                        SPJ {{ $spj->nomor_urut ?? $loop->iteration }} ({{ $spj->details_count }} item)
                                    </a>
                                @empty
                                    <span class="text-muted">Belum dibuat</span>
                                @endforelse
                            </td>
                            <td>{{ $usulan->unassigned_item_count }}</td>
                            <td>{{ rtrim(rtrim(number_format((float) $usulan->unassigned_volume, 3, ',', '.'), '0'), ',') }}</td>
                            <td>
                                @if($usulan->can_create_spj && (int) $usulan->unassigned_item_count > 0)
                                    <a href="{{ route('usulan-belanja.spj.create', $usulan->id) }}" class="btn btn-sm btn-primary">Buat / Tambah SPJ</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-8">Belum ada Usulan Belanja yang disetujui workflow.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $usulans->links() }}
    </div>
</div>
<style>
    .ub-spj-index-card { overflow:hidden; border-radius:12px; }
    .ub-page-header { background:linear-gradient(110deg,#f2f7ff,#fff 70%); border-bottom:1px solid #e8edf5; }
    .ub-heading-icon { display:grid; place-items:center; width:46px; height:46px; flex:0 0 46px; border-radius:12px; background:#fff; box-shadow:0 3px 12px #3699ff18; }
    .ub-filter-panel { padding:16px; margin-inline:0; border:1px solid #edf0f5; border-radius:10px; background:#f9fafc; }
    .ub-data-table { min-width:1000px; }
    .ub-data-table thead th { color:#7e8299; background:#f5f8fa; font-size:11px; text-transform:uppercase; white-space:nowrap; }
    @media(max-width:767.98px) { .ub-page-header { padding:16px; } .ub-filter-panel { padding:12px; } }
</style>
@endsection
