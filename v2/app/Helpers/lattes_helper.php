<?php
function extrairDados(string $xmlPath, $id): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    // O arquivo está em ISO-8859-1; o DOM respeita o encoding do próp. XML.
    if (!$dom->load($xmlPath)) {
        throw new RuntimeException("Não foi possível ler o XML em {$xmlPath}");
    }

    $xp = new DOMXPath($dom);
    $geral = lattes_dados_gerais($xp);
    $artigos = lattes_artigos($xp);
    $trabalhos = lattes_trabalhos_eventos($xp);
    $livros = lattes_livros_capitulos($xp);
    $demais = lattes_demais_biblio($xp);
    $producao_tecnica = lattes_producao_tecnica($xp);
    $formacao = lattes_formacao($xp);

    $data = [
        'ID' => $id,
        'geral' => $geral,
        'formacao' => $formacao,
        'producao_tecnica' => $producao_tecnica,
        'demais'   => $demais,
        'livros'    => $livros,
        'artigos'  => $artigos,
        'trabalhos' => $trabalhos,
    ];

    return $data;
}

/**************************************************************************/
function lattes_authors(DOMXPath $xp, DOMElement $art): array
{
    $authors = [];
    foreach ($xp->query('AUTORES', $art) as $a) {
        /** @var DOMElement $a */
        $authors[] = [
            'ordem'  => $a->getAttribute('ORDEM-DE-AUTORIA'),
            'nome'   => $a->getAttribute('NOME-COMPLETO-DO-AUTOR') ?: $a->getAttribute('NOME-PARA-CITACAO'),
            'idCNPq' => $a->getAttribute('NRO-ID-CNPQ'),
        ];
    }
    return $authors;
}

function lattes_keywords(DOMXPath $xp, DOMElement $art): array
{
    $keywords = [];
    foreach ($xp->query('PALAVRAS-CHAVE', $art) as $a) {
        /** @var DOMElement $a */
        for ($r = 1; $r <= 6; $r++) {
            $keyn = $a->getAttribute('PALAVRA-CHAVE-' . $r);
            $keywords[] = $keyn;
        }
    }
    return $keywords;
}

function lattes_areas(DOMXPath $xp, DOMElement $art): array
    {
    $areas = [];
    foreach ($xp->query('AREAS-DO-CONHECIMENTO/AREA-DO-CONHECIMENTO-1', $art) as $a) {
        /** @var DOMElement $a */
        $areas[] = $a->getAttribute('NOME-GRANDE-AREA-DO-CONHECIMENTO');
        $areas[] = $a->getAttribute('NOME-DA-AREA-DO-CONHECIMENTO');
        $areas[] = $a->getAttribute('NOME-DA-SUB-AREA-DO-CONHECIMENTO');
        $areas[] = $a->getAttribute('NOME-DA-ESPECIALIDADE');
    }
    return $areas;
    }

function lattes_formacao(DOMXPath $xp): array
{
    $formacoes = [];

    // Todos os itens dentro de DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO
    $nodes = $xp->query('//DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/*');

    $attrs = function (?DOMElement $el): array {
        if (!$el) return [];
        $out = [];
        /** @var DOMAttr $a */
        foreach ($el->attributes ?? [] as $a) {
            $out[$a->name] = $a->value;
        }
        return $out;
    };

    /** Helper para pegar o 1º filho cujo nome começa com prefixo (ex.: DADOS-BASICOS / DETALHAMENTO) */
    $firstByPrefix = function (DOMXPath $xp, DOMElement $ctx, string $prefix): ?DOMElement {
        return $xp->query('*[starts-with(local-name(), "' . $prefix . '")]', $ctx)->item(0);
    };

    foreach ($nodes as $n) {
        /** @var DOMElement $n */
        $tipo    = $n->nodeName; // ex.: MESTRADO, DOUTORADO, GRADUACAO...
        $dados   = $firstByPrefix($xp, $n, 'DADOS-BASICOS');
        $detalhe = $firstByPrefix($xp, $n, 'DETALHAMENTO');

        $dadosA   = $attrs($dados);
        $detalheA = $attrs($detalhe);
        $nodeA    = $attrs($n); // ex.: SEQUENCIA-FORMACAO, etc.

        // Campos comuns (se existirem; caso não, ficam vazios)
        $nomeCurso   = $dadosA['NOME-DO-CURSO']      ?? ($detalheA['NOME-DO-CURSO'] ?? '');
        $instituicao = $detalheA['NOME-INSTITUICAO'] ?? ($dadosA['NOME-INSTITUICAO'] ?? '');
        $ini         = $dadosA['ANO-DE-INICIO']      ?? ($detalheA['ANO-DE-INICIO'] ?? '');
        $fim         = $dadosA['ANO-DE-CONCLUSAO']   ?? ($detalheA['ANO-DE-CONCLUSAO'] ?? '');
        $status      = $dadosA['STATUS-DO-CURSO']    ?? ($detalheA['STATUS-DO-CURSO'] ?? '');

        // Se você já tem helpers como nos artigos, dá pra reaproveitar:
        $keywords = function_exists('lattes_keywords') ? lattes_keywords($xp, $n) : [];
        $areas    = function_exists('lattes_areas')    ? lattes_areas($xp, $n)    : [];

        $formacoes[] = [
            'type'            => strtolower($tipo),     // ex.: 'mestrado'
            'nome_curso'      => $nomeCurso,
            'instituicao'     => $instituicao,
            'ano_inicio'      => $ini,
            'ano_conclusao'   => $fim,
            'status'          => $status,
            'sequencia'       => $nodeA['SEQUENCIA-FORMACAO'] ?? '',
            // blocos completos de atributos (caso precise de algo específico depois)
            'attrs_item'      => $nodeA,
            'dados_basicos'   => $dadosA,
            'detalhamento'    => $detalheA,
            // extras
            'keywords'        => $keywords,
            'areas'           => $areas,
        ];
    }

    return $formacoes;
}


