    <div class="container py-5">

        <h2 class="mb-4">
            <i class="bi bi-bank"></i> Instituições Registradas
        </h2>

        <div class="table-responsive shadow rounded-3">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Instituição</th>
                        <th>País</th>
                        <th>UF</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($instituicoes as $inst): ?>
                        <tr>
                            <td><?= $inst['id'] ?></td>
                            <td><?= $inst['codigo_instituicao_empresa'] ?: '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['nome_instituicao_empresa'] ?: '<span class="text-muted">Sem nome</span>' ?></td>
                            <td><?= $inst['pais'] ?: '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['uf'] ?: '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['cidade'] ?: '<span class="text-muted">—</span>' ?></td>
                            <td>
                                <a href="<?= base_url('instituicoes/view/' . $inst['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>