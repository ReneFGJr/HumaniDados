<?php
// Agrupa os dados por natureza
$porNatureza = [];

foreach ($dados as $row) {
    $natureza = $row['natureza'] ?: 'SEM_NATUREZA';
    $porNatureza[$natureza][] = $row;
}
?>

<div class="container mt-4">

    <h3 class="mb-4">Indicadores de Atividade por Natureza - <?= $pag;?></h3>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="naturezaTabs" role="tablist">
        <?php $i = 0; foreach ($porNatureza as $natureza => $itens): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link <?= $i === 0 ? 'active' : '' ?>"
                    id="tab-<?= esc($natureza) ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#pane-<?= esc($natureza) ?>"
                    type="button"
                    role="tab">
                    <?= esc(str_replace('_', ' ', $natureza)) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content border border-top-0 p-4 bg-light">

        <?php $i = 0; foreach ($porNatureza as $natureza => $itens): ?>
            <div
                class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>"
                id="pane-<?= esc($natureza) ?>"
                role="tabpanel">

                <div class="row">

                    <?php foreach ($itens as $item): ?>
                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-muted">
                                        <?= esc($item['atividade'] ?: 'Não informado') ?>
                                    </h6>
                                    <div class="display-6 fw-bold">
                                        <?= esc($item['total']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- Total da Natureza -->
                <div class="mt-3 text-end">
                    <strong>Total:</strong>
                    <?= array_sum(array_column($itens, 'total')) ?>
                </div>

            </div>
        <?php $i++; endforeach; ?>

    </div>
</div>
