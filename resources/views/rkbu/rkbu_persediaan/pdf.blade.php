<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 16px;
            text-align: center;
        }

        .meta {
            margin-bottom: 14px;
            text-align: center;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background: #eee;
            text-align: center;
        }

        .number {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>RENCANA KEBUTUHAN BARANG UNIT ({{ strtoupper($title) }})</h1>
    <div class="meta">
        Tahun Anggaran: {{ $tahunAnggaran ?: '-' }} |
        Dicetak: {{ $printedAt->format('d-m-Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor RKBU</th>
                <th>Subkategori RKBU</th>
                <th>Unit</th>
                <th>Sumber Dana</th>
                <th>Status Approval</th>
                <th>Nama Barang</th>
                <th>Spesifikasi</th>
                <th>Volume</th>
                <th>Harga Satuan</th>
                <th>Total Anggaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $record)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $record->rkbu?->nomor_rkbu ?? '-' }}</td>
                    <td>
                        {{ $record->rkbu?->subKategoriRkbu?->kode_sub_kategori_rkbu ?? '-' }}
                        - {{ $record->rkbu?->subKategoriRkbu?->nama_sub_kategori_rkbu ?? '-' }}
                    </td>
                    <td>{{ $record->rkbu?->unit?->nama_unit ?? '-' }}</td>
                    <td>{{ $record->rkbu?->sumberDana?->nama_sumber_dana ?? '-' }}</td>
                    <td>{{ $approvalStatuses[$record->rkbu?->status ?? ''] ?? '-' }}</td>
                    <td>{{ $record->nama_barang }}</td>
                    <td>{{ $record->spesifikasi ?: '-' }}</td>
                    <td class="number">
                        {{ number_format((float) $record->jumlah_volume, 0, ',', '.') }}
                        {{ $record->satuan_1 }}
                    </td>
                    <td class="number">
                        Rp {{ number_format((float) $record->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="number">
                        Rp {{ number_format((float) $record->total_anggaran, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="center">Tidak ada data RKBU.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
