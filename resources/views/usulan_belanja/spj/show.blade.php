@extends('layouts.main')

@section('title', 'Detail SPJ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail SPJ {{ $spj->nomor_urut }}</h3>
            <div class="card-toolbar">
                <a href="{{ route('usulan-belanja.spj.index') }}" class="btn btn-light">Kembali</a>
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
            <div class="table-responsive mb-7">
                <table class="table table-row-bordered gy-3">
                    <tr>
                        <th class="w-25">Nomor SPJ</th>
                        <td>{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}
                        </td>
                    </tr>
                    <tr>
                        <th>Nomor Usulan Belanja</th>
                        <td>{{ $spj->usulanBelanja?->no_usulan_belanja ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pengusul</th>
                        <td>{{ $spj->usulanBelanja?->nama_pengusul ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Penyedia</th>
                        <td>{{ $namaPerusahaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Anggaran</th>
                        <td>{{ $spj->usulanBelanja?->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status SPJ</th>
                        <td><span class="badge badge-light-primary">{{ $spj->status_spj }}</span></td>
                    </tr>
                </table>
            </div>

            @if ($canManageSpj)
                <details class="mb-6">
                    <summary class="btn btn-sm btn-light-primary">Tambah Item</summary>
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

            <h4 class="mb-4">Item dalam SPJ ({{ $items->count() }})</h4>
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle gy-4">
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
                        @forelse($items as $item)
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
                                <td>
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
                                            class="mt-2" onsubmit="return confirm('Hapus item ini dari SPJ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Item SPJ tidak ditemukan.</td>
                            </tr>
                        @endforelse
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
        <div class="mt-5">
            <!--begin::Navbar-->
            <div class="card mb-5">
                <div class="card-body">
                      @if (auth()->user()->hasRole('Administrator') ||
                    auth()->user()->hasRole('Super Admin') ||
                    auth()->user()->hasRole('Admin Pengadaan') ||
                    auth()->user()->hasRole('Pendukung PPK') ||
                    auth()->user()->hasRole('Pengurus Barang') ||
                    auth()->user()->roles()->whereIn('nama_role', ['PPK', 'PPBJ', 'PPTK', 'Verifikator Pengadaan', 'PPK Keuangan', 'Bendahara'])->exists() ||
                    in_array(auth()->user()->position?->level_jabatan, ['PPK', 'PPBJ', 'PPTK', 'Verifikator', 'PPK Keuangan', 'Direktur', 'Bendahara'], true) ||
                    (isset($workflowStep, $canWorkflowStep) && $canWorkflowStep) || (isset($canValidasi) && $canValidasi))
                <div class="mt-8">
                    @include('usulan_belanja.pengadaan.show')
                </div>
            @endif
                </div>
            </div>
          
        </div>

@endsection



