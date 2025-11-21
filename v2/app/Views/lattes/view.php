<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">

            <h2 class="fw-bold mb-4 text-primary">
                <i class="bi bi-person-vcard me-2"></i>
                Perfil do Pesquisador
            </h2>

            <!-- NAV TABS -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="geral-tab" data-bs-toggle="tab"
                        data-bs-target="#geral" type="button" role="tab">
                        Dados Gerais
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="instituicao-tab" data-bs-toggle="tab"
                        data-bs-target="#instituicao" type="button" role="tab">
                        Instituição
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="formacao-tab" data-bs-toggle="tab"
                        data-bs-target="#formacao" type="button" role="tab">
                        Formação Acadêmica
                    </button>
                </li>
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content pt-4" id="myTabContent">

                <!-- =======================
                     ABA 1 - Dados Gerais
                ======================== -->
                <div class="tab-pane fade show active" id="geral" role="tabpanel">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h5 class="text-secondary">Nome Completo</h5>
                                <p class="fw-bold fs-5"><?= $pesquisador['nome_completo'] ?></p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="text-secondary">IDLattes</h6>
                                <p class="fw-semibold"><?= $pesquisador['idlattes'] ?></p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="text-secondary">ORCID</h6>
                                <p class="fw-semibold"><?= $pesquisador['orcID'] ?: '—' ?></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="text-secondary">Nacionalidade</h6>
                                <p class="fw-semibold"><?= $pesquisador['nacionalidade'] ?></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="text-secondary">Nascimento</h6>
                                <p class="fw-semibold">
                                    <?= $pesquisador['nascimento_cidade'] ?> — <?= $pesquisador['nascimento_pais'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 bg-light border rounded-3 h-100">
                                <h6 class="text-secondary">Última Atualização</h6>
                                <p class="fw-semibold">
                                    <?php require("file_existe.php"); ?>
                                    <a href="<?= base_url('/lattes/extractor/'.$pesquisador['idlattes']) ?>"><i class="bi bi-recycle me-2"></i></a>
                                    <?= $pesquisador['data_atualizacao'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =======================
                     ABA 2 - Instituição
                ======================== -->
                <div class="tab-pane fade" id="instituicao" role="tabpanel">

                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-light">

                        <h4 class="text-primary fw-bold mb-3">
                            <i class="bi bi-building me-2"></i>
                            Instituição de Vínculo
                        </h4>
                        <?php if (isset($pesquisador['instituição'])) { ?>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="text-secondary">Nome</h6>
                                    <p class="fw-semibold"><?= $pesquisador['instituição']['nome_instituicao_empresa'] ?></p>
                                </div>

                                <div class="col-md-3">
                                    <h6 class="text-secondary">Cidade</h6>
                                    <p><?= $pesquisador['instituição']['cidade'] ?></p>
                                </div>

                                <div class="col-md-3">
                                    <h6 class="text-secondary">UF</h6>
                                    <p><?= $pesquisador['instituição']['uf'] ?></p>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="text-secondary">País</h6>
                                    <p><?= $pesquisador['instituição']['pais'] ?></p>
                                </div>

                                <div class="col-md-8">
                                    <h6 class="text-secondary">Código Institucional</h6>
                                    <p><?= $pesquisador['instituição']['codigo_instituicao_empresa'] ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- =======================
                     ABA 3 - Formação
                ======================== -->
                <div class="tab-pane fade" id="formacao" role="tabpanel">

                    <?php foreach ($pesquisador['formacao'] as $f): ?>
                        <div class="card mb-3 border-0 shadow-sm rounded-4 p-3">

                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="fw-bold text-primary"><?= $f['tipo'] ?> - <?= $f['nome_curso'] ?></h5>
                                    <p class="text-secondary mb-1"><?= $f['nome_curso'] ?></p>
                                    <p class="fw-semibold"><?= $f['nome_instituicao'] ?></p>
                                </div>

                                <div class="col-md-4 text-end">
                                    <span class="badge bg-primary fs-6"><?= $f['ano_inicio'] ?> - <?= $f['ano_conclusao'] ?></span>
                                </div>

                            </div>

                            <hr>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <small class="text-muted">Status:</small>
                                    <p><?= $f['status_curso'] ?></p>
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">Bolsa:</small>
                                    <p><?= $f['flag_bolsa'] ?></p>
                                </div>

                                <div class="col-md-4">
                                    <small class="text-muted">Orientador:</small>
                                    <p><?= $f['orientador'] ?: '—' ?></p>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>

            </div>
        </div>
    </div>
</div>