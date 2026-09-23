<?php

namespace App\Libraries;

use App\Models\CurriculoLattesModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Throwable;

class ImportadorIndicadoresLattes
{
    public function importar(string $diretorio, CurriculoLattesModel $model): array
    {
        if (!is_dir($diretorio) || !is_readable($diretorio)) {
            throw new RuntimeException('Diretório database/xml ausente ou sem permissão de leitura.');
        }
        $resultado = ['total' => 0, 'inseridos' => 0, 'atualizados' => 0, 'erros' => 0, 'arquivos' => []];
        $extrator = new LattesIndicadores();
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($diretorio, RecursiveDirectoryIterator::SKIP_DOTS));
        $arquivos = [];
        foreach ($iterator as $arquivo) {
            if ($arquivo->isFile() && !$arquivo->isLink() && strtolower($arquivo->getExtension()) === 'xml') {
                $arquivos[] = $arquivo->getPathname();
            }
        }
        sort($arquivos, SORT_STRING);
        foreach ($arquivos as $arquivo) {
            $resultado['total']++;
            $linha = ['arquivo' => substr($arquivo, strlen(rtrim($diretorio, '/\\')) + 1), 'id_lattes' => '', 'nome' => '', 'status' => 'erro', 'mensagem' => ''];
            try {
                $dados = $extrator->extrairArquivo($arquivo);
                $linha['id_lattes'] = $dados['id_lattes'];
                $linha['nome'] = $dados['nome'];
                $linha['status'] = $model->salvarIndicadores($dados);
                $resultado[$linha['status'] === 'inserido' ? 'inseridos' : 'atualizados']++;
            } catch (Throwable $e) {
                $resultado['erros']++;
                $linha['mensagem'] = $e instanceof RuntimeException && !($e instanceof \CodeIgniter\Database\Exceptions\DatabaseException)
                    ? $e->getMessage() : 'Falha ao processar ou salvar o arquivo. Consulte o log da aplicação.';
                log_message('error', 'Importação de indicadores ({arquivo}): {erro}', ['arquivo' => basename($arquivo), 'erro' => $e->getMessage()]);
            }
            $resultado['arquivos'][] = $linha;
        }
        return $resultado;
    }
}
