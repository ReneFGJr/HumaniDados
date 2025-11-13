<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">
        Importar Lista de IDs Lattes
    </h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('lattes/import') ?>">
        <div class="mb-3">
            <label for="idlattes_lista" class="form-label fw-semibold">
                Cole abaixo os IDs Lattes (um por linha):
            </label>
            <textarea id="idlattes_lista" name="idlattes_lista" rows="10"
                class="form-control" placeholder="Exemplo:
1234567890123456
9876543210987654
..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-upload"></i> Importar IDs
        </button>
        <a href="<?= base_url('lattes') ?>" class="btn btn-secondary">Voltar</a>
    </form>
</div>