/***************************************************************** ARTIGOS */
function lattes_artigos($xp)
    {
    // Caminho clássico no Lattes:
    $nArtigos = $xp->query('//PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS/ARTIGO-PUBLICADO');
    $artigos = [];
    foreach ($nArtigos as $art) {
        // Filhos do ARTIGO-PUBLICADO
        $dados = $xp->query('DADOS-BASICOS-DO-ARTIGO', $art)->item(0);
        $det   = $xp->query('DETALHAMENTO-DO-ARTIGO',   $art)->item(0);
        // Evitar notices quando algum bloco não existir
        $get = fn(?DOMElement $el, string $attr) => $el ? $el->getAttribute($attr) : '';
        // Autores (há vários nós AUTORES diretamente dentro de ARTIGO-PUBLICADO)
        $autores = lattes_authors($xp,$art);
        $keywords = lattes_keywords($xp, $art);
        $areas = lattes_areas($xp,$art);

        $artigos[] = [
            // DADOS-BASICOS-DO-ARTIGO
            'type'     => 'artigo',
            'titulo'   => $get($dados, 'TITULO-DO-ARTIGO'),
            'natureza' => $get($dados, 'NATUREZA'),
            'ano'      => $get($dados, 'ANO-DO-ARTIGO'),
            'doi'      => $get($dados, 'DOI'),
            'issn'     => $get($dados, 'ISSN'),
            'isbn'     => $get($dados, 'ISBN'),
            'idioma'   => $get($dados, 'IDIOMA'),
            'pais'     => $get($dados, 'PAIS-DE-PUBLICACAO'),
            // DETALHAMENTO-DO-ARTIGO
            'revista'  => $get($det, 'TITULO-DO-PERIODICO-OU-REVISTA'),
            'local'    => $get($det, 'LOCAL-DE-PUBLICACAO'),
            'issn'     => $get($det, 'ISSN'),
            'volume'   => $get($det, 'VOLUME'),
            'fasciculo' => $get($det, 'FASCICULO'),
            'pag_ini'  => $get($det, 'PAGINA-INICIAL'),
            'pag_fim'  => $get($det, 'PAGINA-FINAL'),
            // lista de autores
            'autores'  => $autores,
            // lista de autores
            'keywords' => $keywords,
            // lista de áreas
            'areas' => $areas
        ];
    }
    return $artigos;
}

