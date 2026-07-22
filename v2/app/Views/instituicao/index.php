<?php $busca = $busca ?? ''; ?>

    <div class="container py-5">

        <h2 class="mb-4">
            <i class="bi bi-bank"></i> Instituições Registradas
        </h2>

        <form method="get" class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>

                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Buscar por instituição, país, UF ou cidade..."
                    value="<?= esc($busca) ?>"
                >

                <button class="btn btn-primary" type="submit">
                    Buscar
                </button>

                <?php if ($busca !== ''): ?>
                    <a href="<?= base_url('instituicoes') ?>" class="btn btn-outline-secondary">
                        Limpar
                    </a>
                <?php endif; ?>
            </div>
        </form>

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
                    <?php if (empty($instituicoes)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Nenhuma instituição encontrada.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($instituicoes as $inst): ?>
                        <tr>
                            <td><?= esc($inst['id']) ?></td>
                            <td><?= $inst['codigo_instituicao_empresa'] ? esc($inst['codigo_instituicao_empresa']) : '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['nome_instituicao_empresa'] ? esc($inst['nome_instituicao_empresa']) : '<span class="text-muted">Sem nome</span>' ?></td>
                            <td><?= $inst['pais'] ? esc($inst['pais']) : '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['uf'] ? esc($inst['uf']) : '<span class="text-muted">—</span>' ?></td>
                            <td><?= $inst['cidade'] ? esc($inst['cidade']) : '<span class="text-muted">—</span>' ?></td>
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