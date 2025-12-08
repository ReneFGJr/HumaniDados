<!-- =======================
     ABA 3 - Formação
======================== -->
<div class="tab-pane fade" id="formacao" role="tabpanel">

    <?php foreach ($pesquisador['formacao'] as $f): ?>
        <div class="card mb-4 bg-hd text-hd border border-secondary rounded-4 shadow-sm p-4">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start flex-wrap">

                <div class="mb-3">
                    <h5 class="fw-bold text-hd-info mb-1">
                        <i class="bi bi-mortarboard-fill me-2"></i>
                        <?= $f['tipo'] ?> — <?= $f['nome_curso'] ?>
                    </h5>

                    <p class="text-hd-tx mb-1">
                        <i class="bi bi-bank me-1"></i>
                        <?= $f['nome_instituicao'] ?>
                    </p>

                    <p class="text-hd-tx mb-0">
                        <i class="bi bi-geo-alt me-1"></i>
                        <?= $f['cidade'] ?? '' ?> <?= isset($f['uf']) ? '(' . $f['uf'] . ')' : '' ?>
                    </p>
                </div>

                <div class="text-end">
                    <span class="badge bg-info text-dark fs-6 px-3 py-2">
                        <i class="bi bi-calendar-event me-1"></i>
                        <?= $f['ano_inicio'] ?> — <?= $f['ano_conclusao'] ?>
                    </span>
                </div>

            </div>

            <hr class="border-secondary">

            <!-- Details -->
            <div class="row g-4">

                <div class="col-md-4">
                    <p class="text-hd-tx mb-1"><small>Status</small></p>
                    <p class="fw-semibold"><?= $f['status_curso'] ?></p>
                </div>

                <div class="col-md-4">
                    <p class="text-hd-tx mb-1"><small>Bolsa</small></p>
                    <p class="fw-semibold"><?= $f['flag_bolsa'] ?></p>
                </div>

                <div class="col-md-4">
                    <p class="text-hd-tx mb-1"><small>Orientador</small></p>
                    <p class="fw-semibold"><?= $f['orientador'] ?: '—' ?></p>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

</div>