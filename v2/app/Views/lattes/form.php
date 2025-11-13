<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">
        <?= isset($pesquisador) ? 'Editar Pesquisador' : 'Novo Pesquisador' ?>
    </h3>

    <form method="post" action="<?= base_url('lattes/store') ?>">
        <?php if (isset($pesquisador)): ?>
            <input type="hidden" name="id" value="<?= $pesquisador['id'] ?>">
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-md-4">
                <label>ID Lattes</label>
                <input type="text" name="idlattes" class="form-control" required
                    value="<?= $pesquisador['idlattes'] ?? '' ?>">
            </div>
            <div class="col-md-8">
                <label>Nome completo</label>
                <input type="text" name="nome_completo" class="form-control" required
                    value="<?= $pesquisador['nome_completo'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label>Nacionalidade</label>
                <input type="text" name="nacionalidade" class="form-control"
                    value="<?= $pesquisador['nacionalidade'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Ano Graduação</label>
                <input type="number" name="ano_graduacao" class="form-control"
                    value="<?= $pesquisador['ano_graduacao'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Ano Mestrado</label>
                <input type="number" name="ano_mestrado" class="form-control"
                    value="<?= $pesquisador['ano_mestrado'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Ano Doutorado</label>
                <input type="number" name="ano_doutorado" class="form-control"
                    value="<?= $pesquisador['ano_doutorado'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label>Ano Pós-Doc</label>
                <input type="number" name="ano_posdoutorado" class="form-control"
                    value="<?= $pesquisador['ano_posdoutorado'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label>Data Atualização Lattes</label>
                <input type="date" name="data_atualizacao" class="form-control"
                    value="<?= $pesquisador['data_atualizacao'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label>Situação da Coleta</label>
                <select name="situacao_coleta" class="form-select">
                    <?php
                    $situacoes = ['pendente', 'em_coleta', 'coletado', 'erro'];
                    $atual = $pesquisador['situacao_coleta'] ?? 'pendente';
                    foreach ($situacoes as $s):
                    ?>
                        <option value="<?= $s ?>" <?= $s == $atual ? 'selected' : '' ?>>
                            <?= ucfirst($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Salvar
            </button>
            <a href="<?= base_url('lattes') ?>" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>