<?php
$totalGeral = 0;
foreach ($artigos as $item) {
    $totalGeral += $item['total'];
}
?>
<div class="container py-4">

    <h3 class="mb-4">📊 Indicadores de Artigos / Idiomas</h3>

    <!-- CARDS POR idioma -->
    <div class="row">
        <?php foreach ($artigos as $item): ?>
            <?php
                $idioma = $item['idioma'] ?: 'SEM_INFORMAÇÃO';

                $badge = match ($idioma) {
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
                            <?= str_replace('_', ' ', $idioma) ?>
                        </span>
                        <h3><?= number_format($item['total'], 0, ',', '.') ?></h3>
                        <small>Artigos</small>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

</div>

