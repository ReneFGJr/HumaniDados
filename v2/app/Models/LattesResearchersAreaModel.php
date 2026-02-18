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

    function areasConhecimentoAll()
    {
        return $this->areasResearcherAll();
    }

    function areasResearcherAll($idlattes = '')
    {
        $cp = 'a1.cnpq_area as area_1, ';
        $cp .= 'a2.cnpq_area as area_2, ';
        $cp .= 'a3.cnpq_area as area_3, ';
        $cp .= 'a4.cnpq_area as area_4, ';
        $cp .= 'a1.cnpq_icone as cnpq_icone';

        $this
            ->select($cp)
            ->join('areas_cnpq as a1', 'a1.id_cnpq = ra_area_1', 'left')
            ->join('areas_cnpq as a2', 'a2.id_cnpq = ra_area_2', 'left')
            ->join('areas_cnpq as a3', 'a3.id_cnpq = ra_area_3', 'left')
            ->join('areas_cnpq as a4', 'a4.id_cnpq = ra_area_4', 'left');
        if ($idlattes) {
            $this->where('ra_idlattes', $idlattes);
        }
        $dt = $this->findAll();

        foreach ($dt as $k => $v) {
            $name = $v['area_1'];
            $name = str_replace('_', ' ', $name);
            $name = ucfirst(strtolower($name));
            $dt[$k]['area_1'] = $name;
        }

        $mtx = [];
        foreach ($dt as $v) {
            $area1 = $v['area_1'] ?: '';
            $area2 = $v['area_2'] ?: '';
            $area3 = $v['area_3'] ?: '';
            $area4 = $v['area_4'] ?: '';

            if (!isset($mtx[$area1])) {
                $mtx[$area1]['total'] = 1;
            } else {
                $mtx[$area1]['total']++;
            }
            /*********** Área 2 */
            if ($area2 != '') {
                if (!isset($mtx[$area1][$area2])) {
                    $mtx[$area1][$area2]['total'] = 1;
                } else {
                    $mtx[$area1][$area2]['total']++;
                }
            }
            /*********** Área 3 */
            if ($area3 != '') {
                if (!isset($mtx[$area1][$area2][$area3])) {
                    $mtx[$area1][$area2][$area3]['total'] = 1;
                } else {
                    $mtx[$area1][$area2][$area3]['total']++;
                }
            }
            /*********** Área 3 */
            if ($area4 != '') {
                if (!isset($mtx[$area1][$area2][$area3][$area4])) {
                    $mtx[$area1][$area2][$area3][$area4]['total'] = 1;
                } else {
                    $mtx[$area1][$area2][$area3]['total']++;
                }
            }

        }
        return $mtx;
    }

    function areasResearcher($idlattes)
    {
        $cp = 'cnpq_area, cnpq_icone';
        $dt = $this
            ->select($cp)
            ->join('areas_cnpq', 'id_cnpq = ra_area_1', 'left')
            ->where('ra_idlattes', $idlattes)
            ->groupby($cp)
            ->findAll();
        foreach ($dt as $k => $v) {
            $name = $v['cnpq_area'];
            $name = str_replace('_',' ', $name);
            $name = ucfirst(strtolower($name));
            $dt[$k]['cnpq_area'] = $name;
        }
        return $dt;
    }

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
