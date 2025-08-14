"""
lattes_helper.py — Port Python 3 da biblioteca de parsing de Currículo Lattes.

Requisitos:
    - Python 3.9+
    - lxml (pip install lxml)

API principal:
    extrair_dados(xml_path: str, id: str | None = None) -> dict

As funções abaixo espelham aproximadamente as funções do original em PHP:
    - lattes_dados_gerais
    - lattes_formacao
    - lattes_artigos
    - lattes_trabalhos_eventos
    - lattes_livros_capitulos
    - lattes_textos
    - lattes_demais_biblio
    - lattes_producao_tecnica
    - lattes_artistica
    - lattes_authors
    - lattes_keywords
    - lattes_areas

Observação:
    O XML do Lattes costuma vir em ISO-8859-1; o lxml respeita o encoding
    declarado no próprio XML. Se necessário, converta previamente.
"""
from __future__ import annotations

from dataclasses import dataclass, asdict
from typing import Any, Dict, List, Optional, Iterable
from lxml import etree

# ====================== Helpers genéricos ======================


def _parse(xml_path: str) -> etree._ElementTree:
    """Carrega o XML no lxml com erros internos suprimidos."""
    parser = etree.XMLParser(recover=True, encoding=None, huge_tree=True)
    with open(xml_path, "rb") as f:
        data = f.read()
    return etree.fromstring(
        data, parser=parser)  # returns Element; wrap for xpath root


def _attrs(el: Optional[etree._Element]) -> Dict[str, str]:
    if el is None:
        return {}
    return {k: v for k, v in el.attrib.items()}


def _get(el: Optional[etree._Element], attr: str, default: str = "") -> str:
    return el.get(attr) if (el is not None and attr in el.attrib) else default


def _first_attr(el: Optional[etree._Element], names: Iterable[str]) -> str:
    if el is None:
        return ""
    for n in names:
        if n in el.attrib:
            return el.attrib[n]
    return ""


def _first_attr_startswith(el: Optional[etree._Element],
                           prefixes: Iterable[str]) -> str:
    if el is None:
        return ""
    for k, v in el.attrib.items():
        for p in prefixes:
            if k.startswith(p):
                return v
    return ""


def _first_child_by_prefix(ctx: etree._Element,
                           prefix: str) -> Optional[etree._Element]:
    # *[starts-with(local-name(), 'DADOS-BASICOS')]
    res = ctx.xpath(f'./*[starts-with(local-name(), "{prefix}")]')
    return res[0] if res else None


def _xpath(ctx: etree._Element, path: str) -> List[etree._Element]:
    res = ctx.xpath(path)
    return list(res) if res is not None else []


# ====================== Autores / Palavras / Áreas ======================


def lattes_authors(ctx: etree._Element,
                   node: etree._Element) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    for a in _xpath(node, './AUTORES'):
        out.append({
            "ordem":
            _get(a, "ORDEM-DE-AUTORIA"),
            "nome":
            _get(a, "NOME-COMPLETO-DO-AUTOR") or _get(a, "NOME-PARA-CITACAO"),
            "idCNPq":
            _get(a, "NRO-ID-CNPQ"),
        })
    return out


def lattes_keywords(ctx: etree._Element, node: etree._Element) -> List[str]:
    out: List[str] = []
    for a in _xpath(node, './PALAVRAS-CHAVE'):
        for r in range(1, 7):
            k = _get(a, f"PALAVRA-CHAVE-{r}")
            if k:
                out.append(k)
    return out


def lattes_areas(ctx: etree._Element, node: etree._Element) -> List[str]:
    areas: List[str] = []
    for a in _xpath(node, './AREAS-DO-CONHECIMENTO/AREA-DO-CONHECIMENTO-1'):
        areas.extend([
            _get(a, "NOME-GRANDE-AREA-DO-CONHECIMENTO"),
            _get(a, "NOME-DA-AREA-DO-CONHECIMENTO"),
            _get(a, "NOME-DA-SUB-AREA-DO-CONHECIMENTO"),
            _get(a, "NOME-DA-ESPECIALIDADE"),
        ])
    return [x for x in areas if x]


