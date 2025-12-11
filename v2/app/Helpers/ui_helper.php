<?php
function breadcrumb(array $items)
{
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';

    $total = count($items);
    $i = 1;

    foreach ($items as $label => $url) {

        // Último → ativo
        if ($i == $total) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">'
                   . htmlspecialchars($label) .
                   '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . base_url($url) . '">'
                   . htmlspecialchars($label) .
                   '</a></li>';
        }

        $i++;
    }

    $html .= '</ol></nav>';

    return $html;
}
