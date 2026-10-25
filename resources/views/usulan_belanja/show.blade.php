
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
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive ub-summary-wrap">
            <table class="table table-row-bordered gy-3 ub-summary-table">
                <tr>
                    <th class="w-25">Nomor Usulan</th>
                    <td>{{ $usulanBelanja->no_usulan_belanja }}</td>
                </tr>
                <tr>
                    <th>Nama Pengusul</th>
                    <td>{{ $usulanBelanja->nama_pengusul }}</td>
                </tr>
                <tr>
                    <th>Subkategori RKBU</th>
                    <td>
                        {{ $usulanBelanja->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Tahun Anggaran</th>
                    <td>
                        {{ $usulanBelanja->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-light-primary">
                            {{ strtoupper($usulanBelanja->status_usulan) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Validasi</th>
                    <td>
                        {{ $usulanBelanja->tanggal_validasi?->format('d-m-Y H:i') ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Validator Terakhir</th>
                    <td>{{ $usulanBelanja->nama_validasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{{ $usulanBelanja->catatan ?? '-' }}</td>
                </tr>
            </table>
        </div>

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
                                data-volume-tersedia="{{ $item->volume_tersedia }}">
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
                                            Sisa anggaran atau volume RKBU tidak cukup.
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
    .ub-detail-shell { overflow:hidden; border-radius:12px; }
    .ub-page-header { flex-wrap:wrap; gap:14px; background:linear-gradient(110deg,#f2f7ff,#fff 70%); border-bottom:1px solid #e8edf5; }
    .ub-heading-icon { display:grid; place-items:center; width:46px; height:46px; flex:0 0 46px; border-radius:12px; background:#fff; box-shadow:0 3px 12px #3699ff18; }
    .ub-summary-wrap { padding:12px 16px; border:1px solid #edf0f5; border-radius:10px; background:#fbfcfe; }
    .ub-summary-table { margin:0; }
    .ub-summary-table th { width:25%; color:#7e8299; font-size:13px; }
    .ub-summary-table td { color:#3f4254; font-weight:600; overflow-wrap:anywhere; }
    .ub-item-section > .d-flex:first-child { padding:15px 17px; border:1px solid #e8edf5; border-radius:10px 10px 0 0; background:#f5f8ff; }
    .ub-data-table { min-width:900px; }
    .ub-data-table thead th { color:#7e8299; background:#f5f8fa; font-size:11px; text-transform:uppercase; white-space:nowrap; }
    @media(max-width:767.98px) { .ub-page-header { align-items:flex-start; flex-direction:column; } .ub-page-header .card-toolbar { display:flex; flex-wrap:wrap; gap:8px; width:100%; } .ub-summary-table th { width:auto; min-width:135px; } .ub-summary-wrap { padding:8px; } }
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
                                round((float) $item->harga_satuan * (float) $item->volume, 2) <= (float) $item->sisa_anggaran_rkbu
                                && (float) $item->volume <= (float) $item->sisa_vol_rkbu
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
                                                    $itemTidakCukup = $estimasiAnggaranItem > (float) $item->sisa_anggaran_rkbu
                                                        || (float) $item->volume > (float) $item->sisa_vol_rkbu;
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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Validasi</h3>
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
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title">Keputusan Validasi</h3>
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
                let itemTidakCukup = false;

                document.querySelectorAll('.usulan-item-row').forEach(function (row) {
                    const inputs = row.querySelectorAll('.usulan-item-input');
                    let subtotal;
                    let ppn;
                    let volume = Number(row.dataset.volumeTersedia) || 0;

                    if (inputs.length) {
                        const hargaSatuan = Number(inputs[0].value) || 0;
                        volume = Number(inputs[1].value) || 0;
                        const persenPpn = Number(inputs[2].value) || 0;
                        subtotal = Math.round(hargaSatuan * volume * 100) / 100;
                        ppn = Math.round(subtotal * persenPpn) / 100;
                    } else {
                        subtotal = Number(row.querySelector('.usulan-item-total').dataset.value) || 0;
                        ppn = subtotal * (Number(row.dataset.ppn) || 0) / 100;
                    }

                    const budgetAvailable = Number(row.dataset.anggaranTersedia) || 0;
                    const volumeAvailable = Number(row.dataset.volumeTersedia) || 0;
                    const insufficient = subtotal > budgetAvailable || volume > volumeAvailable;
                    const warning = row.querySelector('.usulan-item-warning');
                    const remainingBudget = Math.round((budgetAvailable - subtotal) * 100) / 100;
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
                    itemTidakCukup = itemTidakCukup || insufficient;
                    totalUsulan += subtotal;
                    ppn = Math.round(ppn * 100) / 100;
                    totalPpn += ppn;
                });

                const submitButton = document.getElementById('usulan-items-update-submit');
                if (submitButton) {
                    submitButton.disabled = itemTidakCukup;
                }

                totalUsulan = Math.round(totalUsulan * 100) / 100;
                totalPpn = Math.round(totalPpn * 100) / 100;
                document.getElementById('usulan-total').textContent = numberFormat.format(totalUsulan);
                document.getElementById('usulan-ppn').textContent = numberFormat.format(totalPpn);
                document.getElementById('usulan-grand-total').textContent =
                    numberFormat.format(totalUsulan + totalPpn);
            }

            document.querySelectorAll('.usulan-item-input').forEach(function (input) {
                input.addEventListener('input', updateUsulanTotals);
            });

            updateUsulanTotals();
        });
    </script>
@endpush
