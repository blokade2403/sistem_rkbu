{{-- <canvas id="chartRealisasi" height="100"></canvas> --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Daftar Inputan</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                        <i class="ri-file-list-3-line align-middle"></i> Generate Report
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            @foreach ($dataGabungan as $item)
                                <th class="fa-sm">{{ $item['bulan'] }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Target</th>
                            @foreach ($dataGabungan as $item)
                                <td class="fa-sm">{{ number_format($item['target'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <th>Pembayaran</th>
                            @foreach ($dataGabungan as $item)
                                <td class="fa-sm">{{ number_format($item['realisasi'], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


{{-- @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($dataGabungan->pluck('bulan')) !!};
        const dataTarget = {!! json_encode($dataGabungan->pluck('target')) !!}.map(x => Number(x));
        const dataRealisasi = {!! json_encode($dataGabungan->pluck('realisasi')) !!}.map(x => Number(x));

        // Hitung kumulatif
        const dataTargetKumulatif = dataTarget.reduce((acc, val, i) => {
            acc.push((acc[i - 1] || 0) + val);
            return acc;
        }, []);

        const dataRealisasiKumulatif = dataRealisasi.reduce((acc, val, i) => {
            acc.push((acc[i - 1] || 0) + val);
            return acc;
        }, []);

        const maxY = Math.max(...dataTargetKumulatif, ...dataRealisasiKumulatif);

        const ctx = document.getElementById('chartRealisasi').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Target Bulanan',
                        data: dataTarget,
                        borderColor: 'blue',
                        backgroundColor: 'blue',
                        fill: false,
                        tension: 0
                    },
                    {
                        label: 'Pembayaran Bulanan',
                        data: dataRealisasi,
                        borderColor: 'orange',
                        backgroundColor: 'orange',
                        fill: false,
                        tension: 0
                    },
                    {
                        label: 'Target Kumulatif',
                        data: dataTargetKumulatif,
                        borderColor: 'darkblue',
                        backgroundColor: 'darkblue',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0
                    },
                    {
                        label: 'Pembayaran Kumulatif',
                        data: dataRealisasiKumulatif,
                        borderColor: 'darkorange',
                        backgroundColor: 'darkorange',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Realisasi dan Target Pembayaran per Bulan (Termasuk Kumulatif)'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString(
                                    'id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: maxY * 1.1,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    </script>
@endpush --}}
