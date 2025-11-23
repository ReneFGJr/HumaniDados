<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">
                <i class="bi bi-people"></i> Pesquisadores Vinculados
            </h4>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>ID Lattes</th>
                            <th>ORCID</th>
                            <th>Doutorado</th>
                            <th>Atualização</th>
                            <th>Cidade / País</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($instituicao['pesquisadores'] as $p): ?>
                            <tr>
                                <td>
                                    <strong><?= $p['nome_completo']; ?></strong>
                                </td>

                                <td>
                                    <span class="text-primary"><?= $p['idlattes']; ?></span>
                                </td>

                                <td>
                                    <?php if ($p['orcID']): ?>
                                        <a href="<?= $p['orcID']; ?>" target="_blank" class="badge bg-success">
                                            <i class="bi bi-link-45deg"></i> ORCID
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= $p['ano_doutorado'] ?: '<span class="text-muted">—</span>'; ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y", strtotime($p['data_atualizacao'])); ?>
                                </td>

                                <td>
                                    <?= $p['nascimento_cidade']; ?> / <?= $p['nascimento_pais']; ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('lattes/view/' . $p['id']); ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-person-badge"></i> Ver Perfil
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>