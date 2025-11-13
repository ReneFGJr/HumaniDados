<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">Pesquisadores - Lattes</h3>
        <?php if (session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('lattes/create') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Novo
            </a>
            <a href="<?= base_url('lattes/import') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Importar
            </a>

            <a href="<?= base_url('lattes/verify-files') ?>" class="btn btn-success">
                <i class="bi bi-recycle"></i>
            </a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Lattes</th>
                <th>Nome</th>
                <th>Nacionalidade</th>
                <th>Doutorado</th>
                <th>Últ. Atualização</th>
                <th>Situação</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesquisadores as $p): ?>
                <tr>
                    <td><?= esc($p['idlattes']) ?></td>
                    <td><?= esc($p['nome_completo']) ?></td>
                    <td><?= esc($p['nacionalidade']) ?></td>
                    <td><?= esc($p['ano_doutorado']) ?></td>
                    <td><?= esc($p['data_atualizacao']) ?></td>
                    <td>
                        <?php
                        switch ($p['situacao_coleta']) {
                            case 'coletado':
                                $badgeClass = 'bg-success';
                                break;
                            case 'pendente':
                                $badgeClass = 'bg-secondary';
                                break;
                            case 'Erro':
                                $badgeClass = 'bg-danger';
                                break;
                            default:
                                $badgeClass = 'bg-info';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= esc($p['situacao_coleta']) ?></span>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('lattes/view/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= base_url('lattes/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('lattes/delete/' . $p['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir este pesquisador?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>