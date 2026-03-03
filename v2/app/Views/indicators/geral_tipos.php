<div class="container mt-5">

    <h2 class="mb-4">📊 Produção Total (Científica + Artística)</h2>

    <canvas id="graficoUnificado"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const producao = <?= json_encode($producao) ?>;

    // ==============================
    // Separar dados
    // ==============================

    // Científica
    const cientificaLabels = Object.keys(producao.cientifica);
    const cientificaValores = Object.values(producao.cientifica);

    // Artística
    const artisticaLabels = producao.artistica.map(item => item.tipo);
    const artisticaValores = producao.artistica.map(item => item.total);

    // Unificar labels
    const labels = [...cientificaLabels, ...artisticaLabels];

    // Dataset Científica (preenche zeros onde for artística)
    const datasetCientifica = [
        ...cientificaValores,
        ...Array(artisticaValores.length).fill(0)
    ];

    // Dataset Artística (preenche zeros onde for científica)
    const datasetArtistica = [
        ...Array(cientificaValores.length).fill(0),
        ...artisticaValores
    ];

    // ==============================
    // Gráfico
    // ==============================

    const ctx = document.getElementById('graficoUnificado');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                    label: 'Produção Científica',
                    data: datasetCientifica,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Produção Artística',
                    data: datasetArtistica,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)' // 🌸 rosado
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>