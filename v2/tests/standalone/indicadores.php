<?php

// Execute: php v2/tests/standalone/indicadores.php
define('FCPATH', dirname(__DIR__, 2) . '/public/');
define('ENVIRONMENT', 'testing');
require dirname(__DIR__, 2) . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootConsole($paths);

use App\Libraries\LattesIndicadores;
use App\Libraries\ImportadorIndicadoresLattes;
use App\Models\CurriculoLattesModel;

$checks = 0;
function verificar($esperado, $recebido, string $mensagem): void
{
    global $checks;
    $checks++;
    if ($esperado !== $recebido) {
        throw new RuntimeException($mensagem . ': esperado ' . var_export($esperado, true) . ', recebido ' . var_export($recebido, true));
    }
}
function rejeitar(callable $acao, string $mensagem): void
{
    try {
        $acao();
    } catch (RuntimeException $e) {
        verificar(true, true, $mensagem);
        return;
    }
    throw new RuntimeException($mensagem);
}

$xml = <<<'XML'
<CURRICULO-VITAE NUMERO-IDENTIFICADOR="0000039015885890" DATA-ATUALIZACAO="29042021">
<DADOS-GERAIS NOME-COMPLETO="Pesquisador de teste" PAIS-DE-NASCIMENTO="Brasil" PAIS-DE-NACIONALIDADE="Brasil">
<FORMACAO-ACADEMICA-TITULACAO><GRADUACAO/><MESTRADO/><DOUTORADO/><POS-DOUTORADO/><POS-DOUTORADO/></FORMACAO-ACADEMICA-TITULACAO>
<AREAS-DE-ATUACAO>
<AREA-DE-ATUACAO SEQUENCIA-AREA-DE-ATUACAO="2" NOME-DA-AREA-DO-CONHECIMENTO="Segunda"/>
<AREA-DE-ATUACAO SEQUENCIA-AREA-DE-ATUACAO="1" NOME-DA-AREA-DO-CONHECIMENTO="Física"/>
</AREAS-DE-ATUACAO>
<ATUACOES-PROFISSIONAIS><ATUACAO-PROFISSIONAL>
<ATIVIDADES-DE-ENSINO><ENSINO><DISCIPLINA/><DISCIPLINA/></ENSINO></ATIVIDADES-DE-ENSINO>
<ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO><PARTICIPACAO-EM-PROJETO>
<PROJETO-DE-PESQUISA NATUREZA="PESQUISA"/><PROJETO-DE-PESQUISA NATUREZA="EXTENSAO"/>
<PROJETO-DE-PESQUISA NATUREZA="ENSINO"/><PROJETO-DE-PESQUISA NATUREZA="DESENVOLVIMENTO"/><PROJETO-DE-PESQUISA NATUREZA="OUTRA"/>
</PARTICIPACAO-EM-PROJETO></ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO>
</ATUACAO-PROFISSIONAL></ATUACOES-PROFISSIONAIS>
</DADOS-GERAIS>
<PRODUCAO-BIBLIOGRAFICA>
<ARTIGOS-PUBLICADOS><ARTIGO-PUBLICADO/><ARTIGO-PUBLICADO/></ARTIGOS-PUBLICADOS>
<TRABALHOS-EM-EVENTOS>
<TRABALHO-EM-EVENTOS><DADOS-BASICOS-DO-TRABALHO NATUREZA="COMPLETO"/></TRABALHO-EM-EVENTOS>
<TRABALHO-EM-EVENTOS><DADOS-BASICOS-DO-TRABALHO NATUREZA="RESUMO"/></TRABALHO-EM-EVENTOS>
<TRABALHO-EM-EVENTOS><DADOS-BASICOS-DO-TRABALHO NATUREZA="RESUMO_EXPANDIDO"/></TRABALHO-EM-EVENTOS>
</TRABALHOS-EM-EVENTOS>
<LIVROS-E-CAPITULOS><LIVROS-PUBLICADOS-OU-ORGANIZADOS>
<LIVRO-PUBLICADO-OU-ORGANIZADO><DADOS-BASICOS-DO-LIVRO TIPO="LIVRO_PUBLICADO"/></LIVRO-PUBLICADO-OU-ORGANIZADO>
<LIVRO-PUBLICADO-OU-ORGANIZADO><DADOS-BASICOS-DO-LIVRO TIPO="LIVRO_ORGANIZADO_OU_EDICAO"/></LIVRO-PUBLICADO-OU-ORGANIZADO>
</LIVROS-PUBLICADOS-OU-ORGANIZADOS></LIVROS-E-CAPITULOS>
</PRODUCAO-BIBLIOGRAFICA>
<PRODUCAO-TECNICA><SOFTWARE><DETALHAMENTO-DO-SOFTWARE><REGISTRO-OU-PATENTE/><REGISTRO-OU-PATENTE/></DETALHAMENTO-DO-SOFTWARE></SOFTWARE><SOFTWARE/>
<DEMAIS-TIPOS-DE-PRODUCAO-TECNICA><CURSO-DE-CURTA-DURACAO-MINISTRADO/></DEMAIS-TIPOS-DE-PRODUCAO-TECNICA>
</PRODUCAO-TECNICA>
<OUTRA-PRODUCAO><ORIENTACOES-CONCLUIDAS>
<ORIENTACOES-CONCLUIDAS-PARA-MESTRADO><DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO TIPO-DE-ORIENTACAO="ORIENTADOR_PRINCIPAL"/></ORIENTACOES-CONCLUIDAS-PARA-MESTRADO>
<ORIENTACOES-CONCLUIDAS-PARA-MESTRADO><DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO TIPO-DE-ORIENTACAO="CO_ORIENTADOR"/></ORIENTACOES-CONCLUIDAS-PARA-MESTRADO>
<OUTRAS-ORIENTACOES-CONCLUIDAS><DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS NATUREZA="TRABALHO_DE_CONCLUSAO_DE_CURSO_GRADUACAO"/></OUTRAS-ORIENTACOES-CONCLUIDAS>
<OUTRAS-ORIENTACOES-CONCLUIDAS><DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS NATUREZA="INICIACAO_CIENTIFICA"/></OUTRAS-ORIENTACOES-CONCLUIDAS>
<OUTRAS-ORIENTACOES-CONCLUIDAS><DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS NATUREZA="MONOGRAFIA_DE_CONCLUSAO_DE_CURSO_APERFEICOAMENTO_E_ESPECIALIZACAO"/></OUTRAS-ORIENTACOES-CONCLUIDAS>
<OUTRAS-ORIENTACOES-CONCLUIDAS><DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS NATUREZA="ORIENTACAO-DE-OUTRA-NATUREZA"/></OUTRAS-ORIENTACOES-CONCLUIDAS>
</ORIENTACOES-CONCLUIDAS></OUTRA-PRODUCAO>
<DADOS-COMPLEMENTARES>
<FORMACAO-COMPLEMENTAR><FORMACAO-COMPLEMENTAR-CURSO-DE-CURTA-DURACAO/><FORMACAO-COMPLEMENTAR-CURSO-DE-CURTA-DURACAO/></FORMACAO-COMPLEMENTAR>
<ORIENTACOES-EM-ANDAMENTO><ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO><DETALHAMENTO-DA-ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO TIPO-DE-ORIENTACAO="CO_ORIENTADOR"/></ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO></ORIENTACOES-EM-ANDAMENTO>
<PARTICIPACAO-EM-BANCA-TRABALHOS-CONCLUSAO>
<PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO><DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO NATUREZA="Exame de qualificação de mestrado"/></PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO>
<PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO><DADOS-BASICOS-DA-PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO NATUREZA="Exame de qualificação de doutorado"/></PARTICIPACAO-EM-BANCA-DE-EXAME-QUALIFICACAO>
</PARTICIPACAO-EM-BANCA-TRABALHOS-CONCLUSAO>
<PARTICIPACAO-EM-EVENTOS-CONGRESSOS><PARTICIPACAO-EM-CONGRESSO/></PARTICIPACAO-EM-EVENTOS-CONGRESSOS>
</DADOS-COMPLEMENTARES>
</CURRICULO-VITAE>
XML;
$extrator = new LattesIndicadores();
$dados = $extrator->extrairXml($xml);
foreach ([
    'id_lattes' => '0000039015885890', 'atualizacao_cv' => '2021-04-29', 'nascimento' => 'Brasil',
    'primeira_area' => 'Física', 'end_prof_instituicao' => null, 'pos_doutorado' => 2,
    'artigos_periodicos' => 2, 'artigos_eventos' => 1, 'resumos' => 1, 'resumos_expandidos' => 1,
    'livros_completos' => 1, 'livros_organizados' => 1, 'softwares_com_patente' => 1,
    'softwares_sem_patente' => 1, 'cursos_curta_duracao_formacao' => 2,
    'cursos_curta_duracao_producao' => 1, 'atividades_ensino' => 1,
    'orientacoes_mestrado_principal' => 1, 'orientacoes_mestrado_coorientador' => 1,
    'orientacoes_doutorado_andamento_principal' => 0, 'orientacoes_doutorado_andamento_coorientador' => 1,
    'orientacoes_tcc' => 1, 'orientacoes_ic' => 1, 'orientacoes_especializacao' => 1,
    'orientacoes_outra_natureza' => 1, 'bancas_qualificacao_mestrado' => 1,
    'bancas_qualificacao_doutorado' => 1, 'projetos_pesquisa' => 1, 'projetos_extensao' => 1,
    'projetos_ensino' => 1, 'projetos_desenvolvimento' => 1, 'projetos_outros' => 1,
    'participacao_congressos' => 1, 'participacao_seminarios' => 0,
] as $campo => $esperado) {
    verificar($esperado, $dados[$campo], $campo);
}
rejeitar(fn () => $extrator->extrairXml('<invalid>'), 'XML malformado deve falhar');
rejeitar(fn () => $extrator->extrairXml('<OUTRO/>'), 'Raiz incorreta deve falhar');
rejeitar(fn () => $extrator->extrairXml('<!DOCTYPE x><CURRICULO-VITAE/>'), 'DTD deve falhar');
rejeitar(fn () => $extrator->extrairXml(str_replace('0000039015885890', '123', $xml)), 'ID inválido deve falhar');
rejeitar(fn () => $extrator->extrairXml(str_replace('29042021', '31022021', $xml)), 'Data impossível deve falhar');
rejeitar(fn () => $extrator->extrairXml($xml, '0000000000000001'), 'IDs divergentes devem falhar');
$semId = str_replace('NUMERO-IDENTIFICADOR="0000039015885890"', 'NUMERO-IDENTIFICADOR=""', $xml);
verificar('0000039015885890', $extrator->extrairXml($semId, '0000039015885890')['id_lattes'], 'Fallback para nome do arquivo');
rejeitar(fn () => $extrator->extrairXml($semId), 'Sem ID e sem fallback deve falhar');

