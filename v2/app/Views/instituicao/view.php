<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-building"></i> Instituição
            </h4>
        </div>

        <?php
        pre($instituicao);
        ?>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Código da Instituição</h6>
                    <p class="fw-bold"><?= $instituicao['codigo_instituicao_empresa']; ?></p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Nome da Instituição</h6>
                    <p class="fw-bold"><?= $instituicao['nome_instituicao_empresa']; ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <h6 class="text-muted">País</h6>
                    <p class="fw-bold"><?= $instituicao['pais']; ?></p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted">UF</h6>
                    <p class="fw-bold"><?= $instituicao['uf']; ?></p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted">Cidade</h6>
                    <p class="fw-bold"><?= $instituicao['cidade']; ?></p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Criado em</h6>
                    <p><?= date("d/m/Y H:i", strtotime($instituicao['created_at'])); ?></p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Atualizado em</h6>
                    <p><?= date("d/m/Y H:i", strtotime($instituicao['updated_at'])); ?></p>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <i class="bi bi-people"></i>
                <strong>Pesquisadores vinculados:</strong>
                <?= $instituicao['pesquisadores_total']; ?>
            </div>
        </div>

        <div>
            <?php require('researcher_list.php'); ?>
        </div>

        <div class="card-footer text-end">
            <a href="<?= base_url('instituicoes') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
    </div>
</div>