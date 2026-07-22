<?php
$titulo = $titulo ?? 'Página';
$slug = $slug ?? 'em-construcao';
?>

<div class="hd-construcao-wrap py-5">
    <div class="hd-aurora hd-aurora-1"></div>
    <div class="hd-aurora hd-aurora-2"></div>

    <div class="container position-relative">
        <div class="hd-card mx-auto">
            <div class="hd-gear-zone" aria-hidden="true">
                <div class="hd-gear hd-gear-lg">&#9881;</div>
                <div class="hd-gear hd-gear-sm">&#9881;</div>
                <div class="hd-pulse"></div>
            </div>

            <p class="hd-tag mb-2">Produção Técnica</p>
            <h1 class="hd-title mb-3"><?= esc($titulo) ?></h1>
            <p class="hd-subtitle mb-4">
                Esta visualização está em construção. Estamos preparando os indicadores para esta seção.
            </p>

            <div class="hd-progress" role="img" aria-label="Progresso de desenvolvimento da página">
                <div class="hd-progress-fill"></div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
                <a class="btn btn-primary" href="<?= base_url('producao_tecnica/orientacoes') ?>">Ver Orientações</a>
                <a class="btn btn-outline-secondary" href="<?= base_url('indicators') ?>">Voltar para Indicadores</a>
            </div>

            <p class="text-muted small mt-3 mb-0">ID da seção: <?= esc($slug) ?></p>
        </div>
    </div>
</div>

<style>
.hd-construcao-wrap {
    position: relative;
    overflow: hidden;
    min-height: 70vh;
    background: radial-gradient(circle at 20% 15%, #d7f1ff 0%, rgba(215, 241, 255, 0) 45%),
                radial-gradient(circle at 80% 85%, #ffe9cc 0%, rgba(255, 233, 204, 0) 45%),
                linear-gradient(135deg, #f7fbff 0%, #fff9ef 100%);
}

.hd-aurora {
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    filter: blur(35px);
    opacity: 0.45;
    pointer-events: none;
}

.hd-aurora-1 {
    top: -120px;
    left: -120px;
    background: #8dd3ff;
    animation: hdFloat 8s ease-in-out infinite;
}

.hd-aurora-2 {
    right: -140px;
    bottom: -140px;
    background: #ffd28d;
    animation: hdFloat 10s ease-in-out infinite reverse;
}

.hd-card {
    max-width: 760px;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 18px;
    box-shadow: 0 20px 45px rgba(35, 46, 89, 0.14);
    padding: 36px 28px;
    text-align: center;
}

.hd-tag {
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-weight: 700;
    color: #0d6efd;
}

.hd-title {
    font-size: clamp(1.6rem, 1.25rem + 1.6vw, 2.45rem);
    font-weight: 800;
    color: #1d2733;
}

.hd-subtitle {
    font-size: 1.06rem;
    color: #566172;
}

.hd-gear-zone {
    position: relative;
    height: 120px;
    margin-bottom: 12px;
}

.hd-gear {
    position: absolute;
    line-height: 1;
    color: #2768d8;
    text-shadow: 0 10px 20px rgba(39, 104, 216, 0.22);
}

.hd-gear-lg {
    font-size: 72px;
    left: calc(50% - 62px);
    top: 12px;
    animation: hdSpin 8s linear infinite;
}

.hd-gear-sm {
    font-size: 44px;
    left: calc(50% + 10px);
    top: 58px;
    color: #f1992f;
    animation: hdSpinReverse 6s linear infinite;
}

.hd-pulse {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 22px;
    height: 22px;
    margin-left: -11px;
    margin-top: -11px;
    border-radius: 50%;
    background: #0dcaf0;
    box-shadow: 0 0 0 rgba(13, 202, 240, 0.5);
    animation: hdPulse 2s ease-out infinite;
}

.hd-progress {
    width: min(420px, 100%);
    height: 12px;
    background: #e9edf4;
    border-radius: 999px;
    margin: 0 auto;
    overflow: hidden;
    border: 1px solid #dde5f1;
}

.hd-progress-fill {
    height: 100%;
    width: 46%;
    background: linear-gradient(90deg, #0d6efd 0%, #0dcaf0 100%);
    border-radius: 999px;
    animation: hdProgress 3s ease-in-out infinite alternate;
}

@keyframes hdSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes hdSpinReverse {
    from { transform: rotate(360deg); }
    to { transform: rotate(0deg); }
}

@keyframes hdPulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(13, 202, 240, 0.45);
    }
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 18px rgba(13, 202, 240, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(13, 202, 240, 0);
    }
}

@keyframes hdFloat {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    50% { transform: translateY(14px) translateX(10px); }
}

@keyframes hdProgress {
    from { width: 34%; }
    to { width: 72%; }
}

@media (max-width: 576px) {
    .hd-card {
        padding: 28px 18px;
    }

    .hd-gear-zone {
        height: 100px;
    }

    .hd-gear-lg {
        font-size: 60px;
        left: calc(50% - 52px);
    }

    .hd-gear-sm {
        font-size: 38px;
        left: calc(50% + 2px);
        top: 52px;
    }
}
</style>
