<h2>Novo Usuário</h2>
<form method="post" action="<?= base_url('users/store') ?>" class="mt-4">

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Função</label>
        <select name="role" class="form-select">
            <option value="user">Usuário</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <button class="btn btn-success">Salvar</button>
    <a href="<?= base_url('users') ?>" class="btn btn-secondary">Voltar</a>
</form>