<!-- =======================
     ABA 2 - Instituição
======================== -->
<div class="tab-pane fade" id="instituicao" role="tabpanel">

    <div class="card border-0 shadow-sm rounded-4 p-3 bg-dark text-light">

        <h4 class="text-info fw-bold mb-3">
            <i class="bi bi-building me-2"></i>
            Instituição de Vínculo
        </h4>

        <?php if (isset($pesquisador['instituição'])) { ?>
            <div class="row g-3">

                <div class="col-md-6">
                    <h6 class="text-white-50">Nome</h6>
                    <p class="fw-semibold text-light">
                        <?= $pesquisador['instituição']['nome_instituicao_empresa'] ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white-50">Cidade</h6>
                    <p class="text-light">
                        <?= $pesquisador['instituição']['cidade'] ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white-50">UF</h6>
                    <p class="text-light">
                        <?= $pesquisador['instituição']['uf'] ?>
                    </p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-white-50">País</h6>
                    <p class="text-light">
                        <?= $pesquisador['instituição']['pais'] ?>
                    </p>
                </div>

                <div class="col-md-8">
                    <h6 class="text-white-50">Código Institucional</h6>
                    <p class="text-light">
                        <?= $pesquisador['instituição']['codigo_instituicao_empresa'] ?>
                    </p>
                </div>

            </div>
        <?php } ?>

    </div>
</div>