<div class="text-center py-5">
    <h1 class="display-4 text-secondary fw-bold">Bem-vindo ao Projeto HumaniDados</h1>
    <p class="lead">Uma iniciativa para valorizar e analisar a produção científica nas Humanidades.</p>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <img src="<?= base_url('assets/images/logo_humanidados.png') ?>" alt="Banner HumaniDados" class="img-fluid mb-4">
        </div>
        <div class="col-md-3">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Pesquisadores</h5>
                    <p class="card-text display-6 fw-bold"><?= number_format($data['resume']['pesquisadores']['total'], 0, ',', '.') ?? 0 ?></p>
                    cadastrados
                </div>
            </div>
        </div>
        <!-- Instituições Card -->
        <div class="col-md-3">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Instituições</h5>
                    <p class="card-text display-6 fw-bold"><?= number_format($data['resume']['instituicao_total'], 0, ',', '.') ?? 0 ?></p>
                    vinculadas
                </div>
            </div>
        </div>
        <!-- Universidades Card -->
        <div class="col-md-3">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Universidades</h5>
                    <p class="card-text display-6 fw-bold"><?= number_format($data['resume']['universidade_total'], 0, ',', '.') ?? 0 ?></p>
                    vinculadas
                </div>
            </div>
        </div>
        <!-- Prod. Artistica -->
        <div class="col-md-3">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Produção Artística</h5>
                    <p class="card-text display-6 fw-bold"><?= number_format($data['resume']['producao_artistica'],0,',','.') ?? 0 ?></p>
                    trabalhos
                </div>
            </div>
        </div>
    </div>
</div>