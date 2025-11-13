<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducaoArtisticaModel extends Model
{
    protected $table = 'producao_artistica';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_lattes',
        'sequencia_producao',
        'tipo',

        // Dados básicos
        'natureza',
        'titulo',
        'ano',
        'pais',
        'idioma',
        'flag_relevancia',
        'titulo_ingles',
        'meio_divulgacao',
        'home_page',
        'flag_divulgacao_cientifica',

        // Detalhamento geral / Artes Visuais
        'premiacao',
        'atividade_autores',
        'instituicao_evento',
        'local_evento',
        'cidade_evento',
        'temporada',
        'informacoes_adicionais',

        // Artes Cênicas
        'tipo_evento',
        'data_estreia',
        'data_encerramento',
        'local_estreia',
        'instituicao_promotora_premio',
        'obra_referencia',
        'autor_obra_referencia',
        'ano_obra_referencia',
        'duracao',
        'flag_itinerante',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Insere autores */
    public function salvarAutores($idProducao, $autores)
    {
        $autorModel = model('ProducaoArtisticaAutoresModel');
        foreach ($autores as $a) {
            $autorModel->insert([
                'id_producao' => $idProducao,
                'nome_completo' => $a['nome_completo'],
                'nome_citacao' => $a['nome_citacao'],
                'ordem_autoria' => $a['ordem'],
                'id_cnpq' => $a['id_cnpq']
            ]);
        }
    }

    /** Insere keywords */
    public function salvarPalavrasChave($idProducao, $keywords)
    {
        $kwModel = model('ProducaoArtisticaKeywordsModel');
        foreach ($keywords as $k) {
            if (!empty($k)) {
                $kwModel->insert([
                    'id_producao' => $idProducao,
                    'keyword' => $k
                ]);
            }
        }
    }

    /** Insere áreas */
    public function salvarAreasConhecimento($idProducao, $areas)
    {
        $areaModel = model('ProducaoArtisticaAreasModel');
        foreach ($areas as $ar) {
            $areaModel->insert([
                'id_producao' => $idProducao,
                'grande_area' => $ar['grande_area'],
                'area' => $ar['area'],
                'sub_area' => $ar['sub_area'],
                'especialidade' => $ar['especialidade']
            ]);
        }
    }
}
