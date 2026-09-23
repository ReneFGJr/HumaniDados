<?php

namespace App\Libraries;

use DateTimeImmutable;
use RuntimeException;
use SimpleXMLElement;

/**
 * Extrai uma linha de indicadores por currículo, sem consultar o banco.
 * Mapeamento baseado no XSD Lattes disponível em v_old/public/_repository.
 */
class LattesIndicadores
{
    private const CONTAGENS = [
        'graduacao' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/GRADUACAO',
        'especializacao' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/ESPECIALIZACAO',
        'mestrado' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/MESTRADO',
        'doutorado' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/DOUTORADO',
        'pos_doutorado' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/POS-DOUTORADO',
        'livre_docencia' => 'DADOS-GERAIS/FORMACAO-ACADEMICA-TITULACAO/LIVRE-DOCENCIA',
        'cursos_curta_duracao_formacao' => '//FORMACAO-COMPLEMENTAR/FORMACAO-COMPLEMENTAR-CURSO-DE-CURTA-DURACAO',
        'cursos_extensao' => '//FORMACAO-COMPLEMENTAR/FORMACAO-COMPLEMENTAR-DE-EXTENSAO-UNIVERSITARIA',
        'mba' => '//FORMACAO-COMPLEMENTAR/MBA',
        'outros_cursos' => '//FORMACAO-COMPLEMENTAR/OUTROS',
        'direcao_administracao' => '//DIRECAO-E-ADMINISTRACAO',
        'linhas_pesquisa' => '//LINHA-DE-PESQUISA',
        'atividades_ensino' => '//ENSINO',
        'atividades_extensao' => '//EXTENSAO-UNIVERSITARIA',
        'conselhos_comissoes_consultorias' => '//CONSELHO-COMISSAO-E-CONSULTORIA',
        'estagios' => '//ESTAGIO',
        'servicos_tecnicos' => '//SERVICO-TECNICO-ESPECIALIZADO',
        'treinamentos_ministrados' => '//TREINAMENTO-MINISTRADO',
        'outras_atividades_tecnico_cientificas' => '//OUTRA-ATIVIDADE-TECNICO-CIENTIFICA',
        'membro_corpo_editorial' => '//MEMBRO-DO-CORPO-EDITORIAL',
        'membro_comite_assessoramento' => '//MEMBRO-DE-COMITE-DE-ASSESSORAMENTO',
        'revisor_periodico' => '//REVISOR-DE-PERIODICO',
        'revisor_projeto_agencia_fomento' => '//REVISOR-DE-PROJETO-DE-AGENCIA-DE-FOMENTO',
        'artigos_periodicos' => 'PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS/ARTIGO-PUBLICADO',
        'capitulos_livros' => '//CAPITULO-DE-LIVRO-PUBLICADO',
        'artigos_aceitos' => '//ARTIGO-ACEITO-PARA-PUBLICACAO',
        'textos_jornais_revistas' => '//TEXTO-EM-JORNAL-OU-REVISTA',
        'apresentacao_trabalhos' => '//APRESENTACAO-DE-TRABALHO',
        'partituras' => '//PARTITURA-MUSICAL',
        'traducoes' => '//TRADUCAO',
        'prefacios_posfacios' => '//PREFACIO-POSFACIO',
        'outras_producoes_bibliograficas' => '//OUTRA-PRODUCAO-BIBLIOGRAFICA',
        'produtos' => '//PRODUTO-TECNOLOGICO',
        'processos' => '//PROCESSOS-OU-TECNICAS',
        'trabalhos_tecnicos' => '//TRABALHO-TECNICO',
        'cartas_mapas' => '//CARTA-MAPA-OU-SIMILAR',
        'cursos_curta_duracao_producao' => '//CURSO-DE-CURTA-DURACAO-MINISTRADO',
        'material_didatico' => '//DESENVOLVIMENTO-DE-MATERIAL-DIDATICO-OU-INSTRUCIONAL',
        'editoracao' => '//EDITORACAO',
        'manutencao_obra_artistica' => '//MANUTENCAO-DE-OBRA-ARTISTICA',
        'maquetes' => '//MAQUETE',
        'entrevistas_programas_midia' => '//PROGRAMA-DE-RADIO-OU-TV',
        'relatorios_pesquisa' => '//RELATORIO-DE-PESQUISA',
        'websites_blogs' => '//MIDIA-SOCIAL-WEBSITE-BLOG',
        'outras_producoes_tecnicas' => '//OUTRA-PRODUCAO-TECNICA',
        'artes_cenicas' => '//ARTES-CENICAS',
        'musicas' => '//MUSICA',
        'artes_visuais' => '//ARTES-VISUAIS',
        'outras_producoes_artisticas' => '//OUTRA-PRODUCAO-ARTISTICA-CULTURAL',
        'patentes' => '//PATENTE',
        'marcas' => '//MARCA',
        'cultivar_protegida' => '//CULTIVAR-PROTEGIDA',
        'cultivar_registrada' => '//CULTIVAR-REGISTRADA',
        'desenhos' => '//DESENHO-INDUSTRIAL',
        'topografias' => '//TOPOGRAFIA-DE-CIRCUITO-INTEGRADO',
        'supervisoes_pos_doutorado' => '//ORIENTACOES-CONCLUIDAS-PARA-POS-DOUTORADO',
        'supervisoes_pos_doutorado_andamento' => '//ORIENTACAO-EM-ANDAMENTO-DE-POS-DOUTORADO',
        'orientacoes_tcc_andamento' => '//ORIENTACAO-EM-ANDAMENTO-DE-GRADUACAO',
        'orientacoes_ic_andamento' => '//ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA',
        'orientacoes_especializacao_andamento' => '//ORIENTACAO-EM-ANDAMENTO-DE-APERFEICOAMENTO-ESPECIALIZACAO',
        'orientacoes_outra_natureza_andamento' => '//OUTRAS-ORIENTACOES-EM-ANDAMENTO',
        'bancas_tcc' => '//PARTICIPACAO-EM-BANCA-DE-GRADUACAO',
        'bancas_especializacao' => '//PARTICIPACAO-EM-BANCA-DE-APERFEICOAMENTO-ESPECIALIZACAO',
        'bancas_mestrado' => '//PARTICIPACAO-EM-BANCA-DE-MESTRADO',
        'bancas_doutorado' => '//PARTICIPACAO-EM-BANCA-DE-DOUTORADO',
        'bancas_livre_docencia' => '//BANCA-JULGADORA-PARA-LIVRE-DOCENCIA',
        'bancas_concurso' => '//BANCA-JULGADORA-PARA-CONCURSO-PUBLICO',
        'outras_bancas' => '//OUTRAS-BANCAS-JULGADORAS | //OUTRAS-PARTICIPACOES-EM-BANCA | //BANCA-JULGADORA-PARA-PROFESSOR-TITULAR | //BANCA-JULGADORA-PARA-AVALIACAO-CURSOS',
        'premios_titulos' => '//PREMIO-TITULO',
        'organizacao_eventos' => '//ORGANIZACAO-DE-EVENTO',
        'participacao_congressos' => '//PARTICIPACAO-EM-CONGRESSO',
        'participacao_seminarios' => '//PARTICIPACAO-EM-SEMINARIO',
        'participacao_simposios' => '//PARTICIPACAO-EM-SIMPOSIO',
        'participacao_oficinas' => '//PARTICIPACAO-EM-OFICINA',
        'participacao_encontros' => '//PARTICIPACAO-EM-ENCONTRO',
        'participacao_feiras' => '//PARTICIPACAO-EM-FEIRA',
        'participacao_exposicoes' => '//PARTICIPACAO-EM-EXPOSICAO',
        'participacao_olimpiadas' => '//PARTICIPACAO-EM-OLIMPIADA',
        'participacao_outros_eventos' => '//OUTRAS-PARTICIPACOES-EM-EVENTOS-CONGRESSOS',
    ];