# ====================== Dados gerais ======================


def lattes_dados_gerais(root: etree._Element) -> Dict[str, Any]:
    dg = _xpath(root, '//DADOS-GERAIS')
    if not dg:
        return {}
    dg = dg[0]

    resumo_node = _xpath(dg, './RESUMO-CV')
    resumo = ""
    if resumo_node:
        resumo_el = resumo_node[0]
        # O texto pode vir no atributo TEXTO-RESUMO-CV-RH ou TEXTO-RESUMO-CV
        resumo = (_get(resumo_el, "TEXTO-RESUMO-CV-RH")
                  or _get(resumo_el, "TEXTO-RESUMO-CV") or "")

    def _endereco(el: Optional[etree._Element]) -> Dict[str, str]:
        if el is None:
            return {}
        fields = [
            "CEP",
            "CAIXA-POSTAL",
            "BAIRRO",
            "CIDADE",
            "UF",
            "PAIS",
            "DDD",
            "TELEFONE",
            "NUMERO",
            "LOGRADOURO",
            "COMPLEMENTO",
            "RAMAL",
            "HOME-PAGE",
            "E-MAIL",
        ]
        return {f: _get(el, f) for f in fields}

    end_prof = _xpath(dg, './ENDERECO/ENDERECO-PROFISSIONAL')
    end_res = _xpath(dg, './ENDERECO/ENDERECO-RESIDENCIAL')

    idiomas = []
    for idm in _xpath(dg, './IDIOMAS/IDIOMA'):
        idiomas.append({
            "idioma": _get(idm, "IDIOMA"),
            "proficiencia": {
                "compreende": _get(idm, "PROFICIENCIA-DE-LEITURA"),
                "fala": _get(idm, "PROFICIENCIA-DE-FALA"),
                "escreve": _get(idm, "PROFICIENCIA-DE-ESCRITA"),
                "le": _get(idm, "PROFICIENCIA-DE-LEITURA"),
            }
        })

    areas_atu = []
    for a in _xpath(dg, './AREAS-DE-ATUACAO/AREA-DE-ATUACAO'):
        areas_atu.append({k: _get(a, k) for k in a.attrib.keys()})

    return {
        "nome": _get(dg, "NOME-COMPLETO"),
        "nome_citacao": _get(dg, "NOME-EM-CITACOES-BIBLIOGRAFICAS"),
        "orcid": _get(dg, "ORCID-ID"),
        "resumo": resumo,
        "endereco_profissional": _endereco(end_prof[0] if end_prof else None),
        "endereco_residencial": _endereco(end_res[0] if end_res else None),
        "idiomas": idiomas,
        "areas_de_atuacao": areas_atu,
    }


# ====================== Formação ======================


def lattes_formacao(root: etree._Element) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    nodes = _xpath(root, '//DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/*')

    for n in nodes:
        tipo = etree.QName(n).localname  # MESTRADO, DOUTORADO, GRADUACAO...
        dados = _first_child_by_prefix(n, "DADOS-BASICOS")
        detalhe = _first_child_by_prefix(n, "DETALHAMENTO")

        dadosA = _attrs(dados)
        detalheA = _attrs(detalhe)
        nodeA = _attrs(n)

        nome_curso = dadosA.get("NOME-DO-CURSO") or detalheA.get(
            "NOME-DO-CURSO", "")
        instituicao = detalheA.get("NOME-INSTITUICAO") or dadosA.get(
            "NOME-INSTITUICAO", "")
        ini = dadosA.get("ANO-DE-INICIO") or detalheA.get("ANO-DE-INICIO", "")
        fim = dadosA.get("ANO-DE-CONCLUSAO") or detalheA.get(
            "ANO-DE-CONCLUSAO", "")
        status = dadosA.get("STATUS-DO-CURSO") or detalheA.get(
            "STATUS-DO-CURSO", "")

        out.append({
            "type": tipo.lower(),
            "nome_curso": nome_curso,
            "instituicao": instituicao,
            "ano_inicio": ini,
            "ano_conclusao": fim,
            "status": status,
            "sequencia": nodeA.get("SEQUENCIA-FORMACAO", ""),
            "attrs_item": nodeA,
            "dados_basicos": dadosA,
            "detalhamento": detalheA,
            "autores": [],  # não se aplica, mantido por compatibilidade
            "keywords": [],
            "areas": [],
        })
    return out


