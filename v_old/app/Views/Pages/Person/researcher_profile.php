<!-- app/Views/cv_view.php -->
<?php
/** @var array $cv */
if (!isset($cv)) {
    $cv = [];
}

/* -------- Helpers / mapeamentos básicos -------- */
$g = $cv['geral'] ?? [];
$end = $g['endereco_profissional'] ?? [];
$idiomas = $g['idiomas'] ?? [];
$areasAtu = $g['areas_de_atuacao'] ?? [];
$formacoes = $cv['formacao'] ?? [];
$prodTec   = $cv['producao_tecnica'] ?? [];

/* Alguns datasets comuns p/ produção acadêmica */
$prodAcadBloc = $cv['producao_bibliografica']
    ?? $cv['producao_academica']
    ?? $cv['producao_cientifica']
    ?? [];

/* Pode vir já normalizado, ou em sub-blocos como artigos/livros/etc */
$artigos   = $prodAcadBloc['artigos']   ?? $cv['artigos']   ?? [];
$livros    = $prodAcadBloc['livros']    ?? $cv['livros']    ?? [];
$capitulos = $prodAcadBloc['capitulos'] ?? $cv['capitulos'] ?? [];
$eventos   = $prodAcadBloc['trabalhos_eventos'] ?? $cv['trabalhos_eventos'] ?? ($prodAcadBloc['trabalhos_em_eventos'] ?? []);
$teses     = $prodAcadBloc['teses_dissertacoes'] ?? $cv['teses_dissertacoes'] ?? [];

/* >>> Produção Artística (novidade) <<< */
$prodArt = $cv['producao_artistica']
    ?? $cv['producao_artistico_cultural']
    ?? $cv['producao_artistica_cultural']
    ?? [];

/* Cabeçalho */
$nome      = $g['nome_completo'] ?? '—';
$citado    = $g['citacoes_bibliograficas'] ?? '';
$nacional  = $g['nacionalidade'] ?? '';
$resumo    = trim($g['resumo_cv'] ?? '');
$org       = $end['NOME-INSTITUICAO-EMPRESA'] ?? '';
$orgDepto  = $end['NOME-ORGAO'] ?? '';
$orgCidade = ($end['CIDADE'] ?? '');
$orgUF     = ($end['UF'] ?? '');
$orgPais   = ($end['PAIS'] ?? '');
$localTxt  = trim(implode(' • ', array_filter([$orgCidade, $orgUF, $orgPais])));

/* Ordena formação por conclusão desc */
usort($formacoes, function ($a, $b) {
    $aa = (int)($a['attrs_item']['ANO-DE-CONCLUSAO'] ?? 0);
    $bb = (int)($b['attrs_item']['ANO-DE-CONCLUSAO'] ?? 0);
    return $bb <=> $aa;
});

/* --------- Funções utilitárias ---------- */
function badge($text)
{
    return '<span class="badge text-bg-secondary me-1 mb-1">' . esc($text) . '</span>';
}
function get($arr, $key, $def = '')
{
    return isset($arr[$key]) && $arr[$key] !== '' ? $arr[$key] : $def;
}

/* Formata autores a partir de várias estruturas possíveis */
function formatAutores($arrAutores)
{
    if (empty($arrAutores) || !is_array($arrAutores)) return '';
    $out = [];
    foreach ($arrAutores as $a) {
        if (is_string($a)) {
            $out[] = $a;
            continue;
        }
        $nome = $a['nome'] ?? ($a['NOME-COMPLETO-DO-AUTOR'] ?? '');
        $cit  = $a['NOME-PARA-CITACAO'] ?? '';
        $out[] = $cit ?: $nome;
    }
    return implode('; ', array_filter($out));
}

/* Normaliza um item bibliográfico para colunas padrão */
function normAcadItem(array $it, string $tipoPadrao)
{
    // tenta chaves “Lattes-like” e genéricas
    $t = [
        'tipo'     => $it['tipo'] ?? $it['natureza'] ?? $tipoPadrao,
        'ano'      => $it['ano'] ?? $it['ANO'] ?? $it['ANO-DO-ARTIGO'] ?? $it['year'] ?? '',
        'titulo'   => $it['titulo'] ?? $it['TITULO'] ?? $it['TITULO-DO-ARTIGO'] ?? $it['TITULO-DO-TRABALHO'] ?? $it['title'] ?? '—',
        'veiculo'  => $it['revista'] ?? $it['periodico'] ?? $it['PERIODICO'] ?? $it['NOME-DO-PERIODICO-OU-REVISTA']
            ?? $it['evento'] ?? $it['NOME-DO-EVENTO'] ?? $it['livro'] ?? $it['BOOK-TITLE'] ?? '',
        'volume'   => $it['volume'] ?? $it['VOLUME'] ?? '',
        'numero'   => $it['numero'] ?? $it['NUMERO'] ?? $it['fasciculo'] ?? '',
        'paginas'  => $it['paginas'] ?? $it['PAGINAS'] ?? ($it['PAGINA-INICIAL'] ?? '') . (isset($it['PAGINA-FINAL']) ? '-' . $it['PAGINA-FINAL'] : ''),
        'doi'      => $it['doi'] ?? $it['DOI'] ?? '',
        'url'      => $it['url'] ?? $it['URL'] ?? $it['SITE'] ?? '',
        'autores'  => formatAutores($it['autores'] ?? $it['AUTORES'] ?? [])
    ];
    // limpeza básica
    foreach ($t as $k => $v) {
        $t[$k] = is_string($v) ? trim($v) : $v;
    }
    return $t;
}

