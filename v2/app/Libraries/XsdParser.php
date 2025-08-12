<?php

namespace App\Libraries;

use SimpleXMLElement;

class XsdParser
{
    private string $file;
    private SimpleXMLElement $xml;
    private string $xsNs; // prefixo real do namespace xs
    private array $types = [];        // complexType/simpleType mapeados por nome
    private array $elements = [];     // elementos globais
    private array $attrGroups = [];   // attributeGroup por nome

    public function __construct(string $xsdFile)
    {
        $this->file = $xsdFile;
        libxml_use_internal_errors(true);
        $this->xml = simplexml_load_file($this->file);
        if (!$this->xml) {
            $errors = array_map(fn($e) => trim($e->message), libxml_get_errors());
            throw new \RuntimeException("Erro lendo XSD: " . implode("; ", $errors));
        }

        // Descobre o prefixo usado para o XML Schema (xs/xsd...)
        $namespaces = $this->xml->getDocNamespaces(true);
        $this->xsNs = array_search('http://www.w3.org/2001/XMLSchema', $namespaces, true);
        if ($this->xsNs === false) {
            // Tenta sem prefixo (default ns)
            $this->xsNs = '';
        }
    }

    /**
     * Constrói árvore partindo dos elementos globais.
     * @return array [tree, meta]
     */
    public function build(): array
    {
        // Se o root já for <schema>, use-o; senão, procure um filho <schema>
        $schema = $this->xml;
        $rootLocal = strtolower($this->xml->getName()); // respeita prefixo (xs:schema -> schema)
        if ($rootLocal !== 'schema') {
            $schema = $this->firstChild($this->xml, 'schema');
            if (!$schema) {
                throw new \RuntimeException('Elemento <schema> não encontrado no XSD.');
            }
        }

        // --- o restante permanece igual ---
        foreach ($this->children($schema, 'complexType') as $ct) {
            $name = (string)$ct['name'];
            if ($name !== '') $this->types[$name] = $ct;
        }
        foreach ($this->children($schema, 'simpleType') as $st) {
            $name = (string)$st['name'];
            if ($name !== '') $this->types[$name] = $st;
        }
        foreach ($this->children($schema, 'attributeGroup') as $ag) {
            $name = (string)$ag['name'];
            if ($name !== '') $this->attrGroups[$name] = $ag;
        }
        foreach ($this->children($schema, 'element') as $el) {
            $name = (string)$el['name'];
            if ($name !== '') $this->elements[$name] = $el;
        }

        $tree = [];
        foreach ($this->elements as $name => $el) {
            $tree[] = $this->nodeFromElement($el, $name);
        }

        $meta = [
            'elements'        => count($this->elements),
            'types'           => count($this->types),
            'attributeGroups' => count($this->attrGroups),
            'file'            => basename($this->file),
        ];

        return [$tree, $meta];
    }


    /** Helpers */
    private function children(SimpleXMLElement $ctx, string $local): array
    {
        // Usa XPath com local-name() para ignorar prefixos (xs:, xsd:, etc.)
        $res = $ctx->xpath("./*[local-name()='{$local}']");
        return is_array($res) ? $res : [];
    }

    private function firstChild(SimpleXMLElement $ctx, string $local): ?SimpleXMLElement
    {
        $res = $this->children($ctx, $local);
        return $res[0] ?? null;
    }

    private function qnameParts(string $qname): array
    {
        // Retorna [prefix, local]
        $pos = strpos($qname, ':');
        return $pos === false ? ['', $qname] : [substr($qname, 0, $pos), substr($qname, $pos + 1)];
    }

    private function getDocumentation(?SimpleXMLElement $ctx): ?string
    {
        if (!$ctx) return null;
        $ann = $this->firstChild($ctx, 'annotation');
        if (!$ann) return null;
        foreach ($this->children($ann, 'documentation') as $doc) {
            $txt = trim((string)$doc);
            if ($txt !== '') return $txt;
        }
        return null;
    }