# ====================== Artigos ======================


def lattes_artigos(root: etree._Element) -> List[Dict[str, Any]]:
    artigos: List[Dict[str, Any]] = []
    for art in _xpath(
            root,
            '//PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS/ARTIGO-PUBLICADO'):
        dados = _xpath(art, './DADOS-BASICOS-DO-ARTIGO')
        det = _xpath(art, './DETALHAMENTO-DO-ARTIGO')
        dados = dados[0] if dados else None
        det = det[0] if det else None

        autores = lattes_authors(root, art)
        keywords = lattes_keywords(root, art)
        areas = lattes_areas(root, art)

        artigos.append({
            "type": "artigo",
            "titulo": _get(dados, "TITULO-DO-ARTIGO"),
            "natureza": _get(dados, "NATUREZA"),
            "ano": _get(dados, "ANO-DO-ARTIGO"),
            "doi": _get(dados, "DOI"),
            "idioma": _get(dados, "IDIOMA"),
            "pais": _get(dados, "PAIS-DE-PUBLICACAO"),
            "veiculo": _get(det, "TITULO-DO-PERIODICO-OU-REVISTA"),
            "issn": _get(det, "ISSN"),
            "volume": _get(det, "VOLUME"),
            "fasciculo": _get(det, "FASCICULO"),
            "pag_ini": _get(det, "PAGINA-INICIAL"),
            "pag_fim": _get(det, "PAGINA-FINAL"),
            "autores": autores,
            "keywords": keywords,
            "areas": areas,
            "dados_raw": _attrs(dados),
            "detalhe_raw": _attrs(det),
        })
    return artigos


# ====================== Trabalhos em eventos ======================


def lattes_trabalhos_eventos(root: etree._Element) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    for trab in _xpath(
            root,
            '//PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS/TRABALHO-EM-EVENTOS'
    ):
        dados = _xpath(trab, './DADOS-BASICOS-DO-TRABALHO')
        det = _xpath(trab, './DETALHAMENTO-DO-TRABALHO')
        dados = dados[0] if dados else None
        det = det[0] if det else None

        autores = lattes_authors(root, trab)
        keywords = lattes_keywords(root, trab)
        areas = lattes_areas(root, trab)

        out.append({
            "type":
            "trabalho-evento",
            "titulo":
            _get(dados, "TITULO-DO-TRABALHO"),
            "natureza":
            _get(dados, "NATUREZA"),
            "ano":
            _get(dados, "ANO-DO-TRABALHO"),
            "doi":
            _get(dados, "DOI"),
            "idioma":
            _get(dados, "IDIOMA"),
            "pais":
            _get(dados, "PAIS-DO-EVENTO") or _get(dados, "PAIS-DE-PUBLICACAO"),
            "veiculo":
            _get(det, "NOME-DO-EVENTO"),
            "local":
            _get(det, "CIDADE-DO-EVENTO"),
            "issn":
            _get(det, "ISSN"),
            "volume":
            _get(det, "VOLUME"),
            "fasciculo":
            _get(det, "FASCICULO"),
            "pag_ini":
            _get(det, "PAGINA-INICIAL"),
            "pag_fim":
            _get(det, "PAGINA-FINAL"),
            "autores":
            autores,
            "keywords":
            keywords,
            "areas":
            areas,
            "dados_raw":
            _attrs(dados),
            "detalhe_raw":
            _attrs(det),
        })
    return out
# ====================== Livros & Capítulos ======================


