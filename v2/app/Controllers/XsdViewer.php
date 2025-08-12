<?php

namespace App\Controllers;

use App\Libraries\XsdParser;
use CodeIgniter\Controller;

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

        $dom = new DOMDocument();
        $dom->load($this->xsdPath);

        // Now you can use DOM methods to traverse the XSD structure
        // For example, to get all elements:
        $elements = $dom->getElementsByTagNameNS('http://www.w3.org/2001/XMLSchema', 'element');

        foreach ($elements as $element) {
            echo "Element Name: " . $element->getAttribute('name') . "\n";
            // You can also access other attributes like 'type', 'minOccurs', 'maxOccurs', etc.
        }
        echo "=========================";
        exit;

        if (!is_file($this->xsdPath)) {
            return view('xsd_viewer', [
                'error' => "XSD não encontrado em: {$this->xsdPath}",
                'tree'  => [],
                'meta'  => []
            ]);
        }

        $parser = new XsdParser($this->xsdPath);
        [$tree, $meta] = $parser->build(); // $tree = elementos globais, $meta = tipos/estatísticas

        return view('xsd_viewer', [
            'error' => null,
            'tree'  => $tree,
            'meta'  => $meta
        ]);
    }
}
