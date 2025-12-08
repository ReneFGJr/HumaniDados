<div class="tab-pane fade" id="production" role="tabpanel">

<?php
$totalLivros = count($pesquisador['livros']);
$totalCapitulos = count($pesquisador['capitulos']);
$totalArtigos = count($pesquisador['artigos']);
$totalGeral = $totalLivros + $totalCapitulos + $totalArtigos;
?>

<!-- ===============================  
     SUMÁRIO DA PRODUÇÃO  
================================ -->
<h5 class="text-hd-tx text-center mb-4">Produção Científica – Formato ABNT</h5>

<div class="row text-center mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="text-primary">Livros</h5>
                <h2><?= $totalLivros ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="text-success">Capítulos</h5>
                <h2><?= $totalCapitulos ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="text-danger">Artigos</h5>
                <h2><?= $totalArtigos ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="text-dark">Total Geral</h5>
                <h2><?= $totalGeral ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- ===============================  
     GRÁFICOS  
================================ -->
<div class="row mb-5">
    <div class="col-md-6">
        <canvas id="graficoPizza"></canvas>
    </div>

    <div class="col-md-6">
        <canvas id="graficoBarras"></canvas>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const dados = {
    livros: <?= $totalLivros ?>,
    capitulos: <?= $totalCapitulos ?>,
    artigos: <?= $totalArtigos ?>,
};

new Chart(document.getElementById('graficoPizza'), {
    type: 'pie',
    data: {
        labels: ['Livros', 'Capítulos', 'Artigos'],
        datasets: [{
            data: [dados.livros, dados.capitulos, dados.artigos],
        }]
    }
});

new Chart(document.getElementById('graficoBarras'), {
    type: 'bar',
    data: {
        labels: ['Livros', 'Capítulos', 'Artigos'],
        datasets: [{
            label: 'Quantidade',
            data: [dados.livros, dados.capitulos, dados.artigos]
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>


<!-- =======================================================
 LIVROS PUBLICADOS
======================================================= -->
<h5 class="text-hd-tx mt-5">Livros publicados ou organizados
    <span class="contador">(<?= $totalLivros ?> itens)</span>
</h5>

<?php if ($totalLivros == 0): ?>
    <p class="text-muted">Nenhum livro cadastrado.</p>
<?php endif; ?>

<div class="mt-3">
<?php foreach ($pesquisador['livros'] as $l): ?>
    <div class="ref-item abnt">
        <?php
        echo "<strong>{$l['autor_nome']}</strong>. ";
        echo "<em>{$l['titulo']}.</em> ";

        if (!empty($l['edicao'])) echo "{$l['edicao']} ed. ";
        if (!empty($l['cidade_editora'])) echo "{$l['cidade_editora']}: ";
        echo "{$l['nome_editora']}, {$l['ano']}. ";
        if (!empty($l['isbn'])) echo "ISBN: {$l['isbn']}.";
        ?>
    </div>
<?php endforeach; ?>
</div>


<!-- =======================================================
 CAPÍTULOS DE LIVROS
======================================================= -->
<h5 class="text-hd-tx mt-5">Capítulos de livros publicados
    <span class="contador">(<?= $totalCapitulos ?> itens)</span>
</h5>

<div class="mt-3">
<?php foreach ($pesquisador['capitulos'] as $c): ?>
    <div class="ref-item abnt">
        <?php
        echo "<strong>{$c['autor_nome']}</strong>. {$c['titulo_capitulo']}. ";
        echo "In: <strong>{$c['organizadores']}</strong> (Org.). ";
        echo "<em>{$c['titulo_livro']}.</em> ";

        if (!empty($c['cidade_editora'])) echo "{$c['cidade_editora']}: ";
        echo "{$c['nome_editora']}, {$c['ano']}. ";

        if (!empty($c['pagina_inicial']) && !empty($c['pagina_final'])) {
            echo "p. {$c['pagina_inicial']}-{$c['pagina_final']}. ";
        }
        if (!empty($c['isbn'])) echo "ISBN: {$c['isbn']}.";
        ?>
    </div>
<?php endforeach; ?>
</div>


<!-- =======================================================
 ARTIGOS PUBLICADOS
======================================================= -->
<h5 class="text-hd-tx mt-5">Artigos publicados
    <span class="contador">(<?= $totalArtigos ?> itens)</span>
</h5>

<div class="mt-3">
<?php foreach ($pesquisador['artigos'] as $a): ?>
    <div class="ref-item abnt">
        <?php
        echo "<strong>{$a['autor_nome']}</strong>. ";
        echo "{$a['titulo']}. ";
        echo "<em>{$a['periodico']}</em>, ";

        if (!empty($a['volume'])) echo "v. {$a['volume']}, ";
        if (!empty($a['serie'])) echo "n. {$a['serie']}, ";

        echo "{$a['ano']}, ";
        echo "p. {$a['pagina_inicial']}-{$a['pagina_final']}. ";
        if (!empty($a['doi'])) echo "DOI: {$a['doi']}.";
        ?>
    </div>
<?php endforeach; ?>
</div>

</div>
