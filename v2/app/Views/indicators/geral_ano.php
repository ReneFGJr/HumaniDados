<div class="container mt-5">
    <h2 class="mb-4">📊 Produção por Tipo e Ano</h2>
    <canvas id="graficoBarraAno"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (function() {

        const producao = <?= json_encode($producaoAno) ?>;
        const anos = Object.keys(producao.cientifica.artigos);
        const datasets = [];

        // ==========================
        // Científica (Azul)
        // ==========================
        const azulTons = [
            'rgba(54,0,235,0.8)',
            'rgba(30,100,255,0.8)',
            'rgba(0,200,255,0.8)',
            'rgba(0,0,100,0.8)',
            'rgba(0,0,200,0.8)'
        ];

        let i = 0;

        for (let tipo in producao.cientifica) {
            datasets.push({
                label: 'Científica - ' + tipo.toUpperCase(),
                data: anos.map(ano => parseInt(producao.cientifica[tipo][ano] ?? 0)),
                backgroundColor: azulTons[i % azulTons.length],
                barThickness: 35
            });
            i++;
        }

        // ==========================
        // Artística (Rosa)
        // ==========================
        const rosaTons = [
            'rgba(255,0,180,0.8)',
            'rgba(255,100,132,0.8)',
            'rgba(255,200,147,0.8)',
            'rgba(219,0,200,0.8)'
        ];

        i = 0;

        for (let tipo in producao.artistica) {
            datasets.push({
                label: 'Artística - ' + tipo,
                data: anos.map(ano => parseInt(producao.artistica[tipo][ano] ?? 0)),
                backgroundColor: rosaTons[i % rosaTons.length],
                barThickness: 35
            });
            i++;
        }

        const canvas = document.getElementById('graficoBarraAno');

        // 🔥 evita erro de canvas já utilizado
        if (Chart.getChart(canvas)) {
            Chart.getChart(canvas).destroy();
        }

        new Chart(canvas, {
            type: 'bar',
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
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    })();
</script>