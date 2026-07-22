<?php
$total_registros = (int)($total_registros ?? 0);
$total_instituicoes = (int)($total_instituicoes ?? 0);
$top_instituicoes = $top_instituicoes ?? [];
$top_nao_universidades = $top_nao_universidades ?? [];
$por_uf = $por_uf ?? [];
$por_pais = $por_pais ?? [];
$lattes_update_buckets = $lattes_update_buckets ?? [];
$lattes_update_reference_date = $lattes_update_reference_date ?? null;
?>

<div class="container py-4">
    <h2 class="mb-4">Indicadores por Instituicao</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-1">Registros de Vinculos</h6>
                    <h3 class="mb-0"><?= number_format((int) $total_registros, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-1">Instituicoes Unicas</h6>
                    <h3 class="mb-0"><?= number_format((int) $total_instituicoes, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="mb-1">UFs Mapeadas</h6>
                    <h3 class="mb-0"><?= number_format(count($por_uf), 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Top Instituicoes por Vinculo (Universidades e Equivalentes)</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Instituicao</th>
                                <th>Codigo</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($top_instituicoes)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sem dados para exibir.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($top_instituicoes as $idx => $item): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td><?= esc($item['nome']) ?></td>
                                        <td><?= esc($item['codigo'] !== '' ? $item['codigo'] : 'N/I') ?></td>
                                        <td class="text-end"><?= number_format((int) $item['total'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>Distribuicao por UF</strong>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (empty($por_uf)): ?>
                        <li class="list-group-item text-muted">Sem dados.</li>
                    <?php else: ?>
                        <?php foreach ($por_uf as $uf => $total): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= esc($uf) ?></span>
                                <span class="badge text-bg-primary"><?= number_format((int) $total, 0, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Distribuicao por Pais</strong>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (empty($por_pais)): ?>
                        <li class="list-group-item text-muted">Sem dados.</li>
                    <?php else: ?>
                        <?php foreach ($por_pais as $pais => $total): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= esc($pais) ?></span>
                                <span class="badge text-bg-secondary"><?= number_format((int) $total, 0, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Top Instituicoes por Vinculo (Nao Universidades)</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Instituicao</th>
                                <th>Codigo</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($top_nao_universidades)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sem dados para exibir.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($top_nao_universidades as $idx => $item): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td><?= esc($item['nome']) ?></td>
                                        <td><?= esc($item['codigo'] !== '' ? $item['codigo'] : 'N/I') ?></td>
                                        <td class="text-end"><?= number_format((int) $item['total'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Tempo de Atualizacao do Lattes</strong>
                    <?php if ($lattes_update_reference_date): ?>
                        <small class="text-muted d-block mt-1">Baseado no registro mais recente: <?= esc($lattes_update_reference_date) ?></small>
                    <?php else: ?>
                        <small class="text-muted d-block mt-1">Nao foi possivel identificar uma data de referencia valida.</small>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <canvas id="graficoTempoAtualizacaoLattes" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const buckets = <?= json_encode($lattes_update_buckets, JSON_UNESCAPED_UNICODE) ?>;
        const labels = Object.keys(buckets || {});
        const valores = Object.values(buckets || {});

        const canvas = document.getElementById('graficoTempoAtualizacaoLattes');
        if (!canvas || labels.length === 0) {
            return;
        }

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pesquisadores',
                    data: valores,
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.75)',
                        'rgba(13, 110, 253, 0.75)',
                        'rgba(13, 202, 240, 0.75)',
                        'rgba(111, 66, 193, 0.75)',
                        'rgba(255, 193, 7, 0.75)',
                        'rgba(253, 126, 20, 0.75)',
                        'rgba(220, 53, 69, 0.75)',
                        'rgba(108, 117, 125, 0.75)',
                        'rgba(52, 58, 64, 0.75)'
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
                    }
                }
            }
        });
    })();
</script>