/******************************************* Dados Gerais */
function lattes_dados_gerais(DOMXPath $xp): array
{
    // Nó DADOS-GERAIS (há apenas um por currículo)
    /** @var DOMElement|null $dg */
    $dg = $xp->query('//DADOS-GERAIS')->item(0);
    if (!$dg) {
        return []; // não há dados gerais
    }

    // Helper p/ pegar atributo com fallback vazio
    $get = fn(string $attr) => $dg->getAttribute($attr) ?: '';

    // RESUMO-CV
    $nResumo = $xp->query('RESUMO-CV', $dg)->item(0);
    $resumo  = '';
    if ($nResumo instanceof DOMElement) {
        // Alguns XMLs têm TEXTO-RESUMO-CV-RH, outros TEXTO-RESUMO-CV
        $resumo = $nResumo->getAttribute('TEXTO-RESUMO-CV-RH')
               ?: $nResumo->getAttribute('TEXTO-RESUMO-CV')
               ?: '';
    }

    // OUTRAS-INFORMACOES-RELEVANTES
    $nOutras = $xp->query('OUTRAS-INFORMACOES-RELEVANTES', $dg)->item(0);
    $outras  = $nOutras instanceof DOMElement
        ? ($nOutras->getAttribute('OUTRAS-INFORMACOES-RELEVANTES') ?: '')
        : '';

    // ENDEREÇOS (profissional e residencial)
    $getAddr = function (?DOMElement $el): array {
        if (!$el) return [];
        $fields = [
            'CODIGO-INSTITUICAO-EMPRESA',
            'NOME-INSTITUICAO-EMPRESA',
            'CODIGO-ORGAO',
            'NOME-ORGAO',
            'CODIGO-UNIDADE',
            'NOME-UNIDADE',
            'PAIS','UF','CIDADE','BAIRRO','LOGRADOURO','NUMERO','COMPLEMENTO','CEP',
            'CAIXA-POSTAL','DDD','TELEFONE','FAX','RAMAL','HOME-PAGE','E-MAIL'
        ];
        $out = [];
        foreach ($fields as $f) {
            $out[$f] = $el->getAttribute($f) ?: '';
        }
        return $out;
    };

    /** @var DOMElement|null $endProf */
    $endProf = $xp->query('ENDERECO/ENDERECO-PROFISSIONAL', $dg)->item(0);
    /** @var DOMElement|null $endRes */
    $endRes  = $xp->query('ENDERECO/ENDERECO-RESIDENCIAL', $dg)->item(0);

    // IDIOMAS
    $idiomas = [];
    foreach ($xp->query('IDIOMAS/IDIOMA', $dg) as $i) {
        /** @var DOMElement $i */
        $idiomas[] = [
            'idioma'        => $i->getAttribute('IDIOMA') ?: $i->getAttribute('DESCRICAO-DO-IDIOMA'),
            'leitura'       => $i->getAttribute('PROFICIENCIA-DE-LEITURA'),
            'fala'          => $i->getAttribute('PROFICIENCIA-DE-FALA'),
            'escrita'       => $i->getAttribute('PROFICIENCIA-DE-ESCRITA'),
            'compreensao'   => $i->getAttribute('PROFICIENCIA-DE-COMPREENSAO') // alguns XMLs usam "COMPREENSAO"
                               ?: $i->getAttribute('PROFICIENCIA-DE-COMPREENSÃO'),
        ];
    }

    // ÁREAS DE ATUAÇÃO
    $areasAtuacao = [];
    foreach ($xp->query('AREAS-DE-ATUACAO/AREA-DE-ATUACAO', $dg) as $a) {
        /** @var DOMElement $a */
        $areasAtuacao[] = [
            'grande_area'   => $a->getAttribute('NOME-GRANDE-AREA-DO-CONHECIMENTO')
                              ?: $a->getAttribute('GRANDE-AREA-DO-CONHECIMENTO'),
            'area'          => $a->getAttribute('NOME-DA-AREA-DO-CONHECIMENTO')
                              ?: $a->getAttribute('AREA-DO-CONHECIMENTO'),
            'subarea'       => $a->getAttribute('NOME-DA-SUB-AREA-DO-CONHECIMENTO')
                              ?: $a->getAttribute('SUB-AREA-DO-CONHECIMENTO'),
            'especialidade' => $a->getAttribute('NOME-DA-ESPECIALIDADE')
                              ?: $a->getAttribute('ESPECIALIDADE'),
        ];
    }

    // PRÊMIOS/TÍTULOS
    $premios = [];
    foreach ($xp->query('PREMIOS-TITULOS/PREMIO-TITULO', $dg) as $p) {
        /** @var DOMElement $p */
        $premios[] = [
            'nome'        => $p->getAttribute('NOME-DO-PREMIO-OU-TITULO'),
            'ano'         => $p->getAttribute('ANO'),
            'instituicao' => $p->getAttribute('NOME-DA-INSTITUICAO'),
            'pais'        => $p->getAttribute('PAIS'),
        ];
    }

    // LICENÇAS
    $licencas = [];
    foreach ($xp->query('LICENCAS/LICENCA', $dg) as $l) {
        /** @var DOMElement $l */
        $licencas[] = [
            'ano_inicio' => $l->getAttribute('ANO-INICIO'),
            'ano_fim'    => $l->getAttribute('ANO-FIM'),
            'descricao'  => $l->getAttribute('DESCRICAO'),
        ];
    }

    // Monta o pacote final
    return [
        'type'                    => 'dados-gerais',
        'nome_completo'           => $get('NOME-COMPLETO'),
        'citacoes_bibliograficas' => $get('NOME-EM-CITACOES-BIBLIOGRAFICAS'),
        'nacionalidade'           => $get('NACIONALIDADE'),
        'cpf'                     => $get('CPF'),
        'passaporte'              => $get('NUMERO-DO-PASSAPORTE'),
        'pais_nascimento'         => $get('PAIS-DE-NASCIMENTO'),
        'uf_nascimento'           => $get('UF-NASCIMENTO'),
        'cidade_nascimento'       => $get('CIDADE-NASCIMENTO'),
        'resumo_cv'               => $resumo,
        'outras_informacoes'      => $outras,
        'endereco_profissional'   => $getAddr($endProf),
        'idiomas'                 => $idiomas,
        'areas_de_atuacao'        => $areasAtuacao,
        'premios_titulos'         => $premios,
        'licencas'                => $licencas,
    ];
}