/* >>> Normalizador para itens artísticos <<< */
function normArtItem(array $it)
{
    $sub = $it['subtipo'] ?? $it['natureza'] ?? $it['tipo'] ?? '—';
    $veic = $it['veiculo'] ?? $it['evento'] ?? $it['mostra'] ?? $it['exposicao'] ?? ($it['NOME-DO-EVENTO'] ?? '');
    $aut = formatAutores($it['autores'] ?? $it['AUTORES'] ?? []);
    $kw  = $it['keywords'] ?? $it['palavras_chave'] ?? [];
    if (is_string($kw)) $kw = array_filter(array_map('trim', preg_split('/[,;]+/', $kw)));
    return [
        'titulo' => trim($it['titulo'] ?? $it['TITULO'] ?? $it['obra'] ?? '—'),
        'sub'    => trim($sub),
        'ano'    => trim($it['ano'] ?? $it['ANO'] ?? ''),
        'local'  => trim($it['local'] ?? $it['LOCAL-DA-APRESENTACAO'] ?? ''),
        'veic'   => trim($veic),
        'autores'=> $aut,
        'kw'     => array_values(array_filter(array_map('trim', is_array($kw) ? $kw : []))),
        'url'    => trim($it['url'] ?? $it['URL'] ?? ''),
    ];
}

/* Constrói a lista final de produção acadêmica, a partir de blocos diversos */
$acadItems = [];

/* Se já veio como lista “flat” */
if (isset($prodAcadBloc[0]) && is_array($prodAcadBloc[0])) {
    foreach ($prodAcadBloc as $it) $acadItems[] = normAcadItem($it, 'Geral');
}

/* Artigos */
foreach ($artigos as $it) {
    $acadItems[] = normAcadItem($it, 'Artigo');
}
/* Livros */
foreach ($livros as $it) {
    $acadItems[] = normAcadItem($it, 'Livro');
}
/* Capítulos */
foreach ($capitulos as $it) {
    $acadItems[] = normAcadItem($it, 'Capítulo');
}
/* Trabalhos em eventos */
foreach ($eventos as $it) {
    $acadItems[] = normAcadItem($it, 'Trabalho em Evento');
}
/* Teses/Dissertações (se presentes) */
foreach ($teses as $it) {
    $acadItems[] = normAcadItem($it, 'Tese/Dissertação');
}

/* Ordena por ano desc, depois título */
usort($acadItems, function ($a, $b) {
    $aa = (int)($a['ano'] ?: 0);
    $bb = (int)($b['ano'] ?: 0);
    if ($aa === $bb) return strcasecmp($a['titulo'], $b['titulo']);
    return $bb <=> $aa;
});

/* >>> Monta a lista de produção artística a partir do(s) bloco(s) <<< */
$artItems = [];
if (!empty($prodArt)) {
    if (isset($prodArt[0]) && is_array($prodArt[0])) {
        // lista “flat”
        foreach ($prodArt as $it) $artItems[] = normArtItem($it);
    } else {
        // agrupado por chaves (ex.: apresentacoes, obras, exposicoes...)
        foreach ($prodArt as $k => $grupo) {
            if (!is_array($grupo)) continue;
            if (isset($grupo[0]) && is_array($grupo[0])) {
                foreach ($grupo as $it) $artItems[] = normArtItem($it);
            } else {
                foreach ($grupo as $maybe) {
                    if (is_array($maybe) && (isset($maybe['titulo']) || isset($maybe['TITULO']))) {
                        $artItems[] = normArtItem($maybe);
                    } elseif (is_array($maybe)) {
                        foreach ($maybe as $it) if (is_array($it)) $artItems[] = normArtItem($it);
                    }
                }
            }
        }
    }
    // ordena por ano desc, depois título
    usort($artItems, function ($a, $b) {
        $aa = (int)($a['ano'] ?: 0);
        $bb = (int)($b['ano'] ?: 0);
        if ($aa === $bb) return strcasecmp($a['titulo'], $b['titulo']);
        return $bb <=> $aa;
    });
}
?>