    private function nodeFromElement(SimpleXMLElement $el, ?string $forcedName = null): array
    {
        $name = $forcedName ?? (string)$el['name'];
        $type = (string)$el['type'];
        $min  = (string)($el['minOccurs'] ?? '1');
        $max  = (string)($el['maxOccurs'] ?? '1');
        $path = rtrim($parentPath, '/') . '/' . ($name ?: '(anonymous)');

        $node = [
            'kind'          => 'element',
            'name'          => $name,
            'type'          => $type ?: null,
            'minOccurs'     => $min,
            'maxOccurs'     => $max,
            'documentation' => $this->getDocumentation($el),
            'attributes'    => [],
            'children'      => [],
            'path'          => $path,
        ];

        // complexType inline?
        if ($ct = $this->firstChild($el, 'complexType')) {
            $node = $this->mergeComplexType($node, $ct, $path);
        }
        // referenciado por type=?
        elseif ($type) {
            [, $lname] = $this->qnameParts($type);
            if (isset($this->types[$lname])) {
                $t = $this->types[$lname];
                if ($t->getName() === 'complexType') {
                    $node = $this->mergeComplexType($node, $t, $path);
                } else {
                    // simpleType nomeado
                    $node['base']   = $this->simpleTypeBase($t);
                    $node['facets'] = $this->simpleTypeFacets($t);
                }
            }
        }

        // Se não tiver children mas tiver attributes, também mostramos na hierarquia
        if (empty($node['children']) && !empty($node['attributes'])) {
            foreach ($node['attributes'] as $a) {
                $node['children'][] = [
                    'kind'  => 'attribute',
                    'name'  => '@' . $a['name'],
                    'type'  => $a['type'] ?: null,
                    'use'   => $a['use'] ?? 'optional',
                    'path'  => $path . '/@' . $a['name'],
                    'children' => [],
                    'documentation' => $a['documentation'] ?? null,
                ];
            }
        }        return $node;
    }

    private function mergeComplexType(array $node, SimpleXMLElement $ct): array
    {
        // sequencia/choice/all
        foreach (['sequence', 'all', 'choice'] as $grp) {
            if ($grpEl = $this->firstChild($ct, $grp)) {
                foreach ($this->children($grpEl, 'element') as $childEl) {
                    $node['children'][] = $this->nodeFromElement($childEl);
                }
            }
        }

        // complexContent (extension/restriction)
        if ($cc = $this->firstChild($ct, 'complexContent')) {
            foreach (['extension', 'restriction'] as $kind) {
                if ($ext = $this->firstChild($cc, $kind)) {
                    $base = (string)$ext['base'];
                    $node['base'] = $base ?: null;

                    // herda filhos do base se houver (types nomeados)
                    if ($base) {
                        [, $lname] = $this->qnameParts($base);
                        if (isset($this->types[$lname]) && $this->types[$lname]->getName() === 'complexType') {
                            $node = $this->mergeComplexType($node, $this->types[$lname]);
                        }
                    }
                    // adiciona elementos do extension/restriction
                    foreach (['sequence', 'all', 'choice'] as $grp) {
                        if ($grpEl = $this->firstChild($ext, $grp)) {
                            foreach ($this->children($grpEl, 'element') as $childEl) {
                                $node['children'][] = $this->nodeFromElement($childEl);
                            }
                        }
                    }
                    // atributos dentro de extension/restriction
                    $this->collectAttributes($node, $ext);
                }
            }
        }

        // atributos diretos do complexType
        $this->collectAttributes($node, $ct);

        return $node;
    }

    private function collectAttributes(array &$node, SimpleXMLElement $ctx): void
    {
        foreach ($this->children($ctx, 'attribute') as $att) {
            $node['attributes'][] = [
                'name'  => (string)$att['name'],
                'type'  => (string)$att['type'],
                'use'   => (string)($att['use'] ?? 'optional'),
                'default' => (string)($att['default'] ?? ''),
                'documentation' => $this->getDocumentation($att),
            ];
        }
        foreach ($this->children($ctx, 'attributeGroup') as $ag) {
            $ref = (string)$ag['ref'];
            if ($ref) {
                [, $lname] = $this->qnameParts($ref);
                if (isset($this->attrGroups[$lname])) {
                    foreach ($this->children($this->attrGroups[$lname], 'attribute') as $att) {
                        $node['attributes'][] = [
                            'name'  => (string)$att['name'],
                            'type'  => (string)$att['type'],
                            'use'   => (string)($att['use'] ?? 'optional'),
                            'default' => (string)($att['default'] ?? ''),
                            'documentation' => $this->getDocumentation($att),
                        ];
                    }
                }
            }
        }
    }

    private function simpleTypeBase(SimpleXMLElement $st): ?string
    {
        if ($res = $this->firstChild($st, 'restriction')) {
            return (string)$res['base'] ?: null;
        }
        if ($list = $this->firstChild($st, 'list')) {
            return 'list of ' . ((string)$list['itemType'] ?: 'anonymous');
        }
        if ($union = $this->firstChild($st, 'union')) {
            return 'union';
        }
        return null;
    }

    private function simpleTypeFacets(SimpleXMLElement $st): array
    {
        $facets = [];
        if ($res = $this->firstChild($st, 'restriction')) {
            foreach ($res->children() as $f) {
                $name = $f->getName();
                $val  = (string)$f['value'];
                if ($name && $val !== '') {
                    $facets[] = "$name = $val";
                }
                // enum
                if ($name === 'enumeration') {
                    $facets[] = "enumeration: $val";
                }
            }
        }
        return $facets;
    }
}
