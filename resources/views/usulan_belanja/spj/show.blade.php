@extends('layouts.main')

@section('title', 'Detail SPJ')

@section('content')
    <div class="card card-flush shadow-sm spj-detail-shell">
        <div class="card-header border-0 bg-light-primary pt-6 pb-5">
            <div class="d-flex align-items-center gap-4">
                <div class="spj-detail-heading-icon"><i class="ki-duotone ki-document fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i></div>
                <div>
                    <h3 class="card-title fw-bold text-gray-900 mb-1">Detail SPJ {{ $spj->nomor_urut }}</h3>
                    <div class="text-muted fs-7">{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                        <span class="mx-2">•</span>{{ $namaPerusahaan ?? 'Penyedia belum ditentukan' }}
                    </div>
                </div>
            </div>
            <div class="card-toolbar">
                <span class="badge badge-light-primary fs-7 me-3">{{ $spj->status_spj ?: 'Belum diproses' }}</span>
                <a href="{{ route('usulan-belanja.spj.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Perubahan tidak dapat disimpan.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if ($errors->has('idpaket') && (auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Super Admin')))
                        <a href="{{ route('usulan-belanja.pengadaan.assignments') }}" class="btn btn-sm btn-light-danger mt-3">
                            Atur Penugasan Pendukung PPK
                        </a>
                    @endif
                </div>
            @endif
            <div class="row g-3 mb-7">
                <div class="col-12 col-sm-6 col-xl-3"><div class="spj-detail-stat"><span>Nomor Usulan</span><strong>{{ $spj->usulanBelanja?->no_usulan_belanja ?? '-' }}</strong></div></div>
                <div class="col-12 col-sm-6 col-xl-3"><div class="spj-detail-stat"><span>Pengusul</span><strong>{{ $spj->usulanBelanja?->nama_pengusul ?? '-' }}</strong></div></div>
                <div class="col-12 col-sm-6 col-xl-3"><div class="spj-detail-stat"><span>Penyedia</span><strong>{{ $namaPerusahaan ?? '-' }}</strong></div></div>
                <div class="col-12 col-sm-6 col-xl-3"><div class="spj-detail-stat"><span>Tahun Anggaran</span><strong>{{ $spj->usulanBelanja?->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</strong></div></div>
            </div>

            <div class="card card-bordered shadow-xs mb-7 spj-detail-info-card">
                <div class="card-header min-h-60px bg-light">
                    <h4 class="card-title fw-bold text-gray-800"><i class="ki-duotone ki-category fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>Informasi Program dan RKBU</h4>
                </div>
                <div class="card-body py-2">
                    @php
                        $rkbuInfo = $spj->usulanBelanja?->rkbu;
                        $subKategoriInfo = $rkbuInfo?->subKategoriRkbu;
                        $subKegiatanInfo = $rkbuInfo?->subKegiatan;
                    @endphp
                    <div class="row g-0">
                        <div class="col-12"><div class="spj-detail-info-row"><span>Program</span><strong>{{ $program?->kode_program }} {{ $program?->nama_program ?? '-' }}</strong></div></div>
                        <div class="col-12"><div class="spj-detail-info-row"><span>Kegiatan</span><strong>{{ $kegiatan?->kode_kegiatan }} {{ $kegiatan?->nama_kegiatan ?? '-' }}</strong></div></div>
                        <div class="col-12"><div class="spj-detail-info-row"><span>Subkegiatan</span><strong>{{ $subKegiatanInfo?->kode_sub_kegiatan }} {{ $subKegiatanInfo?->nama_sub_kegiatan ?? '-' }}</strong></div></div>
                        <div class="col-12"><div class="spj-detail-info-row"><span>Rekening Belanja</span><strong>{{ $rekeningBelanja?->kode_rekening_belanja }} {{ $rekeningBelanja?->nama_rekening_belanja ?? '-' }}</strong></div></div>
                        <div class="col-12"><div class="spj-detail-info-row"><span>Sub Kategori RKBU</span><strong>{{ $subKategoriInfo?->kode_sub_kategori_rkbu }} {{ $subKategoriInfo?->nama_sub_kategori_rkbu ?? '-' }}</strong></div></div>
                    </div>
                </div>
            </div>

            @if ($canManageSpj)
                <details class="spj-add-items mb-6">
                    <summary class="btn btn-sm btn-light-success">
                        <i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Tambah Item
                    </summary>
                    <div class="table-responsive mt-4">
                        <table class="table table-row-bordered align-middle gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Nama Item</th>
                                    <th>Nomor RKBU</th>
                                    <th>Sisa Volume Usulan</th>
                                    <th style="width: 220px">Volume Ditambahkan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availableItems as $availableItem)
                                    <tr>
                                        <td>{{ $availableItem->nama_barang }}</td>
                                        <td>{{ $availableItem->nomor_rkbu }}</td>
                                        <td>{{ rtrim(rtrim(number_format($availableItem->spj_remaining_volume, 3, ',', '.'), '0'), ',') }}
                                            {{ $availableItem->satuan_1 }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('usulan-belanja.spj.items.add', $spj->id) }}"
                                                id="add-item-{{ $availableItem->link_id }}">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $availableItem->link_id }}">
                                                <input type="number" name="volume" min="0.001"
                                                    max="{{ $availableItem->spj_remaining_volume }}" step="0.001"
                                                    class="form-control form-control-sm" required>
                                            </form>
                                        </td>
                                        <td><button type="submit" form="add-item-{{ $availableItem->link_id }}"
                                                class="btn btn-sm btn-primary">Tambah</button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada item dengan sisa volume
                                            untuk ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </details>
            @endif

            <div class="card card-bordered shadow-xs mb-7 spj-items-card">
                <div class="card-header bg-light min-h-65px">
                    <div>
                        <h4 class="card-title fw-bold text-gray-800 mb-1">Item dalam SPJ</h4>
                        <span class="text-muted fs-7">Rincian barang, volume, dan nilai pengadaan.</span>
                    </div>
                    <div class="card-toolbar d-flex flex-wrap gap-2">
                        <span class="badge badge-light-primary px-3 py-2">{{ $items->count() }} item</span>
                        <span class="badge badge-light-success px-3 py-2">Total Rp {{ number_format((float) $items->sum('total_anggaran') + (float) $items->sum('total_ppn'), 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table id="spj-items-table" class="table table-row-dashed table-hover align-middle gy-4 spj-items-table">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>No.</th>
                            <th>Nama Item</th>
                            <th>Spesifikasi</th>
                            <th>Nomor RKBU</th>
                            <th class="text-end">Volume</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Total + PPN</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->spesifikasi ?: '-' }}</td>
                                <td>{{ $item->nomor_rkbu }}</td>
                                <td class="text-end">{{ $item->volume }} {{ $item->satuan_1 }}</td>
                                <td class="text-end">Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-end">Rp
                                    {{ number_format((float) $item->total_anggaran + (float) $item->total_ppn, 0, ',', '.') }}
                                </td>
                                <td class="spj-item-action-cell">
                                    <div class="spj-item-actions">
                                    <details>
                                        <summary class="btn btn-sm btn-light-primary">Edit</summary>
                                        <form method="POST"
                                            action="{{ route('usulan-belanja.spj.items.update', [$spj->id, $item->spj_detail_id]) }}"
                                            class="mt-3" style="min-width: 260px">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-2">
                                                <label class="form-label">Spesifikasi</label>
                                                <textarea name="spesifikasi" rows="2" class="form-control">{{ $item->spesifikasi }}</textarea>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Volume ({{ $item->satuan_1 }})</label>
                                                <input type="number" name="volume" min="0.001" step="0.001"
                                                    value="{{ $item->volume }}" class="form-control" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Harga Satuan (Rp)</label>
                                                <input type="number" name="harga_satuan" min="0" step="0.01"
                                                    value="{{ $item->harga_satuan }}" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                        </form>
                                    </details>
                                    @if ($isAdmin)
                                        <form method="POST"
                                            action="{{ route('usulan-belanja.spj.items.delete', [$spj->id, $item->spj_detail_id]) }}"
                                            class="m-0" onsubmit="return confirm('Hapus item ini dari SPJ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                        </form>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="6" class="text-end">Total</td>
                            <td class="text-end">Rp
                                {{ number_format((float) $items->sum('total_anggaran') + (float) $items->sum('total_ppn'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                        </table>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
        <section class="spj-workflow-section mt-7">
            <div class="spj-workflow-heading">
                <div class="spj-detail-heading-icon"><i class="ki-duotone ki-route fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i></div>
                <div>
                    <h4 class="fw-bold text-gray-900 mb-1">Alur Pengadaan dan Validasi</h4>
                    <div class="text-muted fs-7">Riwayat proses dan tindakan yang tersedia pada tahap SPJ ini.</div>
                </div>
            </div>
            <div class="spj-workflow-content">
                      @if (auth()->user()->hasRole('Administrator') ||
                    auth()->user()->hasRole('Super Admin') ||
                    auth()->user()->hasRole('Admin Pengadaan') ||
                    auth()->user()->hasRole('Pendukung PPK') ||
                    auth()->user()->hasRole('Pengurus Barang') ||
                    auth()->user()->roles()->whereIn('nama_role', ['PPK', 'PPBJ', 'PPTK', 'Verifikator Pengadaan', 'PPK Keuangan', 'Bendahara'])->exists() ||
                    in_array(auth()->user()->position?->level_jabatan, ['PPK', 'PPBJ', 'PPTK', 'Verifikator', 'PPK Keuangan', 'Direktur', 'Bendahara'], true) ||
                    (isset($workflowStep, $canWorkflowStep) && $canWorkflowStep) || (isset($canValidasi) && $canValidasi))
                <div class="mt-8">
                    @include('usulan_belanja.pengadaan.show', $pengadaanPanelData ?? [])
                </div>
            @else
                <div class="alert alert-light-info mb-0">Alur pengadaan hanya ditampilkan kepada petugas yang terlibat dalam proses ini.</div>
            @endif
            </div>
        </section>

<style>
    .spj-detail-shell { overflow: hidden; border-radius: 12px; }
    .spj-detail-shell > .card-header { flex-wrap: wrap; gap: 14px; }
    .spj-detail-heading-icon { display: grid; place-items: center; flex: 0 0 48px; width: 48px; height: 48px; border-radius: 12px; background: #fff; box-shadow: 0 2px 8px #3e7eff12; }
    .spj-detail-stat { display: flex; flex-direction: column; justify-content: center; min-height: 82px; height: 100%; gap: 6px; padding: 15px 17px; border: 1px solid #eef0f5; border-radius: 10px; background: #fff; box-shadow: 0 2px 8px #181c3208; }
    .spj-detail-stat span { color: #a1a5b7; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .spj-detail-stat strong { color: #3f4254; font-size: 14px; overflow-wrap: anywhere; }
    .spj-detail-info-card, .spj-items-card { overflow: hidden; border-radius: 10px; }
    .spj-detail-info-row { display: flex; align-items: flex-start; gap: 20px; padding: 11px 13px; border-bottom: 1px solid #f1f1f4; border-radius: 6px; }
    .spj-detail-info-row:last-child { border-bottom: 0; }
    .spj-detail-info-row span { flex: 0 0 175px; color: #7e8299; font-size: 13px; font-weight: 600; }
    .spj-detail-info-row strong { color: #3f4254; font-size: 13px; font-weight: 600; overflow-wrap: anywhere; }
    .spj-add-items summary { list-style: none; cursor: pointer; width: fit-content; }
    .spj-add-items summary::-webkit-details-marker { display: none; }
    .spj-items-table { min-width: 900px; }
    .spj-items-table tbody td { font-size: 13px; line-height: 1.45; }
    .spj-items-table thead th { color: #7e8299; background: #f5f8fa; font-size: 11px; text-transform: uppercase; white-space: nowrap; }
    .spj-item-action-cell { width: 132px; min-width: 132px; white-space: nowrap; }
    .spj-item-actions { display: flex; align-items: center; gap: 6px; }
    .spj-item-actions details { margin: 0; }
    .spj-item-actions summary { list-style: none; cursor: pointer; }
    .spj-item-actions summary::-webkit-details-marker { display: none; }
    .spj-item-actions .btn { padding: 7px 10px; font-size: 12px; }
    .spj-workflow-heading { display: flex; align-items: center; gap: 15px; margin-bottom: 14px; padding: 18px 20px; border: 1px solid #e7efff; border-radius: 10px; background: linear-gradient(110deg, #f2f7ff, #fff 68%); }
    .spj-workflow-content { padding: 0 2px; }
    .spj-workflow-content > .mt-8 { margin-top: 0 !important; }
    .spj-workflow-content > .mt-8 > .card { border: 1px solid #e8edf5; border-radius: 10px; box-shadow: 0 3px 14px #181c320a; }
    @media (max-width: 767.98px) {
        .spj-detail-shell > .card-header { align-items: flex-start; flex-direction: column; }
        .spj-detail-shell > .card-header .card-toolbar { width: 100%; justify-content: space-between; }
        .spj-detail-info-row { flex-direction: column; gap: 3px; }
        .spj-detail-info-row span { flex-basis: auto; }
        .spj-workflow-heading { align-items: flex-start; padding: 15px; }
        .spj-items-card > .card-header { align-items: flex-start; flex-direction: column; gap: 12px; padding-top: 16px; padding-bottom: 16px; }
    }
</style>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#spj-items-table').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                order: [],
                language: {
                    lengthMenu: 'Tampilkan _MENU_ data per halaman',
                    search: 'Cari:',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ item',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 item',
                    zeroRecords: 'Tidak ada item yang cocok dengan pencarian',
                    emptyTable: 'Item SPJ tidak ditemukan.',
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



