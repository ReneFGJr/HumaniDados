<div class="container mt-5">
    <div class="row">
        <div class="col-6">
            <h2 class="mb-4">🌍 Produção Científica por Idioma</h2>
            <canvas id="graficoCientificaIdioma" height="120"></canvas>

        </div>
        <div class="col-6">

            <h2 class="mb-4">🎨 Produção Artística por Idioma</h2>
            <canvas id="graficoArtisticaIdioma" height="120"></canvas>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const producaoIdioma = <?= json_encode($producaoIdioma) ?>;

    // Paleta de cores
    const cores = [
        '#6a5acd',
        '#9370db',
        '#ba55d3',
        '#dda0dd',
        '#8a2be2',
        '#9932cc',
        '#7b68ee',
        '#c71585'
    ];

    // ======================
    // Científica
    // ======================

    const labelsCientifica = Object.keys(producaoIdioma.cientifica);
    const dadosCientifica = Object.values(producaoIdioma.cientifica);

    new Chart(document.getElementById('graficoCientificaIdioma'), {
        type: 'pie',
        data: {
            labels: labelsCientifica,
            datasets: [{
                data: dadosCientifica,
                backgroundColor: cores
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Produção Científica por Idioma'
                }
            }
        }
    });


    // ======================
    // Artística
    // ======================

    const labelsArtistica = Object.keys(producaoIdioma.artistica);
    const dadosArtistica = Object.values(producaoIdioma.artistica);

    new Chart(document.getElementById('graficoArtisticaIdioma'), {
        type: 'pie',
        data: {
            labels: labelsArtistica,
            datasets: [{
                data: dadosArtistica,
                backgroundColor: cores
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Produção Artística por Idioma'
                }
            }
        }
    });
</script>