def lattes_livros_capitulos(root: etree._Element) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []

    # Livros publicados/organizados
    for n in _xpath(
            root,
            '//PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS/LIVRO-PUBLICADO-OU-ORGANIZADO'
    ):
        dados = _xpath(n, './DADOS-BASICOS-DO-LIVRO')
        det = _xpath(n, './DETALHAMENTO-DO-LIVRO')
        dados = dados[0] if dados else None
        det = det[0] if det else None

        out.append({
            "type": "livro",
            "titulo": _get(dados, "TITULO-DO-LIVRO"),
            "natureza": _get(dados, "NATUREZA"),
            "ano": _get(dados, "ANO"),
            "doi": _get(dados, "DOI"),
            "idioma": _get(dados, "IDIOMA"),
            "pais": _get(dados, "PAIS-DE-PUBLICACAO"),
            "local": _get(det, "LOCAL-DE-PUBLICACAO"),
            "editora": _get(det, "NOME-DA-EDITORA"),
            "isbn": _get(det, "ISBN"),
            "num_paginas": _get(det, "NUMERO-DE-PAGINAS"),
            "organizadores": _get(det, "ORGANIZADORES"),
            "dados_raw": _attrs(dados),
            "detalhe_raw": _attrs(det),
        })

    # Capítulos de livros
    for n in _xpath(
            root,
            '//PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/CAPITULOS-DE-LIVROS-PUBLICADOS/CAPITULO-DE-LIVRO-PUBLICADO'
    ):
        dados = _xpath(n, './DADOS-BASICOS-DO-CAPITULO')
        det = _xpath(n, './DETALHAMENTO-DO-CAPITULO')
        dados = dados[0] if dados else None
        det = det[0] if det else None

        out.append({
            "type": "capitulo",
            "titulo": _get(dados, "TITULO-DO-CAPITULO-DO-LIVRO"),
            "natureza": _get(dados, "NATUREZA"),
            "ano": _get(dados, "ANO"),
            "doi": _get(dados, "DOI"),
            "idioma": _get(dados, "IDIOMA"),
            "pais": _get(dados, "PAIS-DE-PUBLICACAO"),
            "livro_titulo": _get(det, "TITULO-DO-LIVRO"),
            "isbn": _get(det, "ISBN"),
            "local": _get(det, "LOCAL-DE-PUBLICACAO"),
            "editora": _get(det, "NOME-DA-EDITORA"),
            "pag_ini": _get(det, "PAGINA-INICIAL"),
            "pag_fim": _get(det, "PAGINA-FINAL"),
            "dados_raw": _attrs(dados),
            "detalhe_raw": _attrs(det),
        })

    return out


# ====================== Textos em jornais/revistas ======================


def lattes_textos(root: etree._Element) -> List[Dict[str, Any]]:
    out: List[Dict[str, Any]] = []
    for tx in _xpath(
            root,
            '//PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS/TEXTO-EM-JORNAL-OU-REVISTA'
    ):
        dados = _xpath(tx, './DADOS-BASICOS-DO-TEXTO')
        det = _xpath(tx, './DETALHAMENTO-DO-TEXTO')
        dados = dados[0] if dados else None
        det = det[0] if det else None

        autores = lattes_authors(root, tx)
        keywords = lattes_keywords(root, tx)
        areas = lattes_areas(root, tx)

        out.append({
            "type": "texto",
            "titulo": _get(dados, "TITULO-DO-TEXTO"),
            "natureza": _get(dados, "NATUREZA"),
            "ano": _get(dados, "ANO-DO-TEXTO"),
            "doi": _get(dados, "DOI"),
            "idioma": _get(dados, "IDIOMA"),
            "pais": _get(dados, "PAIS-DE-PUBLICACAO"),
            "veiculo": _get(det, "TITULO-DO-JORNAL-OU-REVISTA"),
            "local": _get(det, "LOCAL-DE-PUBLICACAO"),
            "issn": _get(det, "ISSN"),
            "volume": _get(det, "VOLUME"),
            "fasciculo": _get(det, "FASCICULO"),
            "pag_ini": _get(det, "PAGINA-INICIAL"),
            "pag_fim": _get(det, "PAGINA-FINAL"),
            "autores": autores,
            "keywords": keywords,
            "areas": areas,
            "dados_raw": _attrs(dados),
            "detalhe_raw": _attrs(det),
        })
    return out
# ====================== Demais produção bibliográfica ======================