    public function extrairArquivo(string $arquivo): array
    {
        $conteudo = file_get_contents($arquivo);
        if ($conteudo === false) {
            throw new RuntimeException('Não foi possível ler o arquivo XML.');
        }

        $nome = pathinfo($arquivo, PATHINFO_FILENAME);
        $idArquivo = preg_match('/^[0-9]{16}$/D', $nome) ? $nome : null;
        return $this->extrairXml($conteudo, $idArquivo);
    }

    public function extrairXml(string $conteudo, ?string $idArquivo = null): array
    {
        // O formato Lattes não precisa de DTD nem de entidades externas.
        if (stripos($conteudo, '<!DOCTYPE') !== false || stripos($conteudo, '<!ENTITY') !== false) {
            throw new RuntimeException('XML com DTD ou entidades não é permitido.');
        }

        $anterior = libxml_use_internal_errors(true);
        try {
            $xml = simplexml_load_string($conteudo, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOBLANKS);
            if ($xml === false || $xml->getName() !== 'CURRICULO-VITAE') {
                throw new RuntimeException('XML inválido ou sem a raiz CURRICULO-VITAE.');
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($anterior);
        }

        $id = trim((string) $xml['NUMERO-IDENTIFICADOR']);
        if ($id === '' && $idArquivo !== null) {
            $id = $idArquivo;
        } elseif ($idArquivo !== null && $id !== $idArquivo) {
            throw new RuntimeException('O ID do XML é diferente do ID no nome do arquivo.');
        }
        if (!preg_match('/^[0-9]{16}$/D', $id)) {
            throw new RuntimeException('ID Lattes ausente ou diferente de 16 dígitos.');
        }

        $gerais = $xml->{'DADOS-GERAIS'};
        $nome = trim((string) $gerais['NOME-COMPLETO']);
        if ($nome === '') {
            throw new RuntimeException('Nome completo ausente no currículo.');
        }

        $data = trim((string) $xml['DATA-ATUALIZACAO']);
        $atualizacao = null;
        if ($data !== '') {
            $atualizacao = DateTimeImmutable::createFromFormat('!dmY', $data);
            if (!$atualizacao || $atualizacao->format('dmY') !== $data) {
                throw new RuntimeException('Data de atualização inválida.');
            }
        }

        $endereco = $gerais->{'ENDERECO'}->{'ENDERECO-PROFISSIONAL'};
        $areas = $xml->xpath('DADOS-GERAIS/AREAS-DE-ATUACAO/AREA-DE-ATUACAO') ?: [];
        usort($areas, static fn ($a, $b) => ((int) ($a['SEQUENCIA-AREA-DE-ATUACAO'] ?? 999)) <=> ((int) ($b['SEQUENCIA-AREA-DE-ATUACAO'] ?? 999)));
        $area = $areas[0] ?? null;
        $registro = [
            'id_lattes' => $id,
            'nome' => $nome,
            'atualizacao_cv' => $atualizacao ? $atualizacao->format('Y-m-d') : null,
            // A coluna Nascimento da planilha representa o país de nascimento.
            'nascimento' => $this->atributo($gerais, 'PAIS-DE-NASCIMENTO'),
            'nacionalidade' => $this->atributo($gerais, 'PAIS-DE-NACIONALIDADE') ?? $this->atributo($gerais, 'NACIONALIDADE'),
            'nome_em_citacoes' => $this->atributo($gerais, 'NOME-EM-CITACOES-BIBLIOGRAFICAS'),
            'end_prof_instituicao' => $this->atributo($endereco, 'NOME-INSTITUICAO-EMPRESA'),
            'end_prof_orgao' => $this->atributo($endereco, 'NOME-ORGAO'),
            'end_prof_unidade' => $this->atributo($endereco, 'NOME-UNIDADE'),
            'end_prof_pais' => $this->atributo($endereco, 'PAIS'),
            'end_prof_uf' => $this->atributo($endereco, 'UF'),
            'end_prof_cidade' => $this->atributo($endereco, 'CIDADE'),
            'primeira_grande_area' => $this->atributo($area, 'NOME-GRANDE-AREA-DO-CONHECIMENTO'),
            'primeira_area' => $this->atributo($area, 'NOME-DA-AREA-DO-CONHECIMENTO'),
        ];

        foreach (self::CONTAGENS as $campo => $xpath) {
            $registro[$campo] = count($xml->xpath($xpath) ?: []);
        }

        foreach (['artigos_eventos' => 'COMPLETO', 'resumos_expandidos' => 'RESUMO_EXPANDIDO', 'resumos' => 'RESUMO'] as $campo => $natureza) {
            $registro[$campo] = count($xml->xpath('//TRABALHO-EM-EVENTOS[DADOS-BASICOS-DO-TRABALHO/@NATUREZA="' . $natureza . '"]') ?: []);
        }
        foreach (['livros_completos' => 'LIVRO_PUBLICADO', 'livros_organizados' => 'LIVRO_ORGANIZADO_OU_EDICAO'] as $campo => $tipo) {
            $registro[$campo] = count($xml->xpath('//LIVRO-PUBLICADO-OU-ORGANIZADO[DADOS-BASICOS-DO-LIVRO/@TIPO="' . $tipo . '"]') ?: []);
        }

        $registro['softwares_com_patente'] = count($xml->xpath('//SOFTWARE[.//REGISTRO-OU-PATENTE]') ?: []);
        $registro['softwares_sem_patente'] = count($xml->xpath('//SOFTWARE[not(.//REGISTRO-OU-PATENTE)]') ?: []);

        foreach (['mestrado', 'doutorado'] as $nivel) {
            foreach ([false, true] as $andamento) {
                $tag = $andamento ? 'ORIENTACAO-EM-ANDAMENTO-DE-' : 'ORIENTACOES-CONCLUIDAS-PARA-';
                $base = 'orientacoes_' . $nivel . ($andamento ? '_andamento' : '');
                $registro[$base . '_principal'] = 0;
                $registro[$base . '_coorientador'] = 0;
                foreach ($xml->xpath('//' . $tag . strtoupper($nivel)) ?: [] as $orientacao) {
                    $atributos = $orientacao->xpath('./*/@TIPO-DE-ORIENTACAO') ?: [];
                    $tipo = $this->normalizar((string) ($atributos[0] ?? ''));
                    if (in_array($tipo, ['CO_ORIENTADOR', 'COORIENTADOR'], true)) {
                        $registro[$base . '_coorientador']++;
                    } elseif ($tipo === 'ORIENTADOR_PRINCIPAL') {
                        $registro[$base . '_principal']++;
                    } else {
                        throw new RuntimeException('Tipo de orientação de ' . $nivel . ' não reconhecido: ' . $tipo);
                    }
                }
            }
        }

        $outras = [
            'TRABALHO_DE_CONCLUSAO_DE_CURSO_GRADUACAO' => 'orientacoes_tcc',
            'INICIACAO_CIENTIFICA' => 'orientacoes_ic',
            'MONOGRAFIA_DE_CONCLUSAO_DE_CURSO_APERFEICOAMENTO_E_ESPECIALIZACAO' => 'orientacoes_especializacao',
        ];
        foreach (array_merge(array_values($outras), ['orientacoes_outra_natureza']) as $campo) {
            $registro[$campo] = 0;
        }
        foreach ($xml->xpath('//OUTRAS-ORIENTACOES-CONCLUIDAS') ?: [] as $orientacao) {
            $natureza = $this->normalizar((string) $orientacao->{'DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS'}['NATUREZA']);
            $registro[$outras[$natureza] ?? 'orientacoes_outra_natureza']++;
        }

        $registro['bancas_qualificacao_mestrado'] = 0;
        $registro['bancas_qualificacao_doutorado'] = 0;
        foreach ($xml->xpath('//PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO') ?: [] as $banca) {
            $natureza = $this->normalizar((string) $banca->{'DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO'}['NATUREZA']);
            if (str_contains($natureza, 'MESTRADO')) {
                $registro['bancas_qualificacao_mestrado']++;
            } elseif (str_contains($natureza, 'DOUTORADO')) {
                $registro['bancas_qualificacao_doutorado']++;
            } else {
                $registro['outras_bancas']++;
            }
        }

        foreach (['pesquisa', 'extensao', 'ensino', 'desenvolvimento', 'outros'] as $tipo) {
            $registro['projetos_' . $tipo] = 0;
        }
        foreach ($xml->xpath('//PROJETO-DE-PESQUISA') ?: [] as $projeto) {
            $tipo = strtolower($this->normalizar((string) $projeto['NATUREZA']));
            $campo = in_array($tipo, ['pesquisa', 'extensao', 'ensino', 'desenvolvimento'], true) ? $tipo : 'outros';
            $registro['projetos_' . $campo]++;
        }

        return $registro;
    }

    private function atributo(?SimpleXMLElement $elemento, string $nome): ?string
    {
        $valor = trim((string) ($elemento[$nome] ?? ''));
        return $valor === '' ? null : $valor;
    }

    private function normalizar(string $valor): string
    {
        $valor = strtr(trim($valor), [
            'á' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e',
            'í' => 'i', 'ó' => 'o', 'õ' => 'o', 'ô' => 'o', 'ú' => 'u', 'ç' => 'c',
            'Á' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E',
            'Í' => 'I', 'Ó' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ú' => 'U', 'Ç' => 'C',
        ]);
        return strtoupper(preg_replace('/[\s-]+/u', '_', $valor));
    }
}

