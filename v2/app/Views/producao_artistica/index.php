<?php
// ======================================================
// AGRUPA POR CLASSE > SUBCLASSE > ATIVIDADE (somado)
// ======================================================
$classes = [];

foreach ($dados as $item) {

    $classe   = $item['tipo'];
    $natureza = $item['natureza'];
    $atividade = trim($item['atividade']) !== '' ? $item['atividade'] : 'Sem atividade';
    $total = (int)$item['total'];

    // Inicializa a classe
    if (!isset($classes[$classe])) {
        $classes[$classe] = [];
    }

    // Inicializa a natureza
    if (!isset($classes[$classe][$natureza])) {
        $classes[$classe][$natureza] = [];
    }

    // Inicializa a atividade
    if (!isset($classes[$classe][$natureza][$atividade])) {
        $classes[$classe][$natureza][$atividade] = 0;
    }

    // Soma os totais da mesma atividade
    $classes[$classe][$natureza][$atividade] += $total;
}

// ======================================================
// CORES DINÂMICAS POR CLASSE
// ======================================================
function corTipo($tipo)
{
    return match ($tipo) {
        'MUSICA'         => ['#4da3ff', '🎵 Música'],
        'ARTES-VISUAIS'  => ['#4ade80', '🎨 Artes Visuais'],
        'ARTES-CENICAS'  => ['#ff7070', '🎭 Artes Cênicas'],
        default          => ['#a0aec0', 'Outro']
    };
}
?>

<style>
    body {
        background: #111;
        color: #eee;
        font-family: Arial, sans-serif;
    }

    h2,
    h3,
    h5 {
        color: #fff;
    }

    .classe-box {
        background: #1a1a1a;
        border-radius: 14px;
        margin-bottom: 40px;
        padding: 25px;
        border: 1px solid #2c2c2c;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.06);
    }

    .subclasse {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #333;
    }

    .atividade-item {
        display: flex;
        justify-content: space-between;
        background: #141414;
        padding: 8px 14px;
        border-radius: 6px;
        margin-bottom: 8px;
        border-left: 4px solid;
    }

    .atividade {
        color: #eee;
    }

    .atividade-total {
        color: #fff;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 6px;
    }
</style>


<div class="container py-5">
    <h2 class="text-center fw-bold mb-5">Produção Artística / Cultural</h2>

    <div class="row">

        <?php foreach ($classes as $classe => $subclasses):
            list($cor, $rotulo) = corTipo($classe);
        ?>

            <div class="classe-box col-md-3">
                <h3 class="fw-bold mb-4" style="color: <?= $cor ?>;">
                    <?= $rotulo ?>
                </h3>

                <?php foreach ($subclasses as $natureza => $atividades): ?>
                    <div class="subclasse">
                        <h5 class="mb-2" style="color: <?= $cor ?>;">
                            <?= str_replace('_', ' ', $natureza) ?>
                        </h5>

                        <?php foreach ($atividades as $atividadeNome => $total): ?>
                            <div class="atividade-item" style="border-left-color: <?= $cor ?>;">
                                <span class="atividade">
                                    <?= str_replace('_', ' ', $atividadeNome) ?>
                                </span>
                                <span class="atividade-total" style="background: <?= $cor ?>;">
                                    <?= $total ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>

    </div>
</div>