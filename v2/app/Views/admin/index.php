<?php helper('form'); ?>
<h1 class="mb-3">Administração</h1>
<p>Importe os currículos disponíveis em <code>database/xml</code> e exporte os indicadores consolidados.</p>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-primary" href="<?= site_url('admin/export/all') ?>">Exportação indicadores</a>
    <form method="post" action="<?= site_url('admin/inport/alttes') ?>" id="importar-lattes">
        <?= csrf_field() ?>
        <button class="btn btn-success" type="submit">Inportar do Lattes</button>
    </form>
</div>
<p id="importacao-progresso" class="alert alert-info" role="status" hidden>Importando os XMLs. Aguarde a conclusão para ver os resultados.</p>

<?php if ($erro): ?>
    <div class="alert alert-danger" role="alert"><?= esc($erro) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if ($resultado !== null): ?>
    <section class="mb-4" aria-labelledby="resultado-importacao">
        <h2 class="h4" id="resultado-importacao">Resultado da importação</h2>
        <div class="alert <?= $resultado['erros'] ? 'alert-warning' : 'alert-success' ?>">
            Arquivos encontrados: <strong><?= (int) $resultado['total'] ?></strong> ·
            Inseridos: <strong><?= (int) $resultado['inseridos'] ?></strong> ·
            Atualizados: <strong><?= (int) $resultado['atualizados'] ?></strong> ·
            Erros: <strong><?= (int) $resultado['erros'] ?></strong>
        </div>
        <?php if ($resultado['total'] === 0): ?>
            <p>Nenhum arquivo XML encontrado em database/xml.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead><tr><th>Arquivo</th><th>ID Lattes</th><th>Nome</th><th>Resultado</th><th>Mensagem</th></tr></thead>
                    <tbody>
                    <?php foreach ($resultado['arquivos'] as $arquivo): ?>
                        <tr>
                            <td><?= esc($arquivo['arquivo']) ?></td>
                            <td><?= esc($arquivo['id_lattes']) ?></td>
                            <td><?= esc($arquivo['nome']) ?></td>
                            <td><span class="badge <?= $arquivo['status'] === 'erro' ? 'bg-danger' : 'bg-success' ?>"><?= esc($arquivo['status']) ?></span></td>
                            <td><?= esc($arquivo['mensagem']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<h2 class="h4">Indicadores salvos <span class="badge bg-secondary"><?= (int) $total ?> currículos</span></h2>
<p class="text-muted">A importação atualiza o registro pelo ID Lattes. As contagens são recalculadas a partir do XML, sem acumular valores de importações anteriores.</p>
<?php if (!$curriculos): ?>
    <p>Nenhum currículo disponível para exibição.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th>ID Lattes</th><th>Nome</th><th>Atualização</th><th>Instituição</th><th>Artigos em periódicos</th><th>Todos os indicadores</th></tr></thead>
            <tbody>
            <?php foreach ($curriculos as $curriculo): ?>
                <tr>
                    <td class="text-nowrap"><?= esc($curriculo['id_lattes']) ?></td>
                    <td><?= esc($curriculo['nome']) ?></td>
                    <td class="text-nowrap"><?= esc($curriculo['atualizacao_cv'] ?? '') ?></td>
                    <td><?= esc($curriculo['end_prof_instituicao'] ?? '') ?></td>
                    <td><?= (int) $curriculo['artigos_periodicos'] ?></td>
                    <td>
                        <details>
                            <summary>Ver indicadores</summary>
                            <dl class="mt-2">
                                <?php foreach ($campos as $campo): ?>
                                    <dt><?= esc(ucwords(str_replace('_', ' ', $campo))) ?></dt>
                                    <dd><?= esc((string) ($curriculo[$campo] ?? '—')) ?></dd>
                                <?php endforeach; ?>
                            </dl>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $pager->links() ?>
<?php endif; ?>

<script>
document.getElementById('importar-lattes').addEventListener('submit', function () {
    this.querySelector('button').disabled = true;
    document.getElementById('importacao-progresso').hidden = false;
});
</script>
