<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Usuários Cadastrados</h2>
    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">Novo Usuário</a>
</div>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Função</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= esc($u['name']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><span class="badge bg-<?= $u['role'] == 'admin' ? 'danger' : 'secondary' ?>">
                        <?= ucfirst($u['role']) ?></span></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>