<?php
// Execute: php v2/tests/standalone/indicadores_globais.php
define('FCPATH', dirname(__DIR__, 2) . '/public/');
define('ENVIRONMENT', 'testing');
require dirname(__DIR__, 2) . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootConsole($paths);
$db = Config\Database::connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true], false);
$model = new App\Models\CurriculoLattesModel($db);
$service = new App\Libraries\IndicadoresGlobais();
$cols = ['id_lattes TEXT PRIMARY KEY', 'nome TEXT', 'atualizacao_cv TEXT', 'end_prof_instituicao TEXT', 'primeira_grande_area TEXT'];
foreach ($service::GRUPOS as $grupo) {
    foreach ($grupo['itens'] as $campo => $label) {
        if (!in_array($campo, $model->campos(), true)) throw new RuntimeException('Campo desconhecido: ' . $campo);
        $cols[] = $campo . ' INTEGER DEFAULT 0';
    }
}
$db->query('CREATE TABLE curriculos_lattes (' . implode(',', $cols) . ')');
$check = static function ($esperado, $valor, $label) {
    if ($esperado !== $valor) throw new RuntimeException($label . ': ' . json_encode($valor));
};
$vazio = $service->resumo($model);
$check(0, $vazio['curriculos'], 'Base vazia');
$check(0, $vazio['grupos']['bibliografica']['total'], 'Soma vazia');
$check([], $vazio['areas'], 'Distribuição vazia');
$check(true, str_contains(view('indicators/global', ['resumo' => $vazio, 'erro' => null]), 'Um panorama em construção'), 'Estado vazio');
$model->insertBatch([
    ['id_lattes' => '0000000000000001', 'nome' => 'A', 'end_prof_instituicao' => ' Instituto A ', 'primeira_grande_area' => 'CIENCIAS_HUMANAS', 'artigos_periodicos' => 5, 'orientacoes_tcc' => 2, 'atualizacao_cv' => '2020-01-01'],
    ['id_lattes' => '0000000000000002', 'nome' => 'B', 'end_prof_instituicao' => 'Instituto A', 'primeira_grande_area' => 'CIENCIAS_HUMANAS', 'artigos_periodicos' => 3, 'orientacoes_tcc' => 1, 'atualizacao_cv' => '2025-04-30'],
    ['id_lattes' => '0000000000000003', 'nome' => 'C', 'end_prof_instituicao' => '', 'primeira_grande_area' => null, 'artigos_periodicos' => 0, 'orientacoes_tcc' => 0, 'atualizacao_cv' => null],
]);
$resumo = $service->resumo($model);
$check(3, $resumo['curriculos'], 'Contagem');
$check(1, $resumo['instituicoes'], 'Instituições distintas sem vazios');
$check(2, $resumo['com_data'], 'Cobertura de datas');
$check('2025-04-30', $resumo['ultima_atualizacao'], 'Última atualização');
$check(8, $resumo['grupos']['bibliografica']['total'], 'Publicações');
$check(3, $resumo['grupos']['orientacoes']['total'], 'Orientações');
$check(2, count($resumo['areas']), 'Agrupamento por área e não por pesquisador');
$check(['nome' => 'CIENCIAS_HUMANAS', 'total' => 2], $resumo['areas'][0], 'Distribuição');
$check(['nome' => 'Não informado', 'total' => 1], $resumo['areas'][1], 'Ausência de área');
$check(['nome' => 'Instituto A', 'total' => 2], $resumo['instituicoes_top'][0], 'Normalização de espaços');
$resumo['instituicoes_top'][0]['nome'] = '<script>alert(1)</script>';
$html = view('indicators/global', ['resumo' => $resumo, 'erro' => null]);
$check(false, str_contains($html, '<script>alert(1)</script>'), 'Escape de conteúdo');
$check(true, str_contains($html, '66,7%'), 'Percentuais sobre toda a base');
$check(9, substr_count($html, 'data-ig-group='), 'Nove temas disponíveis');
$erro = view('indicators/global', ['resumo' => null, 'erro' => 'Indisponível']);
$check(true, str_contains($erro, 'role="alert"'), 'Estado de erro');
echo "OK: agregações, agrupamentos, datas, estados vazio/erro e renderização segura." . PHP_EOL;
