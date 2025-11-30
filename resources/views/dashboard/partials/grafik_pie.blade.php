<div class="col-xl-3 col-md-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Grafik Anggaran Berdasarkan Jenis Belanja</h4>
        </div>
        <div class="card-body">
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js + Plugin DataLabels -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    const pegawai = {{ $jenis_pegawai }};
    const modal = {{ $query_modal }};
    const operasional = {{ $query_operasional }};
    const persediaan = {{ $query_persediaan }};

    const total = pegawai + modal + operasional + persediaan;

    const ctx = document.getElementById('pieChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                'Belanja Pegawai',
                'Belanja Modal',
                'Belanja Operasional',
                'Belanja Persediaan'
            ],
            datasets: [{
                data: [
                    pegawai,
                    modal,
                    operasional,
                    persediaan
                ],
                backgroundColor: [
                    '#2E86C1',
                    '#28B463',
                    '#AF7AC5',
                    '#F1C40F'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: function(value, ctx) {
                        // Hitung persen
                        let persen = (value / total * 100).toFixed(1);

                        // Format angka agar ada pemisah ribuan
                        let nilai = value.toLocaleString();

                        return nilai + "\n(" + persen + "%)";
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw.toLocaleString();
                            const persen = ((context.raw / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${persen}%)`;
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
