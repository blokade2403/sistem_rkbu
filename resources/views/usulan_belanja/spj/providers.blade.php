@extends('layouts.main')

@section('title', 'SPJ per Penyedia')

@section('content')
    <div class="card card-flush shadow-sm provider-spj-card">
        <div class="card-header border-0 pt-7 pb-3 align-items-center">
            <div class="d-flex align-items-center gap-4">
                <div class="provider-spj-icon">
                    <i class="ki-duotone ki-shop fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-gray-900 mb-1">Daftar SPJ per Perusahaan Penyedia</h3>
                    <div class="text-muted fs-7">Pantau nilai, dokumen, dan progres setiap SPJ.</div>
                </div>
            </div>
            <div class="card-toolbar">
                <span class="badge badge-light-primary fs-7 px-4 py-3">
                    <i class="ki-duotone ki-document fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                    {{ $spjs->count() }} SPJ
                </span>
            </div>
        </div>

        <div class="card-body pt-4">
            <div class="provider-filter-panel mb-6">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="ki-duotone ki-filter fs-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    <span class="fw-bold text-gray-800">Filter daftar</span>
                </div>
                <form method="GET" action="{{ route('usulan-belanja.spj.providers') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-5 col-lg-4">
                        <label for="perusahaan_id" class="form-label fw-semibold">Perusahaan penyedia</label>
                        <select name="perusahaan_id" id="perusahaan_id" class="form-select form-select-solid">
                            <option value="">Semua perusahaan</option>
                            @foreach ($perusahaans as $perusahaan)
                                <option value="{{ $perusahaan->id_perusahaan }}" @selected(request('perusahaan_id') == $perusahaan->id_perusahaan)>
                                    {{ $perusahaan->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <label for="status_spj" class="form-label fw-semibold">Status SPJ</label>
                        <select name="status_spj" id="status_spj" class="form-select form-select-solid">
                            <option value="">Semua status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status_spj') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit">
                            <i class="ki-duotone ki-filter fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Filter
                        </button>
                        <a href="{{ route('usulan-belanja.spj.providers') }}" class="btn btn-light flex-grow-1">Reset</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive provider-spj-table-wrap">
                <table id="spj-provider-table" class="table table-row-dashed table-hover align-middle gy-5 gs-4 provider-spj-table">
                    <thead>
                        <tr class="fw-bold text-muted text-uppercase fs-8">
                            <th class="text-center">No.</th>
                            <th>SPJ dan Penyedia</th>
                            <th>Dokumen Referensi</th>
                            <th class="text-end">Ringkasan Biaya</th>
                            <th>Status</th>
                            <th>Progres Validasi &amp; Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spjs as $spj)
                            @php
                                $statusKey = strtolower(trim((string) $spj->status_spj));
                                $statusColor = str_contains($statusKey, 'selesai') || str_contains($statusKey, 'lunas')
                                    ? 'success'
                                    : (str_contains($statusKey, 'revisi') || str_contains($statusKey, 'perbaikan')
                                        ? 'warning'
                                        : (str_contains($statusKey, 'tolak') ? 'danger' : 'primary'));
                                $tahapanTampil = [];
                                $menungguBerikutnya = false;
                                foreach ($spj->workflow_timeline ?? [] as $tahap) {
                                    if ($menungguBerikutnya) break;
                                    $tahapanTampil[] = $tahap;
                                    $statusTahap = strtolower((string) ($tahap['status'] ?? ''));
                                    $menungguBerikutnya = ! in_array($statusTahap, ['selesai', 'setuju', 'disetujui'], true);
                                }
                            @endphp
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-start gap-3 provider-spj-identity">
                                        <div class="symbol symbol-45px symbol-circle flex-shrink-0">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-4">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($spj->nama_perusahaan ?: 'P', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column min-w-0">
                                            <span class="fw-bold text-gray-900 text-wrap">{{ $spj->nama_perusahaan }}</span>
                                            <a href="{{ route('usulan-belanja.spj.show', $spj->spj_id) }}" class="text-primary fw-semibold mt-1">
                                                {{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                                            </a>
                                            <span class="text-muted fs-7 mt-1">Pengusul: {{ $spj->nama_pengusul ?: '-' }}</span>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <span class="badge badge-light">{{ $spj->jumlah_item }} item</span>
                                                <span class="badge badge-light">Volume {{ rtrim(rtrim(number_format((float) $spj->jumlah_volume, 3, ',', '.'), '0'), ',') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-spj-documents">
                                        <div><span>Usulan</span><strong>{{ $spj->no_usulan_belanja ?: '-' }}</strong></div>
                                        <div><span>Surat Pesanan</span><strong>{{ $spj->pengadaan_no_surat_pesanan ?: '-' }}</strong></div>
                                        <div><span>SPK</span><strong>{{ $spj->nomor_spk ?: '-' }}</strong></div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="provider-spj-amounts">
                                        <div><span>Netto</span><strong>Rp {{ number_format((float) $spj->netto, 0, ',', '.') }}</strong></div>
                                        <div><span>PPN</span><strong>Rp {{ number_format((float) $spj->ppn, 0, ',', '.') }}</strong></div>
                                        <div class="provider-spj-total"><span>Total</span><strong>Rp {{ number_format((float) $spj->total, 0, ',', '.') }}</strong></div>
                                    </div>
                                </td>
                                <td><span class="badge badge-light-{{ $statusColor }} fs-7">{{ $spj->status_spj ?: 'Belum diproses' }}</span></td>
                                <td>
                                    @if (count($tahapanTampil))
                                        <div class="spj-timeline">
                                            @foreach ($tahapanTampil as $tahap)
                                                @php
                                                    $statusTahap = strtolower((string) ($tahap['status'] ?? ''));
                                                    $selesai = in_array($statusTahap, ['selesai', 'setuju', 'disetujui'], true);
                                                    $revisi = $statusTahap === 'revisi';
                                                    $menunggu = ! $selesai && ! $revisi && empty($tahap['status']);
                                                @endphp
                                                <div class="spj-timeline-item {{ $selesai ? 'is-done' : ($revisi ? 'is-revision' : ($menunggu ? 'is-pending' : '')) }}">
                                                    <span class="spj-timeline-dot"></span>
                                                    <div class="spj-timeline-content">
                                                        <div class="fw-semibold text-gray-800">{{ $tahap['nama'] }}</div>
                                                        <div class="text-muted fs-7">
                                                            @if ($menunggu)
                                                                Menunggu tahap selanjutnya
                                                            @else
                                                                {{ $tahap['status'] }}
                                                                @if ($tahap['tanggal'])
                                                                    · {{ \Illuminate\Support\Carbon::parse($tahap['tanggal'])->format('d/m/Y') }}
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fs-7">Belum ada progres validasi.</span>
                                    @endif
                                    @if ($spj->tanggal_pembayaran)
                                        <div class="provider-spj-payment mt-3">
                                            <i class="ki-duotone ki-check-circle fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            Dibayar {{ \Illuminate\Support\Carbon::parse($spj->tanggal_pembayaran)->format('d/m/Y') }}
                                            <strong>Rp {{ number_format((float) $spj->pembayaran, 0, ',', '.') }}</strong>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('usulan-belanja.spj.show', $spj->spj_id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .provider-spj-card { overflow: hidden; border-radius: 12px; }
        .provider-spj-icon { width: 50px; height: 50px; display: grid; place-items: center; border-radius: 12px; background: linear-gradient(145deg, #eef6ff, #e4efff); }
        .provider-filter-panel { padding: 20px; border: 1px solid #eef0f5; border-radius: 10px; background: #f9fafc; }
        .provider-spj-table-wrap { border: 1px solid #eef0f5; border-radius: 10px; }
        .provider-spj-table { min-width: 1120px; margin-bottom: 0 !important; }
        .provider-spj-table thead th { padding-top: 16px; padding-bottom: 16px; color: #7e8299; background: #f5f8fa; white-space: nowrap; }
        .provider-spj-table tbody td { padding-top: 18px; padding-bottom: 18px; }
        .provider-spj-documents, .provider-spj-amounts { display: flex; flex-direction: column; gap: 7px; min-width: 175px; }
        .provider-spj-documents div, .provider-spj-amounts div { display: flex; flex-direction: column; gap: 1px; }
        .provider-spj-documents span, .provider-spj-amounts span { color: #a1a5b7; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; }
        .provider-spj-documents strong, .provider-spj-amounts strong { color: #5e6278; font-size: 12px; font-weight: 600; overflow-wrap: anywhere; }
        .provider-spj-amounts .provider-spj-total { margin-top: 3px; padding-top: 7px; border-top: 1px dashed #e4e6ef; }
        .provider-spj-total strong { color: #181c32; font-size: 13px; }
        .spj-timeline { min-width: 220px; }
        .spj-timeline-item { position: relative; display: flex; gap: 10px; min-height: 42px; padding-bottom: 9px; }
        .spj-timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 5px; top: 12px; bottom: 0; width: 2px; background: #e4e6ef; }
        .spj-timeline-dot { position: relative; z-index: 1; flex: 0 0 12px; width: 12px; height: 12px; margin-top: 3px; border: 3px solid #a1a5b7; border-radius: 50%; background: #fff; }
        .spj-timeline-item.is-done .spj-timeline-dot { border-color: #50cd89; }
        .spj-timeline-item.is-revision .spj-timeline-dot { border-color: #f1416c; }
        .spj-timeline-item.is-pending .spj-timeline-dot { border-color: #ffc700; }
        .spj-timeline-content { min-width: 0; }
        .provider-spj-payment { display: flex; flex-wrap: wrap; align-items: center; gap: 5px; color: #7e8299; font-size: 12px; }
        .provider-spj-payment strong { color: #50cd89; }
        @media (max-width: 767.98px) {
            .provider-spj-card > .card-header { align-items: flex-start; flex-direction: column; gap: 14px; }
            .provider-filter-panel { padding: 15px; }
            .provider-spj-table { min-width: 1060px; }
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#spj-provider-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [],
                language: {
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    search: 'Cari:',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ SPJ',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 SPJ',
                    zeroRecords: 'Tidak ada data yang cocok',
                    emptyTable: 'Belum ada SPJ untuk filter ini.',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });
        });
    </script>
@endpush