/********************************************************* TRABALOS EVENTOS */
function lattes_trabalhos_eventos(DOMXPath $xp): array
{
    // Nó clássico no Lattes:
    // /CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS/TRABALHO-EM-EVENTOS
    $nTrab = $xp->query('//PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS/TRABALHO-EM-EVENTOS');

    $trabalhos = [];
    foreach ($nTrab as $trab) {
        /** @var DOMElement $trab */
        $dados = $xp->query('DADOS-BASICOS-DO-TRABALHO', $trab)->item(0);
        $det   = $xp->query('DETALHAMENTO-DO-TRABALHO',   $trab)->item(0);

        // Helper seguro p/ atributos
        $get = fn(?DOMElement $el, string $attr) => $el ? $el->getAttribute($attr) : '';

        // Autores, palavras-chave e áreas (suas helpers)
        $autores  = lattes_authors($xp, $trab);
        $keywords = lattes_keywords($xp, $trab);
        $areas    = lattes_areas($xp, $trab);

        $trabalhos[] = [
            'type'        => 'eventos',
            // DADOS-BASICOS-DO-TRABALHO
            'titulo'      => $get($dados, 'TITULO-DO-TRABALHO'),
            'natureza'    => $get($dados, 'NATUREZA'),                  // ex.: COMPLETO, RESUMO
            'ano'         => $get($dados, 'ANO-DO-TRABALHO'),
            'doi'         => $get($dados, 'DOI'),
            'idioma'      => $get($dados, 'IDIOMA'),
            'pais_evento' => $get($dados, 'PAIS-DO-EVENTO') ?: $get($dados, 'PAIS-DE-PUBLICACAO'),
            'meio'        => $get($dados, 'MEIO-DE-DIVULGACAO'),        // se existir

            // DETALHAMENTO-DO-TRABALHO
            'evento'          => $get($det, 'NOME-DO-EVENTO'),
            'ano_realizacao'  => $get($det, 'ANO-DE-REALIZACAO'),
            'cidade_evento'   => $get($det, 'CIDADE-DO-EVENTO'),
            'titulo_anais'    => $get($det, 'TITULO-DOS-ANAIS-OU-PROCEEDINGS'),
            'editora'         => $get($det, 'EDITORA'),
            'volume'          => $get($det, 'VOLUME'),
            'serie'           => $get($det, 'SERIE'),
            'fasciculo'       => $get($det, 'FASCICULO'),
            'pag_ini'         => $get($det, 'PAGINA-INICIAL'),
            'pag_fim'         => $get($det, 'PAGINA-FINAL'),
            'issn'            => $get($det, 'ISSN'),                    // às vezes presente
            'isbn'            => $get($det, 'ISBN'),                    // às vezes presente

            // listas
            'autores'   => $autores,
            'keywords'  => $keywords,
            'areas'     => $areas,
        ];
    }

    return $trabalhos;
}


