<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducaoArtisticaKeywordsModel extends Model
{
    protected $table = 'producao_artistica_keywords';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_producao', 'keyword'];
}
