<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducaoArtisticaAutoresModel extends Model
{
    protected $table = 'producao_artistica_autores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_producao',
        'nome_completo',
        'nome_citacao',
        'ordem_autoria',
        'id_cnpq'
    ];
}