function lattes_livros_capitulos(DOMXPath $xp): array
{
    $itens = [];

    // helpers p/ atributos (com fallback de nomes)
    $get = fn(?DOMElement $el, string $attr) => $el ? $el->getAttribute($attr) : '';
    $ga  = function (?DOMElement $el, array $names) use ($get) {
        foreach ($names as $n) {
            $v = $get($el, $n);
            if ($v !== '') return $v;
        }
        return '';
    };

    // ==============================
    // LIVROS PUBLICADOS/ORGANIZADOS
    // ==============================
    $nLivros = $xp->query(
        '//PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS/LIVRO-PUBLICADO-OU-ORGANIZADO'
    );

    foreach ($nLivros as $n) {
        /** @var DOMElement $n */
        $dados = $xp->query('DADOS-BASICOS-DO-LIVRO', $n)->item(0);
        $det   = $xp->query('DETALHAMENTO-DO-LIVRO',   $n)->item(0);

        $autores  = lattes_authors($xp, $n);
        $keywords = lattes_keywords($xp, $n);
        $areas    = lattes_areas($xp, $n);

        $itens[] = [
            'type'       => 'livro',
            // DADOS-BASICOS-DO-LIVRO
            'titulo'     => $ga($dados, ['TITULO-DO-LIVRO', 'TITULO']),
            'natureza'   => $ga($dados, ['NATUREZA']),
            'ano'        => $ga($dados, ['ANO', 'ANO-DO-LIVRO']),
            'doi'        => $ga($dados, ['DOI']),
            'idioma'     => $ga($dados, ['IDIOMA']),
            'pais'       => $ga($dados, ['PAIS-DE-PUBLICACAO', 'PAIS']),
            // DETALHAMENTO-DO-LIVRO
            'isbn'       => $ga($det,   ['ISBN']),
            'editora'    => $ga($det,   ['EDITORA']),
            'cidade'     => $ga($det,   ['CIDADE-DA-EDITORA', 'CIDADE-DA-EDITORA-DA-PUBLICACAO']),
            'edicao'     => $ga($det,   ['NUMERO-DA-EDICAO-REVISAO', 'NUMERO-DA-EDICAO']),
            'num_paginas'=> $ga($det,   ['NUMERO-DE-PAGINAS']),
            // listas
            'autores'    => $autores,
            'keywords'   => $keywords,
            'areas'      => $areas,
        ];
    }

    // =========================
    // CAPÍTULOS DE LIVRO
    // =========================
    $nCaps = $xp->query(
        '//PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/CAPITULOS-DE-LIVROS-PUBLICADOS/CAPITULO-DE-LIVRO-PUBLICADO'
    );

    foreach ($nCaps as $n) {
        /** @var DOMElement $n */
        $dados = $xp->query('DADOS-BASICOS-DO-CAPITULO', $n)->item(0);
        $det   = $xp->query('DETALHAMENTO-DO-CAPITULO',  $n)->item(0);

        $autores  = lattes_authors($xp, $n);
        $keywords = lattes_keywords($xp, $n);
        $areas    = lattes_areas($xp, $n);

        $itens[] = [
            'type'         => 'capitulo',
            // DADOS-BASICOS-DO-CAPITULO
            'titulo'       => $ga($dados, ['TITULO-DO-CAPITULO', 'TITULO']),
            'natureza'     => $ga($dados, ['NATUREZA']),
            'ano'          => $ga($dados, ['ANO', 'ANO-DO-CAPITULO']),
            'doi'          => $ga($dados, ['DOI']),
            'idioma'       => $ga($dados, ['IDIOMA']),
            'pais'         => $ga($dados, ['PAIS-DE-PUBLICACAO', 'PAIS']),
            // DETALHAMENTO-DO-CAPITULO
            'livro_titulo' => $ga($det,   ['TITULO-DO-LIVRO']),
            'isbn'         => $ga($det,   ['ISBN']),
            'editora'      => $ga($det,   ['EDITORA']),
            'cidade'       => $ga($det,   ['CIDADE-DA-EDITORA', 'CIDADE-DA-EDITORA-DA-PUBLICACAO']),
            'pag_ini'      => $ga($det,   ['PAGINA-INICIAL']),
            'pag_fim'      => $ga($det,   ['PAGINA-FINAL']),
            'num_paginas'  => $ga($det,   ['NUMERO-DE-PAGINAS']),
            // listas
            'autores'      => $autores,
            'keywords'     => $keywords,
            'areas'        => $areas,
        ];
    }

    return $itens;
}
/****************************************** Textos em Jornais ou Revistas */
function lattes_textos(DOMXPath $xp): array
{
    // nó dos textos de jornal/revista
    $nTextos = $xp->query('//PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS/TEXTO-EM-JORNAL-OU-REVISTA');
    $textos = [];

    foreach ($nTextos as $tx) {
        /** @var DOMElement $tx */
        $dados = $xp->query('DADOS-BASICOS-DO-TEXTO', $tx)->item(0);
        $det   = $xp->query('DETALHAMENTO-DO-TEXTO',   $tx)->item(0);

        // evita notices quando algum bloco não existir
        $get = fn(?DOMElement $el, string $attr) => $el ? $el->getAttribute($attr) : '';

        // autores / palavras-chave / áreas (mesma lógica dos artigos)
        $autores  = lattes_authors($xp, $tx);
        $keywords = lattes_keywords($xp, $tx);
        $areas    = lattes_areas($xp, $tx);

        $textos[] = [
            // DADOS-BASICOS-DO-TEXTO
            'type'      => 'texto',
            'titulo'    => $get($dados, 'TITULO-DO-TEXTO'),
            'natureza'  => $get($dados, 'NATUREZA'),
            'ano'       => $get($dados, 'ANO-DO-TEXTO'),
            'doi'       => $get($dados, 'DOI'),
            'idioma'    => $get($dados, 'IDIOMA'),
            'pais'      => $get($dados, 'PAIS-DE-PUBLICACAO'),

            // DETALHAMENTO-DO-TEXTO
            'veiculo'   => $get($det, 'TITULO-DO-JORNAL-OU-REVISTA'),
            'local'     => $get($det, 'LOCAL-DE-PUBLICACAO'),
            'issn'      => $get($det, 'ISSN'),
            'volume'    => $get($det, 'VOLUME'),
            'fasciculo' => $get($det, 'FASCICULO'),
            'pag_ini'   => $get($det, 'PAGINA-INICIAL'),
            'pag_fim'   => $get($det, 'PAGINA-FINAL'),

            // relacionados
            'autores'   => $autores,
            'keywords'  => $keywords,
            'areas'     => $areas,
        ];
    }

    return $textos;
}
/******************************************* Outras Publicações */
function lattes_demais_biblio(DOMXPath $xp): array
{
    $items = [];

    // mapeamento dos subtipos e dos nós de dados básicos/detalhamento
    $map = [
        [
            'tag'    => 'OUTRA-PRODUCAO-BIBLIOGRAFICA',
            'basic'  => 'DADOS-BASICOS-DE-OUTRA-PRODUCAO',
            'detail' => 'DETALHAMENTO-DE-OUTRA-PRODUCAO',
        ],
        [
            'tag'    => 'PARTITURA-MUSICAL',
            'basic'  => 'DADOS-BASICOS-DA-PARTITURA',
            'detail' => 'DETALHAMENTO-DA-PARTITURA',
        ],
        [
            'tag'    => 'PREFACIO-POSFACIO',
            'basic'  => 'DADOS-BASICOS-DO-PREFACIO-POSFACIO',
            'detail' => 'DETALHAMENTO-DO-PREFACIO-POSFACIO',
        ],
        [
            'tag'    => 'TRADUCAO',
            'basic'  => 'DADOS-BASICOS-DA-TRADUCAO',
            'detail' => 'DETALHAMENTO-DA-TRADUCAO',
        ],
    ];

    // helpers
    $get = fn(?DOMElement $el, string $attr) => $el ? $el->getAttribute($attr) : '';

    $firstAttrStartsWith = function (?DOMElement $el, array $prefixes): string {
        if (!$el || !$el->hasAttributes()) return '';
        /** @var DOMAttr $attr */
        foreach ($el->attributes as $attr) {
            foreach ($prefixes as $p) {
                if (strpos($attr->name, $p) === 0) {
                    return (string)$attr->value;
                }
            }
        }
        return '';
    };

    foreach ($map as $cfg) {
        $nodes = $xp->query("//PRODUCAO-BIBLIOGRAFICA/DEMAIS-TIPOS-DE-PRODUCAO-BIBLIOGRAFICA/{$cfg['tag']}");
        if (!$nodes || $nodes->length === 0) continue;

        foreach ($nodes as $n) {
            /** @var DOMElement $n */
            $dados = $xp->query($cfg['basic'],  $n)->item(0);
            $det   = $xp->query($cfg['detail'], $n)->item(0);

            // título: pega o primeiro atributo cujo nome começa com "TITULO"
            $titulo = $firstAttrStartsWith($dados, ['TITULO'])
                   ?: $firstAttrStartsWith($det,   ['TITULO']);

            // ano: tenta "ANO" / "ANO-DO-*" / "ANO-DA-*"
            $ano = $firstAttrStartsWith($dados, ['ANO', 'ANO-DO', 'ANO-DA'])
                ?: $firstAttrStartsWith($det,   ['ANO', 'ANO-DO', 'ANO-DA']);

            // DOI/ISSN/ISBN se existirem em qualquer um dos blocos
            $doi  = $get($dados, 'DOI')  ?: $get($det, 'DOI');
            $issn = $get($det,   'ISSN') ?: $get($dados, 'ISSN');
            $isbn = $get($det,   'ISBN') ?: $get($dados, 'ISBN');

            // demais campos comuns
            $idioma = $get($dados, 'IDIOMA');
            $pais   = $get($dados, 'PAIS-DE-PUBLICACAO') ?: $get($det, 'PAIS-DE-PUBLICACAO');
            $natureza = $get($dados, 'NATUREZA');

            // às vezes aparecem em detalhamento
            $local     = $get($det, 'LOCAL-DE-PUBLICACAO');
            $volume    = $get($det, 'VOLUME');
            $fasciculo = $get($det, 'FASCICULO');
            $pag_ini   = $get($det, 'PAGINA-INICIAL');
            $pag_fim   = $get($det, 'PAGINA-FINAL');

            // específicos de “outras produções” com frequência
            $meio = $get($dados, 'MEIO-DE-DIVULGACAO');

            // listas auxiliares
            $autores  = function_exists('lattes_authors')  ? lattes_authors($xp, $n)  : [];
            $keywords = function_exists('lattes_keywords') ? lattes_keywords($xp, $n) : [];
            $areas    = function_exists('lattes_areas')    ? lattes_areas($xp, $n)    : [];

            $items[] = [
                'type'       => 'demais-bibliografica',
                'subtipo'    => $n->tagName,  // OUTRA-PRODUCAO-BIBLIOGRAFICA | PARTITURA-MUSICAL | ...
                'titulo'     => $titulo,
                'natureza'   => $natureza,
                'ano'        => $ano,
                'doi'        => $doi,
                'issn'       => $issn,
                'isbn'       => $isbn,
                'idioma'     => $idioma,
                'pais'       => $pais,
                'local'      => $local,
                'meio'       => $meio,
                'volume'     => $volume,
                'fasciculo'  => $fasciculo,
                'pag_ini'    => $pag_ini,
                'pag_fim'    => $pag_fim,
                'autores'    => $autores,
                'keywords'   => $keywords,
                'areas'      => $areas,
                // você pode incluir aqui outros atributos específicos que seu XML trouxer
            ];
        }
    }

    return $items;
}
/************************************************************************ Producao tecnicao */
function lattes_producao_tecnica(DOMXPath $xp): array
{
    $items = [];

    // helper: pega o primeiro atributo existente na ordem dada
    $firstAttr = function (?DOMElement $el, array $names): string {
        if (!$el) return '';
        foreach ($names as $n) {
            if ($el->hasAttribute($n)) return $el->getAttribute($n);
        }
        return '';
    };

    // helper: coleta todos os atributos do nó (útil pra depuração/armazenar bruto)
    $attrs = function (?DOMElement $el): array {
        if (!$el) return [];
        $out = [];
        foreach ($el->attributes as $a) {
            /** @var DOMAttr $a */
            $out[$a->name] = $a->value;
        }
        return $out;
    };

    // Mapeamento por subtipo: XPaths + nomes dos blocos de dados/detalhamento e campos comuns
    $map = [
        // raiz direta


        // DEMAIS-TIPOS-DE-PRODUCAO-TECNICA
        'APRESENTACAO-DE-TRABALHO' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA/APRESENTACAO-DE-TRABALHO',
            'dados' => 'DADOS-BASICOS-DA-APRESENTACAO-DE-TRABALHO',
            'det'   => 'DETALHAMENTO-DA-APRESENTACAO-DE-TRABALHO',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE-DA-APRESENTACAO'],
            'veic'  => ['NOME-DO-EVENTO'],
        ],
        'RELATORIO-DE-PESQUISA' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA/RELATORIO-DE-PESQUISA',
            'dados' => 'DADOS-BASICOS-DO-RELATORIO-DE-PESQUISA',
            'det'   => 'DETALHAMENTO-DO-RELATORIO-DE-PESQUISA',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE-DA-INSTITUICAO', 'CIDADE'], // variações
            'veic'  => ['INSTITUICAO-FINANCIADORA', 'INSTITUICAO-PROMOTORA'],
        ],
        'ORGANIZACAO-DE-EVENTO' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA/ORGANIZACAO-DE-EVENTO',
            'dados' => 'DADOS-BASICOS-DA-ORGANIZACAO-DE-EVENTO',
            'det'   => 'DETALHAMENTO-DA-ORGANIZACAO-DE-EVENTO',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE-DO-EVENTO', 'CIDADE'],
            'veic'  => ['NOME-DO-EVENTO'],
        ],
        'PROGRAMA-DE-RADIO-OU-TV' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA/PROGRAMA-DE-RADIO-OU-TV',
            'dados' => 'DADOS-BASICOS-DO-PROGRAMA-DE-RADIO-OU-TV',
            'det'   => 'DETALHAMENTO-DO-PROGRAMA-DE-RADIO-OU-TV',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE-DA-EMISSORA', 'CIDADE'],
            'veic'  => ['NOME-DA-EMISSORA'],
        ],
        'MIDIA-SOCIAL-WEBSITE-BLOG' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA/MIDIA-SOCIAL-WEBSITE-BLOG',
            'dados' => 'DADOS-BASICOS-DA-MIDIA-SOCIAL-WEBSITE-BLOG',
            'det'   => 'DETALHAMENTO-DA-MIDIA-SOCIAL-WEBSITE-BLOG',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['NOME-DO-CANAL', 'PLATAFORMA'],
        ],

        // Exemplos prontos para habilitar (se houver no seu XML):
        'SOFTWARE' => [
             'xpath' => '//PRODUCAO-TECNICA/SOFTWARE',
             'dados' => 'DADOS-BASICOS-DO-SOFTWARE',
             'det'   => 'DETALHAMENTO-DO-SOFTWARE',
             'title' => ['TITULO-DO-SOFTWARE','TITULO'],
             'year'  => ['ANO'],
             'local' => ['CIDADE-DA-INSTITUICAO','CIDADE'],
             'veic'  => ['INSTITUICAO-PROMOTORA'],
         ],
         'PATENTE' => [
             'xpath' => '//PRODUCAO-TECNICA/PATENTE',
             'dados' => 'DADOS-BASICOS-DA-PATENTE',
             'det'   => 'DETALHAMENTO-DA-PATENTE',
             'title' => ['TITULO'],
             'year'  => ['ANO'],
             'local' => ['CIDADE'],
             'veic'  => ['INSTITUICAO-DE-REGISTRO'],
         ],
        'CULTIVAR-REGISTRADA' => [
            'xpath' => '//PRODUCAO-TECNICA/CULTIVAR-REGISTRADA',
            'dados' => 'DADOS-BASICOS-DA-CULTIVAR',
            'det'   => 'DETALHAMENTO-DA-CULTIVAR',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'CULTIVAR-REGISTRADA' => [
            'xpath' => '//PRODUCAO-TECNICA/CULTIVAR-REGISTRADA',
            'dados' => 'DADOS-BASICOS-DA-CULTIVAR',
            'det'   => 'DETALHAMENTO-DA-CULTIVAR',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'CULTIVAR-PROTEGIDA' => [
            'xpath' => '//PRODUCAO-TECNICA/CULTIVAR-PROTEGIDA',
            'dados' => 'DADOS-BASICOS-DA-CULTIVAR',
            'det'   => 'DETALHAMENTO-DA-CULTIVAR',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'DESENHO-INDUSTRIAL' => [
            'xpath' => '//PRODUCAO-TECNICA/DESENHO-INDUSTRIAL',
            'dados' => 'DADOS-BASICOS-DO-DESENHO-INDUSTRIAL',
            'det'   => 'DETALHAMENTO-DO-DESENHO-INDUSTRIAL',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'MARCA' => [
            'xpath' => '//PRODUCAO-TECNICA/MARCA',
            'dados' => 'DADOS-BASICOS-DA-MARCA',
            'det'   => 'DETALHAMENTO-DA-MARCA',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'TOPOGRAFIA-DE-CIRCUITO-INTEGRADO' => [
            'xpath' => '//PRODUCAO-TECNICA/TOPOGRAFIA-DE-CIRCUITO-INTEGRADO',
            'dados' => 'DADOS-BASICOS-DA-TOPOGRAFIA-DE-CIRCUITO-INTEGRADO',
            'det'   => 'DETALHAMENTO-DA-TOPOGRAFIA-DE-CIRCUITO-INTEGRADO',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'TRABALHO-TECNICO' => [
            'xpath' => '//PRODUCAO-TECNICA/TRABALHO-TECNICO',
            'dados' => 'DADOS-BASICOS-DO-TRABALHO-TECNICO',
            'det'   => 'DETALHAMENTO-DO-TRABALHO-TECNICO',
            'title' => ['TITULO-DO-TRABALHO-TECNICO', 'TITULO'],
            'year'  => ['ANO', 'ANO-DO-TRABALHO'],
            'local' => ['CIDADE-DO-TRABALHO'],
            'veic'  => ['INSTITUICAO-PROMOTORA'], // se houver
        ],
        'PRODUTO-TECNOLOGICO' => [
            'xpath' => '//PRODUCAO-TECNICA/PRODUTO-TECNOLOGICO',
            'dados' => 'DADOS-BASICOS-DO-PRODUTO-TECNOLOGICO',
            'det'   => 'DETALHAMENTO-DO-PRODUTO-TECNOLOGICO',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'PROCESSOS-OU-TECNICAS' => [
            'xpath' => '//PRODUCAO-TECNICA/PROCESSOS-OU-TECNICAS',
            'dados' => 'DADOS-BASICOS-DO-PROCESSOS-OU-TECNICAS',
            'det'   => 'DETALHAMENTO-DO-PROCESSOS-OU-TECNICAS',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],
        'DEMAIS-TIPOS-DE-PRODUCAO-TECNICA' => [
            'xpath' => '//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA',
            'dados' => 'DADOS-BASICOS-DO-DEMAIS-TIPOS-DE-PRODUCAO-TECNICA',
            'det'   => 'DETALHAMENTO-DO-DEMAIS-TIPOS-DE-PRODUCAO-TECNICA',
            'title' => ['TITULO'],
            'year'  => ['ANO'],
            'local' => ['CIDADE'],
            'veic'  => ['INSTITUICAO-DE-REGISTRO'],
        ],

    ];

    foreach ($map as $subtipo => $cfg) {
        $nodes = $xp->query($cfg['xpath']);
        foreach ($nodes as $n) {
            /** @var DOMElement $n */
            $dados = $xp->query($cfg['dados'], $n)->item(0);
            $det   = $xp->query($cfg['det'],   $n)->item(0);

            // campos comuns (com fallback de nomes)
            $titulo   = $firstAttr($dados, $cfg['title']);
            $ano      = $firstAttr($dados, $cfg['year'] ?? ['ANO', 'ANO-DO-TRABALHO']);
            $natureza = $firstAttr($dados, ['NATUREZA']);
            $doi      = $firstAttr($dados, ['DOI']);
            $pais     = $firstAttr($dados, ['PAIS', 'PAIS-DE-PUBLICACAO']);
            $idioma   = $firstAttr($dados, ['IDIOMA']);

            $local    = $firstAttr($det,   $cfg['local'] ?? ['CIDADE']);
            $veiculo  = $firstAttr($det,   $cfg['veic']  ?? []);

            // seus helpers
            $autores  = function_exists('lattes_authors')  ? lattes_authors($xp, $n)  : [];
            $keywords = function_exists('lattes_keywords') ? lattes_keywords($xp, $n) : [];
            $areas    = function_exists('lattes_areas')    ? lattes_areas($xp, $n)    : [];

            $items[] = [
                'type'       => 'producao-tecnica',
                'subtipo'    => $subtipo,             // p.ex. TRABALHO-TECNICO, APRESENTACAO-DE-TRABALHO...
                'titulo'     => $titulo,
                'natureza'   => $natureza,
                'ano'        => $ano,
                'doi'        => $doi,
                'pais'       => $pais,
                'idioma'     => $idioma,
                'local'      => $local,
                'veiculo'    => $veiculo,             // evento, emissora, instituição etc.
                'autores'    => $autores,
                'keywords'   => $keywords,
                'areas'      => $areas,

                // (opcional) atributos crus para conferência/depuração
                'dados_raw'  => $attrs($dados),
                'detalhe_raw'=> $attrs($det),
            ];
        }
    }

    return $items;
}
