<?php

namespace App\Controllers;

use App\Libraries\IndicadoresGlobais;
use App\Models\CurriculoLattesModel;
use Throwable;

class GlobalIndicators extends BaseController
{
    public function index(): string
    {
        $data = ['resumo' => null, 'erro' => null];
        try {
            $data['resumo'] = (new IndicadoresGlobais())->resumo(new CurriculoLattesModel());
        } catch (Throwable $e) {
            log_message('error', 'Indicadores globais: {erro}', ['erro' => $e->getMessage()]);
            $data['erro'] = 'Os indicadores estão temporariamente indisponíveis. Tente novamente mais tarde.';
            $this->response->setStatusCode(503);
        }
        return view('layout/header') . view('indicators/global', $data) . view('layout/footer');
    }
}
