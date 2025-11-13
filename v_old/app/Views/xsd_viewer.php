<?php

/**
 * @var ?string $error
 * @var array $tree
 * @var array $meta
 */
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>XSD Viewer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/xsd-viewer.css">
</head>

<body>
    <header class="xv-header">
        <h1>XSD Viewer</h1>
        <div class="xv-meta">
            <?php if (!empty($meta)): ?>
                <span>Arquivo: <strong><?= esc($meta['file']) ?></strong></span>
                <span>Elementos: <strong><?= esc($meta['elements']) ?></strong></span>
                <span>Tipos: <strong><?= esc($meta['types']) ?></strong></span>
                <span>AttrGroups: <strong><?= esc($meta['attributeGroups']) ?></strong></span>
            <?php endif; ?>
        </div>
        <div class="xv-actions">
            <input id="xv-search" type="search" placeholder="Buscar por nome...">
            <button id="xv-expand">Expandir tudo</button>
            <button id="xv-collapse">Recolher tudo</button>
        </div>
    </header>

    <?php if ($error): ?>
        <div class="xv-error"><?= esc($error) ?></div>
    <?php else: ?>
        <section id="xv-tree" class="xv-tree"></section>
    <?php endif; ?>

    <template id="tpl-node">
        <li class="xv-node">
            <div class="xv-row">
                <button class="xv-toggle" aria-label="alternar" title="Alternar"></button>
                <span class="xv-name"></span>
                <span class="xv-badge"></span>
            </div>
            <div class="xv-props"></div>
            <ul class="xv-children"></ul>
        </li>
    </template>

    <script>
        window.__XSD_TREE__ = <?= json_encode($tree, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="/assets/xsd-viewer.js"></script>
</body>

</html>