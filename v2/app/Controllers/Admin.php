<?php

namespace App\Controllers;

use App\Libraries\ImportadorIndicadoresLattes;
use App\Models\CurriculoLattesModel;
use Throwable;

class Admin extends BaseController
{
    public function index()
    {
        return $this->pagina();
    }

    public function importPage()
    {
        return $this->pagina();
    }

    public function importLattes()
    {
        $model = new CurriculoLattesModel();
        try {
            if (!db_connect()->tableExists('curriculos_lattes')) {
                throw new \RuntimeException('A tabela curriculos_lattes ainda não existe. Crie a tabela com o SQL dos indicadores antes de importar.');
            }
            set_time_limit(0);
            $resultado = (new ImportadorIndicadoresLattes())->importar(dirname(rtrim(ROOTPATH, '/\\')) . '/database/xml', $model);
            session()->setFlashdata('indicadores_resultado', $resultado);
        } catch (Throwable $e) {
            log_message('error', 'Importação de indicadores: {erro}', ['erro' => $e->getMessage()]);
            session()->setFlashdata('indicadores_erro', 'Não foi possível iniciar a importação. Verifique o banco, a tabela curriculos_lattes e o diretório database/xml.');
        }
        return redirect()->to(site_url('admin/inport/alttes'));
    }

    public function exportAll()
    {
        $model = new CurriculoLattesModel();
        try {
            $campos = $model->campos();
            $stream = fopen('php://temp/maxmemory:5242880', 'w+');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, $campos, ';', '"', '');
            // Paginação por chave evita carregar todos os registros de uma vez.
            $ultimo = null;
            do {
                $query = $model->orderBy('id_lattes', 'ASC');
                if ($ultimo !== null) {
                    $query->where('id_lattes >', $ultimo);
                }
                $linhas = $query->findAll(500);
                foreach ($linhas as $linha) {
                    $valores = [];
                    foreach ($campos as $campo) {
                        $valor = (string) ($linha[$campo] ?? '');
                        // Preserva o ID ao abrir em planilhas e neutraliza fórmulas.
                        if ($campo === 'id_lattes' || preg_match('/^[\\s]*[=+@-]|^[\\t\\r\\n]/u', $valor)) {
                            $valor = "'" . $valor;
                        }
                        $valores[] = $valor;
                    }
                    fputcsv($stream, $valores, ';', '"', '');
                    $ultimo = $linha['id_lattes'];
                }
            } while (count($linhas) === 500);
            rewind($stream);
            $csv = stream_get_contents($stream);
            fclose($stream);
            return $this->response->download('indicadores_lattes_' . date('Y-m-d') . '.csv', $csv)
                ->setContentType('text/csv', 'UTF-8');
        } catch (Throwable $e) {
            if (isset($stream) && is_resource($stream)) {
                fclose($stream);
            }
            log_message('error', 'Exportação de indicadores: {erro}', ['erro' => $e->getMessage()]);
            return redirect()->to(site_url('admin'))->with('indicadores_erro', 'Não foi possível exportar. Verifique a conexão e a tabela curriculos_lattes.');
        }
    }

    private function pagina(): string
    {
        $model = new CurriculoLattesModel();
        $data = [
            'resultado' => session()->getFlashdata('indicadores_resultado'),
            'erro' => session()->getFlashdata('indicadores_erro'),
            'curriculos' => [],
            'total' => 0,
            'pager' => null,
            'campos' => $model->campos(),
        ];
        try {
            if (!db_connect()->tableExists('curriculos_lattes')) {
                $data['erro'] = 'A tabela curriculos_lattes ainda não existe. Crie a tabela com o SQL dos indicadores antes de importar.';
            } else {
                $data['curriculos'] = $model->orderBy('nome', 'ASC')->paginate(25);
                $data['pager'] = $model->pager;
                $data['total'] = $model->pager->getTotal();
            }
        } catch (Throwable $e) {
            log_message('error', 'Consulta de indicadores: {erro}', ['erro' => $e->getMessage()]);
            $data['erro'] = 'Não foi possível consultar os indicadores. Verifique a conexão com o banco.';
        }
        return view('layout/header') . view('admin/index', $data) . view('layout/footer');
    }
}
