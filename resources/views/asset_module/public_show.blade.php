<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Aset - {{ $asset->nama_asset }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px 14px; background: #f3f6fa; color: #1f2937; font: 15px/1.5 Arial, sans-serif; }
        .card { max-width: 680px; margin: 0 auto; overflow: hidden; border: 1px solid #e5eaf1; border-radius: 16px; background: #fff; box-shadow: 0 8px 28px rgba(32,48,72,.08); }
        .header { padding: 22px 24px; background: linear-gradient(120deg,#eef5ff,#fff); border-bottom: 1px solid #e8edf4; }
        .eyebrow { color: #697586; font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        h1 { margin: 5px 0; font-size: 24px; line-height: 1.25; }
        .register { color: #566274; }
        .body { padding: 12px 24px 22px; }
        .row { display: grid; grid-template-columns: minmax(130px, 38%) 1fr; gap: 12px; padding: 12px 0; border-bottom: 1px solid #edf0f4; }
        .row:last-child { border-bottom: 0; }
        .label { color: #778295; }
        .value { font-weight: 600; overflow-wrap: anywhere; }
        .condition { display: inline-block; padding: 3px 10px; border-radius: 999px; background: #e8f7ee; color: #16834c; font-size: 12px; }
        .condition.danger { background: #fff0f0; color: #b42318; }
        .footer { padding: 12px 24px; border-top: 1px solid #edf0f4; color: #8993a2; font-size: 12px; }
        @media(max-width:480px) { body { padding: 10px; } .header { padding: 18px; } .body { padding: 8px 18px 18px; } h1 { font-size: 21px; } .row { grid-template-columns: 1fr; gap: 3px; } }
    </style>
</head>
<body>
    <main class="card">
        <header class="header">
            <div class="eyebrow">Informasi aset</div>
            <h1>{{ $asset->nama_asset }}</h1>
            <div class="register">No. register: {{ $asset->no_register ?: '-' }}</div>
        </header>
        <section class="body">
            <div class="row"><div class="label">Kode aset</div><div class="value">{{ $asset->kode_asset ?: '-' }}</div></div>
            <div class="row"><div class="label">Merk / tipe</div><div class="value">{{ trim(($asset->merk ?? '') . ' ' . ($asset->type ?? '')) ?: '-' }}</div></div>
            <div class="row"><div class="label">Spesifikasi</div><div class="value">{{ $asset->spek ?: '-' }}</div></div>
            <div class="row"><div class="label">Kondisi</div><div class="value"><span class="condition {{ $asset->kondisi_asset === 'Rusak Berat' ? 'danger' : '' }}">{{ $asset->kondisi_asset ?: 'Belum Dinilai' }}</span></div></div>
            <div class="row"><div class="label">Status daftar aset</div><div class="value">{{ $asset->status_reklas_arb === 'Reklas' ? 'Dalam usulan penghapusan' : ($asset->status_asset ?: 'Aktif') }}</div></div>
            <div class="row"><div class="label">Tahun perolehan</div><div class="value">{{ $asset->tahun_perolehan ?: '-' }}</div></div>
            <div class="row"><div class="label">Jumlah</div><div class="value">{{ rtrim(rtrim(number_format((float) $asset->jumlah_asset, 3, ',', '.'), '0'), ',') }} {{ $asset->satuan }}</div></div>
            <div class="row"><div class="label">Unit</div><div class="value">{{ $asset->unit?->nama_unit ?: '-' }}</div></div>
            <div class="row"><div class="label">Lokasi</div><div class="value">{{ $asset->lokasi_asset ?: $asset->Penempatan?->lokasi_barang ?: '-' }}</div></div>
            <div class="row"><div class="label">Sumber perolehan</div><div class="value">{{ $asset->rkbu?->nomor_rkbu ?: ($asset->hibah ? 'Hibah / manual' : 'Aset awal / manual') }}</div></div>
        </section>
        <footer class="footer">Informasi inventaris barang. Hubungi unit pengelola aset untuk pembaruan data.</footer>
    </main>
</body>
</html>
