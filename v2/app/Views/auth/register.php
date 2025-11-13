<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h4 class="text-center mb-4 text-primary fw-bold">Cadastro</h4>

                    <form method="post" action="<?= base_url('register') ?>">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>