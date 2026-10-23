<div class="table-responsive w-100 mt-4 mb-6">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
        <thead>
            <tr class="text-muted fw-bold">
                <th>Rekening Belanja</th>
                <th class="text-end">Pagu Anggaran</th>
                <th class="text-end">Total RKBU Disetujui</th>
                <th class="text-end">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paguAccountSummary as $summary)
                <tr>
                    <td>{{ $summary['rekening'] }}</td>
                    <td class="text-end">Rp {{ number_format($summary['pagu'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($summary['approved_total'], 0, ',', '.') }}</td>
                    <td class="text-end fw-bold {{ $summary['selisih'] < 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($summary['selisih'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Pagu rekening untuk tahun anggaran ini belum tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
