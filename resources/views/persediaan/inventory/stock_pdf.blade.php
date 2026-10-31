<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Persediaan</title>
    <style>
        @page { margin: 24px 28px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 9px; }
        h1 { font-size: 18px; margin: 0 0 5px; }
        .muted { color: #64748b; }
        .meta { margin-bottom: 14px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; vertical-align: top; }
        th { background: #eff6ff; text-align: left; }
        .right { text-align: right; }
        tfoot td { font-weight: bold; background: #f8fafc; }
    </style>
</head>
<body>
    <h1>Laporan Stok Persediaan</h1>
    <div class="meta muted">
        Dicetak: {{ $printedAt->format('d-m-Y H:i') }}<br>
        @if (!empty($filters['search'])) Pencarian: {{ $filters['search'] }}<br>@endif
        @if (!empty($filters['sub_kategori_rkbu_id'])) Subkategori RKBU: {{ $items->first()?->subKategoriRkbu?->nama_sub_kategori_rkbu ?: $filters['sub_kategori_rkbu_id'] }}<br>@endif
        Jumlah jenis barang: {{ number_format($items->count(), 0, ',', '.') }}
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%">No.</th><th style="width: 12%">Kode</th><th>Nama barang / spesifikasi</th><th>Subkategori RKBU</th><th>Unit gudang</th>
                <th class="right">Stok</th><th>Satuan</th><th class="right">Harga + PPN</th><th class="right">Nilai persediaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                @php
                    $unitPrice = (float) ($item->harga_satuan ?? 0) * (1 + (float) ($item->ppn ?? 0) / 100);
                    $itemValue = (float) $item->stok * $unitPrice;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td><td>{{ $item->kode_barang ?: '-' }}</td>
                    <td><strong>{{ $item->nama_barang }}</strong>@if ($item->spesifikasi)<br><span class="muted">{{ $item->spesifikasi }}</span>@endif</td>
                    <td>{{ $item->subKategoriRkbu?->nama_sub_kategori_rkbu ?: '-' }}</td><td>{{ $item->unit?->nama_unit ?: '-' }}</td>
                    <td class="right">{{ number_format((float) $item->stok, 3, ',', '.') }}</td><td>{{ $item->satuan ?: '-' }}</td>
                    <td class="right">{{ $item->harga_satuan !== null ? 'Rp ' . number_format($unitPrice, 0, ',', '.') : '-' }}</td>
                    <td class="right">{{ $item->harga_satuan !== null ? 'Rp ' . number_format($itemValue, 0, ',', '.') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center">Tidak ada barang yang sesuai dengan filter.</td></tr>
            @endforelse
        </tbody>
        <tfoot><tr><td colspan="8" class="right">Total nilai persediaan</td><td class="right">Rp {{ number_format($totalValue, 0, ',', '.') }}</td></tr></tfoot>
    </table>
</body>
</html>
