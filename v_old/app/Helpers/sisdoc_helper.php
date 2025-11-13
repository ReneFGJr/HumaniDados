<?php
function pre($data, $stop = false)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($stop) {
        exit;
    }
}

function sonumero($string)
{
    // Remove tudo que não seja número (0-9)
    return preg_replace('/\D/', '', $string);
}

function buildHierarchy(array $adj, string $root, array &$memo = [], array $stack = []): array
{
    // já calculado? (memoization)
    if (array_key_exists($root, $memo)) {
        return $memo[$root];
    }

    // ciclo? (protege de referências circulares)
    if (in_array($root, $stack, true)) {
        // você pode só sinalizar a referência em vez de expandir
        return $memo[$root] = ['__ref__' => $root];
    }

    $stack[] = $root;

    // Pega filhos diretos do nó (ou vazio se for folha)
    $children = $adj[$root] ?? [];

    // Garante que $children é array de chaves (nomes dos filhos)
    if (!is_array($children)) {
        $children = []; // robustez
    }

    $node = [];
    foreach ($children as $childName => $unused) {
        // Se o filho não tem definição própria no mapa, é folha []
        $node[$childName] = buildHierarchy($adj, (string)$childName, $memo, $stack);
    }

    return $memo[$root] = $node;
}

/**
 * Descobre todas as raízes (pais que nunca aparecem como filhos)
 * e monta uma floresta com cada raiz expandida.
 */
function buildForest(array $adj): array
{
    $parents = array_keys($adj);
    $childrenSet = [];
    foreach ($adj as $p => $children) {
        foreach ((array)$children as $c => $unused) {
            $childrenSet[$c] = true;
        }
    }
    // raízes = pais que não são filhos de ninguém
    $roots = array_values(array_diff($parents, array_keys($childrenSet)));

    $forest = [];
    $memo = [];
    foreach ($roots as $r) {
        $forest[$r] = buildHierarchy($adj, $r, $memo);
    }
    return $forest;
}

/** Impressão simples em texto (indentado) */
function printTree(array $tree, int $level = 0): string
{
    $sx = '';
    $pad = str_repeat('  ', $level);
    foreach ($tree as $name => $sub) {
        $sx .= $pad . '• (' . $level . ') ' . $name . PHP_EOL;
        if (is_array($sub) && !isset($sub['__ref__']) && $sub) {
            $sx .= printTree($sub, $level + 1);
        } elseif (isset($sub['__ref__'])) {
            $sx .= $pad . '  ↩ ref: ' . $sub['__ref__'] . PHP_EOL;
        }
    }
    return $sx;
}

/** (Opcional) Gera uma <ul> HTML a partir da árvore */
function treeToHtmlList(array $tree, $n=0): string
{
    $html = "<ul>";
    foreach ($tree as $name => $sub) {
        $html .= "<li>(".$n.")" . htmlspecialchars((string)$name);
        if (is_array($sub) && !isset($sub['__ref__']) && $sub) {
            $html .= treeToHtmlList($sub, $n + 1);
        } elseif (isset($sub['__ref__'])) {
            $html .= ' <em>('.$n.') - (ref: ' . htmlspecialchars($sub['__ref__']) . ')</em>';
        }
        $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
}
