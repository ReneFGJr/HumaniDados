<?php

namespace App\Models;

use CodeIgniter\Model;

class InstituicaoLattesModel extends Model
{
    protected $table = 'instituicoes_lattes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'codigo_instituicao_empresa',
        'nome_instituicao_empresa',
        'codigo_orgao',
        'nome_orgao',
        'codigo_unidade',
        'nome_unidade',
        'logradouro_complemento',
        'pais',
        'uf',
        'cep',
        'cidade',
        'bairro',
        'ddd',
        'telefone',
        'ramal',
        'fax',
        'caixa_postal',
        'home_page'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    /**
     * ================================================================
     * 🔍 salvarSeNaoExistir()
     * Recebe um array de dados do XML
     * Verifica se já existe a instituição pelo código
     * Se não existir → cadastra
     * Retorna o ID da instituição
     * ================================================================
     */
    public function checkInstituicao(array $dados)
    {
        if (!isset($dados['CODIGO-INSTITUICAO-EMPRESA'])) {
            return null; // XML inválido
        }

        $codigo = (string) $dados['CODIGO-INSTITUICAO-EMPRESA'];

        // 🔎 1. Verificar se já existe no banco
        $existe = $this->where('codigo_instituicao_empresa', $codigo)->first();

        if ($existe) {
            $nomeNovo = trim((string)($dados['NOME-INSTITUICAO-EMPRESA'] ?? ''));
            $nomeAtual = trim((string)($existe['nome_instituicao_empresa'] ?? ''));

            if ($nomeNovo !== '' && $nomeNovo !== $nomeAtual) {
                $this->update($existe['id'], [
                    'nome_instituicao_empresa' => $nomeNovo,
                ]);
            }

            return $existe['id']; // já cadastrado
        }

        // 🆕 2. Inserir novo
        $insertData = [
            'codigo_instituicao_empresa' => $codigo,
            'nome_instituicao_empresa'   => (string)($dados['NOME-INSTITUICAO-EMPRESA'] ?? ''),
            'pais'                       => (string)($dados['PAIS'] ?? ''),
            'uf'                         => (string)($dados['UF'] ?? ''),
            'cidade'                     => (string)($dados['CIDADE'] ?? ''),
        ];

        return $this->insert($insertData);
    }

    function pesquisadoresVinculados($id, $codigoInstituicao = null)
    {
        $LattesResearcherModel = new LattesResearcherModel();

        $codigoInstituicao = trim((string) ($codigoInstituicao ?? ''));
        if ($codigoInstituicao !== '') {
            $idsInstituicao = $this
                ->select('id')
                ->where('codigo_instituicao_empresa', $codigoInstituicao)
                ->findColumn('id');

            if (!empty($idsInstituicao)) {
                return $LattesResearcherModel
                    ->whereIn('vinculo_instituicao', $idsInstituicao)
                    ->orderBy('nome_completo')
                    ->findAll();
            }
        }

        return $LattesResearcherModel
            ->where('vinculo_instituicao', $id)
            ->orderBy('nome_completo')
            ->findAll();
    }

    function le($id)
    {
        $dt = $this->find($id);
        $codigo = $dt['codigo_instituicao_empresa'] ?? null;
        $pesquisadores = $this->pesquisadoresVinculados($id, $codigo);
        $dt['pesquisadores'] = $pesquisadores;
        $dt['pesquisadores_total'] = count($pesquisadores);
        return $dt;
    }
}