<style>
    body {
        color: #0f172a;
        background: #e2e8f0;
    }

    /* dark elegante */
    .card {
        background: #c1c7ceff;
        border: 1px solid #c1c7ceff;
    }

    .nav-tabs .nav-link {
        color: #2e1010ff;
    }

    .nav-tabs .nav-link.active {
        color: #0f172a;
        background: #e2e8f0;
        border-color: #1f2937 #1f2937 #132954ff;
    }

    .chip {
        border: 1px solid #334155;
        border-radius: 999px;
        padding: .25rem .6rem;
        margin: .15rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .chip i {
        font-size: .9rem;
    }

    .timeline {
        position: relative;
        padding-left: 1.25rem;
    }

    .timeline::before {
        content: "";
        position: absolute;
        left: 6px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #0b4aa1ff;
    }

    .t-item {
        position: relative;
        margin-bottom: 1rem;
        padding-left: 1rem;
    }

    .t-item::before {
        content: "";
        position: absolute;
        left: -2px;
        top: .2rem;
        width: 10px;
        height: 10px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, .25);
    }

    a,
    .link-light {
        color: #93c5fd;
    }

    .table {
        --bs-table-bg: #fff;
        --bs-table-striped-bg: #aaa;
        --bs-table-hover-bg: #333;
        color: #031832ff;
    }

    .badge {
        border: 1px solid #333a4a;
        background: #bedbc7ff;
        color: #020c17ff;
    }

    .searchbox {
        background: #d9add1ff;
        border: 1px solid #1f2937;
        color: #041c3dff;
    }
