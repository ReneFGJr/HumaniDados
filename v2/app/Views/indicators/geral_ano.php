<div class="container mt-5">
    <h2 class="mb-4">📈 Evolução da Produção por Tipo</h2>
    <canvas id="graficoLinhaAno"></canvas>
</div>

<script>
    (function() {

        const producao = <?= json_encode($producaoAno) ?>;
        const anos = Object.keys(producao.cientifica.artigos);

        const datasets = [];

        // 🎨 Paletas
        const azulBase = 54; // Científica
        const rosaBase = 255; // Artística

        let i = 0;

        // ==========================
        // Científica (tons de azul)
        // ==========================
        for (let tipo in producao.cientifica) {

            datasets.push({
                label: tipo.toUpperCase(),
                data: anos.map(ano => parseInt(producao.cientifica[tipo][ano] ?? 0)),
                borderColor: `rgba(${azulBase}, ${162 + i*10}, ${235 - i*10}, 1)`,
                backgroundColor: `rgba(${azulBase}, ${162 + i*10}, ${235 - i*10}, 0.1)`,
                tension: 0.3,
                fill: false
            });

            i++;
        }

        i = 0;

        // ==========================
        // Artística (tons de rosa)
        // ==========================
        for (let tipo in producao.artistica) {

            datasets.push({
                label: tipo,
                data: anos.map(ano => parseInt(producao.artistica[tipo][ano] ?? 0)),
                borderColor: `rgba(${rosaBase}, ${99 + i*15}, ${132 + i*10}, 1)`,
                backgroundColor: `rgba(${rosaBase}, ${99 + i*15}, ${132 + i*10}, 0.1)`,
                tension: 0.3,
                fill: false,
                borderDash: [5, 5] // 🔥 artística tracejada para diferenciar
            });

            i++;
        }

        const canvas = document.getElementById('graficoLinhaAno');

        if (Chart.getChart(canvas)) {
            Chart.getChart(canvas).destroy();
        }

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: anos,
                datasets: datasets
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    })();
</script>