// Banco isolado: nunca escreve no banco configurado no .env.
config('Database')->tests = [
    'DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true,
    'foreignKeys' => true, 'busyTimeout' => 1000, 'dateFormat' => ['date' => 'Y-m-d', 'datetime' => 'Y-m-d H:i:s', 'time' => 'H:i:s'],
];
config('Database')->defaultGroup = 'tests';
$db = Config\Database::connect('tests');
$model = new CurriculoLattesModel($db);
verificar([], array_values(array_diff($model->campos(), array_keys($dados))), 'Todos os campos devem ser extraídos');
verificar([], array_values(array_diff(array_keys($dados), $model->campos())), 'Sem campos extras');
$definicoes = [];
foreach ($model->campos() as $campo) {
    $definicoes[] = '"' . $campo . '" ' . ($campo === 'id_lattes' ? 'TEXT PRIMARY KEY' : (is_int($dados[$campo]) ? 'INTEGER NOT NULL DEFAULT 0' : 'TEXT'));
}
$db->query('CREATE TABLE curriculos_lattes (' . implode(',', $definicoes) . ')');
verificar('inserido', $model->salvarIndicadores($dados), 'Primeira importação');
verificar('atualizado', $model->salvarIndicadores($dados), 'Reimportação');
verificar(1, $model->countAllResults(), 'Reimportação não pode duplicar');
verificar('0000039015885890', $model->find('0000039015885890')['id_lattes'], 'Preservar zeros no banco');
$dados['artigos_periodicos'] = 0;
$model->salvarIndicadores($dados);
verificar(0, (int) $model->find($dados['id_lattes'])['artigos_periodicos'], 'Substituir contagem anterior por zero');

