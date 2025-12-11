<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="tab-pane fade" id="artistic" role="tabpanel">

    <h3 class="text-hd-info mb-4">
        <i class="bi bi-palette-fill"></i> Painel de Produção Artística – Artes Visuais
    </h3>

    <div class="row g-3">

        <!-- ====== Cards ====== -->
        <?php foreach ($producao_artistica as $d): ?>
            <div class="col-md-3">
                <div class="card card-dark p-3 rounded-4 shadow-sm h-100">
                    <h6 class="text-hd-tx"><?= $d['natureza'] ?></h6>
                    <h3 class="text-hd-info fw-bold"><?= $d['total'] ?></h3>
                    <p class="text-hd-tx small"><?= $d['tipo'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <hr class="border-secondary my-4">

    <!-- ====== Gráficos ====== -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-dark p-3 rounded-4">
                <h5 class="text-hd-info">Distribuição por Natureza (Barras)</h5>
                <canvas id="graficoBarras"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-dark p-3 rounded-4">
                <h5 class="text-hd-info">Distribuição Geral (Pizza)</h5>
                <canvas id="graficoPizza"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?= $gr_sankey; ?>
        </div>
    </div>

    <!-- ====== Preparação dos producao_artistica ====== -->
    <script>
        const labels = <?= json_encode(array_column($producao_artistica, 'natureza')) ?>;
        const valores = <?= json_encode(array_column($producao_artistica, 'total')) ?>;

        // ===== Gráfico de Barras =====
        new Chart(document.getElementById('graficoBarras'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total',
                    data: valores,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                }
            }
        });

        // ===== Gráfico de Pizza =====
        new Chart(document.getElementById('graficoPizza'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: valores
                }]
            }
        });
    </script>
</div>