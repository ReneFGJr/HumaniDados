<?php

namespace App\Models;

use CodeIgniter\Model;

class LattesResearchersAreaModel extends Model
{
    protected $table            = 'lattes_researchers_area';
    protected $primaryKey       = 'id_ra';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'ra_idlattes',
        'ra_area_1',
        'ra_area_2',
        'ra_area_3',
        'ra_area_4'
    ];

    protected $useTimestamps = false;

    function extactAreas($xml, $idlattes)
    {
        /* Zera Dados anteriores */
        $this->zeraDados($idlattes);

        $AreasCnpqModel = new AreasCnpqModel();
        if (!$xml || !isset($xml->{'DADOS-GERAIS'})) {
            return [];
        }

        $xml = $xml->{'DADOS-GERAIS'};


        if (isset($xml->{'AREAS-DE-ATUACAO'})) {
            foreach ($xml->{'AREAS-DE-ATUACAO'}->children() as $tipo => $items) {
                $itemsArray = (array) $items;
                $areas = $itemsArray['@attributes'];
                $area1 = $AreasCnpqModel->getByName($areas['NOME-GRANDE-AREA-DO-CONHECIMENTO'],1);
                $area2 = $AreasCnpqModel->getByName($areas['NOME-DA-AREA-DO-CONHECIMENTO'],2);
                $area3 = $AreasCnpqModel->getByName($areas['NOME-DA-SUB-AREA-DO-CONHECIMENTO'],3);
                $area4 = $AreasCnpqModel->getByName($areas['NOME-DA-ESPECIALIDADE'],4);
                $this->saveAreas($idlattes,$area1,$area2,$area3,$area4);
            }
        }
    }

    function saveAreas($idlattes, $area1, $area2, $area3, $area4)
    {
        $data = [
            'ra_idlattes' => $idlattes,
            'ra_area_1' => $area1 ?? 0,
            'ra_area_2' => $area2 ?? 0,
            'ra_area_3' => $area3 ?? 0,
            'ra_area_4' => $area4 ?? 0,
        ];

        $this->insert($data);
    }

    function zeraDados($idlattes)
    {
        $this->where('ra_idlattes', $idlattes)->delete();
    }
}
