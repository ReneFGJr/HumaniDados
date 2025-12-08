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

                <li class="nav-item" role="production">
                    <button class="nav-link" id="production-tab" data-bs-toggle="tab"
                        data-bs-target="#production" type="button" role="tab">
                        Produção Ciêntífica
                    </button>
                </li>                    

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab"
                        data-bs-target="#dashboard" type="button" role="tab">
                        Dashboard
                    </button>
                </li>            

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="xml-tab" data-bs-toggle="tab"
                        data-bs-target="#xml" type="button" role="tab">
                        Ver XML
                    </button>
                </li>
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content pt-4" id="myTabContent">

                <!-- =======================
                     ABA 1 - Dados Gerais
                ======================== -->
                <?php require("view_myTab.php") ?>

                <!-- =======================
                     ABA 2 - Instituição
                ======================== -->
                <?php require("view_instituicao.php") ?>

                <!-- =======================
                     ABA 3 - Formação
                ======================== -->
                <?php require("view_formacao.php") ?>

                <!-- =======================
                     ABA 6 - Produção Científica
                ======================== -->
                <?php require("view_production.php") ?>                

                <!-- =======================
                     ABA 5 - Dashboard Produção Artística
                ======================== -->
                <?php
                $producao_artistica = $pesquisador['producao_artistica'];
                require("dashboard/prod_artistica.php");
                ?>

                <!-- =======================
                     ABA 9 - Ver XML
                ======================== -->
                <?php require("view_xml.php") ?>
            </div>
        </div>
    </div>
</div>