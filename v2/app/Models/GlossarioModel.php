<?php

namespace App\Models;

use CodeIgniter\Model;

class GlossarioModel extends Model
{
    protected $table            = 'glossario';
    protected $primaryKey       = 'id_term';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'term_termo',
        'term_description'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // não existe updated_at na tabela

    // -------------------------------------------------------------
    // 🔎 Buscar termo pelo nome (exato)
    // -------------------------------------------------------------
    public function getTerms()
    {
        return $this->orderby('term_termo')->findAll();
    }

    // -------------------------------------------------------------
    // 🔎 Buscar termo pelo nome (exato)
    // -------------------------------------------------------------
    public function getByTerm($term)
    {
        return $this->where('term_termo', $term)->first();
    }

    // -------------------------------------------------------------
    // 🔎 Buscar termo por parte do nome (like)
    // -------------------------------------------------------------
    public function searchTerms($text)
    {
        return $this->like('term_termo', $text)
            ->orLike('term_description', $text)
            ->findAll();
    }
}