$diretorio = sys_get_temp_dir() . '/humanidados_indicadores_' . bin2hex(random_bytes(6));
mkdir($diretorio);
mkdir($diretorio . '/sub');
try {
    file_put_contents($diretorio . '/0000039015885890.XML', $xml);
    file_put_contents($diretorio . '/sub/invalido.xml', '<invalid>');
    file_put_contents($diretorio . '/ignorado.txt', 'não é XML');
    $resultado = (new ImportadorIndicadoresLattes())->importar($diretorio, $model);
    verificar(2, $resultado['total'], 'Varredura recursiva e extensão maiúscula');
    verificar(1, $resultado['atualizados'], 'Arquivo válido salvo apesar do erro em outro');
    verificar(1, $resultado['erros'], 'Erro individual reportado');
    verificar(2, count($resultado['arquivos']), 'Resultado por arquivo');
} finally {
    unlink($diretorio . '/0000039015885890.XML');
    unlink($diretorio . '/sub/invalido.xml');
    unlink($diretorio . '/ignorado.txt');
    rmdir($diretorio . '/sub');
    rmdir($diretorio);
}
foreach (glob(dirname(rtrim(ROOTPATH, '/\\')) . '/database/xml/*.xml') as $arquivo) {
    $real = $extrator->extrairArquivo($arquivo);
    verificar(119, count($real), 'Campos do XML real ' . basename($arquivo));
}