</style>
<div class="container py-4">

    <!-- Cabeçalho -->
    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                style="width:64px;height:64px;background:#0b1220;border:1px solid #1f2937;">
                <i class="bi bi-person-fill fs-2 text-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="h3 mb-1"><?= esc($nome) ?></h1>
            <div class="text-secondary small">
                <?= esc($citado ?: '— citações abreviadas') ?>
                <?php if ($org): ?> • <?= esc($org) ?><?php endif; ?>
                <?php if ($orgDepto): ?> • <?= esc($orgDepto) ?><?php endif; ?>
                <?php if ($localTxt): ?> • <?= esc($localTxt) ?><?php endif; ?>
            </div>
        </div>
        <div class="col-12 col-md-auto">
            <div class="d-flex gap-2">
                <?php if (!empty($cv['ID'])): ?>
                    <span class="chip"><i class="bi bi-hash"></i><?= esc($cv['ID']) ?></span>
                <?php endif; ?>
                <?php if ($nacional): ?>
                    <span class="chip"><i class="bi bi-flag"></i><?= esc($nacional) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Abas -->
    <ul class="nav nav-tabs mb-3 no-print" id="tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-geral" data-bs-toggle="tab" data-bs-target="#pane-geral" type="button" role="tab">
                <i class="bi bi-card-text me-1"></i>Geral
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-formacao" data-bs-toggle="tab" data-bs-target="#pane-formacao" type="button" role="tab">
                <i class="bi bi-mortarboard me-1"></i>Formação
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-prod-acad" data-bs-toggle="tab" data-bs-target="#pane-prod-acad" type="button" role="tab">
                <i class="bi bi-journals me-1"></i>Produção Acadêmica
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-prod" data-bs-toggle="tab" data-bs-target="#pane-prod" type="button" role="tab">
                <i class="bi bi-easel2 me-1"></i>Produção Técnica
            </button>
        </li>
        <!-- >>> Nova aba: Produção Artística <<< -->
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-prod-art" data-bs-toggle="tab" data-bs-target="#pane-prod-art" type="button" role="tab">
                <i class="bi bi-brush me-1"></i>Produção Artística
            </button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- GERAL -->
        <div class="tab-pane fade show active" id="pane-geral" role="tabpanel" aria-labelledby="tab-geral">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i>Dados gerais</h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="small text-secondary">Nome completo</div>
                                    <div><?= esc($nome) ?></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="small text-secondary">Citações bibliográficas</div>
                                    <div><?= esc($citado ?: '—') ?></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="small text-secondary">País de nascimento</div>
                                    <div><?= esc($g['pais_nascimento'] ?? '—') ?></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="small text-secondary">Cidade de nascimento</div>
                                    <div><?= esc($g['cidade_nascimento'] ?? '—') ?></div>
                                </div>
                            </div>
                            <?php if ($resumo): ?>
                                <hr class="border-secondary-subtle">
                                <div class="small text-secondary mb-1">Resumo do CV</div>
                                <div class="collapse show" id="cvResumo">
                                    <div><?= nl2br(esc($resumo)) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-building me-2"></i>Endereço profissional</h6>
                            <div class="small text-secondary">Instituição</div>
                            <div class="mb-2"><?= esc($org ?: '—') ?></div>
                            <div class="small text-secondary">Órgão/Unidade</div>
                            <div class="mb-2"><?= esc($orgDepto ?: '—') ?></div>
                            <div class="small text-secondary">Localização</div>
                            <div><?= esc($localTxt ?: '—') ?></div>
                            <?php if (!empty($end['CEP'])): ?>
                                <div class="mt-2 small text-secondary">CEP</div>
                                <div><?= esc($end['CEP']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($end['TELEFONE'])): ?>
                                <div class="mt-2 small text-secondary">Telefone</div>
                                <div><?= esc($end['DDD'] ?? '') ?> <?= esc($end['TELEFONE']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($idiomas)): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-translate me-2"></i>Idiomas</h6>
                                <?php foreach ($idiomas as $idi): ?>
                                    <div class="d-flex justify-content-between border-bottom border-secondary-subtle py-2">
                                        <div><strong><?= esc($idi['idioma'] ?? '—') ?></strong></div>
                                        <div class="text-end small">
                                            <div>Leitura: <?= esc($idi['leitura'] ?? '—') ?></div>
                                            <div>Fala: <?= esc($idi['fala'] ?? '—') ?></div>
                                            <div>Escrita: <?= esc($idi['escrita'] ?? '—') ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($areasAtu)): ?>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-diagram-3 me-2"></i>Áreas de atuação</h6>
                                <?php foreach ($areasAtu as $a): ?>
                                    <div class="mb-2">
                                        <?= badge($a['grande_area'] ?? '—') ?>
                                        <?php if (!empty($a['area'])) echo badge($a['area']); ?>
                                        <?php if (!empty($a['subarea'])) echo badge($a['subarea']); ?>
                                        <?php if (!empty($a['especialidade'])) echo badge($a['especialidade']); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FORMAÇÃO -->
        <div class="tab-pane fade" id="pane-formacao" role="tabpanel" aria-labelledby="tab-formacao">
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-funnel me-2"></i>Filtro rápido</h6>
                            <input type="search" id="f-search" class="form-control searchbox" placeholder="Filtrar por curso, instituição, palavra-chave…">
                            <div class="form-text text-secondary mt-2">Dica: digite “doutorado”, “mestrado”, “licenciatura”…</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-mortarboard me-2"></i>Histórico acadêmico</h5>
                            <div class="timeline" id="f-list">
                                <?php foreach ($formacoes as $f):
                                    $ai = $f['attrs_item'] ?? [];
                                    $nivel = [
                                        '1' => 'Graduação',
                                        '3' => 'Mestrado',
                                        '4' => 'Doutorado',
                                        '5' => 'Pós-doutorado',
                                        '7' => 'Técnico/Profissionalizante'
                                    ][$ai['NIVEL'] ?? ''] ?? ucfirst($f['type'] ?? 'Formação');
                                    $curso = $ai['NOME-CURSO'] ?? ($ai['NOME-CURSO-INGLES'] ?? ($f['nome_curso'] ?? '—'));
                                    $inst  = $ai['NOME-INSTITUICAO'] ?? ($f['instituicao'] ?? '—');
                                    $inicio = $ai['ANO-DE-INICIO'] ?? $f['ano_inicio'] ?? '—';
                                    $fim    = $ai['ANO-DE-CONCLUSAO'] ?? $f['ano_conclusao'] ?? '—';
                                    $tcc    = $ai['TITULO-DA-DISSERTACAO-TESE']
                                        ?? $ai['TITULO-DO-TRABALHO-DE-CONCLUSAO-DE-CURSO'] ?? '';
                                ?>
                                    <div class="t-item" data-search="<?= strtolower($nivel . ' ' . $curso . ' ' . $inst . ' ' . $tcc) ?>">
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <div class="fw-semibold"><?= esc($nivel) ?> • <?= esc($curso) ?></div>
                                            <div class="text-secondary"><?= esc($inicio) ?> – <?= esc($fim) ?></div>
                                        </div>
                                        <div class="small text-secondary"><?= esc($inst) ?></div>
                                        <?php if ($tcc): ?>
                                            <div class="mt-1"><i class="bi bi-journal-text me-1"></i><em><?= esc($tcc) ?></em></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUÇÃO ACADÊMICA -->
        <div class="tab-pane fade" id="pane-prod-acad" role="tabpanel" aria-labelledby="tab-prod-acad">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <h5 class="card-title mb-0 me-auto"><i class="bi bi-journals me-2"></i>Itens de produção acadêmica</h5>
                        <div class="no-print d-flex flex-wrap gap-2">
                            <input type="search" id="a-search" class="form-control searchbox" style="max-width:260px" placeholder="Filtrar por título, autor, veículo, ano, DOI…">
                            <select id="a-page-size" class="form-select" style="width:auto">
                                <option value="10">10/pg</option>
                                <option value="20" selected>20/pg</option>
                                <option value="50">50/pg</option>
                                <option value="0">Tudo</option>
                            </select>
                            <button class="btn btn-outline-success" id="a-export-csv"><i class="bi bi-filetype-csv"></i> CSV</button>
                            <button class="btn btn-outline-primary" id="a-export-pdf"><i class="bi bi-filetype-pdf"></i> PDF</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="a-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 280px;">Título</th>
                                    <th>Tipo</th>
                                    <th>Ano</th>
                                    <th>Veículo</th>
                                    <th>V/N/Pág.</th>
                                    <th>Autores</th>
                                    <th>DOI/URL</th>
                                </tr>
                            </thead>
                            <tbody id="a-tbody">
                                <?php foreach ($acadItems as $it):
                                    $vn = trim(($it['volume'] ? 'v.' . $it['volume'] : '') .
                                        ($it['numero'] ? ' n.' . $it['numero'] : ''));
                                    $vp = trim(implode(' • ', array_filter([$vn, $it['paginas']])));
                                    $link = $it['doi'] ? 'https://doi.org/' . ltrim($it['doi'], 'https://doi.org/') : $it['url'];
                                    $search = strtolower(implode(' ', [
                                        $it['titulo'],
                                        $it['tipo'],
                                        $it['ano'],
                                        $it['veiculo'],
                                        $it['autores'],
                                        $it['doi'],
                                        $it['url']
                                    ]));
                                ?>
                                    <tr data-search="<?= esc($search, 'attr') ?>">
                                        <td class="fw-semibold"><?= esc($it['titulo'] ?: '—') ?></td>
                                        <td><span class="badge"><?= esc($it['tipo'] ?: '—') ?></span></td>
                                        <td><?= esc($it['ano'] ?: '—') ?></td>
                                        <td><?= esc($it['veiculo'] ?: '—') ?></td>
                                        <td><?= esc($vp ?: '—') ?></td>
                                        <td><?= esc($it['autores'] ?: '—') ?></td>
                                        <td>
                                            <?php if ($link): ?>
                                                <a href="<?= esc($link) ?>" target="_blank" rel="noopener" class="link-light">
                                                    <?= esc($it['doi'] ?: $it['url']) ?>
                                                </a>
                                            <?php else: ?>—<?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex align-items-center gap-2 mt-2 no-print" id="a-pager">
                        <button class="btn btn-outline-light btn-sm" id="a-prev"><i class="bi bi-chevron-left"></i></button>
                        <span class="small" id="a-info">Página 1/1</span>
                        <button class="btn btn-outline-light btn-sm" id="a-next"><i class="bi bi-chevron-right"></i></button>
                    </div>

                    <?php if (empty($acadItems)): ?>
                        <div class="text-secondary mt-3">Nenhum item de produção acadêmica encontrado nas chaves esperadas (ex.: <code>producao_bibliografica</code>, <code>artigos</code>, <code>livros</code>, <code>capitulos</code>, <code>trabalhos_eventos</code>...).</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- PRODUÇÃO TÉCNICA -->
        <div class="tab-pane fade" id="pane-prod" role="tabpanel" aria-labelledby="tab-prod">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <h5 class="card-title mb-0 me-auto"><i class="bi bi-easel2 me-2"></i>Itens de produção técnica</h5>
                        <div class="no-print d-flex flex-wrap gap-2">
                            <input type="search" id="p-search" class="form-control searchbox" style="max-width:260px" placeholder="Filtrar por título, ano, cidade, palavra-chave…">
                            <select id="page-size" class="form-select" style="width:auto">
                                <option value="10">10/pg</option>
                                <option value="20" selected>20/pg</option>
                                <option value="50">50/pg</option>
                                <option value="0">Tudo</option>
                            </select>
                            <button class="btn btn-outline-success" id="export-csv"><i class="bi bi-filetype-csv"></i> CSV</button>
                            <button class="btn btn-outline-primary" id="export-pdf"><i class="bi bi-filetype-pdf"></i> PDF</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="p-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 220px;">Título</th>
                                    <th>Subtipo</th>
                                    <th>Ano</th>
                                    <th>Local</th>
                                    <th>Veículo / Evento</th>
                                    <th>Palavras-chave</th>
                                </tr>
                            </thead>
                            <tbody id="p-tbody">
                                <?php foreach ($prodTec as $p):
                                    $kw = array_values(array_filter($p['keywords'] ?? [], fn($x) => trim((string)$x) !== ''));
                                    $veic = $p['veiculo'] ?? '';
                                    $local = $p['local'] ?? '';
                                    $sub = $p['subtipo'] ?? ($p['natureza'] ?? '—');
                                    $ano = $p['ano'] ?? '—';
                                    $titulo = $p['titulo'] ?? '—';
                                    $autor0 = $p['autores'][0]['nome'] ?? '';
                                    $keysTxt = strtolower($titulo . ' ' . $sub . ' ' . $ano . ' ' . $local . ' ' . $veic . ' ' . implode(' ', $kw) . ' ' . $autor0);
                                ?>
                                    <tr data-search="<?= esc($keysTxt, 'attr') ?>">
                                        <td class="fw-semibold">
                                            <?= esc($titulo) ?><br>
                                            <span class="small text-secondary"><?= esc($autor0 ? 'por ' . $autor0 : '') ?></span>
                                        </td>
                                        <td><span class="badge"><?= esc($sub) ?></span></td>
                                        <td><?= esc($ano) ?></td>
                                        <td><?= esc($local ?: '—') ?></td>
                                        <td><?= esc($veic ?: '—') ?></td>
                                        <td>
                                            <?php if ($kw): foreach ($kw as $k) echo badge($k);
                                            else: echo '—';
                                            endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-2 no-print" id="pager">
                        <button class="btn btn-outline-light btn-sm" id="prev-page"><i class="bi bi-chevron-left"></i></button>
                        <span class="small" id="page-info">Página 1/1</span>
                        <button class="btn btn-outline-light btn-sm" id="next-page"><i class="bi bi-chevron-right"></i></button>
                    </div>

                </div>
            </div>
        </div>

        <!-- >>> PRODUÇÃO ARTÍSTICA (NOVA) <<< -->
        <div class="tab-pane fade" id="pane-prod-art" role="tabpanel" aria-labelledby="tab-prod-art">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                        <h5 class="card-title mb-0 me-auto"><i class="bi bi-brush me-2"></i>Itens de produção artística</h5>
                        <div class="no-print d-flex flex-wrap gap-2">
                            <input type="search" id="art-search" class="form-control searchbox" style="max-width:260px" placeholder="Filtrar por título, ano, local, evento, palavra-chave…">
                            <select id="art-page-size" class="form-select" style="width:auto">
                                <option value="10">10/pg</option>
                                <option value="20" selected>20/pg</option>
                                <option value="50">50/pg</option>
                                <option value="0">Tudo</option>
                            </select>
                            <button class="btn btn-outline-success" id="art-export-csv"><i class="bi bi-filetype-csv"></i> CSV</button>
                            <button class="btn btn-outline-primary" id="art-export-pdf"><i class="bi bi-filetype-pdf"></i> PDF</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle" id="art-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 260px;">Título</th>
                                    <th>Subtipo</th>
                                    <th>Ano</th>
                                    <th>Local</th>
                                    <th>Evento/Mostra</th>
                                    <th>Autores</th>
                                    <th>Palavras-chave</th>
                                    <th>URL</th>
                                </tr>
                            </thead>
                            <tbody id="art-tbody">
                                <?php foreach ($artItems as $it):
                                  $kwTxt = $it['kw'] ? implode(' | ', $it['kw']) : '';
                                  $search = strtolower(trim($it['titulo'].' '.$it['sub'].' '.$it['ano'].' '.$it['local'].' '.$it['veic'].' '.$kwTxt.' '.$it['autores']));
                                ?>
                                  <tr data-search="<?= esc($search, 'attr') ?>">
                                    <td class="fw-semibold"><?= esc($it['titulo']) ?></td>
                                    <td><span class="badge"><?= esc($it['sub'] ?: '—') ?></span></td>
                                    <td><?= esc($it['ano'] ?: '—') ?></td>
                                    <td><?= esc($it['local'] ?: '—') ?></td>
                                    <td><?= esc($it['veic'] ?: '—') ?></td>
                                    <td><?= esc($it['autores'] ?: '—') ?></td>
                                    <td>
                                      <?php if ($it['kw']): foreach ($it['kw'] as $k) echo badge($k);
                                      else: echo '—'; endif; ?>
                                    </td>
                                    <td>
                                      <?php if (!empty($it['url'])): ?>
                                        <a href="<?= esc($it['url']) ?>" target="_blank" rel="noopener" class="link-light"><?= esc($it['url']) ?></a>
                                      <?php else: ?>—<?php endif; ?>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-2 no-print" id="art-pager">
                        <button class="btn btn-outline-light btn-sm" id="art-prev"><i class="bi bi-chevron-left"></i></button>
                        <span class="small" id="art-info">Página 1/1</span>
                        <button class="btn btn-outline-light btn-sm" id="art-next"><i class="bi bi-chevron-right"></i></button>
                    </div>

                    <?php if (empty($artItems)): ?>
                        <div class="text-secondary mt-3">Nenhum item de produção artística encontrado nas chaves esperadas (ex.: <code>producao_artistica</code>, <code>producao_artistico_cultural</code>...).</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /tab-content -->

    <div class="text-secondary small mt-3">
        Fonte: dados estruturados fornecidos pelo usuário.
    </div>

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ===== Formação (filtro) =====
    const fSearch = document.getElementById('f-search');
    fSearch?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase().trim();
        document.querySelectorAll('#f-list .t-item').forEach(el => {
            const hay = el.getAttribute('data-search') || '';
            el.style.display = hay.includes(q) ? '' : 'none';
        });
    });

    // ===== Utilitários comuns (filtro/paginação) =====
    function getPageSize(sel) {
        const val = parseInt(sel.value, 10);
        return isNaN(val) || val < 0 ? 20 : val;
    }
    function applyFilter(listEls, query) {
        let visible = [];
        listEls.forEach(el => {
            const hay = (el.getAttribute('data-search') || '').toLowerCase();
            const show = hay.includes(query);
            el.style.display = show ? '' : 'none';
            if (show) visible.push(el);
        });
        return visible;
    }
    function paginate(listEls, page, pageSize, infoEl) {
        if (pageSize === 0) {
            listEls.forEach(el => el.style.display = '');
            infoEl.textContent = `Página 1/1 • ${listEls.length} itens`;
            return { totalPages: 1, page: 1 };
        }
        const total = listEls.length;
        const totalPages = Math.max(1, Math.ceil(total / pageSize));
        const p = Math.min(Math.max(1, page), totalPages);
        listEls.forEach((el, i) => {
            const start = (p - 1) * pageSize;
            const end = start + pageSize;
            el.style.display = (i >= start && i < end) ? '' : 'none';
        });
        infoEl.textContent = `Página ${p}/${totalPages} • ${total} itens`;
        return { totalPages, page: p };
    }

    // ===== Produção Técnica (filtro/paginação/export) =====
    const pSearch = document.getElementById('p-search');
    const pageSizeSel = document.getElementById('page-size');
    const tbody = document.getElementById('p-tbody');
    const rows = Array.from(tbody ? tbody.querySelectorAll('tr') : []);
    const pageInfo = document.getElementById('page-info');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    let currentPage = 1;

    function refreshTec() {
        const q = (pSearch?.value || '').toLowerCase().trim();
        const filtered = applyFilter(rows, q);
        const ps = getPageSize(pageSizeSel);
        const res = paginate(filtered, currentPage, ps, pageInfo);
        currentPage = res.page;
        prevBtn.disabled = (res.page <= 1);
        nextBtn.disabled = (res.page >= res.totalPages);
    }
    pSearch?.addEventListener('input', () => { currentPage = 1; refreshTec(); });
    pageSizeSel?.addEventListener('change', () => { currentPage = 1; refreshTec(); });
    prevBtn?.addEventListener('click', () => { currentPage--; refreshTec(); });
    nextBtn?.addEventListener('click', () => { currentPage++; refreshTec(); });
    document.getElementById('export-csv')?.addEventListener('click', () => {
        let rowsData = [];
        document.querySelectorAll('#p-table tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return;
            const tds = tr.querySelectorAll('td');
            const title = tds[0]?.childNodes[0]?.textContent.trim() || '';
            const subt = tds[1]?.innerText.trim() || '';
            const ano = tds[2]?.innerText.trim() || '';
            const local = tds[3]?.innerText.trim() || '';
            const veic = tds[4]?.innerText.trim() || '';
            const kws = tds[5]?.innerText.trim().replace(/\s*\n\s*/g, ' ') || '';
            rowsData.push([title, subt, ano, local, veic, kws]);
        });
        const header = ['Título', 'Subtipo', 'Ano', 'Local', 'Veículo/Evento', 'Palavras-chave'];
        const all = [header, ...rowsData];
        const csv = all.map(r => r.map(v => `"${(v??'').toString().replace(/"/g,'""')}"`).join(',')).join('\r\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'producao_tecnica.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });
    document.getElementById('export-pdf')?.addEventListener('click', () => { window.print(); });
    refreshTec();

    // ===== Produção Acadêmica (filtro/paginação/export) =====
    const aSearch = document.getElementById('a-search');
    const aPageSizeSel = document.getElementById('a-page-size');
    const aTbody = document.getElementById('a-tbody');
    const aRows = Array.from(aTbody ? aTbody.querySelectorAll('tr') : []);
    const aInfo = document.getElementById('a-info');
    const aPrev = document.getElementById('a-prev');
    const aNext = document.getElementById('a-next');
    let aPage = 1;

    function refreshAcad() {
        const q = (aSearch?.value || '').toLowerCase().trim();
        const filtered = applyFilter(aRows, q);
        const ps = getPageSize(aPageSizeSel);
        const res = paginate(filtered, aPage, ps, aInfo);
        aPage = res.page;
        aPrev.disabled = (res.page <= 1);
        aNext.disabled = (res.page >= res.totalPages);
    }
    aSearch?.addEventListener('input', () => { aPage = 1; refreshAcad(); });
    aPageSizeSel?.addEventListener('change', () => { aPage = 1; refreshAcad(); });
    aPrev?.addEventListener('click', () => { aPage--; refreshAcad(); });
    aNext?.addEventListener('click', () => { aPage++; refreshAcad(); });

    document.getElementById('a-export-csv')?.addEventListener('click', () => {
        let rowsData = [];
        document.querySelectorAll('#a-table tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return;
            const tds = tr.querySelectorAll('td');
            const titulo = tds[0]?.innerText.trim() || '';
            const tipo = tds[1]?.innerText.trim() || '';
            const ano = tds[2]?.innerText.trim() || '';
            const veic = tds[3]?.innerText.trim() || '';
            const vnp = tds[4]?.innerText.trim() || '';
            const autores = tds[5]?.innerText.trim() || '';
            const doiurl = tds[6]?.innerText.trim() || '';
            rowsData.push([titulo, tipo, ano, veic, vnp, autores, doiurl]);
        });
        const header = ['Título', 'Tipo', 'Ano', 'Veículo', 'V/N/Pág.', 'Autores', 'DOI/URL'];
        const all = [header, ...rowsData];
        const csv = all.map(r => r.map(v => `"${(v??'').toString().replace(/"/g,'""')}"`).join(',')).join('\r\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'producao_academica.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });
    document.getElementById('a-export-pdf')?.addEventListener('click', () => { window.print(); });
    refreshAcad();

    // ===== Produção Artística (filtro/paginação/export) =====
    const artSearch = document.getElementById('art-search');
    const artPageSizeSel = document.getElementById('art-page-size');
    const artTbody = document.getElementById('art-tbody');
    const artRows = Array.from(artTbody ? artTbody.querySelectorAll('tr') : []);
    const artInfo = document.getElementById('art-info');
    const artPrev = document.getElementById('art-prev');
    const artNext = document.getElementById('art-next');
    let artPage = 1;

    function refreshArt() {
        const q = (artSearch?.value || '').toLowerCase().trim();
        const filtered = applyFilter(artRows, q);
        const ps = getPageSize(artPageSizeSel);
        const res = paginate(filtered, artPage, ps, artInfo);
        artPage = res.page;
        artPrev.disabled = (res.page <= 1);
        artNext.disabled = (res.page >= res.totalPages);
    }
    artSearch?.addEventListener('input', () => { artPage = 1; refreshArt(); });
    artPageSizeSel?.addEventListener('change', () => { artPage = 1; refreshArt(); });
    artPrev?.addEventListener('click', () => { artPage--; refreshArt(); });
    artNext?.addEventListener('click', () => { artPage++; refreshArt(); });
    document.getElementById('art-export-csv')?.addEventListener('click', () => {
        let rowsData = [];
        document.querySelectorAll('#art-table tbody tr').forEach(tr => {
            if (tr.style.display === 'none') return;
            const tds = tr.querySelectorAll('td');
            const titulo = tds[0]?.innerText.trim() || '';
            const subt   = tds[1]?.innerText.trim() || '';
            const ano    = tds[2]?.innerText.trim() || '';
            const local  = tds[3]?.innerText.trim() || '';
            const veic   = tds[4]?.innerText.trim() || '';
            const autores= tds[5]?.innerText.trim() || '';
            const kws    = tds[6]?.innerText.trim().replace(/\s*\n\s*/g, ' ') || '';
            const url    = tds[7]?.innerText.trim() || '';
            rowsData.push([titulo, subt, ano, local, veic, autores, kws, url]);
        });
        const header = ['Título','Subtipo','Ano','Local','Evento/Mostra','Autores','Palavras-chave','URL'];
        const all = [header, ...rowsData];
        const csv = all.map(r => r.map(v => `"${(v??'').toString().replace(/"/g,'""')}"`).join(',')).join('\r\n');
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'producao_artistica.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    });
    document.getElementById('art-export-pdf')?.addEventListener('click', () => { window.print(); });
    refreshArt();
</script>
