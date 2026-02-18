<?php
foreach ($pesquisador['areas_conhecimento'] as $area) {
    echo '<i class="bi bi-'. $area['cnpq_icone'].' text-success me-1" title="' . $area['cnpq_area'] . '"></i>';
    echo '<span class="badge bg-secondary me-1 mb-1">' . $area['cnpq_area'] . '</span>';
    echo '<br>';
}
?>