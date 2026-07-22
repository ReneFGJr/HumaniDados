<?php

namespace App\Controllers;

use App\Models\IndicadoresModel;
use App\Models\Lattes\OrientationModel;

class ProducaoTecnica extends BaseController
{
    public function index($pg = '')
    {
        $pg = trim((string) $pg);
        if ($pg === '') {
            $pg = 'orientacoes';
        }

        $arg1 = $this->request->getGet('arg1');
        $arg2 = $this->request->getGet('arg2');
        $arg3 = $this->request->getGet('arg3');

        $cache = new IndicadoresModel();
        $dt = $cache->findByArgs($pg, $arg1, $arg2, $arg3);

        if ($dt === null) {
            $dt = $this->buildIndicadores($pg, $arg1, $arg2, $arg3);
            $cache->saveIndicador($pg, $arg1, $arg2, $arg3, $dt);
        }

        echo view('layout/header');
        if ($pg === 'orientacoes') {
            echo view('producao_tecnica/indicador_orientacoes', [
                'dados' => $dt['orientacoes'] ?? [],
                'pag' => $pg,
            ]);
        } else {
            echo '<div class="container py-4"><h4>Indicador não encontrado.</h4></div>';
        }
        echo view('layout/footer');
    }

    private function buildIndicadores(string $pg, ?string $arg1, ?string $arg2, ?string $arg3): array
    {
        if ($pg !== 'orientacoes') {
            return [];
        }

        $model = new OrientationModel();
        $builder = $model->select('tipo, status, COUNT(*) as total');

        if (!empty($arg1)) {
            $builder->where('tipo', $arg1);
        }
        if (!empty($arg2)) {
            $builder->where('status', $arg2);
        }
        if (!empty($arg3)) {
            $builder->where('ano', $arg3);
        }

        $itens = $builder
            ->groupBy('tipo, status')
            ->orderBy('tipo', 'ASC')
            ->findAll();

        $porTipo = [];
        $porStatus = [];
        $totalGeral = 0;

        foreach ($itens as $item) {
            $tipo = trim((string) ($item['tipo'] ?? 'OUTROS'));
            $status = trim((string) ($item['status'] ?? 'NAO_INFORMADO'));
            $total = (int) ($item['total'] ?? 0);

            if ($tipo === '') {
                $tipo = 'OUTROS';
            }
            if ($status === '') {
                $status = 'NAO_INFORMADO';
            }

            $totalGeral += $total;
            $porTipo[$tipo] = ($porTipo[$tipo] ?? 0) + $total;
            $porStatus[$status] = ($porStatus[$status] ?? 0) + $total;
        }

        $tipos = [];
        foreach ($porTipo as $tipo => $total) {
            $tipos[] = ['tipo' => $tipo, 'total' => $total];
        }

        $statusList = [];
        foreach ($porStatus as $status => $total) {
            $statusList[] = ['status' => $status, 'total' => $total];
        }

        return [
            'orientacoes' => [
                'total_geral' => $totalGeral,
                'por_tipo' => $tipos,
                'por_status' => $statusList,
                'itens' => $itens,
            ],
        ];
    }
}
