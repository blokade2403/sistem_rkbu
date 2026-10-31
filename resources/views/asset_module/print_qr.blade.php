<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak QR Aset</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; color: #111827; font: 14px/1.35 Arial, sans-serif; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; max-width: 210mm; margin: 0 auto 18px; }
        .toolbar button { padding: 9px 16px; border: 0; border-radius: 6px; background: #2563eb; color: #fff; cursor: pointer; }
        .labels { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8mm; max-width: 210mm; margin: auto; }
        .label { min-height: 58mm; padding: 4mm; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; border: 1px dashed #9ca3af; break-inside: avoid; page-break-inside: avoid; }
        .label img { width: 38mm; height: 38mm; object-fit: contain; }
        .register { margin-top: 2mm; font-size: 9pt; font-weight: 700; }
        .name { margin-top: 1mm; font-size: 8pt; overflow-wrap: anywhere; }
        .unit { margin-top: 1mm; color: #4b5563; font-size: 7pt; }
        @page { size: A4; margin: 12mm; }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
            .labels { max-width: none; grid-template-columns: repeat(3, 1fr); gap: 5mm; }
            .label { min-height: 58mm; }
        }
        @media (max-width: 600px) { body { padding: 12px; } .labels { grid-template-columns: repeat(2, 1fr); gap: 10px; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <div><strong>{{ $assets->count() }} label QR aset</strong><div>Gunakan pengaturan cetak ukuran A4.</div></div>
        <button type="button" onclick="window.print()">Cetak QR</button>
    </div>
    <main class="labels">
        @foreach ($assets as $asset)
            <article class="label">
                <img src="{{ route('asset.qr', $asset->id_asset) }}" alt="QR {{ $asset->nama_asset }}">
                <div class="register">{{ $asset->no_register ?: $asset->kode_asset ?: 'ASET' }}</div>
                <div class="name">{{ $asset->nama_asset }}</div>
                <div class="unit">{{ $asset->unit?->nama_unit ?: '-' }}</div>
            </article>
        @endforeach
    </main>
</body>
</html>
