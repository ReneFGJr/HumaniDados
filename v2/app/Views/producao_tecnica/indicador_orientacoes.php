<?php
$totalGeral = (int)($dados['total_geral'] ?? 0);
$porTipo = $dados['por_tipo'] ?? [];
$porStatus = $dados['por_status'] ?? [];
?>

<div class="container py-4">
    <h3 class="mb-4">Indicadores de Orientacoes</h3>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h6>Total Geral</h6>
                    <h2><?= number_format($totalGeral, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="mb-3">Totais por Tipo</h5>
            <table class="table table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porTipo as $item): ?>
                        <tr>
                            <td><?= esc($item['tipo']) ?></td>
                            <td><?= number_format((int)$item['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h5 class="mb-3">Totais por Status</h5>
            <table class="table table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porStatus as $item): ?>
                        <tr>
                            <td><?= esc($item['status']) ?></td>
                            <td><?= number_format((int)$item['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
