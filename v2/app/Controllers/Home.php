<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $LattesResearcherModel = new \App\Models\LattesResearcherModel();
        $ProducaoArtisticaModel = new \App\Models\ProducaoArtisticaModel();
        $data = [];
        $data['resume']['pesquisadores'] = $LattesResearcherModel->select("COUNT(id) AS total")->first();
        $data['resume']['instituicoes'] = $LattesResearcherModel->select("COUNT(id) AS total, vinculo_instituicao")->groupBy('vinculo_instituicao')->findAll();
        $data['resume']['instituicao_total'] = count($data['resume']['instituicoes']);

        $data['resume']['universidades'] = $LattesResearcherModel->universidadesVinculadas();
        $data['resume']['universidade_total'] = count($data['resume']['universidades']);
        $data['resume']['producao_artistica'] = $ProducaoArtisticaModel->totalProducaoArtistica();

        return view('layout/header')
            . view('home', ['data' => $data])
            . view('layout/footer');
    }

    public function about()
    {
        return view('layout/header')
            . view('about')
            . view('layout/footer');
    }

    public function glossary()
    {
        $GlossarioModel = new \App\Models\GlossarioModel();
        $terms = $GlossarioModel->getTerms();

        return view('layout/header')
            . view('glossary', ['glossario' => $terms])
            . view('layout/footer');
    }
}
