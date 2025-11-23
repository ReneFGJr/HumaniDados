<!-- =======================
     ABA 1 - Dados Gerais
======================== -->
<div class="tab-pane fade show active" id="geral" role="tabpanel">

    <div class="row g-3">

        <div class="col-md-6">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h5 class="text-white-50">Nome Completo</h5>
                <p class="fw-bold fs-5"><?= $pesquisador['nome_completo'] ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">IDLattes</h6>
                <p class="fw-semibold"><?= $pesquisador['idlattes'] ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">ORCID</h6>
                <p class="fw-semibold"><?= $pesquisador['orcID'] ?: '—' ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">Nacionalidade</h6>
                <p class="fw-semibold"><?= $pesquisador['nacionalidade'] ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">Nascimento</h6>
                <p class="fw-semibold">
                    <?= $pesquisador['nascimento_cidade'] ?> — <?= $pesquisador['nascimento_pais'] ?>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">Última Atualização</h6>
                <p class="fw-semibold">
                    <?php require("file_existe.php"); ?>
                    <?= $pesquisador['data_atualizacao'] ?>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">Lattes ID</h6>
                <p class="fw-semibold">
                    <a
                        class="link"
                        target="_blank"
                        href="https://lattes.cnpq.br/<?= $pesquisador['idlattes'] ?>">https://lattes.cnpq.br/<?= $pesquisador['idlattes'] ?>
                    </a>
                </p>
            </div>
        </div>
        <? if (isset($pesquisador['instituição']['nome_instituicao_empresa'])) { ?>
        <div class="col-md-8">
            <div class="p-3 bg-dark border border-secondary rounded-3 h-100 text-light">
                <h6 class="text-white-50">Vinculo Institucional</h6>
                <p class="fw-semibold">
                    <?= $pesquisador['instituição']['nome_instituicao_empresa'] ?>
                </p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>