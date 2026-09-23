<?php

namespace App\Libraries;

use App\Models\CurriculoLattesModel;

class IndicadoresGlobais
{
    public const GRUPOS = [
        'formacao' => [
            'titulo' => 'Formação acadêmica', 'icone' => 'mortarboard',
            'descricao' => 'Títulos e etapas de formação registrados nos currículos.',
            'itens' => [
                'graduacao' => 'Graduações',
                'especializacao' => 'Especializações',
                'mestrado' => 'Mestrados',
                'doutorado' => 'Doutorados',
                'pos_doutorado' => 'Pós-doutorados',
                'livre_docencia' => 'Livre-docência',
            ],
        ],
        'bibliografica' => [
            'titulo' => 'Produção bibliográfica', 'icone' => 'journal-richtext',
            'descricao' => 'Publicações e trabalhos que disseminam conhecimento.',
            'itens' => [
                'artigos_periodicos' => 'Artigos em periódicos',
                'artigos_eventos' => 'Trabalhos completos em eventos',
                'resumos_expandidos' => 'Resumos expandidos',
                'resumos' => 'Resumos',
                'livros_completos' => 'Livros publicados',
                'livros_organizados' => 'Livros organizados',
                'capitulos_livros' => 'Capítulos de livros',
                'artigos_aceitos' => 'Artigos aceitos',
                'textos_jornais_revistas' => 'Textos em jornais e revistas',
                'outras_producoes_bibliograficas' => 'Outras produções bibliográficas',
                'partituras' => 'Partituras',
                'traducoes' => 'Traduções',
                'prefacios_posfacios' => 'Prefácios e posfácios',
            ],
        ],
        'tecnica' => [
            'titulo' => 'Produção técnica', 'icone' => 'tools',
            'descricao' => 'Aplicações práticas, materiais e comunicação técnica.',
            'itens' => [
                'trabalhos_tecnicos' => 'Trabalhos técnicos',
                'produtos' => 'Produtos tecnológicos',
                'processos' => 'Processos e técnicas',
                'softwares_sem_patente' => 'Softwares sem registro',
                'material_didatico' => 'Materiais didáticos',
                'cursos_curta_duracao_producao' => 'Cursos de curta duração ministrados',
                'relatorios_pesquisa' => 'Relatórios de pesquisa',
                'websites_blogs' => 'Websites e blogs',
                'entrevistas_programas_midia' => 'Entrevistas e programas de mídia',
                'editoracao' => 'Editoração',
                'cartas_mapas' => 'Cartas e mapas',
                'manutencao_obra_artistica' => 'Manutenção de obras artísticas',
                'maquetes' => 'Maquetes',
                'outras_producoes_tecnicas' => 'Outras produções técnicas',
                'apresentacao_trabalhos' => 'Apresentações de trabalhos',
            ],
        ],
        'artistica' => [
            'titulo' => 'Produção artística', 'icone' => 'palette',
            'descricao' => 'Criações e expressões artísticas e culturais.',
            'itens' => [
                'artes_cenicas' => 'Artes cênicas',
                'musicas' => 'Música',
                'artes_visuais' => 'Artes visuais',
                'outras_producoes_artisticas' => 'Outras produções artísticas',
            ],
        ],
        'orientacoes' => [
            'titulo' => 'Orientações concluídas', 'icone' => 'people',
            'descricao' => 'Formação de pesquisadores e acompanhamento acadêmico concluído.',
            'itens' => [
                'supervisoes_pos_doutorado' => 'Supervisões de pós-doutorado',
                'orientacoes_doutorado_principal' => 'Doutorado · orientação principal',
                'orientacoes_doutorado_coorientador' => 'Doutorado · coorientação',
                'orientacoes_mestrado_principal' => 'Mestrado · orientação principal',
                'orientacoes_mestrado_coorientador' => 'Mestrado · coorientação',
                'orientacoes_tcc' => 'Trabalhos de conclusão de curso',
                'orientacoes_ic' => 'Iniciação científica',
                'orientacoes_especializacao' => 'Especialização',
                'orientacoes_outra_natureza' => 'Outras orientações',
            ],
        ],
        'andamento' => [
            'titulo' => 'Orientações em andamento', 'icone' => 'hourglass-split',
            'descricao' => 'Orientações informadas como em andamento na última versão de cada currículo.',
            'itens' => [
                'supervisoes_pos_doutorado_andamento' => 'Supervisões de pós-doutorado',
                'orientacoes_doutorado_andamento_principal' => 'Doutorado · orientação principal',
                'orientacoes_doutorado_andamento_coorientador' => 'Doutorado · coorientação',
                'orientacoes_mestrado_andamento_principal' => 'Mestrado · orientação principal',
                'orientacoes_mestrado_andamento_coorientador' => 'Mestrado · coorientação',
                'orientacoes_tcc_andamento' => 'Trabalhos de conclusão de curso',
                'orientacoes_ic_andamento' => 'Iniciação científica',
                'orientacoes_especializacao_andamento' => 'Especialização',
                'orientacoes_outra_natureza_andamento' => 'Outras orientações',
            ],
        ],
        'projetos' => [
            'titulo' => 'Projetos', 'icone' => 'diagram-3',
            'descricao' => 'Participações em projetos, organizadas pela natureza da atividade.',
            'itens' => [
                'projetos_pesquisa' => 'Pesquisa',
                'projetos_extensao' => 'Extensão',
                'projetos_ensino' => 'Ensino',
                'projetos_desenvolvimento' => 'Desenvolvimento',
                'projetos_outros' => 'Outros projetos',
            ],
        ],
        'inovacao' => [
            'titulo' => 'Inovação e registros', 'icone' => 'lightbulb',
            'descricao' => 'Propriedade intelectual e registros informados na base.',
            'itens' => [
                'patentes' => 'Patentes',
                'softwares_com_patente' => 'Softwares com registro',
                'marcas' => 'Marcas',
                'cultivar_protegida' => 'Cultivares protegidas',
                'cultivar_registrada' => 'Cultivares registradas',
                'desenhos' => 'Desenhos industriais',
                'topografias' => 'Topografias de circuitos integrados',
            ],
        ],
        'comunidade' => [
            'titulo' => 'Comunidade acadêmica', 'icone' => 'megaphone',
            'descricao' => 'Reconhecimento, eventos e participação na vida acadêmica.',
            'itens' => [
                'premios_titulos' => 'Prêmios e títulos',
                'organizacao_eventos' => 'Organização de eventos',
                'participacao_congressos' => 'Participação em congressos',
                'participacao_seminarios' => 'Participação em seminários',
                'participacao_simposios' => 'Participação em simpósios',
                'participacao_oficinas' => 'Participação em oficinas',
                'participacao_encontros' => 'Participação em encontros',
                'participacao_feiras' => 'Participação em feiras',
                'participacao_exposicoes' => 'Participação em exposições',
                'participacao_olimpiadas' => 'Participação em olimpíadas',
                'participacao_outros_eventos' => 'Participação em outros eventos',
            ],
        ],
    ];