def lattes_demais_biblio(root: etree._Element) -> List[Dict[str, Any]]:
    items: List[Dict[str, Any]] = []

    map_ = [
        {
            "tag": "OUTRA-PRODUCAO-BIBLIOGRAFICA",
            "basic": "DADOS-BASICOS-DE-OUTRA-PRODUCAO",
            "detail": "DETALHAMENTO-DE-OUTRA-PRODUCAO",
        },
        {
            "tag": "PARTITURA-MUSICAL",
            "basic": "DADOS-BASICOS-DA-PARTITURA",
            "detail": "DETALHAMENTO-DA-PARTITURA",
        },
        {
            "tag": "PREFACIO-POSFACIO",
            "basic": "DADOS-BASICOS-DO-PREFACIO-POSFACIO",
            "detail": "DETALHAMENTO-DO-PREFACIO-POSFACIO",
        },
        {
            "tag": "TRADUCAO",
            "basic": "DADOS-BASICOS-DA-TRADUCAO",
            "detail": "DETALHAMENTO-DA-TRADUCAO",
        },
    ]

    for m in map_:
        for n in _xpath(
                root,
                f'//PRODUCAO-BIBLIOGRAFICA/DEMAIS-TIPOS-DE-PRODUCAO-BIBLIOGRAFICA/{m["tag"]}'
        ):
            dados = _xpath(n, f'./{m["basic"]}')
            det = _xpath(n, f'./{m["detail"]}')
            dados = dados[0] if dados else None
            det = det[0] if det else None

            titulo = _first_attr_startswith(
                dados, ["TITULO"]) or _first_attr_startswith(det, ["TITULO"])
            ano = _first_attr_startswith(
                dados, ["ANO", "ANO-DO", "ANO-DA"]) or _first_attr_startswith(
                    det, ["ANO", "ANO-DO", "ANO-DA"])

            doi = _get(dados, "DOI") or _get(det, "DOI")
            issn = _get(det, "ISSN") or _get(dados, "ISSN")
            isbn = _get(det, "ISBN") or _get(dados, "ISBN")

            idioma = _get(dados, "IDIOMA")
            pais = _get(dados, "PAIS-DE-PUBLICACAO") or _get(
                det, "PAIS-DE-PUBLICACAO")
            natureza = _get(dados, "NATUREZA")
            local = _get(det, "LOCAL-DE-PUBLICACAO")
            volume = _get(det, "VOLUME")
            fasciculo = _get(det, "FASCICULO")
            pag_ini = _get(det, "PAGINA-INICIAL")
            pag_fim = _get(det, "PAGINA-FINAL")
            meio = _get(dados, "MEIO-DE-DIVULGACAO")

            autores = lattes_authors(root, n)
            keywords = lattes_keywords(root, n)
            areas = lattes_areas(root, n)

            items.append({
                "type": m["tag"].lower(),
                "titulo": titulo,
                "natureza": natureza,
                "ano": ano,
                "doi": doi,
                "issn": issn,
                "isbn": isbn,
                "idioma": idioma,
                "pais": pais,
                "local": local,
                "meio": meio,
                "volume": volume,
                "fasciculo": fasciculo,
                "pag_ini": pag_ini,
                "pag_fim": pag_fim,
                "autores": autores,
                "keywords": keywords,
                "areas": areas,
                "dados_raw": _attrs(dados),
                "detalhe_raw": _attrs(det),
            })
    return items


# ====================== Produção técnica ======================


