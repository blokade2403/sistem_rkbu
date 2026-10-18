
@extends('layouts.main')

@section('title', 'Detail Usulan Belanja')

@section('content')
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Detail Usulan Belanja</h3>

        <div class="card-toolbar">
            <a href="{{ route('usulan-belanja.index') }}"
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

        <div class="table-responsive">
            <table class="table table-row-bordered gy-4">
                <tr>
                    <th class="w-25">Nomor Usulan</th>
                    <td>{{ $usulanBelanja->no_usulan_belanja }}</td>
                </tr>
                <tr>
                    <th>Nama Pengusul</th>
                    <td>{{ $usulanBelanja->nama_pengusul }}</td>
                </tr>
                <tr>
                    <th>Nomor RKBU</th>
                    <td>
                        {{ $usulanBelanja->rkbu?->nomor_rkbu ?? '-' }}
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

        <div class="mt-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Item Usulan Belanja</h4>
                <span class="badge badge-light-primary">
                    Subkategori: {{ $usulanBelanja->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-row-dashed gy-4">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Barang</th>
                            <th>Spesifikasi</th>
                            <th>Total Anggaran</th>
                            <th>Sisa Vol RKBU</th>
                            <th>Sisa Anggaran RKBU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selectedItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->nama_barang }}
                                    <div class="text-muted fs-7">{{ $item->nomor_rkbu }}</div>
                                </td>
                                <td>{{ $item->spesifikasi ?: '-' }}</td>
                                <td>Rp {{ number_format((float) $item->total_anggaran, 0, ',', '.') }}</td>
                                <td>{{ $item->sisa_vol_rkbu ?? '-' }}</td>
                                <td>
                                    {{ $item->sisa_anggaran_rkbu !== null
                                        ? 'Rp ' . number_format((float) $item->sisa_anggaran_rkbu, 0, ',', '.')
                                        : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada item yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($canManageItems)
                <button type="button"
                        class="btn btn-primary mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#usulan-item-modal"
                        @disabled($availableItems->isEmpty())>
                    Tambah Item
                </button>
            @endif
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
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle gy-4">
                                <thead>
                                    <tr>
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
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       name="items[]"
                                                       value="{{ $item->detail_type }}:{{ $item->detail_id }}"
                                                       aria-label="Pilih {{ $item->nama_barang }}">
                                            </td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->spesifikasi ?: '-' }}</td>
                                            <td>Rp {{ number_format((float) $item->total_anggaran, 0, ',', '.') }}</td>
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
                                            <td colspan="7" class="text-center text-muted">
                                                Tidak ada item approved untuk subkategori ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit"
                                class="btn btn-primary"
                                @disabled($availableItems->isEmpty())>
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
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                        <tr>
                            <td>{{ $approval->step_order }}</td>
                            <td>{{ $approval->validator_type }}</td>
                            <td>{{ $approval->nama_validator ?? '-' }}</td>
                            <td>{{ strtoupper($approval->status) }}</td>
                            <td>{{ $approval->catatan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
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
