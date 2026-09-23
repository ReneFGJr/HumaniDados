<?php
$numero = static fn ($valor) => number_format((int) $valor, 0, ',', '.');
$dataBr = static fn ($valor) => $valor ? date('d/m/Y', strtotime($valor)) : 'Não informada';
?>
<link rel="stylesheet" href="<?= base_url('assets/css/indicadores-globais.css') ?>">
<main class="ig">
    <header class="ig-hero">
        <div><div class="ig-eyebrow">HUMANIDADES EM NÚMEROS</div><h1>Indicadores Globais</h1><p>Um panorama da formação, da produção e da atuação acadêmica dos pesquisadores da base.</p></div>
        <div class="ig-source"><i class="bi bi-database" aria-hidden="true"></i><span>Fonte dos dados<strong>Currículos Lattes importados</strong></span></div>
    </header>
    <?php if ($erro): ?>
        <div class="alert alert-warning my-4" role="alert"><?= esc($erro) ?></div>
    <?php elseif (!$resumo['curriculos']): ?>
        <section class="ig-empty"><i class="bi bi-bar-chart" aria-hidden="true"></i><h2>Um panorama em construção</h2><p>Os indicadores estarão disponíveis após a importação dos currículos Lattes.</p></section>
    <?php else: ?>
        <section class="ig-kpis" aria-label="Destaques da base">
        <?php foreach ([
            ['people', $resumo['curriculos'], 'Currículos na base', 'Pesquisadores representados'],
            ['buildings', $resumo['instituicoes'], 'Instituições', 'Nomes distintos no endereço profissional'],
            ['journal-richtext', $resumo['grupos']['bibliografica']['total'], 'Registros bibliográficos', 'Soma das categorias de publicação'],
            ['mortarboard', $resumo['grupos']['orientacoes']['total'], 'Orientações concluídas', 'Inclui supervisões e coorientações'],
        ] as [$icone, $valor, $titulo, $descricao]): ?>
            <article class="ig-kpi"><i class="bi bi-<?= esc($icone, 'attr') ?>" aria-hidden="true"></i><strong><?= $numero($valor) ?></strong><h2><?= esc($titulo) ?></h2><p><?= esc($descricao) ?></p></article>
        <?php endforeach; ?>
        </section>
        <div class="ig-freshness"><i class="bi bi-clock-history" aria-hidden="true"></i><span>Atualização mais recente de um currículo: <strong><?= esc($dataBr($resumo['ultima_atualizacao'])) ?></strong></span><span><?= $numero($resumo['com_data']) ?> de <?= $numero($resumo['curriculos']) ?> currículos com data informada</span></div>
        <section class="ig-distributions" aria-label="Perfil da base">
        <?php foreach ([
            ['areas', 'Áreas do conhecimento', 'Primeira grande área informada em cada currículo.', 'compass'],
            ['instituicoes_top', 'Instituições em destaque', 'Endereço profissional informado pelos pesquisadores.', 'building'],
        ] as [$chave, $titulo, $descricao, $icone]): ?>
            <?php $visiveis = array_slice($resumo[$chave], 0, 5); $restantes = array_slice($resumo[$chave], 5); ?>
            <article class="ig-panel">
                <div class="ig-panel-title"><i class="bi bi-<?= esc($icone, 'attr') ?>" aria-hidden="true"></i><h2><?= esc($titulo) ?></h2></div><p class="ig-muted"><?= esc($descricao) ?></p>
                <ul class="ig-bars">
                <?php foreach ($visiveis as $linha): ?>
                    <?php $percentual = 100 * (int) $linha['total'] / $resumo['curriculos']; $nome = $chave === 'areas' ? mb_convert_case(str_replace('_', ' ', $linha['nome']), MB_CASE_TITLE, 'UTF-8') : $linha['nome']; ?>
                    <li><div><span><?= esc($nome) ?></span><strong><?= $numero($linha['total']) ?> <small>· <?= number_format($percentual, 1, ',', '.') ?>%</small></strong></div><div class="ig-track" aria-hidden="true"><span style="width: <?= number_format($percentual, 2, '.', '') ?>%"></span></div></li>
                <?php endforeach; ?>
                </ul>
                <?php if ($restantes): ?>
                    <details class="ig-more"><summary>Ver mais <?= count($restantes) ?> <?= $chave === 'areas' ? 'áreas' : 'instituições' ?></summary><ul class="ig-extra">
                    <?php foreach ($restantes as $linha): ?><li><span><?= esc(str_replace('_', ' ', $linha['nome'])) ?></span><strong><?= $numero($linha['total']) ?></strong></li><?php endforeach; ?>
                    </ul></details>
                <?php endif; ?>
                <p class="ig-caption">Contagem de currículos · percentuais sobre toda a base</p>
            </article>
        <?php endforeach; ?>
        </section>
        <section aria-labelledby="ig-explore">
            <div class="ig-section-heading"><div><div class="ig-eyebrow">EXPLORE POR CONTEÚDO</div><h2 id="ig-explore">Dimensões da trajetória acadêmica</h2></div><p>Consulte os indicadores de cada tema.</p></div>
            <div class="ig-filters" role="group" aria-label="Filtrar indicadores por tema" hidden>
                <button type="button" class="is-active" data-ig-filter="todos" aria-pressed="true">Todos os temas</button>
                <?php foreach ($resumo['grupos'] as $chave => $grupo): ?><button type="button" data-ig-filter="<?= esc($chave, 'attr') ?>" aria-pressed="false"><?= esc($grupo['titulo']) ?></button><?php endforeach; ?>
            </div>
            <p class="visually-hidden" id="ig-filter-status" role="status" aria-live="polite"></p>
            <div class="ig-groups">
            <?php foreach ($resumo['grupos'] as $chave => $grupo): ?>
                <?php $principais = array_slice($grupo['itens'], 0, 6, true); $demais = array_slice($grupo['itens'], 6, null, true); ?>
                <article class="ig-panel ig-group" data-ig-group="<?= esc($chave, 'attr') ?>">
                    <div class="ig-group-header"><span class="ig-icon"><i class="bi bi-<?= esc($grupo['icone'], 'attr') ?>" aria-hidden="true"></i></span><div><h3><?= esc($grupo['titulo']) ?></h3><p><?= esc($grupo['descricao']) ?></p></div></div>
                    <div class="ig-group-total"><strong><?= $numero($grupo['total']) ?></strong><span>registros nas categorias abaixo</span></div>
                    <dl class="ig-metrics"><?php foreach ($principais as $campo => $label): ?><div><dt><?= esc($label) ?></dt><dd><?= $numero($grupo['valores'][$campo]) ?></dd></div><?php endforeach; ?></dl>
                    <?php if ($demais): ?><details class="ig-more"><summary>Ver todas as categorias (+<?= count($demais) ?>)</summary><dl class="ig-metrics"><?php foreach ($demais as $campo => $label): ?><div><dt><?= esc($label) ?></dt><dd><?= $numero($grupo['valores'][$campo]) ?></dd></div><?php endforeach; ?></dl></details><?php endif; ?>
                </article>
            <?php endforeach; ?>
            </div>
        </section>
        <aside class="ig-note"><i class="bi bi-info-circle" aria-hidden="true"></i><div><h2>Como ler estes números</h2><p>Os valores somam os registros dos currículos importados. Uma mesma publicação, orientação ou projeto pode constar em mais de um currículo; portanto, os totais não representam itens únicos. Zero indica ausência de registros na base importada. As datas se referem à atualização dos currículos, e as contagens abrangem todo o conteúdo disponível, sem recorte de ano.</p></div></aside>
    <?php endif; ?>
</main>
<script>
(() => {
    const filters = document.querySelector('.ig-filters');
    if (!filters) return;
    filters.hidden = false;
    filters.addEventListener('click', (event) => {
        const button = event.target.closest('[data-ig-filter]');
        if (!button) return;
        const selected = button.dataset.igFilter;
        filters.querySelectorAll('button').forEach(item => {
            const active = item === button;
            item.classList.toggle('is-active', active);
            item.setAttribute('aria-pressed', String(active));
        });
        document.querySelectorAll('[data-ig-group]').forEach(group => {
            group.hidden = selected !== 'todos' && group.dataset.igGroup !== selected;
        });
        document.getElementById('ig-filter-status').textContent = selected === 'todos' ? 'Todos os temas exibidos.' : 'Exibindo: ' + button.textContent;
    });
})();
</script>