def lattes_producao_tecnica(root: etree._Element) -> List[Dict[str, Any]]:
    items: List[Dict[str, Any]] = []

    def add_from_map(tag: str,
                     xpath_root: str,
                     dados: str,
                     det: str,
                     title: Iterable[str],
                     year: Iterable[str],
                     local: Iterable[str] = (),
                     veic: Iterable[str] = ()):
        for n in _xpath(root, f'{xpath_root}/{tag}'):
            dados_n = _xpath(n, f'./{dados}')
            det_n = _xpath(n, f'./{det}')
            dados_n = dados_n[0] if dados_n else None
            det_n = det_n[0] if det_n else None

            item = {
                "type": tag.lower(),
                "titulo": _first_attr(dados_n, title)
                or _first_attr(det_n, title),
                "ano": _first_attr(dados_n, year) or _first_attr(det_n, year),
                "local": _first_attr(det_n, local)
                or _first_attr(dados_n, local),
                "veiculo": _first_attr(det_n, veic)
                or _first_attr(dados_n, veic),
                "dados_raw": _attrs(dados_n),
                "detalhe_raw": _attrs(det_n),
            }
            items.append(item)

    # Exemplos de mapeamento (outros podem ser adicionados conforme necessário)
    add_from_map("APRESENTACAO-DE-TRABALHO",
                 "//PRODUCAO-TECNICA/DEMAIS-TIPOS-DE-PRODUCAO-TECNICA",
                 "DADOS-BASICOS-DA-APRESENTACAO-DE-TRABALHO",
                 "DETALHAMENTO-DA-APRESENTACAO-DE-TRABALHO", ["TITULO"],
                 ["ANO"], ["CIDADE-DA-APRESENTACAO"], ["NOME-DO-EVENTO"])

    add_from_map("SOFTWARE", "//PRODUCAO-TECNICA/SOFTWARE",
                 "DADOS-BASICOS-DO-SOFTWARE", "DETALHAMENTO-DO-SOFTWARE",
                 ["TITULO-DO-SOFTWARE", "TITULO"], ["ANO"], ["CIDADE"],
                 ["FINANCIADOR-DO-PROJETO"])

    add_from_map("TRABALHO-TECNICO", "//PRODUCAO-TECNICA/TRABALHO-TECNICO",
                 "DADOS-BASICOS-DO-TRABALHO-TECNICO",
                 "DETALHAMENTO-DO-TRABALHO-TECNICO",
                 ["TITULO-DO-TRABALHO-TECNICO", "TITULO"], ["ANO"], ["CIDADE"],
                 ["INSTITUICAO-PROMOTORA"])

    return items
# ====================== Produção artística e cultural ======================


def lattes_artistica(root: etree._Element) -> List[Dict[str, Any]]:
    items: List[Dict[str, Any]] = []
    map_ = [
        {
            "tag": "APRESENTACAO-DE-OBRA-ARTISTICA",
            "basic": "DADOS-BASICOS-DA-APRESENTACAO-DE-OBRA-ARTISTICA",
            "detail": "DETALHAMENTO-DA-APRESENTACAO-DE-OBRA-ARTISTICA"
        },
        {
            "tag": "APRESENTACAO-EM-RADIO-OU-TV",
            "basic": "DADOS-BASICOS-DA-APRESENTACAO-EM-RADIO-OU-TV",
            "detail": "DETALHAMENTO-DA-APRESENTACAO-EM-RADIO-OU-TV"
        },
        {
            "tag": "ARTES-CENICAS",
            "basic": "DADOS-BASICOS-DE-ARTES-CENICAS",
            "detail": "DETALHAMENTO-DE-ARTES-CENICAS"
        },
        {
            "tag": "ARTES-VISUAIS",
            "basic": "DADOS-BASICOS-DE-ARTES-VISUAIS",
            "detail": "DETALHAMENTO-DE-ARTES-VISUAIS"
        },
        {
            "tag": "MUSICA",
            "basic": "DADOS-BASICOS-DA-MUSICA",
            "detail": "DETALHAMENTO-DA-MUSICA"
        },
        {
            "tag": "OUTRA-PRODUCAO-ARTISTICA-CULTURAL",
            "basic": "DADOS-BASICOS-DE-OUTRA-PRODUCAO-ARTISTICA-CULTURAL",
            "detail": "DETALHAMENTO-DE-OUTRA-PRODUCAO-ARTISTICA-CULTURAL"
        },
        {
            "tag": "SONOPLASTIA",
            "basic": "DADOS-BASICOS-DE-SONOPLASTIA",
            "detail": "DETALHAMENTO-DE-SONOPLASTIA"
        },
    ]

    for m in map_:
        for n in _xpath(root, f'//PRODUCAO-ARTISTICA-CULTURAL/{m["tag"]}'):
            dados = _xpath(n, f'./{m["basic"]}')
            det = _xpath(n, f'./{m["detail"]}')
            dados = dados[0] if dados else None
            det = det[0] if det else None

            titulo = _first_attr_startswith(
                dados, ["TITULO"]) or _first_attr_startswith(det, ["TITULO"])
            ano = _first_attr_startswith(
                dados, ["ANO", "ANO-DO", "ANO-DA"]) or _first_attr_startswith(
                    det, ["ANO", "ANO-DO", "ANO-DA"])
            doi = _get(dados, "DOI") or _get(det, "DOI")
            issn = _get(det, "ISSN") or _get(dados, "ISSN")
            isbn = _get(det, "ISBN") or _get(dados, "ISBN")
            idioma = _get(dados, "IDIOMA")
            pais = _get(dados, "PAIS-DE-PUBLICACAO") or _get(
                det, "PAIS-DE-PUBLICACAO")
            natureza = _get(dados, "NATUREZA")
            local = _get(det, "LOCAL-DE-PUBLICACAO")
            volume = _get(det, "VOLUME")
            fasciculo = _get(det, "FASCICULO")
            pag_ini = _get(det, "PAGINA-INICIAL")
            pag_fim = _get(det, "PAGINA-FINAL")
            meio = _get(dados, "MEIO-DE-DIVULGACAO")

            autores = lattes_authors(root, n)
            keywords = lattes_keywords(root, n)
            areas = lattes_areas(root, n)

            items.append({
                "type": m["tag"].lower(),
                "titulo": titulo,
                "natureza": natureza,
                "ano": ano,
                "doi": doi,
                "issn": issn,
                "isbn": isbn,
                "idioma": idioma,
                "pais": pais,
                "local": local,
                "meio": meio,
                "volume": volume,
                "fasciculo": fasciculo,
                "pag_ini": pag_ini,
                "pag_fim": pag_fim,
                "autores": autores,
                "keywords": keywords,
                "areas": areas,
                "dados_raw": _attrs(dados),
                "detalhe_raw": _attrs(det),
            })
    return items