    public function resumo(CurriculoLattesModel $model): array
    {
        $query = $model->builder();
        $query->select('COUNT(*) AS curriculos, MAX(atualizacao_cv) AS ultima_atualizacao, MIN(atualizacao_cv) AS primeira_atualizacao, COUNT(atualizacao_cv) AS com_data', false);
        $query->select("COUNT(DISTINCT NULLIF(TRIM(end_prof_instituicao), '')) AS instituicoes", false);
        foreach (self::GRUPOS as $grupo) {
            foreach ($grupo['itens'] as $campo => $label) {
                $query->selectSum($campo);
            }
        }
        $totais = $query->get()->getRowArray();
        $grupos = self::GRUPOS;
        foreach ($grupos as &$grupo) {
            $grupo['total'] = 0;
            $grupo['valores'] = [];
            foreach ($grupo['itens'] as $campo => $label) {
                $valor = (int) ($totais[$campo] ?? 0);
                $grupo['valores'][$campo] = $valor;
                $grupo['total'] += $valor;
            }
        }
        unset($grupo);
        return [
            'curriculos' => (int) $totais['curriculos'],
            'instituicoes' => (int) $totais['instituicoes'],
            'com_data' => (int) $totais['com_data'],
            'ultima_atualizacao' => $totais['ultima_atualizacao'],
            'primeira_atualizacao' => $totais['primeira_atualizacao'],
            'grupos' => $grupos,
            'areas' => $this->distribuicao($model, 'primeira_grande_area'),
            'instituicoes_top' => $this->distribuicao($model, 'end_prof_instituicao'),
        ];
    }

    private function distribuicao(CurriculoLattesModel $model, string $campo): array
    {
        // Os campos são constantes internas, nunca parâmetros da requisição.
        $linhas = $model->builder()
            ->select("COALESCE(NULLIF(TRIM($campo), ''), 'Não informado') AS nome_grupo, COUNT(*) AS total", false)
            ->groupBy('nome_grupo')->orderBy('total', 'DESC')->orderBy('nome_grupo', 'ASC')
            ->get()->getResultArray();
        return array_map(static fn ($linha) => ['nome' => $linha['nome_grupo'], 'total' => (int) $linha['total']], $linhas);
    }
}
