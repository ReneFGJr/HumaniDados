<?php
namespace App\Models;
use CodeIgniter\Model;

class AutoresProducaoModel extends Model
{
    protected $table = 'autores_producao';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tipo', 'id_producao', 'nome', 'citacao', 'ordem', 'id_cnpq'
    ];
}
