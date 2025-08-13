<?php

namespace App\Controllers;

use App\Libraries\XsdParser;
use CodeIgniter\Controller;

helper('sisdoc');
helper('lattes');

class XsdViewer extends Controller
{
    /**
     * Caminho do XSD (ajuste conforme o seu deploy).
     * Você pode mover para .env/Config depois.
     */
    private string $xsdPath = '_repository/xml_cvbase_src_main_resources_CurriculoLattes_12_09_2022.xsd';

    public function index()
    {
        helper('filesystem');
        $sx = view('headers/header');

        $dom = new \DOMDocument();
        $dom->load($this->xsdPath, LIBXML_NONET);

        $elements = $dom->getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema', 'element');

        $DD = [];

        foreach ($elements as $element) {
            /** @var \DOMElement $element */
            $nameE = ($element->getAttribute('name') ?: $element->getAttribute('ref'));
            //echo "<br><b>Element Name</b>: " . $nameE . "<br>\n";
            $DDE = [];

            // pega apenas descendentes xs:element deste elemento (não o documento todo)
            $children = $element->getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema', 'element');
            foreach ($children as $child) {
                /** @var \DOMElement $child */
                $childE = ($child->getAttribute('name') ?: $child->getAttribute('ref'));
                //echo "<br>&nbsp;&nbsp;Child: " . $childE . "<br>\n";
                $DD[$nameE][$childE] = [];
            }
        }

        /************* ORDENAR */
        $adj = $DD;

        // 1) Hierarquia a partir de uma raiz específica:
        $hier = buildHierarchy($adj, 'CURRICULO-VITAE');
        //printTree($hier);
        //pre($hier);

        // 2) Ou montar todas as raízes que existirem no mapa:
        $forest = buildForest($adj);
        $sx .= '<div class="container">';
        $sx .= '<div class="row">';
        $sx .= '<div class="col-12">';
        $sx .= '<pre>';
        foreach ($forest as $root => $tree) {
            $sx .= "== $root ==\n";
            $sx .= printTree($tree);
        }
        $sx .= '</pre>';
        $sx .= '</div></div></div>';
        $sx .= view('headers/footer');
        return $sx;
    }

    function extract()
        {
            $file = '_repository/0004706603300740.xml';
            $data = extrairDados($file);
            pre($data);
            return $data;
        }
}
