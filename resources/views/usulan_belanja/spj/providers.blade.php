@extends('layouts.main')

@section('title', 'SPJ per Penyedia')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar SPJ per Perusahaan Penyedia</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('usulan-belanja.spj.providers') }}" class="row g-3 align-items-end mb-7">
            <div class="col-md-4">
                <label for="perusahaan_id" class="form-label">Perusahaan penyedia</label>
                <select name="perusahaan_id" id="perusahaan_id" class="form-select">
                    <option value="">Semua perusahaan</option>
                    @foreach($perusahaans as $perusahaan)
                        <option value="{{ $perusahaan->id_perusahaan }}" @selected(request('perusahaan_id') == $perusahaan->id_perusahaan)>{{ $perusahaan->nama_perusahaan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status_spj" class="form-label">Status SPJ</label>
                <select name="status_spj" id="status_spj" class="form-select">
                    <option value="">Semua status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status_spj') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="q" class="form-label">Cari</label>
                <input type="search" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="No. usulan / pengusul">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a href="{{ route('usulan-belanja.spj.providers') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>No.</th>
                        <th>Perusahaan Penyedia</th>
                        <th>Nomor SPJ</th>
                        <th>Nomor Usulan</th>
                        <th>Pengusul</th>
                        <th>Jumlah Item</th>
                        <th>Total Volume</th>
                        <th>Status</th>
                        <th>Progres validasi &amp; pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spjs as $spj)
                        <tr>
                            <td>{{ $spjs->firstItem() + $loop->index }}</td>
                            <td class="fw-bold">{{ $spj->nama_perusahaan }}</td>
                            <td>{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $spj->no_usulan_belanja }}</td>
                            <td>{{ $spj->nama_pengusul ?: '-' }}</td>
                            <td>{{ $spj->jumlah_item }}</td>
                            <td>{{ rtrim(rtrim(number_format((float) $spj->jumlah_volume, 3, ',', '.'), '0'), ',') }}</td>
                            <td><span class="badge badge-light-primary">{{ $spj->status_spj }}</span></td>
                            <td>
                                @php
                                    $tahapanTampil = [];
                                    $menungguBerikutnya = false;
                                    foreach ($spj->workflow_timeline ?? [] as $tahap) {
                                        if ($menungguBerikutnya) {
                                            break;
                                        }
                                        $tahapanTampil[] = $tahap;
                                        $statusTahap = strtolower((string) ($tahap['status'] ?? ''));
                                        $tahapTuntas = in_array($statusTahap, ['selesai', 'setuju', 'disetujui'], true);
                                        $menungguBerikutnya = ! $tahapTuntas;
                                    }
                                @endphp
                                <div class="spj-timeline">
                                    @foreach($tahapanTampil as $tahap)
                                        @php
                                            $statusTahap = strtolower((string) ($tahap['status'] ?? ''));
                                            $selesai = in_array($statusTahap, ['selesai', 'setuju', 'disetujui'], true);
                                            $revisi = $statusTahap === 'revisi';
                                            $menunggu = ! $selesai && ! $revisi && empty($tahap['status']);
                                        @endphp
                                        <div class="spj-timeline-item {{ $selesai ? 'is-done' : ($revisi ? 'is-revision' : ($menunggu ? 'is-pending' : '')) }}">
                                            <span class="spj-timeline-dot"></span>
                                            <div class="spj-timeline-content">
                                                <div class="fw-semibold">{{ $tahap['nama'] }}</div>
                                                <div class="text-muted fs-7">
                                                    @if($menunggu)
                                                        Menunggu tahap selanjutnya
                                                    @else
                                                        {{ $tahap['status'] }}
                                                        @if($tahap['tanggal']) · {{ \Illuminate\Support\Carbon::parse($tahap['tanggal'])->format('d/m/Y') }} @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($spj->tanggal_pembayaran)
                                    <div class="text-muted fs-7 mt-2">Dibayar {{ \Illuminate\Support\Carbon::parse($spj->tanggal_pembayaran)->format('d/m/Y') }} · Rp {{ number_format((float) $spj->pembayaran, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td><a href="{{ route('usulan-belanja.spj.show', $spj->spj_id) }}" class="btn btn-sm btn-light-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-8">Belum ada SPJ untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $spjs->links() }}
    </div>
</div>
<style>
    .spj-timeline { min-width: 275px; }
    .spj-timeline-item { position: relative; display: flex; gap: 12px; min-height: 48px; padding-bottom: 12px; }
    .spj-timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 5px; top: 12px; bottom: 0; width: 2px; background: #e4e6ef; }
    .spj-timeline-dot { position: relative; z-index: 1; flex: 0 0 12px; width: 12px; height: 12px; margin-top: 3px; border: 3px solid #a1a5b7; border-radius: 50%; background: #fff; }
    .spj-timeline-item.is-done .spj-timeline-dot { border-color: #50cd89; }
    .spj-timeline-item.is-revision .spj-timeline-dot { border-color: #f1416c; }
    .spj-timeline-item.is-pending .spj-timeline-dot { border-color: #ffc700; }
    .spj-timeline-content { min-width: 0; }
</style>
@endsection
