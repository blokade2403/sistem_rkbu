@extends('layouts.main')

@section('content')
    @php
        $rupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-6">
        <div>
            <h1 class="fs-2 fw-bold text-gray-900 mb-1">Pengaturan Pagu Anggaran</h1>
            <div class="text-muted">Pilih tahun terlebih dahulu untuk melihat pagu pada rekening belanja terkait.</div>
        </div>
        <a href="{{ route('pagu_anggaran.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Pagu</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="card border-0 shadow-sm mb-6">
        <div class="card-body d-flex flex-wrap align-items-end justify-content-between gap-4">
            <form method="GET" action="{{ route('pagu_anggaran.index') }}" class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label for="tahun_anggaran_id" class="form-label fw-semibold">Tahun anggaran</label>
                    <select id="tahun_anggaran_id" name="tahun_anggaran_id" class="form-select" style="min-width: 230px" onchange="this.form.submit()">
                        @foreach ($tahunAnggarans as $tahun)
                            <option value="{{ $tahun->id }}" @selected($tahun->id === $tahunAnggaranId)>{{ $tahun->nama_tahun_anggaran }}{{ $tahun->status === 'aktif' ? ' · Aktif' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-light-primary" type="submit">Tampilkan</button>
            </form>
            @if ($tahunAnggaran)
                <div class="badge badge-light-primary fs-6 px-4 py-3">Menampilkan pagu tahun {{ $tahunAnggaran->nama_tahun_anggaran }}</div>
            @endif
        </div>
    </div>

    @if ($tahunAnggaran)
        <div class="row g-5 mb-6">
            @foreach ([['label' => 'Total Pagu', 'value' => $summary['pagu'], 'color' => 'primary', 'icon' => 'fa-wallet'], ['label' => 'Terpakai RKBU', 'value' => $summary['rkbu'], 'color' => 'info', 'icon' => 'fa-list-check'], ['label' => 'Terpakai Usulan', 'value' => $summary['usulan'], 'color' => 'warning', 'icon' => 'fa-file-invoice'], ['label' => 'Selisih Pagu − RKBU', 'value' => $summary['sisa_rkbu'], 'color' => $summary['sisa_rkbu'] < 0 ? 'danger' : 'success', 'icon' => 'fa-scale-balanced']] as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm"><div class="card-body d-flex align-items-center gap-4">
                        <span class="pagu-summary-icon bg-light-{{ $item['color'] }} text-{{ $item['color'] }}"><i class="fas {{ $item['icon'] }}"></i></span>
                        <div><div class="text-muted fw-semibold mb-1">{{ $item['label'] }}</div><div class="fs-3 fw-bold text-gray-900">{{ $rupiah($item['value']) }}</div></div>
                    </div></div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title d-flex flex-column">
                <h2 class="fw-bold text-gray-900 mb-1">Daftar Pagu Rekening Belanja</h2>
                <span class="text-muted fs-7">{{ $tahunAnggaran ? 'Tahun ' . $tahunAnggaran->nama_tahun_anggaran : 'Pilih tahun anggaran' }} · {{ $pagus->count() }} rekening</span>
            </div>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 align-middle gy-4">
                    <thead><tr class="text-muted fw-bold text-uppercase fs-7">
                        <th style="width:50px">No.</th><th>Rekening Belanja</th><th>Sumber Dana</th>
                        <th class="text-end">Pagu</th><th class="text-end">Terpakai RKBU</th>
                        <th class="text-end">Terpakai Usulan</th><th class="text-end">Sisa vs RKBU</th><th class="text-end">Sisa vs Usulan</th><th class="text-end">Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($pagus as $index => $pagu)
                            @php
                                $sisaRkbu = (float) $pagu->jumlah_pagu - (float) $pagu->rkbu_terpakai;
                                $sisaUsulan = (float) $pagu->jumlah_pagu - (float) $pagu->usulan_terpakai;
                                $rasio = (float) $pagu->jumlah_pagu > 0 ? min(100, max(0, (float) $pagu->rkbu_terpakai / (float) $pagu->jumlah_pagu * 100)) : 0;
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-gray-900">{{ $pagu->rekeningBelanja?->nama_rekening_belanja ?? 'Rekening tidak ditemukan' }}</div>
                                    <div class="text-muted fs-7">Kode {{ $pagu->rekeningBelanja?->kode_rekening_belanja ?? '-' }}</div>
                                </td>
                                <td><span class="badge badge-light-{{ $pagu->jenis_sumber_dana === 'BLUD' ? 'primary' : 'warning' }}">{{ $pagu->jenis_sumber_dana }}</span></td>
                                <td class="text-end fw-semibold">{{ $rupiah($pagu->jumlah_pagu) }}</td>
                                <td class="text-end">{{ $rupiah($pagu->rkbu_terpakai) }}<div class="progress h-4px mt-2"><div class="progress-bar bg-primary" style="width: {{ $rasio }}%"></div></div></td>
                                <td class="text-end">{{ $rupiah($pagu->usulan_terpakai) }}</td>
                                <td class="text-end {{ $sisaRkbu < 0 ? 'text-danger fw-bold' : 'text-success fw-semibold' }}">{{ $rupiah($sisaRkbu) }}</td>
                                <td class="text-end {{ $sisaUsulan < 0 ? 'text-danger fw-bold' : 'text-success fw-semibold' }}">{{ $rupiah($sisaUsulan) }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('pagu_anggaran.history', $pagu) }}" class="btn btn-sm btn-light-info" title="Lihat perubahan pagu"><i class="fas fa-clock-rotate-left"></i></a>
                                    <a href="{{ route('pagu_anggaran.edit', $pagu) }}" class="btn btn-sm btn-light-primary">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-12"><div class="text-muted mb-3">Belum ada pagu untuk tahun ini.</div><a href="{{ route('pagu_anggaran.create') }}" class="btn btn-sm btn-light-primary">Tambahkan pagu pertama</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="alert alert-light-info mb-0 mt-4"><i class="fas fa-circle-info me-2"></i>Pagu mengunci nilai RKBU dan Usulan Belanja untuk setiap rekening pada tahun yang dipilih. Validasi total RKBU diberlakukan mulai fase dengan urutan 2.</div>
        </div>
    </div>

    <style>.pagu-summary-icon{width:48px;height:48px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;font-size:19px;flex:0 0 auto}</style>
@endsection
