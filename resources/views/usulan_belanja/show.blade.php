
@extends('layouts.main')

@section('title', 'Detail Usulan Belanja')

@section('content')
<div class="card mb-6 ub-detail-shell">
    <div class="card-header ub-page-header">
        <div class="card-title d-flex align-items-center gap-3 mb-0">
            <span class="ub-heading-icon"><i class="ki-duotone ki-document fs-2x text-primary"></i></span>
            <div><h3 class="mb-1">Detail Usulan Belanja</h3><span class="text-muted fs-7">{{ $usulanBelanja->no_usulan_belanja }}</span></div>
        </div>

        <div class="card-toolbar">
            @if($canCreateSpj && $usulanBelanja->status_usulan === 'approved')
                <a href="{{ route('usulan-belanja.spj.create', $usulanBelanja->id) }}" class="btn btn-success me-2">
                    <i class="ki-duotone ki-plus fs-2"></i> Buat SPJ
                </a>
            @endif
            @if($canPrint && $usulanBelanja->workflow_instance_id)
                <a href="{{ route('usulan-belanja.print', $usulanBelanja->id) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-danger me-2">
                    <i class="ki-duotone ki-printer fs-2"></i> Unduh PDF
                </a>
            @endif
            @if($canPrint && $usulanBelanja->status_usulan === 'approved')
                <a href="{{ route('usulan-belanja.print-surat-perintah', $usulanBelanja->id) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-success me-2">
                    <i class="ki-duotone ki-printer fs-2"></i> Print Surat Perintah Belanja
                </a>
            @endif
            <a href="{{ $fromRekap ? route('usulan-belanja.rekap') : route('usulan-belanja.index') }}"
               class="btn btn-light">
                Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $statusUsulan = strtolower($usulanBelanja->status_usulan ?? 'draft');
            $statusTone = ['approved' => 'success', 'revision' => 'warning', 'rejected' => 'danger', 'proses' => 'primary'][$statusUsulan] ?? 'secondary';
            $grandTotalUsulan = (float) $selectedItems->sum('total_anggaran') + (float) $selectedItems->sum('total_ppn');
        @endphp
        <section class="ub-overview mb-8">
            <div class="ub-overview-top">
                <div>
                    <div class="ub-eyebrow">Ringkasan usulan</div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                        <h2 class="ub-number mb-0">{{ $usulanBelanja->no_usulan_belanja }}</h2>
                        <span class="badge badge-light-{{ $statusTone }} ub-status-badge">{{ strtoupper($usulanBelanja->status_usulan) }}</span>
                    </div>
                </div>
                <div class="ub-total-highlight"><span>Total belanja + PPN</span><strong>Rp {{ number_format($grandTotalUsulan, 2, ',', '.') }}</strong></div>
            </div>
            <div class="ub-overview-grid">
                <div class="ub-overview-item"><span><i class="ki-duotone ki-user"></i> Pengusul</span><strong>{{ $usulanBelanja->nama_pengusul ?: ($usulanBelanja->pengusul?->nama ?? '-') }}</strong></div>
                <div class="ub-overview-item"><span><i class="ki-duotone ki-category"></i> Subkategori RKBU</span><strong>{{ $usulanBelanja->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}</strong></div>
                <div class="ub-overview-item"><span><i class="ki-duotone ki-calendar"></i> Tahun anggaran</span><strong>{{ $usulanBelanja->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</strong></div>
                <div class="ub-overview-item"><span><i class="ki-duotone ki-time"></i> Tanggal validasi</span><strong>{{ $usulanBelanja->tanggal_validasi?->format('d-m-Y H:i') ?? 'Belum divalidasi' }}</strong></div>
                <div class="ub-overview-item"><span><i class="ki-duotone ki-profile-user"></i> Validator terakhir</span><strong>{{ $usulanBelanja->nama_validasi ?? '-' }}</strong></div>
                <div class="ub-overview-item ub-overview-note"><span><i class="ki-duotone ki-message-text-2"></i> Catatan</span><strong>{{ $usulanBelanja->catatan ?: 'Tidak ada catatan' }}</strong></div>
            </div>
        </section>

        <div class="mt-8 ub-item-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Item Usulan Belanja</h4>
                <span class="badge badge-light-primary">
                    Subkategori: {{ $usulanBelanja->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}
                </span>
            </div>

            @if($canEditItemValues && $usulanBelanja->status_usulan === 'revision')
                <div class="alert alert-warning py-3">
                    Usulan berstatus revisi. Spesifikasi, volume, dan harga satuan dapat diperbarui lalu disimpan.
                </div>
            @endif

            @if($canEditItemValues && $selectedItems->isNotEmpty())
                <form id="usulan-items-update-form"
                      method="POST"
                      action="{{ route('usulan-belanja.items.update', $usulanBelanja->id) }}">
                    @csrf
                    @method('PUT')
                </form>
            @endif

            @if($selectedItems->isNotEmpty())
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="selected-items-search" class="form-label">Cari Item Usulan</label>
                        <input type="search"
                               id="selected-items-search"
                               class="form-control"
                               placeholder="Nama barang, spesifikasi, atau nomor RKBU">
                    </div>
                    <div class="col-md-7 d-flex align-items-end justify-content-md-end">
                        <span id="selected-items-page-info" class="text-muted"></span>
                        <div class="d-flex gap-2 ms-3">
                            <button type="button" id="selected-items-prev" class="btn btn-sm btn-light">Sebelumnya</button>
                            <button type="button" id="selected-items-next" class="btn btn-sm btn-light">Berikutnya</button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-row-dashed align-middle gy-4 ub-data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Barang</th>
                            <th>Spesifikasi</th>
                            <th>Harga Satuan</th>
                            <th>Volume</th>
                            <th>PPN (%)</th>
                            <th>Total Anggaran</th>
                            <th>Sisa Vol RKBU</th>
                            <th>Sisa Anggaran RKBU</th>
                            @if($canManageItems)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selectedItems as $item)
                            <tr class="usulan-item-row"
                                data-ppn="{{ $item->ppn }}"
                                data-anggaran-tersedia="{{ $item->anggaran_tersedia }}"
                                data-volume-tersedia="{{ $item->volume_tersedia }}"
                                data-harga-perencanaan="{{ $item->harga_perencanaan }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->nama_barang }}
                                    <div class="text-muted fs-7">{{ $item->nomor_rkbu }}</div>
                                </td>
                                <td>
                                    @if($canEditItemValues)
                                        <textarea class="form-control form-control-sm"
                                                  name="items[{{ $item->link_id }}][spesifikasi]"
                                                  rows="2"
                                                  maxlength="10000"
                                                  form="usulan-items-update-form">{{ old('items.' . $item->link_id . '.spesifikasi', $item->spesifikasi) }}</textarea>
                                    @else
                                        {{ $item->spesifikasi ?: '-' }}
                                    @endif
                                </td>
                                <td>
                                    @if($canEditItemValues)
                                        <input type="number"
                                               class="form-control form-control-sm usulan-item-input"
                                               name="items[{{ $item->link_id }}][harga_satuan]"
                                               value="{{ old('items.' . $item->link_id . '.harga_satuan', $item->harga_satuan) }}"
                                               min="0"
                                               max="9999999999999999.99"
                                               step="0.01"
                                               form="usulan-items-update-form"
                                               required>
                                    @else
                                        Rp {{ number_format((float) $item->harga_satuan, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    @if($canEditItemValues)
                                        <input type="number"
                                               class="form-control form-control-sm usulan-item-input"
                                               name="items[{{ $item->link_id }}][volume]"
                                               value="{{ old('items.' . $item->link_id . '.volume', $item->volume) }}"
                                               min="0"
                                               max="999999999999999.999"
                                               step="0.001"
                                               form="usulan-items-update-form"
                                               required>
                                    @else
                                        {{ $item->volume }}
                                    @endif
                                </td>
                                <td>
                                    @if($canManageItems)
                                        <input type="number"
                                               class="form-control form-control-sm usulan-item-input"
                                               name="items[{{ $item->link_id }}][ppn]"
                                               value="{{ old('items.' . $item->link_id . '.ppn', $item->ppn) }}"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               form="usulan-items-update-form"
                                               required>
                                    @else
                                        {{ number_format((float) $item->ppn, 2, ',', '.') }}%
                                        @if($canEditItemValues)
                                            <input type="hidden"
                                                   class="usulan-item-input"
                                                   name="items[{{ $item->link_id }}][ppn]"
                                                   value="{{ $item->ppn }}"
                                                   form="usulan-items-update-form">
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    Rp <span class="usulan-item-total"
                                             data-value="{{ $item->total_anggaran }}">{{ number_format((float) $item->total_anggaran, 2, ',', '.') }}</span>
                                    @if($canEditItemValues)
                                        <div class="text-danger fs-7 usulan-item-warning d-none">
                                            Total usulan melebihi sisa anggaran atau volume RKBU.
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="usulan-item-sisa-volume">{{ number_format((float) $item->sisa_vol_rkbu, 3, ',', '.') }}</span>
                                </td>
                                <td>
                                    Rp <span class="usulan-item-sisa-anggaran">{{ number_format((float) $item->sisa_anggaran_rkbu, 2, ',', '.') }}</span>
                                </td>
                                @if($canManageItems)
                                    <td>
                                        <form method="POST"
                                              action="{{ route('usulan-belanja.items.destroy', [$usulanBelanja->id, $item->link_id]) }}"
                                              onsubmit="return confirm('Hapus item ini dari Usulan Belanja?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canManageItems ? 10 : 9 }}" class="text-center text-muted">
                                    Belum ada item yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($canEditItemValues && $selectedItems->isNotEmpty())
                <button type="submit"
                        id="usulan-items-update-submit"
                        class="btn btn-primary mt-3"
                        form="usulan-items-update-form">
                    Simpan Perubahan Item
                </button>
            @endif

            @if($canManageItems)
                <button type="button"
                        class="btn btn-primary mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#usulan-item-modal"
                        @disabled($availableItems->isEmpty())>
                    Tambah Item
                </button>
            @endif

            <div class="d-flex justify-content-end mt-6">
                <div class="table-responsive">
                    <table class="table table-row-bordered w-auto mb-0">
                        <tbody>
                            <tr>
                                <th class="pe-8">Total Usulan Belanja</th>
                                <td class="text-end">Rp <span id="usulan-total">{{ number_format((float) $selectedItems->sum('total_anggaran'), 2, ',', '.') }}</span></td>
                            </tr>
                            <tr>
                                <th class="pe-8">Total PPN</th>
                                <td class="text-end">Rp <span id="usulan-ppn">{{ number_format((float) $selectedItems->sum('total_ppn'), 2, ',', '.') }}</span></td>
                            </tr>
                            <tr class="fw-bold">
                                <th class="pe-8">Total Belanja + PPN</th>
                                <td class="text-end">Rp <span id="usulan-grand-total">{{ number_format((float) ($selectedItems->sum('total_anggaran') + $selectedItems->sum('total_ppn')), 2, ',', '.') }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="usulan-zero-budget-notice" class="alert alert-warning mt-3 d-none" role="status"></div>
        </div>

        @if(
            in_array($usulanBelanja->status_usulan, ['draft', 'revision'])
            && $usulanBelanja->user_id === auth()->id()
        )
            @if($selectedItems->isNotEmpty())
                <form method="POST"
                      action="{{ route(
                          'usulan-belanja.ajukan',
                          $usulanBelanja->id
                      ) }}"
                      onsubmit="return confirm('Ajukan usulan untuk divalidasi?')">
                    @csrf

                    <button type="submit" class="btn btn-primary">
                        Ajukan untuk Validasi
                    </button>
                </form>
            @else
                <div class="alert alert-warning mt-4 mb-0">
                    Pilih minimal satu item RKBU sebelum mengajukan Usulan Belanja.
                </div>
            @endif
        @endif
    </div>
</div>
<style>
    .ub-detail-shell { overflow:hidden; border:1px solid #edf0f5; border-radius:16px; box-shadow:0 8px 28px rgba(31,45,61,.045); }
    .ub-page-header { flex-wrap:wrap; gap:14px; padding:1.4rem 1.6rem; background:linear-gradient(110deg,#eef5ff 0%,#fff 72%); border-bottom:1px solid #e8edf5; }
    .ub-heading-icon { display:grid; place-items:center; width:50px; height:50px; flex:0 0 50px; border-radius:15px; background:#fff; box-shadow:0 5px 18px #3699ff18; }
    .ub-overview { overflow:hidden; border:1px solid #e7edf5; border-radius:14px; background:#fff; }
    .ub-overview-top { display:flex; justify-content:space-between; align-items:center; gap:1rem; padding:1.35rem 1.5rem; background:linear-gradient(110deg,#f3f8ff,#fff 75%); }
    .ub-eyebrow { color:#8a94a6; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
    .ub-number { color:#1f2937; font-size:1.45rem; font-weight:750; letter-spacing:.01em; }
    .ub-status-badge { padding:.55rem .8rem; border-radius:999px; font-size:.7rem; letter-spacing:.04em; }
    .ub-total-highlight { display:flex; flex-direction:column; align-items:flex-end; gap:.25rem; }
    .ub-total-highlight span { color:#7c8798; font-size:.78rem; }
    .ub-total-highlight strong { color:#146cba; font-size:1.25rem; font-weight:750; }
    .ub-overview-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:0; padding:.25rem 1.5rem 1rem; }
    .ub-overview-item { min-width:0; padding:.85rem .75rem; border-top:1px solid #edf0f5; }
    .ub-overview-item span { display:flex; align-items:center; gap:.45rem; margin-bottom:.4rem; color:#8a94a6; font-size:.72rem; font-weight:650; text-transform:uppercase; letter-spacing:.025em; }
    .ub-overview-item span i { color:#3699ff; font-size:1rem; }
    .ub-overview-item strong { display:block; color:#30394b; font-size:.9rem; font-weight:650; overflow-wrap:anywhere; }
    .ub-item-section > .d-flex:first-child { padding:15px 17px; border:1px solid #e8edf5; border-radius:12px 12px 0 0; background:#f5f8ff; }
    .ub-data-table { min-width:900px; }
    .ub-data-table thead th { color:#7e8299; background:#f5f8fa; font-size:11px; text-transform:uppercase; white-space:nowrap; }
    .ub-detail-shell .card { border:1px solid #edf0f5; border-radius:14px; box-shadow:0 4px 16px rgba(31,45,61,.035); }
    .ub-detail-shell .card-header { border-bottom:1px solid #edf0f5; background:#fbfcfe; }
    .ub-detail-shell .table { --bs-table-bg:transparent; }
    .ub-detail-shell .table-responsive { border-radius:10px; }
    .ub-detail-shell .form-control, .ub-detail-shell .form-select { border-radius:8px; }
    .ub-history-card, .ub-decision-card { overflow:hidden; border:1px solid #edf0f5; border-radius:14px; box-shadow:0 4px 16px rgba(31,45,61,.035); }
    .ub-history-card .card-header, .ub-decision-card .card-header { border-bottom:1px solid #edf0f5; background:#f8faff; }
    .ub-history-card .card-title, .ub-decision-card .card-title { display:flex; align-items:center; gap:.75rem; }
    .ub-section-mark { display:inline-grid; place-items:center; width:36px; height:36px; border-radius:11px; background:#eaf3ff; }
    .ub-section-mark-success { background:#e8fff3; }
    @media(max-width:767.98px) { .ub-page-header { align-items:flex-start; flex-direction:column; padding:1rem; } .ub-page-header .card-toolbar { display:flex; flex-wrap:wrap; gap:8px; width:100%; } .ub-overview-top { align-items:flex-start; flex-direction:column; padding:1rem; } .ub-total-highlight { align-items:flex-start; } .ub-overview-grid { grid-template-columns:repeat(2,minmax(0,1fr)); padding:0 .75rem .75rem; } }
    @media(max-width:420px) { .ub-overview-grid { grid-template-columns:1fr; } .ub-number { font-size:1.15rem; } }
</style>

@if($canManageItems)
    <div class="modal fade" id="usulan-item-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST"
                      action="{{ route('usulan-belanja.items.store', $usulanBelanja->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Item Usulan Belanja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">
                            Hanya item dari RKBU berstatus approved dengan subkategori
                            {{ $usulanBelanja->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}
                            yang dapat dipilih.
                        </p>
                        @php
                            $adaItemDenganSisaCukup = $availableItems->contains(fn ($item) =>
                                (float) $item->sisa_anggaran_rkbu > 0
                                && (float) $item->sisa_vol_rkbu > 0
                                && (float) $item->volume > 0
                            );
                        @endphp
                        @if($availableItems->isNotEmpty() && ! $adaItemDenganSisaCukup)
                            <div class="alert alert-warning">
                                Tidak ada item dengan sisa anggaran dan volume RKBU yang mencukupi.
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="available-items-search" class="form-label">Cari Item</label>
                            <input type="search"
                                   id="available-items-search"
                                   class="form-control"
                                   placeholder="Nama barang, spesifikasi, atau nomor RKBU">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle gy-4">
                                <thead>
                                    <tr class="available-item-row">
                                        <th>Pilih</th>
                                        <th>Nama Barang</th>
                                        <th>Spesifikasi</th>
                                        <th>Total Anggaran</th>
                                        <th>Sisa Vol RKBU</th>
                                        <th>Sisa Anggaran RKBU</th>
                                        <th>Nomor RKBU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($availableItems as $item)
                                        <tr>
                                            <td>
                                                @php
                                                    $estimasiAnggaranItem = round((float) $item->harga_satuan * (float) $item->volume, 2);
                                                    $itemTidakCukup = (float) $item->sisa_anggaran_rkbu <= 0
                                                        || (float) $item->sisa_vol_rkbu <= 0
                                                        || (float) $item->volume <= 0;
                                                @endphp
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       name="items[]"
                                                       value="{{ $item->detail_type }}:{{ $item->detail_id }}"
                                                       aria-label="Pilih {{ $item->nama_barang }}"
                                                       @disabled($itemTidakCukup)>
                                                @if($itemTidakCukup)
                                                    <div class="text-danger fs-7 mt-1">
                                                        Sisa anggaran atau volume RKBU tidak cukup.
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->spesifikasi ?: '-' }}</td>
                                            <td>Rp {{ number_format($estimasiAnggaranItem, 2, ',', '.') }}</td>
                                            <td>{{ $item->sisa_vol_rkbu ?? '-' }}</td>
                                            <td>
                                                {{ $item->sisa_anggaran_rkbu !== null
                                                    ? 'Rp ' . number_format((float) $item->sisa_anggaran_rkbu, 0, ',', '.')
                                                    : '-' }}
                                            </td>
                                            <td>{{ $item->nomor_rkbu }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted available-items-empty">
                                                Tidak ada item approved untuk subkategori ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($availableItems->isNotEmpty())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span id="available-items-page-info" class="text-muted"></span>
                                <div class="d-flex gap-2">
                                    <button type="button" id="available-items-prev" class="btn btn-sm btn-light">
                                        Sebelumnya
                                    </button>
                                    <button type="button" id="available-items-next" class="btn btn-sm btn-light">
                                        Berikutnya
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                                class="btn btn-primary"
                                @disabled(! $adaItemDenganSisaCukup)>
                            Tambahkan Item Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<div class="card ub-history-card">
    <div class="card-header">
        <h3 class="card-title"><span class="ub-section-mark"><i class="ki-duotone ki-time text-primary"></i></span>Riwayat Validasi</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-dashed gy-4">
                <thead>
                    <tr>
                        <th>Tahap</th>
                        <th>Jenis Validator</th>
                        <th>Validator</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Waktu Validasi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                        <tr>
                            <td>{{ $approval->step_order }}</td>
                            <td>{{ $approval->validator_type }}</td>
                            <td>{{ $approval->nama_validator ?? '-' }}</td>
                            <td>{{ $approval->jabatan_validator ?? '-' }}</td>
                            <td>{{ strtoupper($approval->status) }}</td>
                            <td>
                                @php($waktuValidasi = $approval->approved_at ?? $approval->closed_at)
                                {{ $waktuValidasi ? \Carbon\Carbon::parse($waktuValidasi)->format('d-m-Y H:i') : '-' }}
                            </td>
                            <td>{{ $approval->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada riwayat approval.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($approvalSaya && $usulanBelanja->status_usulan === 'proses')
    <div class="card ub-decision-card mt-6">
        <div class="card-header">
            <h3 class="card-title"><span class="ub-section-mark ub-section-mark-success"><i class="ki-duotone ki-check-circle text-success"></i></span>Keputusan Validasi</h3>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route(
                      'usulan-belanja.validasi.proses',
                      $usulanBelanja->id
                  ) }}">
                @csrf

                <div class="mb-5">
                    <label class="form-label">Keputusan</label>
                    <select name="aksi" class="form-select" required>
                        <option value="">Pilih keputusan</option>
                        <option value="approved">Setujui</option>
                        <option value="revision">Kembalikan untuk revisi</option>
                        <option value="rejected">Tolak</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan"
                              class="form-control"
                              rows="3"
                              maxlength="5000"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Keputusan
                </button>
            </form>
        </div>
    </div>
@endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemRows = Array.from(document.querySelectorAll('.available-item-row'));
            const itemSearch = document.getElementById('available-items-search');
            const itemsPerPage = 10;
            let currentPage = 1;

            function renderAvailableItems() {
                if (!itemSearch) {
                    return;
                }

                const search = itemSearch.value.trim().toLocaleLowerCase('id-ID');
                const filteredRows = itemRows.filter(function (row) {
                    return row.textContent.toLocaleLowerCase('id-ID').includes(search);
                });
                const pageCount = Math.max(1, Math.ceil(filteredRows.length / itemsPerPage));
                currentPage = Math.min(currentPage, pageCount);

                itemRows.forEach(function (row) {
                    row.hidden = true;
                });

                filteredRows
                    .slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage)
                    .forEach(function (row) {
                        row.hidden = false;
                    });

                const pageInfo = document.getElementById('available-items-page-info');
                const previous = document.getElementById('available-items-prev');
                const next = document.getElementById('available-items-next');
                if (pageInfo) {
                    pageInfo.textContent = filteredRows.length
                        ? `Menampilkan ${(currentPage - 1) * itemsPerPage + 1}-${Math.min(currentPage * itemsPerPage, filteredRows.length)} dari ${filteredRows.length} item`
                        : 'Tidak ada item yang sesuai.';
                }
                if (previous) {
                    previous.disabled = currentPage === 1;
                    previous.hidden = pageCount === 1;
                }
                if (next) {
                    next.disabled = currentPage === pageCount;
                    next.hidden = pageCount === 1;
                }
            }

            if (itemSearch) {
                itemSearch.addEventListener('input', function () {
                    currentPage = 1;
                    renderAvailableItems();
                });
                document.getElementById('available-items-prev')?.addEventListener('click', function () {
                    currentPage--;
                    renderAvailableItems();
                });
                document.getElementById('available-items-next')?.addEventListener('click', function () {
                    currentPage++;
                    renderAvailableItems();
                });
                renderAvailableItems();
            }

            const selectedRows = Array.from(document.querySelectorAll('.usulan-item-row'));
            const selectedSearch = document.getElementById('selected-items-search');
            const selectedPerPage = 10;
            let selectedPage = 1;

            function renderSelectedItems() {
                if (!selectedSearch) return;

                const search = selectedSearch.value.trim().toLocaleLowerCase('id-ID');
                const filteredRows = selectedRows.filter(function (row) {
                    return row.textContent.toLocaleLowerCase('id-ID').includes(search);
                });
                const pageCount = Math.max(1, Math.ceil(filteredRows.length / selectedPerPage));
                selectedPage = Math.min(selectedPage, pageCount);

                selectedRows.forEach(row => row.hidden = true);
                filteredRows
                    .slice((selectedPage - 1) * selectedPerPage, selectedPage * selectedPerPage)
                    .forEach(row => row.hidden = false);

                const pageInfo = document.getElementById('selected-items-page-info');
                const previous = document.getElementById('selected-items-prev');
                const next = document.getElementById('selected-items-next');
                if (pageInfo) {
                    pageInfo.textContent = filteredRows.length
                        ? `Menampilkan ${(selectedPage - 1) * selectedPerPage + 1}-${Math.min(selectedPage * selectedPerPage, filteredRows.length)} dari ${filteredRows.length} item`
                        : 'Tidak ada item yang sesuai.';
                }
                if (previous) {
                    previous.disabled = selectedPage === 1;
                    previous.hidden = pageCount === 1;
                }
                if (next) {
                    next.disabled = selectedPage === pageCount;
                    next.hidden = pageCount === 1;
                }
            }

            selectedSearch?.addEventListener('input', function () {
                selectedPage = 1;
                renderSelectedItems();
            });
            document.getElementById('selected-items-prev')?.addEventListener('click', function () {
                selectedPage--;
                renderSelectedItems();
            });
            document.getElementById('selected-items-next')?.addEventListener('click', function () {
                selectedPage++;
                renderSelectedItems();
            });
            renderSelectedItems();

            const numberFormat = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            function updateUsulanTotals() {
                let totalUsulan = 0;
                let totalPpn = 0;
                const itemsWithZeroBudget = [];
                document.querySelectorAll('.usulan-item-row').forEach(function (row) {
                    const inputs = row.querySelectorAll('.usulan-item-input');
                    let subtotal;
                    let ppn;
                    let totalDenganPpn;
                    let volume = Number(row.dataset.volumeTersedia) || 0;

                    if (inputs.length) {
                        const hargaSatuan = Number(inputs[0].value) || 0;
                        volume = Number(inputs[1].value) || 0;
                        const persenPpn = Number(inputs[2].value) || 0;
                        subtotal = Math.round(hargaSatuan * volume * 100) / 100;
                        ppn = Math.round(subtotal * persenPpn) / 100;
                        totalDenganPpn = subtotal + ppn;
                    } else {
                        subtotal = Number(row.querySelector('.usulan-item-total').dataset.value) || 0;
                        ppn = subtotal * (Number(row.dataset.ppn) || 0) / 100;
                        totalDenganPpn = subtotal + ppn;
                    }

                    const budgetAvailable = Number(row.dataset.anggaranTersedia) || 0;
                    const volumeAvailable = Number(row.dataset.volumeTersedia) || 0;
                    const rawRemainingBudget = budgetAvailable - totalDenganPpn;
                    const remainingBudget = Math.sign(rawRemainingBudget)
                        * Math.round(Math.abs(rawRemainingBudget) * 10) / 10;
                    const insufficient = remainingBudget < 0 || volume > volumeAvailable;
                    const warning = row.querySelector('.usulan-item-warning');
                    const remainingVolume = Math.round((volumeAvailable - volume) * 1000) / 1000;

                    row.querySelector('.usulan-item-total').textContent = numberFormat.format(subtotal);
                    row.querySelector('.usulan-item-sisa-anggaran').textContent =
                        numberFormat.format(remainingBudget);
                    row.querySelector('.usulan-item-sisa-volume').textContent =
                        numberFormat.format(remainingVolume);
                    row.classList.toggle('table-danger', insufficient);
                    if (warning) {
                        warning.classList.toggle('d-none', !insufficient);
                    }
                    if (!insufficient && remainingBudget === 0) {
                        itemsWithZeroBudget.push(row.querySelector('td:nth-child(2)')?.innerText.trim() || 'Item RKBU');
                    }
                    totalUsulan += subtotal;
                    ppn = Math.round(ppn * 100) / 100;
                    totalPpn += ppn;
                });

                totalUsulan = Math.round(totalUsulan * 100) / 100;
                totalPpn = Math.round(totalPpn * 100) / 100;
                document.getElementById('usulan-total').textContent = numberFormat.format(totalUsulan);
                document.getElementById('usulan-ppn').textContent = numberFormat.format(totalPpn);
                document.getElementById('usulan-grand-total').textContent =
                    numberFormat.format(totalUsulan + totalPpn);

                const zeroBudgetNotice = document.getElementById('usulan-zero-budget-notice');
                if (zeroBudgetNotice) {
                    zeroBudgetNotice.classList.toggle('d-none', itemsWithZeroBudget.length === 0);
                    zeroBudgetNotice.textContent = itemsWithZeroBudget.length
                        ? `Lakukan pergeseran Penambahan Anggaran agar Usulan Belanja bisa dilakukan kembali untuk item yang sisa anggarannya 0: ${itemsWithZeroBudget.join(', ')}.`
                        : '';
                }
            }

            document.querySelectorAll('.usulan-item-input').forEach(function (input) {
                input.addEventListener('input', updateUsulanTotals);
            });

            document.getElementById('usulan-items-update-form')?.addEventListener('submit', function (event) {
                const hargaDiBawahRencana = Array.from(document.querySelectorAll('.usulan-item-row')).some(function (row) {
                    const hargaInput = row.querySelector('input[name$="[harga_satuan]"]');
                    return hargaInput && Number(hargaInput.value) < Number(row.dataset.hargaPerencanaan);
                });

                if (hargaDiBawahRencana && !window.confirm('Harga satuan yang diusulkan lebih rendah dari harga perencanaan. Mohon periksa kembali sebelum mengajukan validasi. Lanjutkan?')) {
                    event.preventDefault();
                }
            });

            updateUsulanTotals();
        });
    </script>
@endpush
