<?php
$totalGeral = 0;
foreach ($artigos as $item) {
    $totalGeral += $item['total'];
}
?>
<div class="container py-4">

    <h3 class="mb-4">📊 Indicadores de Artigos</h3>

    <!-- TOTAL GERAL -->
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

    <!-- CARDS POR NATUREZA -->
    <div class="row">
        <?php foreach ($artigos as $item): ?>
            <?php
                $natureza = $item['natureza'] ?: 'SEM_INFORMAÇÃO';

                $badge = match ($natureza) {
                    'COMPLETO'       => 'success',
                    'RESUMO'         => 'warning',
                    'NAO_INFORMADO'  => 'secondary',
                    default          => 'dark'
                };
            ?>

            <div class="col-md-3 mb-3">
                <div class="card bg-light text-dark shadow h-100">
                    <div class="card-body text-center">
                        <span class="badge bg-<?= $badge ?> mb-2">
                            <?= str_replace('_', ' ', $natureza) ?>
                        </span>
                        <h3><?= number_format($item['total'], 0, ',', '.') ?></h3>
                        <small>Artigos</small>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

</div>

