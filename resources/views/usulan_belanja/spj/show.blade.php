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
        <div class="table-responsive mb-7">
            <table class="table table-row-bordered gy-3">
                <tr><th class="w-25">Nomor SPJ</th><td>{{ $spj->no_usulan_belanja }}-{{ str_pad((string) $spj->nomor_urut, 2, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><th>Nomor Usulan Belanja</th><td>{{ $spj->usulanBelanja?->no_usulan_belanja ?? '-' }}</td></tr>
                <tr><th>Pengusul</th><td>{{ $spj->usulanBelanja?->nama_pengusul ?? '-' }}</td></tr>
                <tr><th>Penyedia</th><td>{{ $namaPerusahaan ?? '-' }}</td></tr>
                <tr><th>Tahun Anggaran</th><td>{{ $spj->usulanBelanja?->tahunAnggaran?->nama_tahun_anggaran ?? '-' }}</td></tr>
                <tr><th>Status SPJ</th><td><span class="badge badge-light-primary">{{ $spj->status_spj }}</span></td></tr>
            </table>
        </div>

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
                            <td class="text-end">Rp {{ number_format((float) $item->total_anggaran + (float) $item->total_ppn, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Item SPJ tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="6" class="text-end">Total</td>
                        <td class="text-end">Rp {{ number_format((float) $items->sum('total_anggaran') + (float) $items->sum('total_ppn'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
