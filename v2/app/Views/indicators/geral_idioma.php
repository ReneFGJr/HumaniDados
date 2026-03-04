<div class="container mt-5">
    <div class="row">

        <div class="col-12">
            <h2 class="mb-4">🌍 Produção Científica por Idioma</h2>
            <div class="grafico-wrapper">
                <canvas id="graficoCientificaIdioma"></canvas>
            </div>
        </div>

        <div class="col-12 mt-5">
            <h2 class="mb-4">🎨 Produção Artística por Idioma</h2>
            <div class="grafico-wrapper">
                <canvas id="graficoArtisticaIdioma"></canvas>
            </div>
        </div>

    </div>
</div>

<style>
    .grafico-wrapper {
        position: relative;
        height: 500px;
        /* 🔥 Altura fixa */
        max-height: 500px;
    }
</style>

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
            responsive: true,
            maintainAspectRatio: false, // 🔥 necessário para respeitar os 500px
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
            responsive: true,
            maintainAspectRatio: false, // 🔥 necessário
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