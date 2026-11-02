@php
    $quantity = fn ($value) => rtrim(rtrim(number_format((float) $value, 3, ',', '.'), '0'), ',');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Persediaan - {{ $cards->first()?->item?->nama_barang ?? 'Hasil Filter' }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; color: #111; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        .toolbar { display: flex; justify-content: flex-end; margin: 0 auto 16px; max-width: 1100px; }
        .print-button { border: 0; border-radius: 4px; background: #1689e8; color: #fff; padding: 10px 16px; font-size: 14px; cursor: pointer; }
        .sheet { max-width: 1100px; margin: 0 auto; }
        h1 { margin: 0 0 18px; text-align: center; font-size: 19px; letter-spacing: .4px; }
        .details { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
        .details td { padding: 4px 6px; vertical-align: top; }
        .details td:first-child { width: 135px; font-weight: 700; }
        .details td:nth-child(3) { width: 110px; font-weight: 700; }
        table.movements { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .movements th, .movements td { border: 1px solid #222; padding: 7px 6px; }
        .movements th { text-align: center; font-weight: 700; }
        .movements td { height: 30px; }
        .center { text-align: center; }
        .number { text-align: right; }
        .notes { overflow-wrap: anywhere; }
        .empty { text-align: center; color: #555; }
        @page { size: A4 landscape; margin: 14mm; }
        @media print {
            body { padding: 0; font-size: 11px; }
            .toolbar { display: none; }
            .sheet { max-width: none; }
            .movements thead { display: table-header-group; }
            tr { break-inside: avoid; }
            .sheet { break-after: page; }
            .sheet:last-of-type { break-after: auto; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="toolbar"><button class="print-button" type="button" onclick="window.print()">Cetak Kartu Stok</button></div>
    @forelse ($cards as $card)
    @php
        $item = $card->item;
        $transactions = $card->transactions;
        $openingBalance = (float) ($transactions->first()?->stok_sebelum ?? 0);
        $closingBalance = (float) ($transactions->last()?->stok_sesudah ?? $item->stok);
    @endphp
    <main class="sheet">
        <h1>KARTU PERSEDIAAN BARANG</h1>
        <table class="details">
            <tr><td>Nama Barang</td><td>: {{ $item->nama_barang }}</td><td>Satuan</td><td>: {{ $item->satuan ?: '-' }}</td></tr>
            <tr><td>Kode Barang</td><td>: {{ $item->kode_barang ?: '-' }}</td><td>Unit</td><td>: {{ $item->unit?->nama_unit ?: '-' }}</td></tr>
            <tr><td>Spesifikasi</td><td colspan="3">: {{ $item->spesifikasi ?: '-' }}</td></tr>
        </table>
        <table class="movements">
            <thead><tr><th style="width:5%">No</th><th style="width:13%">Tanggal</th><th style="width:13%">Masuk</th><th style="width:13%">Keluar</th><th style="width:13%">Sisa</th><th>Keterangan</th></tr></thead>
            <tbody>
                @if ($transactions->isEmpty())
                    <tr><td colspan="6" class="empty">Belum ada riwayat mutasi untuk barang ini.</td></tr>
                @else
                    @foreach ($transactions as $index => $transaction)
                        @php
                            $change = (float) $transaction->stok_sesudah - (float) $transaction->stok_sebelum;
                            $incoming = $transaction->jenis === 'masuk' ? abs((float) $transaction->jumlah) : ($transaction->jenis === 'opname' && $change > 0 ? $change : 0);
                            $outgoing = $transaction->jenis === 'keluar' ? abs((float) $transaction->jumlah) : ($transaction->jenis === 'opname' && $change < 0 ? abs($change) : 0);
                            if ($transaction->jenis === 'keluar' && $outgoing == 0 && $change < 0) $outgoing = abs($change);
                            if ($transaction->jenis === 'masuk' && $incoming == 0 && $change > 0) $incoming = $change;
                        @endphp
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="center">{{ $transaction->tanggal?->format('d-m-Y') ?: '-' }}</td>
                            <td class="number">{{ $incoming > 0 ? $quantity($incoming) : '-' }}</td>
                            <td class="number">{{ $outgoing > 0 ? $quantity($outgoing) : '-' }}</td>
                            <td class="number">{{ $quantity($transaction->stok_sesudah) }}</td>
                            <td class="notes">{{ $transaction->keterangan ?: ucfirst($transaction->jenis) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <p>Saldo sebelum transaksi pertama: {{ $quantity($openingBalance) }} {{ $item->satuan }} &nbsp; | &nbsp; Saldo sesudah transaksi terakhir: {{ $quantity($closingBalance) }} {{ $item->satuan }}
            @if (filled($filters['dari'] ?? null) || filled($filters['sampai'] ?? null))
                &nbsp; | &nbsp; Periode: {{ filled($filters['dari'] ?? null) ? \Illuminate\Support\Carbon::parse($filters['dari'])->format('d-m-Y') : 'Awal' }} s.d. {{ filled($filters['sampai'] ?? null) ? \Illuminate\Support\Carbon::parse($filters['sampai'])->format('d-m-Y') : 'Sekarang' }}
            @endif
            &nbsp; | &nbsp; Dicetak: {{ $printedAt->format('d-m-Y H:i') }}</p>
    </main>
    @empty
        <main class="sheet"><h1>KARTU PERSEDIAAN BARANG</h1><p class="empty">Tidak ada mutasi barang yang sesuai dengan filter.</p></main>
    @endforelse
</body>
</html>
