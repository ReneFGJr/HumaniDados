<?php

namespace App\Models;

use CodeIgniter\Model;

class IndicadoresModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indicadores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Campos permitidos para insert/update
    protected $allowedFields = [
        'arg1',
        'arg2',
        'arg3',
        'arg4',
        'result',
    ];

    // Controle de timestamps
    protected $useTimestamps = false; // created_at já é automático no MySQL

    // Validações (opcionais)
    protected $validationRules = [];

    protected $validationMessages = [];

    protected $skipValidation = false;

    // =============================
    // MÉTODOS ÚTEIS
    // =============================

    /**
     * Retorna o último registro salvo
     */
    public function getLast()
    {
        return $this->orderBy('id', 'DESC')->first();
    }

    /**
     * Busca por combinação de argumentos
     */
    public function findByArgs($arg1, $arg2, $arg3, $arg4)
    {
        if ($arg4 === null) {
            $arg4 = '';
        }
        if ($arg3 === null) {
            $arg3 = '';
        }   
        if ($arg2 === null) {
            $arg2 = '';
        }
        if ($arg1 === null) {
            $arg1 = '';
        }
        $dt = $this->where([
            'arg1' => $arg1,
            'arg2' => $arg2,
            'arg3' => $arg3,
            'arg4' => $arg4,
        ])->first();
        if ($dt != []) {
            $dt['result'] = json_decode($dt['result'], true);
        }
        return $dt['result'] ?? null;
    }

    public function saveIndicador($arg1, $arg2, $arg3, $arg4, $result)
    {
        if ($arg4 === null) {
            $arg4 = '';
        }
        if ($arg3 === null) {
            $arg3 = '';
        }   
        if ($arg2 === null) {
            $arg2 = '';
        }
        if ($arg1 === null) {
            $arg1 = '';
        }        
        // se result for array → transforma em JSON
        if (is_array($result)) {
            $result = json_encode($result, JSON_UNESCAPED_UNICODE);
        }

        $dt = $this->where([
            'arg1' => $arg1,
            'arg2' => $arg2,
            'arg3' => $arg3,
            'arg4' => $arg4,
        ])->first();

        if ($dt != []) {
            // Atualiza
            $data = [
                'result' => $result,
            ];
            return $this->update($dt['id'], $data);
        } else {
            $data = [
                'arg1'   => trim($arg1),
                'arg2'   => trim($arg2),
                'arg3'   => trim($arg3),
                'arg4'   => trim($arg4),
                'result' => $result,
            ];

            if ($this->insert($data)) {
                return $this->getInsertID(); // retorna o ID recém inserido
            }
        }


        return false; // erro na inserção
    }
}
