</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<footer class="bg-dark text-light mt-5 pt-4 pb-3">
    <div class="container">

        <div class="row">

            <!-- Logo e Descrição -->
            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">HumaniDados</h5>
                <p class="small text-secondary">
                    Plataforma integrada para análise, organização e visualização de dados
                    em Humanidades.
                </p>
                <img src="<?= base_url('assets/images/logo_ufrgs.png') ?>" alt="UFRGS Logo" style="height: 60px; margin-left: 10px;">
                <img src="<?= base_url('assets/images/logo_ufpe.png') ?>" alt="UFPE Logo" style="height: 60px; margin-left: 10px;">
                <img src="<?= base_url('assets/images/logo_abc.png') ?>" alt="ABC Logo" style="height: 60px; margin-left: 10px;">
            </div>

            <!-- Links úteis -->
            <div class="col-md-3 mb-3">
                <h6 class="fw-bold">Links úteis</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-secondary text-decoration-none">Sobre</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Documentação</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Contato</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Política de Privacidade</a></li>
                </ul>
            </div>

            <!-- Contato -->
            <div class="col-md-3 mb-3">
                <h6 class="fw-bold">Contato</h6>
                <p class="small text-secondary mb-1">
                    <i class="bi bi-envelope"></i> renefgj@gmail.com
                </p>
                <p class="small text-secondary mb-1">
                    <a href="https://github.com/ReneFGJr/HumaniDados" class="text-secondary text-decoration-none" target="_blank"><i class=" bi bi-github"></i> github.com/ReneFGJr/HumaniDados </a>
                </p>
                <p class="small text-secondary">
                    <i class="bi bi-geo-alt"></i> Porto Alegre / Recife • Brasil
                </p>
            </div>

            <!-- Logo e Descrição -->
            <div class="col-md-3 mb-3">
                <h5 class="fw-bold">Agradecimentos</h5>
                <p class="small text-secondary">
                    CNPq (Conselho Nacional de Desenvolvimento Científico e Tecnológico) pelo apoio.
                    <br>
                    <img src="<?= base_url('assets/images/cnpq_logo.png') ?>" alt="CNPq Logo" style="height: 80px; margin-left: 10px;">
                </p>
            </div>
        </div>

        <!-- Linha inferior -->
        <div class="text-center border-top border-secondary pt-3 mt-3">
            <small class="text-secondary">
                © <?= date('Y') ?> HumaniDados — Todos os direitos reservados.
            </small>
        </div>

    </div>
</footer>


</html>