<?php
function pre($data, $stop = true)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($stop) {
        exit;
    }
}

function dtobr($data)
{
    // Converte data no formato AAAA-MM-DD para dd/mm/aaaa
    $data = sonumero($data);
    $ano = round($data[0] . $data[1] . $data[2] . $data[3]);
    if (($ano > 1900) and ($ano < 2100)) {
        $data = substr($data,6,2).'/'.substr($data,4,2).'/'.substr($data,0,4);
    } else {
        $data = substr($data,0,2).'/'.substr($data,2,2).'/'.substr($data,4,4);
    }
    return $data; // Retorna original se não estiver no formato esperado
}

function brtod($data)
{
    // Converte data no formato BR (dd/mm/aaaa) para AAAA-MM-DD
    if ($data == '') {
        return '';
    }
    $data = sonumero($data);
    $ano = round($data[4] . $data[5] . $data[6] . $data[7]);
    if (($ano > 1900) and ($ano < 2100)) {
        $data = substr($data,4,4).'-'.substr($data,2,2).'-'.substr($data,0,2);
    } else {
        $data = substr($data,0,4).'-'.substr($data,4,2).'-'.substr($data,6,2);
    }
    return $data; // Retorna original se não estiver no formato esperado
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
