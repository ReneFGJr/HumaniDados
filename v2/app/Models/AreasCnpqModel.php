<?php

namespace App\Models;

use CodeIgniter\Model;

class AreasCnpqModel extends Model
{
    protected $table            = 'areas_cnpq';
    protected $primaryKey       = 'id_cnpq';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'cnpq_grupo',
        'cnpq_area'
    ];

    protected $useTimestamps = false;

    // ==========================
    // Métodos auxiliares
    // ==========================

    function getByName(string $name, $nivel = 0)
    {
        if ($name == '') return 0;
        $dt = $this->where('cnpq_area', $name)
            ->first();
        if (!$dt){
            $dd = [];
            $dd['cnpq_area'] = $name;
            $dd['cnpq_grupo'] = $nivel;
            $id = $this->set($dd)->insert();
            return $id;
        }
        return $dt['id_cnpq'];
    }
    /**
     * Retorna áreas por grupo CNPq
     */
    public function getByGrupo(int $grupo)
    {
        return $this->where('cnpq_grupo', $grupo)
            ->orderBy('cnpq_area', 'ASC')
            ->findAll();
    }

    /**
     * Busca por nome da área (like)
     */
    public function searchArea(string $term)
    {
        return $this->like('cnpq_area', $term)
            ->orderBy('cnpq_area', 'ASC')
            ->findAll();
    }

    /**
     * Lista grupos distintos
     */
    public function getGrupos()
    {
        return $this->select('cnpq_grupo')
            ->distinct()
            ->orderBy('cnpq_grupo', 'ASC')
            ->findAll();
    }
}