// Exercita o controller, a exportação em mais de um lote e o controle de acesso.
$request = Config\Services::incomingrequest(config('App'), false);
Config\Services::injectMock('request', $request);
$filter = new App\Filters\Admin();
session()->remove(['isLoggedIn', 'user_perfil']);
verificar(302, $filter->before($request)->getStatusCode(), 'Visitante deve ir para login');
session()->set(['isLoggedIn' => true, 'user_perfil' => 'user']);
verificar(403, $filter->before($request)->getStatusCode(), 'Usuário comum não pode administrar');
session()->set('user_perfil', 'admin');
verificar(null, $filter->before($request), 'Administrador autorizado');

$controller = new App\Controllers\Admin();
$controller->initController($request, Config\Services::response(null, false), service('logger'));
$pagina = $controller->index();
verificar(true, str_contains($pagina, 'Exportação indicadores'), 'Página deve ter botão de exportação');
verificar(true, str_contains($pagina, 'admin/inport/alttes'), 'Página deve usar a rota solicitada');
verificar(true, str_contains($pagina, '0000039015885890'), 'Página deve mostrar o ID completo');

$model->update('0000039015885890', ['nome' => '=FORMULA; "teste"']);
$lote = [];
for ($i = 1; $i <= 500; $i++) {
    $lote[] = ['id_lattes' => str_pad((string) $i, 16, '0', STR_PAD_LEFT), 'nome' => 'Teste CSV'];
}
$model->insertBatch($lote);
$download = $controller->exportAll();
verificar(true, $download instanceof CodeIgniter\HTTP\DownloadResponse, 'Exportação deve retornar download');
ob_start();
$download->sendBody();
$csv = ob_get_clean();
verificar("\xEF\xBB\xBF", substr($csv, 0, 3), 'CSV deve conter BOM UTF-8');
$stream = fopen('php://temp', 'w+');
fwrite($stream, substr($csv, 3));
rewind($stream);
verificar($model->campos(), fgetcsv($stream, 0, ';', '"', ''), 'Cabeçalho dos 119 campos');
$totalCsv = 0;
$encontrou = false;
while (($linha = fgetcsv($stream, 0, ';', '"', '')) !== false) {
    $totalCsv++;
    verificar(119, count($linha), 'Largura do CSV');
    if ($linha[0] === "'0000039015885890") {
        verificar("'=FORMULA; \"teste\"", $linha[1], 'Neutralizar fórmula e preservar delimitadores');
        $encontrou = true;
    }
}
fclose($stream);
verificar(501, $totalCsv, 'Exportar todos os registros além do primeiro lote');
verificar(true, $encontrou, 'Preservar zeros do ID no CSV');
session()->remove(['isLoggedIn', 'user_perfil']);

echo "OK: $checks verificações (incluindo XMLs locais e banco SQLite em memória)." . PHP_EOL;
