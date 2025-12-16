<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-4">

    <h4 class="mb-4">📊 Produção por Ano</h4>

    <div class="card bg-light text-dark shadow">
        <div class="card-body">
            <canvas id="graficoAnos" height="120"></canvas>
        </div>
    </div>

</div>

<script>
const ctx = document.getElementById('graficoAnos');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            <?php foreach ($anos as $a): ?>
                "<?= $a['ano'] ?>",
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Total',
            data: [
                <?php foreach ($anos as $a): ?>
                    <?= $a['total'] ?>,
                <?php endforeach; ?>
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: (ctx) => 'Total: ' + ctx.raw
                }
            }
        }
    }
});
</script>