# ====================== Orquestrador ======================


def extrair_dados(xml_path: str, id: Optional[str] = None) -> Dict[str, Any]:
    """
    Lê o XML do Lattes e retorna um dicionário consolidado (similar ao PHP).
    """
    root = _parse(xml_path)

    geral = lattes_dados_gerais(root)
    formacao = lattes_formacao(root)
    artigos = lattes_artigos(root)
    trabalhos = lattes_trabalhos_eventos(root)
    livros_cap = lattes_livros_capitulos(root)
    textos = lattes_textos(root)
    demais = lattes_demais_biblio(root)
    tecnica = lattes_producao_tecnica(root)
    artistica = lattes_artistica(root)

    return {
        "id": id,
        "geral": geral,
        "formacao": formacao,
        "producao_bibliografica": {
            "artigos": artigos,
            "trabalhos_eventos": trabalhos,
            "livros_capitulos": livros_cap,
            "textos": textos,
            "demais": demais,
        },
        "producao_tecnica": tecnica,
        "producao_artistica": artistica,
    }


# ====================== Execução direta ======================

if __name__ == "__main__":
    import json, sys, os

    if len(sys.argv) < 2:
        print("Uso: python lattes_helper.py /caminho/arquivo.xml [id]")
        sys.exit(1)

    xml_path = sys.argv[1]

    # Checa se o arquivo existe e é legível
    if not os.path.isfile(xml_path):
        print(
            f"Erro: arquivo '{xml_path}' não encontrado ou não é um arquivo válido."
        )
        sys.exit(1)

    ident = sys.argv[2] if len(sys.argv) > 2 else None
    data = extrair_dados(xml_path, ident)
    print(json.dumps(data, ensure_ascii=False, indent=2))


# ====================== Compatibilidade com a API PHP ======================


def extrairDados(xml_path: str, id: Optional[str] = None):
    """
    Wrapper para compatibilidade com a função PHP `extrairDados`.
    """
    return extrair_dados(xml_path, id)


def lattes_artisitica(root: etree._Element):
    """
    Wrapper para compatibilidade com a função PHP `lattes_artisitica`
    (grafia comum no legado). Encaminha para `lattes_artistica`.
    """
    return lattes_artistica(root)
