<!-- =======================
     ABA 2 - Instituição
======================== -->
<div class="tab-pane fade" id="instituicao" role="tabpanel">

    <div class="card border-0 shadow-sm rounded-4 p-3 bg-light text-dark">

        <h4 class="text-hd-info fw-bold mb-3">
            <i class="bi bi-building me-2"></i>
            Instituição de Vínculo
        </h4>

        <?php if (isset($pesquisador['instituição'])) { ?>
            <div class="row g-3">

                <div class="col-md-6">
                    <h6 class="text-hd-tx">Nome</h6>
                    <p class="fw-semibold text-hd">
                        <?= $pesquisador['instituição']['nome_instituicao_empresa'] ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <h6 class="text-hd-tx">Cidade</h6>
                    <p class="text-hd">
                        <?= $pesquisador['instituição']['cidade'] ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <h6 class="text-hd-tx">UF</h6>
                    <p class="text-hd">
                        <?= $pesquisador['instituição']['uf'] ?>
                    </p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-hd-tx">País</h6>
                    <p class="text-hd">
                        <?= $pesquisador['instituição']['pais'] ?>
                    </p>
                </div>

                <div class="col-md-8">
                    <h6 class="text-hd-tx">Código Institucional</h6>
                    <p class="text-hd">
                        <?= $pesquisador['instituição']['codigo_instituicao_empresa'] ?>
                    </p>
                </div>

            </div>
        <?php } ?>

    </div>
</div>