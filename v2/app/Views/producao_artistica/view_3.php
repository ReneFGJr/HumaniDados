<?php
/**
 * Pré-processamento dos dados
 */
$tipos = [];
foreach ($dados as $row) {
    $tipo = $row['tipo'] ?: 'NÃO INFORMADO';

    // Natureza
    $natureza = $row['natureza'] ?: 'NÃO INFORMADO';
    $tipos[$tipo]['natureza'][$natureza] =
        ($tipos[$tipo]['natureza'][$natureza] ?? 0) + $row['total'];

    // Atividade
    if (!empty($row['atividade'])) {
        $atividade = $row['atividade'];
        $tipos[$tipo]['atividade'][$atividade] =
            ($tipos[$tipo]['atividade'][$atividade] ?? 0) + $row['total'];
    }
}
?>

<div class="container-fluid mt-4">

    <!-- NAV TABS -->
    <ul class="nav nav-tabs" role="tablist">
        <?php $i = 0; foreach ($tipos as $tipo => $info): ?>
            <li class="nav-item">
                <button class="nav-link <?= $i === 0 ? 'active' : '' ?>"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-<?= md5($tipo) ?>"
                        type="button">
                    <?= esc($tipo) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content mt-4">

        <?php $i = 0; foreach ($tipos as $tipo => $info): ?>
            <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>"
                 id="tab-<?= md5($tipo) ?>">

                <!-- ===================== -->
                <!-- NATUREZA -->
                <!-- ===================== -->
                <h5 class="mb-3">Natureza (agrupada)</h5>
                <div class="row g-3 mb-4">
                    <?php foreach ($info['natureza'] as $natureza => $total): ?>
                        <div class="col-md-3">
                            <div class="card text-bg-primary h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><?= esc($natureza) ?></h6>
                                    <p class="card-text fs-4 fw-bold"><?= $total ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ===================== -->
                <!-- ATIVIDADE -->
                <!-- ===================== -->
                <?php if (!empty($info['atividade'])): ?>
                    <h5 class="mb-3">Atividade</h5>
                    <div class="row g-3">
                        <?php foreach ($info['atividade'] as $atividade => $total): ?>
                            <div class="col-md-3">
                                <div class="card text-bg-dark h-100">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= esc($atividade) ?></h6>
                                        <p class="card-text fs-4 fw-bold"><?= $total ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhuma atividade informada.</p>
                <?php endif; ?>

            </div>
        <?php $i++; endforeach; ?>

    </div>
</div>
