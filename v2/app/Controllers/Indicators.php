<?php

namespace App\Controllers;

use App\Models\LattesResearcherModel;

class Indicators extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->model = new LattesResearcherModel();
        $this->session = session();
    }

    function index()
    {
        $LattesResearcherModel = new LattesResearcherModel();
        $LattesResearchersAreaModel = new \App\Models\LattesResearchersAreaModel();
        $data = [];
        //$data['areas_conhecimento'] = $LattesResearcherModel->areasConhecimento();
        $data['lattes_atualizados'] = $LattesResearcherModel->atualizacaoLattes();
        $data['areas_conhecimento_all'] = $LattesResearchersAreaModel->areasConhecimentoAll();
        $data['producao']['cientifica'] = $LattesResearcherModel->producaoCientifica();
        $data['producao']['artistica'] = $LattesResearcherModel->producaoArtistica();

        $data['producaoAno']['cientifica'] = $LattesResearcherModel->producaoCientificaAno();
        $data['producaoAno']['artistica'] = $LattesResearcherModel->producaoArtisticaAno();

        $data['producaoIdioma']['cientifica'] = $LattesResearcherModel->producaoCientificaIdioma();
        $data['producaoIdioma']['artistica'] = $LattesResearcherModel->producaoArtisticaIdioma();

        $data['producaoCoautoria']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoria();
        $data['producaoCoautoriaAno']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoriaAno();
        $data['producaoCoautoriaPercentual']['cientifica'] = $LattesResearcherModel->producaoCientificaCoautoriaPercentual();


        echo view('layout/header', $data);

        echo view('indicators/geral_tipos', ['dados'=>$data]);
        echo view('indicators/geral_ano', ['dados' => $data]);
        echo view('indicators/geral_idioma', ['dados' => $data]);
        echo view('indicators/geral_coautoria', ['dados' => $data]);
        echo view('indicators/geral_coautoria_ano', ['dados' => $data]);
        echo view('indicators/geral_coautoria_ano_percentual', ['dados' => $data]);

        echo view('indicators/index', $data);
        echo view('layout/footer');
    }

    public function instituicao()
    {
        $instituicoesModel = new \App\Models\InstituicaoLattesModel();

        $builder = $instituicoesModel
            ->select('nome_instituicao_empresa, codigo_instituicao_empresa, pais, uf, cidade')
            ->where('nome_instituicao_empresa IS NOT NULL')
            ->where('nome_instituicao_empresa !=', '');

        $rows = $builder->findAll();

        $totaisInstituicao = [];
        $totaisUf = [];
        $totaisPais = [];

        foreach ($rows as $row) {
            $nome = trim((string) ($row['nome_instituicao_empresa'] ?? ''));
            $codigo = trim((string) ($row['codigo_instituicao_empresa'] ?? ''));
            $uf = strtoupper(trim((string) ($row['uf'] ?? '')));
            $pais = trim((string) ($row['pais'] ?? ''));

            if ($nome === '') {
                continue;
            }

            $keyInstituicao = strtolower($codigo . '|' . preg_replace('/\s+/', ' ', $nome));
            if (!isset($totaisInstituicao[$keyInstituicao])) {
                $totaisInstituicao[$keyInstituicao] = [
                    'nome' => $nome,
                    'codigo' => $codigo,
                    'total' => 0,
                ];
            }
            $totaisInstituicao[$keyInstituicao]['total']++;

            $keyUf = $uf !== '' ? $uf : 'N/I';
            if (!isset($totaisUf[$keyUf])) {
                $totaisUf[$keyUf] = 0;
            }
            $totaisUf[$keyUf]++;

            $keyPais = $pais !== '' ? $pais : 'N/I';
            if (!isset($totaisPais[$keyPais])) {
                $totaisPais[$keyPais] = 0;
            }
            $totaisPais[$keyPais]++;
        }

        usort($totaisInstituicao, static function (array $a, array $b): int {
            return $b['total'] <=> $a['total'];
        });

        $topNaoUniversidades = array_values(array_filter(
            $totaisInstituicao,
            static function (array $item): bool {
                $nome = mb_strtolower((string)($item['nome'] ?? ''), 'UTF-8');
                return mb_strpos($nome, 'universidade', 0, 'UTF-8') === false;
            }
        ));

        arsort($totaisUf);
        arsort($totaisPais);

        $lattesUpdates = $this->model->select('data_atualizacao')->findAll();
        $lattesUpdateBuckets = [
            '0 a 7 dias' => 0,
            '8 a 30 dias' => 0,
            '31 a 60 dias' => 0,
            '61 a 90 dias' => 0,
            '91 a 180 dias' => 0,
            '181 a 365 dias' => 0,
            '366 a 730 dias' => 0,
            'Mais de 730 dias' => 0,
            'Sem data' => 0,
        ];

        $datasValidas = [];
        foreach ($lattesUpdates as $item) {
            $dataAtualizacao = trim((string)($item['data_atualizacao'] ?? ''));
            if ($dataAtualizacao === '' || $dataAtualizacao === '0000-00-00' || $dataAtualizacao === '0000-00-00 00:00:00') {
                continue;
            }

            try {
                $datasValidas[] = new \DateTimeImmutable($dataAtualizacao);
            } catch (\Exception $e) {
                // Ignora datas invalidas nesta etapa de referencia.
            }
        }

        $dataReferencia = null;
        if (!empty($datasValidas)) {
            usort($datasValidas, static function (\DateTimeImmutable $a, \DateTimeImmutable $b): int {
                return $a <=> $b;
            });
            $dataReferencia = end($datasValidas);
            if ($dataReferencia === false) {
                $dataReferencia = null;
            }
        }

        foreach ($lattesUpdates as $item) {
            $dataAtualizacao = trim((string)($item['data_atualizacao'] ?? ''));

            if ($dataAtualizacao === '' || $dataAtualizacao === '0000-00-00' || $dataAtualizacao === '0000-00-00 00:00:00' || $dataReferencia === null) {
                $lattesUpdateBuckets['Sem data']++;
                continue;
            }

            try {
                $dtAtualizacao = new \DateTimeImmutable($dataAtualizacao);
            } catch (\Exception $e) {
                $lattesUpdateBuckets['Sem data']++;
                continue;
            }

            $dias = (int)$dtAtualizacao->diff($dataReferencia)->format('%r%a');
            if ($dias < 0) {
                $dias = 0;
            }

            if ($dias <= 7) {
                $lattesUpdateBuckets['0 a 7 dias']++;
            } elseif ($dias <= 30) {
                $lattesUpdateBuckets['8 a 30 dias']++;
            } elseif ($dias <= 60) {
                $lattesUpdateBuckets['31 a 60 dias']++;
            } elseif ($dias <= 90) {
                $lattesUpdateBuckets['61 a 90 dias']++;
            } elseif ($dias <= 180) {
                $lattesUpdateBuckets['91 a 180 dias']++;
            } elseif ($dias <= 365) {
                $lattesUpdateBuckets['181 a 365 dias']++;
            } elseif ($dias <= 730) {
                $lattesUpdateBuckets['366 a 730 dias']++;
            } else {
                $lattesUpdateBuckets['Mais de 730 dias']++;
            }
        }

        $data = [
            'total_registros' => count($rows),
            'total_instituicoes' => count($totaisInstituicao),
            'top_instituicoes' => array_slice($totaisInstituicao, 0, 50),
            'top_nao_universidades' => array_slice($topNaoUniversidades, 0, 30),
            'por_uf' => $totaisUf,
            'por_pais' => $totaisPais,
            'lattes_update_buckets' => $lattesUpdateBuckets,
            'lattes_update_reference_date' => $dataReferencia ? $dataReferencia->format('d/m/Y') : null,
        ];

        echo view('layout/header', $data);
        echo view('indicators/instituicao', $data);
        echo view('layout/footer');
    }

}
